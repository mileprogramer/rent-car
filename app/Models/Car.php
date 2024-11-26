<?php

namespace App\Models;

use App\Enums\AirConditionerType;
use App\Enums\CarStatus;
use App\Enums\TransmissionType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Car extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'license',
        'model',
        'brand',
        'year',
        'price_per_day',
        'transmission_type',
        'number_of_doors',
        'person_fit_in',
        'car_consumption',
        'air_conditioning_type',
        'status',
        'images',
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    protected $hidden = ['created_at', 'updated_at', 'media'];

    public static function status() :string
    {
        return 'available';
    }

    public static $carsPerPage = 10;

    public static function rules() :array
    {
        return [
            'license' => ['required', 'string' ,'min:4', 'max:8', "unique:cars"],
            'model' => ['required', 'string', 'min:1', 'max:20'],
            'brand' => ['required', 'string', 'min:1', 'max:20'],
            'year' => ['required', 'numeric','between:1950,'.date('Y')],
            'price_per_day' => ['required', 'numeric', 'min:1'],
            'transmission_type' => ['required', 'string', 'in:'. implode(',', TransmissionType::values())],
            'air_conditioning_type' => ['required', 'string', 'in:'. implode(',', AirConditionerType::values())],
            'status' => ['required', 'string', 'in:'. implode(',', CarStatus::values())],
            'car_consumption' => ['required', 'numeric', 'min:1'],
            'person_fit_in' => ['required', 'numeric', 'min:1'],
            'number_of_doors' => ['required', 'numeric', 'min:1'],
            "images" => ['array'],
        ];
    }

    protected function lastTimeUpdated(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $this->updated_at !== $this->created_at ? $this->updated_at : null,
        );
    }

    protected function carsImages(): Attribute
    {
        return Attribute::make(
            get: function() {
                if ($this?->media) {
                    return $this->images = $this->media->map(function ($media) {
                        return $media->getUrl();
                    });
                }

                return collect([]);
            }
        );
    }

    public function scopeAvailableCars(Builder $query): Builder
    {
        return $query->where("status", Car::status());
    }

    public function scopeTotalAvailableCars(Builder $query): int
    {
        return $query->where("status", Car::status())->count();
    }
    public function scopeSearchCars(Builder $query, string $term): Builder
    {
        return $query->where(function ($query) use ($term) {
            $query->where('brand', 'LIKE', '%' . $term . '%')
                ->orWhere('license', 'LIKE', '%' . $term . '%')
                ->orWhere('model', 'LIKE', '%' . $term . '%');
        });
    }

    public function scopeSearchAvailableCars(Builder $query, string $term): Builder
    {
        return $query->availableCars()->where(function ($query) use ($term) {
            $query->where('brand', 'LIKE', '%' . $term . '%')
                ->orWhere('license', 'LIKE', '%' . $term . '%')
                ->orWhere('model', 'LIKE', '%' . $term . '%');
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cars_images');
    }

    protected function imagesUrl(): Attribute
    {
        return Attribute::make(
            get: function() {
                $images = $this->getMedia("cars_images");
                // makes urls
                $urls = [];
                foreach ($images as $image)
                {
                    $urls[] = $image->getUrl();
                }
                return $urls;
            },
        );
    }

    public function rentedCar(): HasOne
    {
        return $this->hasOne(RentedCar::class);
    }
}

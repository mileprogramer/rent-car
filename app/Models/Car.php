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
        'car_consumption',
        'air_conditioning_type',
        'status',
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

    public function rentedCar(): HasOne
    {
        return $this->hasOne(RentedCar::class);
    }
}

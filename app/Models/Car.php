<?php

namespace App\Models;

use App\Enums\AirConditionerType;
use App\Enums\CarStatus;
use App\Enums\TransmissionType;
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


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    protected $hidden = ['created_at', 'updated_at', 'media'];

    public static function status() :string
    {
        return 'available';
    }

    public static $carsPerPage = 10;
    public static $acceptImageType = ["jpeg", "webp", "png", "jpg", "avif"];

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

    public function registerMediaColections()
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

    public function images() :HasMany
    {
        return $this->hasMany(Media::class, "model_id", "id");
    }

    public function rentedCar(): HasOne
    {
        return $this->hasOne(RentedCar::class);
    }
}

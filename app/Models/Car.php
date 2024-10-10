<?php

namespace App\Models;

use App\Enums\AirConditionerType;
use App\Enums\CarStatus;
use App\Enums\TransmissionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Car extends Model
{
    use HasFactory;

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

    protected $hidden = ['created_at', 'updated_at'];

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
            'number_of_doors' => ['required', 'numeric', 'min:1']
        ];
    }

    //
    protected function images(): Attribute
    {
        return Attribute::make(
            get: function(string $value) {
                $allImages = json_decode($value);
                // this is only for now because i do not have the pictures for every car
                if(empty($allImages))
                {
                    $defaultImages = ["car-front-1.jpg", "car-front-2.jpg", "car-front-4.jpg", "car-front-5.jpg"];
                    foreach ($defaultImages as &$image)
                    {
                        $imageName = $image;
                        $image = env('APP_URL') . "/storage/cars-images/default/" . $imageName;
                    }
                    return $defaultImages;
                }
                // this is the for the cars that have the images
                foreach ($allImages as &$image)
                {
                    $imageName = $image;
                    $image = env('APP_URL') . "/storage/cars-images/". $this->id . $imageName;
                }
                return $allImages;
            },
        );
    }

    public function rentedCar(): HasOne
    {
        return $this->hasOne(RentedCar::class);
    }
}

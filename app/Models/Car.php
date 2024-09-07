<?php

namespace App\Models;

use App\Enums\AirConditionerType;
use App\Enums\CarStatus;
use App\Enums\TransmissionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public static function rules() :array
    {
        return [
            'license' => ['string' ,'min:4', 'max:8'],
            'model' => ['string', 'min:3', 'max:20'],
            'brand' => ['string', 'min:3', 'max:20'],
            'year' => ['numeric','between:1950,'.date('Y')],
            'price_per_day' => ['numeric', 'min:1'],
            'transmission_type' => ['string', 'in:'. implode(',', TransmissionType::values())],
            'air_conditioning_type' => ['string', 'in:'. implode(',', AirConditionerType::values())],
            'status' => ['string', 'in:'. implode(',', CarStatus::values())],
            'car_consumption' => ['numeric', 'min:1'],
            'person_fit_in' => ['numeric', 'min:1'],
            'number_of_doors' => ['numeric', 'min:1']
        ];
    }

    public function rentedCar(): HasOne
    {
        return $this->hasOne(RentedCar::class);
    }
}

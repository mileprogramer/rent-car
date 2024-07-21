<?php

namespace App\Models;

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

    public function rentedCar(): HasOne
    {
        return $this->hasOne(RentedCar::class);
    }
}

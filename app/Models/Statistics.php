<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Statistics extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_user',
        'id_car',
        'start_date',
        'return_date',
        'price_per_day',
        'discount',
        'reason_for_discount',
        'car_id',
        'user_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'car_id' => 'integer',
        'user_id' => 'integer',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    // relationships

    public function rentedCars() :HasMany
    {
        return $this->hasMany(RentedCar::class, "car_id", "car_id");
    }

    public function extendedRents() :HasMany
    {
        return $this->hasMany(ExtendRent::class, 'statistics_id', 'id');
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

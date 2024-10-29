<?php

namespace App\Models;

use App\Traits\CarsWithImages;
use App\Traits\DateFormater;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Statistics extends Model
{
    use HasFactory, DateFormater;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_user',
        'id_car',
        'start_date',
        'wanted_return_date',
        'real_return_date',
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

    public static int $perPageStat = 10;

    protected function startDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->getDateFromFormat("d/m/Y", $value),
            set: fn($value) => $this->formatDate($value)
        );
    }

    protected function wantedReturnDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->getDateFromFormat("d/m/Y", $value),
            set: fn($value) => $this->formatDate($value)
        );
    }

    protected function realReturnDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->getDateFromFormat("d/m/Y", $value),
            set: fn($value) => $this->formatDate($value)
        );
    }

    // relationships
    public function rentedCars() :HasMany
    {
        return $this->hasMany(RentedCar::class, "car_id", "car_id");
    }

    public function extendedRents() :HasMany
    {
        return $this->hasMany(ExtendRent::class, 'statistics_id', 'id')->orderBy("created_at", "desc");
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class, "car_id", "id");
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

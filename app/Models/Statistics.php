<?php

namespace App\Models;

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




    protected function calculateTotalPrice($statistic) :int
    {
        $this->data = $statistic;
        $startDate = Carbon::createFromFormat('d/m/Y', trim($statistic->start_date));
        $totalPrice = $startDate->diffInDays(now()) * ($statistic->price_per_day - ($statistic->discount / 100) * $statistic->price_per_day);
        if($statistic->extend_rent)
        {
            $firstValue = $totalPrice;
            $totalPrice = 0;
            $extendedRents = $statistic->extendedRents;

            // this is counting all extende rents it should count to the rents that are not after the today date
            // make an functionality that go throught all extended rents and check does the is start_date after today if yes just do continue
            foreach ($extendedRents as $extendedRent)
            {
                // if start_date is ahead of the today date then continue
                // if today date is before the return_date then return_date is today
                // Parse the start_date from the extended rent
                $startDate = Carbon::createFromFormat('d/m/Y', trim($extendedRent->start_date));

                // Check if start_date is ahead of today's date
                if ($startDate->isFuture()) {
                    continue; // Skip to the next iteration if start_date is ahead of today
                }

                // Check if today is before the return_date and update return_date to today if true
                if (Carbon::now()->isBefore($statistic->return_date)) {
                    $statistic->return_date = Carbon::now(); // Update return_date to today
                }
                $startDate = Carbon::createFromFormat('d/m/Y', trim($extendedRent->start_date));
                $totalPrice += $startDate->diffInDays($statistic->return_date) * ($extendedRent->price_per_day - ($extendedRent->discount / 100) * $extendedRent->price_per_day);
            }
            $totalPrice = $totalPrice + $firstValue;

        }
        return $totalPrice;
    }



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

<?php

namespace App\Models;

use App\Traits\DateFormater;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;

class ExtendRent extends Model
{
    use HasFactory, DateFormater;

    protected $table = 'extended_rents';
    protected $fillable = [
        'id_user',
        'id_car',
        'start_date',
        'return_date',
        'extend_start_date',
        'price_per_day',
        'discount',
        'reason_for_discount',
        'car_id',
        'user_id',
        'statistics_id',
        'created_at',
        'updated_at'
    ];
    protected $casts = [
        'id' => 'integer',
        'car_id' => 'integer',
        'user_id' => 'integer',
    ];

    public function scopeGetExtendedRentsForRent(Builder $query, int $carId, $statisticsId) :Builder
    {
        return $query->where("car_id", $carId)
            ->where("statistics_id", $statisticsId)
            ->orderBy("created_at", "asc");
    }

    public function scopeGetLastExtendedRent(Builder $query, int $carId, $statisticsId) :Builder
    {
        return $query->where("car_id", $carId)
            ->where("statistics_id", $statisticsId)
            ->orderBy("created_at", "asc");
    }

    protected function startDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->getDateFromFormat("d/m/Y", $value),
            set: fn($value) => $this->formatDate($value)
        );
    }

    protected function startDateDefaultFormat(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->getAttributes()['start_date'],
        );
    }

    protected function returnDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->getDateFromFormat("d/m/Y", $value),
            set: fn($value) => $this->formatDate($value)
        );
    }

    protected function returnDateDefaultFormat(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->getAttributes()['return_date'],
        );
    }
    // relationships
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

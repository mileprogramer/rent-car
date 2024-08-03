<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SoldCar extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_car',
        'date_of_sale',
        'price',
        'car_id',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'car_id' => 'integer',
    ];

    protected function rules() :array
    {
        return [
            'car_id' => ['exists:cars,id'],
            'date_of_sale' => ['date'],
            'price' => ['integer', 'min:1']
        ];
    }

    protected function dateOfSale(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => Carbon::parse($value)->format('Y-m-d'),
        );
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }
}

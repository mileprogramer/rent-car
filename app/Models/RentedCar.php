<?php

namespace App\Models;

use App\Rules\ReasonForDiscount;
use App\Rules\ReturnDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RentedCar extends Model
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

    public static function status() :string
    {
        return 'rented';
    }
    public static function rules(array $requestData = []) :array
    {
        return [
            'user_id' => ['exists:users,id'],
            'car_id' => ['exists:cars,id'],
            'start_date' => ['date'],
            'return_date' => ['date', (new ReturnDate())->setData($requestData)],
            'price_per_day' => ['numeric', 'required'],
            'discount' => ['numeric', 'max:100', 'min:0'],
            'reason_for_discount' => (new ReasonForDiscount())->setData($requestData),
            'extended_rent' => 'bool',
        ];
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

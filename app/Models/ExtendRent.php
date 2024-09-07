<?php

namespace App\Models;

use App\Rules\ReasonForDiscount;
use App\Rules\ReturnDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ExtendRent extends Model
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
        'extend_start_date',
        'price_per_day',
        'discount',
        'reason_for_discount',
        'car_id',
        'user_id',
        'statistics_id',
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

    public static function rules($requestData) :array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'car_id' => ['required', 'exists:statistics,car_id'],
            'start_date' => ['required', 'date'],
            'return_date' => ['required', 'date', (new ReturnDate())->setData($requestData)],
            'price_per_day' => ['required', 'numeric', 'required'],
            'discount' => ['required', 'numeric', 'max:100', 'min:0'],
            'reason_for_discount' => (new ReasonForDiscount())->setData($requestData),
        ];
    }

    protected $hidden = ['created_at', 'updated_at'];

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

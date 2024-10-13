<?php

namespace App\Models;

use App\Rules\ReasonForDiscount;
use App\Rules\ReturnDate;
use App\Traits\DateFormater;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RentedCar extends Model
{
    use HasFactory, DateFormater;


    protected $appends = ['extended_rents'];

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

    public static $carsPerPage = 10;

    public static function rules(array $requestData = []) :array
    {
        return [
            'user_id' => ["required", 'exists:users,id'],
            'car_id' => ["required",
                function($attribute, $value, $fail) use ($requestData) {
                    $carExists = Car::where('status', Car::status())
                        ->where('id', $requestData['car_id'])
                        ->exists();
                    if (!$carExists) {
                        $fail('The selected car either does not exist or does not have the required status: ' . Car::status());
                    }
            }],
            'start_date' => ["required", 'date'],
            'return_date' => ["required", 'date', (new ReturnDate())->setData($requestData)],
            'discount' => ["required", 'numeric', 'max:100', 'min:0'],
            'reason_for_discount' => [(new ReasonForDiscount())->setData($requestData)],
            'extended_rent' => 'bool',
        ];
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

    public function getExtendedRentsAttribute()
    {
        $statistics = Statistics::where('created_at', $this->created_at)->first();

        if ($statistics) {
            return ExtendRent::where('car_id', $this->car_id)
                ->where('statistics_id', $statistics->id)
                ->get();
        }

        return collect();
    }

    // relationships
    public function inStatistics() :HasOne
    {
        // custom relationships
        return $this->hasOne(Statistics::class, 'created_at', "created_at");
    }
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class, "car_id", "id");
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }
}

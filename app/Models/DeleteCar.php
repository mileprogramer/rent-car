<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeleteCar extends Model
{
    use HasFactory;
    protected $table = 'deleted_cars';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'car_id',
        'reason_for_delete'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    public static function status() :string
    {
        return 'deleted';
    }

    protected function rules() :array
    {
        return [
            'car_id' => ['required', 'unique:deleted_cars', function($attribute, $value, $fail){
                $car = Car::where("id", $value)->firstOrFail();
                if($car->status !== Car::status())
                    $fail("Car can not be deleted if it is rented");
            }],
            'reason_for_delete' => ['required' ,'string']
        ];
    }


    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'id_card',
        'phone_number',
        'email',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    public static function rules(array $requestData = []) :array
    {
        return [
            "personal_data" => ["required", "max:255", "min:3", "string"],
            "card_id" => ["required", "numeric"],
            "phone_number" => ["required", "numeric"]
        ];
    }


    public function rentedCars(): HasMany
    {
        return $this->hasMany(RentedCars::class);
    }
}

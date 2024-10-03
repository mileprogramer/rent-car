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
        "name",
        "password",
        "remember_token",
        'card_id',
        'phone',
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
            "name" => ["required", "max:255", "min:3", "string"],
            "card_id" => ["required", "numeric", "unique:users"],
            "phone" => ["required", "unique:users"],
            "email" => ["required", "email", "unique:users"],
        ];
    }


    public function rentedCars(): HasMany
    {
        return $this->hasMany(RentedCars::class);
    }
}

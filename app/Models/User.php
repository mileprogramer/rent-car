<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\Rule;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens;

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

    public static $usersPerPage = 10;

    public static function rules(array $requestData = []) :array
    {
        return [
            "name" => ["required", "max:255", "min:3", "string"],
            "card_id" => ["required", "numeric", "unique:users"],
            "phone" => ["required", "unique:users"],
            "email" => ["required", "email", "unique:users"],
        ];
    }

    public static function rulesEdit(array $requestData = []) :array
    {
        return [
            "id" => ["numeric"],
            "name" => ["required", "max:255", "min:3", "string"],
            "card_id" => ["required", "max:20", Rule::unique("users")->where(function ($query) use ($requestData) {
                return $query->where('card_id', '!=', $requestData['card_id']);
            }),],
            "phone" => ["required", ],
            "email" => ["required", "email"],
        ];
    }


    public function rentedCars(): HasMany
    {
        return $this->hasMany(RentedCars::class);
    }
}

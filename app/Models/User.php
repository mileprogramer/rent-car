<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\Rule;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens;
    protected $fillable = [
        "name",
        "password",
        "remember_token",
        'card_id',
        'phone',
        'email',
    ];

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

    public function scopeSearch(Builder $query, $searchTerm)  :Builder
    {
        return $query->where("name", "like" , "%" . $searchTerm . "%")
            ->orWhere("card_id", "like", "%" . $searchTerm. "%");
    }


    public function rentedCars(): HasMany
    {
        return $this->hasMany(RentedCar::class);
    }
}

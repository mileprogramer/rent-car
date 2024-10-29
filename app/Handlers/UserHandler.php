<?php

namespace App\Handlers;

use App\Models\User;
use http\Env\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserHandler
{

    public static function showUsers() : LengthAwarePaginator
    {
        return User::select("id", "name", "email", "phone", "card_id")
            ->paginate(User::$usersPerPage);
    }

    public static function search($searchTerm) : LengthAwarePaginator
    {
        return User::select("id", "name", "email", "phone", "card_id")
            ->where("name", "like" , "%" . $searchTerm . "%")
            ->orWhere("card_id", $searchTerm)
            ->paginate(User::$usersPerPage);
    }

    public static function addNewUser($userData)
    {
        return User::create([
            "name" => $userData['name'],
            "phone" => $userData['phone'],
            "card_id" => $userData['card_id'],
            "email" => $userData['email'],
            "password" => Hash::make("password"),
            "remember_token" => Str::random(10)
        ]);
    }

    public static function editUser(\Illuminate\Http\Request $request) :array
    {
        $data = $request->validate(User::rulesEdit($request->all()));

        $user = User::where("id", $data['id'])->firstOrFail();
        $user->card_id = $data['card_id'];
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'];
        $user->save();

        return [
            "message" => "Use is updated",
        ];
    }

}

<?php

namespace App\Http\Controllers;

use App\Handlers\UserHandler;
use App\Http\Requests\EditUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUsers()
    {
        return response()->json(
            User::select("id", "name", "email", "phone", "card_id")
                ->paginate(User::$usersPerPage)
        );
    }

    public function searchUsers(Request $request)
    {
        return response()->json(
            User::select("id", "name", "email", "phone", "card_id")
                ->search($request->query("search_term"))
                ->paginate(User::$usersPerPage)
        );
    }

    public function updateUser(EditUserRequest $request)
    {
        $data = $request->validated();
        User::where("id", $data['id'])
            ->update([
                "card_id" =>  $data['card_id'],
                "name" => $data['name'],
                "email" => $data['email'],
                "phone" => $data['phone'],
            ]);
        return response()->json(["message"=> "Successfully updated user"]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::select("id", "name", "email", "phone", "card_id")->paginate());
    }

    public function search(Request $request)
    {
        return response()->json(
            User::select("id", "name", "email", "phone", "card_id")
                ->where("name", "like" , "%" . $request->query("search_term") . "%")
                ->orWhere("card_id", $request->query("search_term"))
                ->paginate()
        );
    }

    public function update(Request $request)
    {
        $data = $request->validate(User::rules($request->all()));

        $user = User::where("id", $data['id'])->firstOrFail();
        $user->card_id = $data['card_id'];
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'];
        $user->save();

        return response()->json(["message"=> "Successfully updated user"]);
    }
}

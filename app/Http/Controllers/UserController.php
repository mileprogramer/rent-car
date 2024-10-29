<?php

namespace App\Http\Controllers;

use App\Handlers\UserHandler;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(UserHandler::showUsers());
    }

    public function search(Request $request)
    {
        return response()->json(
            UserHandler::search($request->query("search_term"))
        );
    }

    public function update(Request $request)
    {

        $result = UserHandler::editUser($request);
        return response()->json(["message"=> "Successfully updated user"]);
    }
}

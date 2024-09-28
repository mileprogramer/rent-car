<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return response()->json([
            "data" => User::select("id", "name", "email", "phone", "card_id")->get()
        ]);
    }
}

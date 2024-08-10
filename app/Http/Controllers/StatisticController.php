<?php

namespace App\Http\Controllers;

use App\Models\Statistics;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    public function index()
    {
        return response()->json(Statistics::paginate());
    }
}

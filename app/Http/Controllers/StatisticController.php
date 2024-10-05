<?php

namespace App\Http\Controllers;

use App\Models\Statistics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticController extends Controller
{
    public function index()
    {
        return response()->json(Statistics::with('extendedRents', 'car', 'user')
            ->orderBy("created_at", "desc")
            ->paginate());
    }
}

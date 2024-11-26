<?php

namespace App\Repository;

use App\Models\Statistics;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class StatisticsCarsRepository
{
    static function rentByMonth()
    {
        return DB::select("
            SELECT count(*) as total_cars
            FROM statistics
            WHERE MONTH(start_date) = MONTH(CURDATE())
            AND YEAR(start_date) = YEAR(CURDATE())"
        );
    }

    static function returnedByMonth()
    {
        return DB::select("
            SELECT count(*) as total_cars
            FROM statistics
            WHERE MONTH(start_date) = MONTH(CURDATE())
            AND YEAR(start_date) = YEAR(CURDATE()) AND real_return_date IS NOT NULL"
        );
    }
}

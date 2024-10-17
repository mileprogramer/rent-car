<?php

namespace app\Repositoriums;

use Illuminate\Support\Facades\DB;

class StatisticsCarRepository
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

    static function rentByYear()
    {
        return DB::select("
            SELECT count(*) as total_cars
            FROM statistics
            WHERE YEAR(start_date) = YEAR(CURDATE())"
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

    static function returnedByYear()
    {
        return DB::select("
            SELECT count(*) as total_cars
            FROM statistics
            WHERE YEAR(start_date) = YEAR(CURDATE()) AND real_return_date IS NOT NULL"
        );

    }
}

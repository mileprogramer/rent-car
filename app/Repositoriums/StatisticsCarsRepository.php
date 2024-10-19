<?php

namespace app\Repositoriums;

use App\Models\Statistics;
use Illuminate\Support\Facades\DB;

class StatisticsCarsRepository
{
    static function getStats($query = null, bool $withImages, bool $pagiante)
    {
        if($query === null){
            $query = Statistics::query();
            $query
                ->orderBy("updated_at", "desc");
        }

        $stats = null;

        if($pagiante){
            $stats = $query->paginate(Statistics::$perPageStat);
        }
        else
            $stats = $query->get();

        if($withImages){
            $stats->transform(function($record){
                if($record->car){
                    $record->car->images = $record->car->imagesUrl;
                }
                return $record;
            });
        }

        return $stats;
    }

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

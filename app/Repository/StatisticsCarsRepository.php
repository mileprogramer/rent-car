<?php

namespace App\Repository;

use App\Models\Statistics;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class StatisticsCarsRepository
{

    static function getStatsWithCarImages(Builder $query, bool $paginate)
    {
        $stats = $paginate ? $query->paginate(Statistics::$perPageStat) : $query->get();

        return $stats->transform(function ($record) {
            if ($record->car && $record->car?->media) {
                $record->car->images = $record->car->media->map(function ($media) {
                    return $media->getUrl();
                });
            }
            return $record;
        });
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

<?php

namespace App\Http\Controllers\Api\Sports\V1;

use App\Http\Controllers\Controller;
use App\Models\Sport;
use App\Models\YearlySportsHeadcountStatistic;


define('YEARLY_STATISTICS_CACHE_SECONDS', 86400);


class SportV1Controller extends Controller
{
    public function yearlyHeadcountStatistics($year)
    {
        $cacheKey = 'yearlyHeadcountStatistics_' . $year;
        $results = cache()->remember($cacheKey, YEARLY_STATISTICS_CACHE_SECONDS, function () use ($year) {
            $headcountStatistics = YearlySportsHeadcountStatistic::where('year', $year)
                ->selectRaw('sport_id, SUM(total_headcount) as total_headcount')
                ->groupBy('sport_id')
                ->with('sport')
                ->get();
            return $headcountStatistics->map(function ($item) {
                return [
                    'id' => $item->sport->id,
                    'name' => $item->sport->name,
                    'headcount' => (int)$item->total_headcount,
                ];
            });
        });

        return okHttpResponse($results);
    }

    public function yearlyActivityHeadcountStatistics($year, Sport $sport)
    {
        $cacheKey = 'yearlyDetailHeadcountStatistics_' . $year . '_' . $sport->id;
        $results = cache()->remember($cacheKey, YEARLY_STATISTICS_CACHE_SECONDS, function () use ($year, $sport) {
            $headcountStatistics = YearlySportsHeadcountStatistic::where('year', $year)
                ->where('sport_id', $sport->id)
                ->with('activity')
                ->get();
            return $headcountStatistics->map(function ($item) {
                return [
                    'id' => $item->activity->id,
                    'name' => $item->activity->name,
                    'headcount' => $item->total_headcount,
                ];
            });
        });
        return okHttpResponse($results);
    }

}

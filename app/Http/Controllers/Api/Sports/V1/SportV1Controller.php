<?php

namespace App\Http\Controllers\Api\Sports\V1;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Sport;
use Illuminate\Http\Request;

class SportV1Controller extends Controller
{

    public function yearlyHeadcountStatistics(Request $request)
    {
        $lastYear = date('Y') - 1;
        $sports = Sport::with(['headcountStatistics' => function ($query) use ($lastYear) {
            $query->where('year', $lastYear);
        }])->withCount(['headcountStatistics as headcount'])->get();
        return okHttpResponse($sports);
    }

    public function yearlyDetailHeadcountStatistics(Sport $sport, Request $request)
    {
        $lastYear = date('Y') - 1;
        $activities = Activity::where('sport_id', $sport->id)
            ->with('headcountStatistics')
            ->withCount(['headcountStatistics as headcount' => function ($query) use ($request, $lastYear) {
                $query->where('year', $request->year ?? $lastYear);
            }])->get();
        return okHttpResponse($activities);
    }

}

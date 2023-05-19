<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\YearlySportsHeadcountStatistic;
use Illuminate\Console\Command;

class ArchiveSportYearlyStatistic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sport:archive_head_count_last_year';

    protected $description = 'This script is about summarizing data by year.
    The script retrieves booking queries from the database
    and can be processed in segments based on the actual booking data size. Currently, it is simplified processing';


    public function handle()
    {
        $this->archiveHeadCountByActivity();
    }


    private function archiveHeadCountByActivity()
    {
        $lastYear = date('Y') - 1;

        $activityHeadCounts = Booking::whereYear('date', $lastYear)
            ->selectRaw('activity_id, SUM(headcount) as total_headcount')
            ->groupBy('activity_id')
            ->with('activity')
            ->get();
        foreach ($activityHeadCounts as $activityHeadCount) {
            $this->createOrUpdateYearlyStatistic($lastYear, $activityHeadCount);
        }
    }

    private function createOrUpdateYearlyStatistic($lastYear, $activityHeadCount) {
        $activity = $activityHeadCount->activity;
        $sportId = $activity->sportId;
        $yearlyStatistic = YearlySportsHeadcountStatistic::where('sport_id', $sportId)
            ->where('activity_id', $activityHeadCount->activity_id)
            ->where('year', $lastYear)
            ->first();

        if ($yearlyStatistic) {
            $yearlyStatistic->update([
                'total_headcount' => $activityHeadCount->totalHeadcount,
            ]);
        } else {
            YearlySportsHeadcountStatistic::create([
                'sport_id' => $sportId,
                'activity_id' => $activityHeadCount->activity_id,
                'total_headcount' => $activityHeadCount->totalHeadcount,
                'year' => $lastYear,
            ]);
        }
    }

}

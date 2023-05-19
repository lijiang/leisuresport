<?php

namespace App\Console\Commands;

use App\Models\Sport;
use App\Models\YearlyActivitiesHeadcountStatistic;
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
        $sports = Sport::all();
        foreach ($sports as $sport) {
            $this->archiveHeadCountBySport($sport);
        }
    }


    private function archiveHeadCountBySport($sport)
    {
        $lastYear = date('Y') - 1;
        //get all activities for the current sport
        $activities = $sport->activities;
        $totalHeadcount = 0;
        foreach ($activities as $activity) {
            //get all bookings for the current activity, consider the size of the data size. Currently, it is simplified
            $bookings = $activity->bookings()->whereYear('date', $lastYear)->get();
            //sum headcount of all bookings for the current activity
            var_dump(count($bookings));
            $activityHeadcount = $bookings->sum('headcount');
            $totalHeadcount += $activityHeadcount;
            //check if yearly statistic already exists for current activity and year
            $yearlyStatistic = YearlyActivitiesHeadcountStatistic::where('activity_id', $activity->id)->where('year', $lastYear)->first();
            if ($yearlyStatistic) {
                echo 'update';
                $yearlyStatistic->total_headcount = $activityHeadcount;
                $yearlyStatistic->save();
            } else {
                YearlyActivitiesHeadcountStatistic::create([
                    'activity_id' => $activity->id,
                    'total_headcount' => $activityHeadcount,
                    'year' => $lastYear,
                ]);
            }
        }
        //check if yearly statistic already exists for current sport and year
        $yearlySportStatistic = YearlySportsHeadcountStatistic::where('sport_id', $sport->id)->where('year', $lastYear)->first();
        if ($yearlySportStatistic) {
            $yearlySportStatistic->total_headcount = $totalHeadcount;
            $yearlySportStatistic->save();
        } else {
            YearlySportsHeadcountStatistic::create([
                'sport_id' => $sport->id,
                'total_headcount' => $totalHeadcount,
                'year' => $lastYear,
            ]);
        }
    }

}

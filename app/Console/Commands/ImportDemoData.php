<?php

namespace App\Console\Commands;

use App\Models\Activity;
use App\Models\Booking;
use App\Models\Sport;
use Illuminate\Console\Command;

class ImportDemoData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import-demo-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $footballActivityTypes = [
            '5-a-side football',
            '7-a-side football',
            '11-a-side football (full match)',
            'Indoor football',
            'Street football',
            'Futsal',
            'Beach football',
            'Mini football',
            'Flag football',
            'Touch football'
        ];

        $basketballActivityTypes = [
            '3-on-3 basketball',
            '5-on-5 basketball',
            'Street basketball',
            'Indoor basketball',
            'Beach basketball',
            'Wheelchair basketball',
            'Basketball shooting drills',
            'Basketball skills training',
            'Basketball tournaments',
            'Basketball camps'
        ];

        $tennisActivityTypes = [
            'Singles tennis',
            'Doubles tennis',
            'Mixed doubles tennis',
            'Practice drills',
            'Tennis lessons',
            'Tennis tournaments',
            'Tennis clinics',
            'Tennis camps',
            'Social tennis',
            'Cardio tennis'
        ];


        $swimmingActivityTypes = [
            'Freestyle swimming',
            'Breaststroke swimming',
            'Backstroke swimming',
            'Butterfly swimming',
            'Open water swimming',
            'Swim training drills',
            'Swim lessons',
            'Swim competitions',
            'Aqua aerobics',
            'Water polo'
        ];

        $volleyballActivityTypes = [
            'Indoor volleyball',
            'Beach volleyball',
            'Sitting volleyball',
            'Mixed volleyball',
            'Volleyball drills',
            'Volleyball clinics',
            'Volleyball tournaments',
            'Volleyball leagues',
            'Volleyball camps',
            'Volleyball skills training'
        ];

        $golfActivityTypes = [
            '18-hole golf',
            '9-hole golf',
            'Driving range',
            'Putting practice',
            'Golf lessons',
            'Golf tournaments',
            'Golf clinics',
            'Golf scrambles',
            'Golf simulator',
            'Golf practice drills'
        ];

        $hockeyActivityTypes = [
            'Ice hockey',
            'Field hockey',
            'Inline hockey',
            'Street hockey',
            'Ball hockey',
            'Hockey training drills',
            'Hockey practice sessions',
            'Hockey leagues',
            'Hockey tournaments',
            'Hockey camps'
        ];


        $cricketActivityTypes = [
            'Test cricket',
            'One-Day International (ODI) cricket',
            'Twenty20 (T20) cricket',
            'Club cricket',
            'Street cricket',
            'Indoor cricket',
            'Cricket practice sessions',
            'Cricket coaching',
            'Cricket tournaments',
            'Cricket camps'
        ];

        $rugbyActivityTypes = [
            'Rugby union',
            'Rugby league',
            'Touch rugby',
            'Sevens rugby',
            'Beach rugby',
            'Tag rugby',
            'Rugby training drills',
            'Rugby practice sessions',
            'Rugby tournaments',
            'Rugby camps'
        ];

        $baseballActivityTypes = [
            'Baseball game',
            'Baseball practice',
            'Baseball drills',
            'Baseball training',
            'Baseball tournaments',
            'Baseball camps',
            'Youth baseball',
            'Adult baseball',
            'Baseball leagues',
            'Baseball clinics'
        ];

        $sports = [
            'Football' => $footballActivityTypes,
            'Basketball' => $basketballActivityTypes,
            'Tennis' => $tennisActivityTypes,
            'Swimming' => $swimmingActivityTypes,
            'Volleyball' => $volleyballActivityTypes,
            'Golf' => $golfActivityTypes,
            'Hockey' => $hockeyActivityTypes,
            'Cricket' => $cricketActivityTypes,
            'Rugby' => $rugbyActivityTypes,
            'Baseball' => $baseballActivityTypes,
        ];


        foreach ($sports as $sport => $activities) {
            $sport = Sport::create([
                'name' => $sport,
            ]);
            foreach ($activities as $activity) {
                $activity = Activity::create(['name' => $activity, 'sport_id' => $sport->id]);
                $bookings = [];
                for ($i = 0; $i < 100; $i++) {
                    $bookings[] = [
                        'activity_id' => $activity->id,
                        'headcount' => rand(10, 1000),
                        'date' => $this->getRandomDateLastYear(),
                    ];
                }
                Booking::insert($bookings);
            }
        }

        return 0;
    }

    function getRandomDateLastYear()
    {
        $previousYear = date('Y') - 1;
        $startDate = strtotime($previousYear . '-01-01');
        $endDate = strtotime($previousYear . '-12-31');
        $randomTimestamp = mt_rand($startDate, $endDate);
        $randomDate = date('Y-m-d', $randomTimestamp);
        return $randomDate;
    }
}

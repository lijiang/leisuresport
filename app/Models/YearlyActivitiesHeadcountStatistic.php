<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class YearlyActivitiesHeadcountStatistic extends baseModel
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'total_headcount',
        'year',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}

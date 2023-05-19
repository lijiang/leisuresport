<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class YearlySportsHeadcountStatistic extends baseModel
{
    use HasFactory;

    protected $fillable = [
        'sport_id',
        'activity_id',
        'total_headcount',
        'year',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }
}

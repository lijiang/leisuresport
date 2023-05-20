<?php
// Activity Model
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Activity extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sport_id',
    ];

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function headcountStatistics()
    {
        return $this->hasMany(YearlyActivitiesHeadcountStatistic::class);
    }
}

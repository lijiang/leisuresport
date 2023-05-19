<?php
// Booking Model
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends baseModel
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'headcount',
        'date',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}

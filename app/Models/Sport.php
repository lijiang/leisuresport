<?php

// Sports Model
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sport extends baseModel
{
    use HasFactory;

    protected $fillable = [
        'sport_name',
    ];

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function headcountStatistics()
    {
        return $this->hasMany(YearlySportsHeadcountStatistic::class);
    }

}
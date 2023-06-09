<?php

// Sports Model
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sport extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }


}

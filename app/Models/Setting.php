<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'open_time_am',
        'close_time_am',
        'open_time_pm',
        'close_time_pm',
    ];
}

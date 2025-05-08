<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DineHeadLimit extends Model
{
    use HasFactory;

    protected $fillable=[
        "manager_id",
        'date',
        "section",
        "amount",
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'order_id',
        'user_id',
        'manager_id',
        'number',
        'order_type',
        'price',
        'user_order_status',
        'date',
        'section',
        'buy_sell_type',
        'dine_id',
        'commission',
        'rate',
        'win_lose'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id', 'id');
    }
    
}

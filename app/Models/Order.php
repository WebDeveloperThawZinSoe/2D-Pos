<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'manager_id',
        'commission',
        'rate',
        'order_type',
        'price',
        'status',
        'user_order_status',
        'date',
        'created_by',
        'section',
        'buy_sell_type',
        'dine_id'
    ];
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
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

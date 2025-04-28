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
        'manager_commission',
        'manager_rate',
        'order_type',
        'price',
        'status',
        'user_order_status',
        'date',
        'section',
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

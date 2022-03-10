<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'order_item_id',
        'rate',
        'comment',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}

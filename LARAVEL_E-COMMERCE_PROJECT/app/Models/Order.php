<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'contact_number',
        'shipping_address',
        'payment_method',
        'notes',
        'total_amount',
        'status'
    ];

    // Relationship: Order belongs to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: Order has many OrderItems
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }


public function orderItems()
{
    return $this->hasMany(OrderItem::class);
}
// In Order.php model
public function customer()
{
    return $this->belongsTo(User::class, 'customer_id');
}
// In app/Models/Order.php

}

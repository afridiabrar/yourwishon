<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $fillable = ['user_id', 'billing_address_id', 'payment_type', 'status', 'receipt_no', 'note'];



    public function address()
    {
        return $this->hasOne(Address::class, 'id', 'billing_address_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function order_product()
    {
        return $this->hasMany(OrderProduct::class, 'order_id', 'id');
    }

    public function payments()
    {
        return $this->hasOne(Payment::class, 'order_id', 'id');
    }
}

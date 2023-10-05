<?php

namespace Owlwebdev\Ecom\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'type',
        'admin_id',
        'text',
    ];

    //types
    const STATUS = 'status';
    const PAYMENT = 'payment';
    const SHIPPING = 'shipping';

    public function admin()
    {
        return $this->hasOne(Admin::class, 'id', 'admin_id');
    }
}

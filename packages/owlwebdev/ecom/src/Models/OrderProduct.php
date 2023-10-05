<?php

namespace Owlwebdev\Ecom\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'product_id',
        'option_id',
        'option_data',
        'name',
        'price',
        'special',
        'coupon_price', // price after coupon and discount
        'cost',
        'count',
    ];


    public function getTotalPriceAttribute()
    {
        return number_format(round(($this->current_price * $this->count), 2), 2, '.', '');
    }

    public function getTotalPriceWithoutDiscountAttribute()
    {
        return number_format(round(($this->current_price_without_discount * $this->count), 2), 2, '.', '');
    }

    public function getTotalFullPriceAttribute()
    {
        return number_format(round(($this->price * $this->count), 2), 2, '.', '');
    }

    public function getCurrentPriceAttribute()
    {
        if ($this->coupon_price > 0) {
            $price = $this->coupon_price;
        } else {
            $price = $this->special > 0 ? $this->special : $this->price;
        }

        return number_format(round(($price), 2), 2, '.', '');
    }

    public function getCurrentPriceWithoutDiscountAttribute()
    {
        $price = $this->special > 0 ? $this->special : $this->price;

        return number_format(round(($price), 2), 2, '.', '');
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id')->with('prices');
    }

    public function order()
    {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }

    public function getOptionAttribute()
    {
        return json_decode($this->option_data, true);
    }
}

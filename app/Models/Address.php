<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "main",
        "shipping_name",
        "shipping_lastname",
        "shipping_surname",
        "shipping_phone",
        "shipping_country",
        "shipping_province",
        "shipping_city",
        "shipping_city_id",
        "shipping_address",
        'shipping_street',
        'shipping_house',
        "shipping_apartment",
        "shipping_postcode",
        "shipping_branch",
        "shipping_branch_id",
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

}

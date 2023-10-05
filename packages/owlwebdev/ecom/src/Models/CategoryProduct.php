<?php

namespace Owlwebdev\Ecom\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CategoryProduct extends Pivot
{
    use HasFactory;

    protected $table = 'category_product';

    public $timestamps = false;

    protected $guarded = [];
}

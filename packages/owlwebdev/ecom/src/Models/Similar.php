<?php

namespace Owlwebdev\Ecom\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Similar extends Pivot
{
    use HasFactory;

    protected $table = 'similar_products';

    public $timestamps = false;

    protected $guarded = [];
}

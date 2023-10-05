<?php

namespace Owlwebdev\Ecom\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Related extends Pivot
{
    use HasFactory;

    protected $table = 'related_products';

    public $timestamps = false;

    protected $guarded = [];
}

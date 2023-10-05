<?php

namespace Owlwebdev\Ecom\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AttributeCategory extends Pivot
{
    use HasFactory;

    protected $table = 'attribute_category';

    public $timestamps = false;

    protected $guarded = [];
}

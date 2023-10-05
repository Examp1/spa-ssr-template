<?php

namespace Owlwebdev\Ecom\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AttributeAttributeGroup extends Pivot
{
    use HasFactory;

    protected $table = 'attribute_attribute_group';

    public $timestamps = false;

    protected $guarded = [];
}

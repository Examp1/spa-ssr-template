<?php

namespace Owlwebdev\Ecom\Models\Translations;

use Illuminate\Database\Eloquent\Model;

class AttributeGroupTranslation extends Model
{
    protected $table = 'attribute_group_translations';
    public $timestamps = false;
    protected $fillable = ['name'];
}

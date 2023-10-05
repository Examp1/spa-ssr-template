<?php

namespace Owlwebdev\Ecom\Interfaces;

use Owlwebdev\Ecom\Models\Attribute;

class Attr
{
    public $id;
    public $attr_slug;
    public $attr_name;
    public $slug;
    public $text = [];

    /**
     * Attribute constructor.
     * @param Attribute $attribute
     */
    public function __construct($attribute)
    {
        $this->id = $attribute->id;
        $this->attr_name = $attribute->name;
        $this->attr_slug = $attribute->slug;
    }
}

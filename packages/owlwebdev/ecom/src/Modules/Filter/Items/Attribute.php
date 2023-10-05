<?php

namespace Owlwebdev\Ecom\Modules\Filter\Items;

class Attribute extends Item
{
    public $attribute_id, $attribute_group_id, $type;
    public $is_main, $parent_id;

    private $_productAttributes = [];

    public function __construct($filter, $attribute)
    {
        parent::__construct($filter);

        $this->slug = $attribute->slug;
        $this->name = $attribute->name;
        $this->attribute_id = $attribute->attribute_id;
        $this->attribute_group_id = $attribute->attribute_group_id;
        $this->type = $attribute->type;

        $this->display = $attribute->display;
        $this->expanded = $attribute->expanded;
        $this->logic = $attribute->logic;
        $this->is_main = $attribute->is_main;
        $this->parent_id = $attribute->parent_id;

        $this->_productAttributes = $attribute->productAttributes;
    }

    /**
     * @inheritDoc
     */
    public function modifyProductsQuery($query)
    {
        if (empty($this->values))
            return $query;

        $query = $query->whereHas('productAttributes', function ($q) {
            /* @var \Illuminate\Database\Eloquent\Builder $q */

            if (count($this->values) == 1) {
                $q->where('slug', $this->values[0]);
            } else {
                $q->whereIn('slug', $this->values);
            }
        });

        return $query;
    }

    /**
     * @inheritDoc
     */
    public function parseOptions($products = null)
    {
        $this->_options = [];


        if ($products == null) {
            // Если продуктов нет, берем из $this->_productAttributes
            foreach ($this->_productAttributes as $attribute) {
                if ($attribute->attr_slug !== $this->slug)
                    continue;

                if (empty($attribute->value) || empty($attribute->slug))
                    continue;

                $this->_options[] = [
                    'slug'       => $attribute->slug,
                    'text'       => $attribute->value,
                    'product_id' => $attribute->product_id,
                    'alt'        => $attribute->alt,
                ];
            }
        } else {
            foreach ($products as $product) {
                foreach ($product->productAttributes as $attribute) {
                    if ($attribute->attr_slug !== $this->slug)
                        continue;

                    if (empty($attribute->value) || empty($attribute->slug))
                        continue;

                    $this->_options[] = [
                        'slug'       => $attribute->slug,
                        'text'       => $attribute->value,
                        'product_id' => $attribute->product_id,
                        'alt'        => $attribute->alt,
                    ];
                }
            }
        }

        return $this;
    }
}

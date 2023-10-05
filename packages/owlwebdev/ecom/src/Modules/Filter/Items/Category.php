<?php

namespace Owlwebdev\Ecom\Modules\Filter\Items;

class Category extends Item
{
    public $slug = 'category';
    private $_subcategories = [];

    public function __construct($filter, $category)
    {
        parent::__construct($filter);

        $this->display = 'select';
        $this->name = __('Categories');
        $this->_subcategories = $category->childrenList;
    }

    public function options()
    {
        if ($this->_options == null) {
            $this->parseOptions();
        }

        $options = [];

        // $products = $this->filter->getProducts($this);

        // // Групируем options, и фильруем продукты
        // foreach ($this->_options as $option) {
        //     if ($products && !in_array($option['product_id'], $products))
        //         continue;

        //     if (!isset($options[$option['slug']])) {
        //         $options[$option['slug']] = [
        //             'text'  => $option['value'],
        //             'slug'  => $option['slug'],
        //             'count' => 1,
        //         ];
        //     } else {
        //         $options[$option['slug']]['count'] += 1;
        //     }
        // }

        foreach ($this->_options as $option) {
            $options[$option['slug']] = [
                'text'  => $option['value'],
                'slug'  => $option['slug'],
                'count' => $option['count'] ?? 0, //disabled
            ];
        }

        return $options;
    }

    /**
     * @inheritDoc
     */
    public function parseOptions($products = null)
    {
        $this->_options = [];

        // if ($products == null) {
            // foreach ($this->_subcategories as $subcat) {
            //     $this->_options[] = [
            //         'slug'        => $subcat->slug,
            //         'value'       => $subcat->name,
            //         'category_id' => $subcat->id,
            //         // 'count'       => $subcat->countProductsInSubCategories(),
            //     ];
            // }
        // } else {
        //     foreach ($products as $product) {
        //         if ($product->categories) {
        //             foreach ($product->categories as $cat) {
        //                 $this->_options[] = [
        //                     'text'      => $cat->name,
        //                     'slug'       => $cat->slug,
        //                     'category_id' => $cat->id,
        //                 ];
        //             }
        //         }
        //     }
        // }

        foreach ($this->_subcategories as $subcat) {
            $this->_options[] = [
                'slug'        => $subcat->slug,
                'value'       => $subcat->name,
                'category_id' => $subcat->id,
                // 'count'       => $subcat->countProductsInSubCategories(),
            ];
        }


        return $this;
    }

    /**
     * @inheritDoc
     */
    public function modifyProductsQuery($query)
    {
        $cats = $this->values;
        if (empty($this->values))
            return $query;

        //product has only main category
        // if (count($this->values) == 1) {
        //     $query->where('products.category_id', $this->values[0]);
        // } else {
        //     $query->whereIn('products.category_id', $this->values);
        // }
        // return $query;

        //product has multiple categories
        return $query->whereHas('categories', function ($query) use ($cats) {
            if (count($cats) == 1) {
                $query->where('categories.id', $cats[0]);
            } else {
                $query->whereIn('categories.id', $cats);
            }
        });
    }
}

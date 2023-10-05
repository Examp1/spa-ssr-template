<?php

namespace Owlwebdev\Ecom\Modules\Filter\Items;

class Catalog extends Item
{
    public $slug = 'subcategories';
    private $_subcategories = [];

    public function __construct($filter, $category)
    {
        parent::__construct($filter);

        $this->display = 'links';
        $this->name = __('Catalog');
        $this->_subcategories = $category->subcategories;
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
                'category_id' => $option['category_id'],
                'children' => $option['children'],
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
            $children = [];

            if ($subcat->childrenList->isNotEmpty()) {
                foreach ($subcat->childrenList as $child) {
                    $children[] = [
                        'slug'        => $child->slug,
                        'value'       => $child->translateOrDefault(app()->getLocale())->name,
                        'category_id' => $child->id,
                    ];
                }
            }

            $this->_options[] = [
                'slug'        => $subcat->slug,
                'value'       => $subcat->translateOrDefault(app()->getLocale())->name,
                'category_id' => $subcat->id,
                'children'    => $children,
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
        return $query;
    }
}

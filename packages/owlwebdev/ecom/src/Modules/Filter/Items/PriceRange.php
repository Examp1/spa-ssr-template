<?php

namespace Owlwebdev\Ecom\Modules\Filter\Items;

use Owlwebdev\Ecom\Models\Category;
use Owlwebdev\Ecom\Models\Product;
use Illuminate\Support\Collection;

class PriceRange extends Item
{
    public $slug = 'price-range';

    protected $has = false;
    protected $min = 0;
    protected $max = 1000;
    protected $from = 0;
    protected $to = 1000;
    protected $step = 0.5;

    public function __construct($filter, $display = 'none', Category $category = null)
    {
        parent::__construct($filter);

        $this->display = $display;
        $this->name = __('categories.price-range');

        if ($category) {
            $prices = \Cache::remember('priceRangeByCategory_' . $category->id, config('cache.expire'), function () use ($category) {
                $query = Product::getQueryWithPrices()
                    ->join('product_categories', function ($q) use ($category) {  // Продукты в выбраной категории
                        /* @var  \Illuminate\Database\Query\JoinClause $q */
                        $q->on('product_categories.product_id', '=', 'products.id')
                            ->where('product_categories.category_id', '=', $category->id);
                    });

                $result = [];

                if ($rec = (clone $query)->orderByRaw('ifnull(ps.price, products.price) ASC')->first()) {
                    $result['min'] = $rec->getCurrentPrice();
                }

                if ($rec = (clone $query)->orderByRaw('ifnull(ps.price, products.price) DESC')->first()) {
                    $result['max'] = $rec->getCurrentPrice();
                }

                return $result;
            });


            if (isset($prices['min'])) {
                $this->min = $prices['min'];
            }

            if (isset($prices['max'])) {
                $this->max = $prices['max'];
            }
        }

        if ($range = \request()->get('price-range')) {
            $range = explode('-', $range);
            if (count($range) == 2) {

                if (floatval($range[0]) >= $this->min && floatval($range[1] <= $this->max)) {
                    $this->has = true;

                    $this->from = floatval($range[0]);
                    $this->to = floatval($range[1]);

                    \MetaTag::setTags([
                        'robots' => 'noindex,nofollow',
                    ]);
                }
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function modifyProductsQuery($query)
    {
        if ($this->has) {
            $query->where(function ($query) {
                /* @var \Illuminate\Database\Eloquent\Builder $query */

                $query->where(function ($query) {
                    /* @var \Illuminate\Database\Eloquent\Builder $query */

                    $query->whereNull('ps.price')
                        ->where('products.price', '>=', $this->from)
                        ->where('products.price', '<=', $this->to);
                })->orWhere(function ($query) {
                    /* @var \Illuminate\Database\Eloquent\Builder $query */

                    $query->whereNotNull('ps.price')
                        ->where('ps.price', '>=', $this->from)
                        ->where('ps.price', '<=', $this->to);
                });
            });
        }

        return $query;
    }

    public function render()
    {
        return view('filter.price-range', [
            'item' => $this,
            'min'  => $this->min,
            'max'  => $this->max,
            'from' => $this->from,
            'to'   => $this->to,
        ]);
    }

    /**
     * @return Collection
     */
    public function getSelectedFilters()
    {
        $list = new Collection([]);

        if ($this->has) {
            $list->push(new SelectedItemRange($this, 'price-range', $this->from, $this->to, 'categories.selectedFilter_range_label'));
        }

        return $list;
    }
}

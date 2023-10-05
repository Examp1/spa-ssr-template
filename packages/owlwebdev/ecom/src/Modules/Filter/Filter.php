<?php

namespace Owlwebdev\Ecom\Modules\Filter;

use Owlwebdev\Ecom\Models\Filter as FilterModel;
use Owlwebdev\Ecom\Modules\Filter\Items\Attribute;
use Owlwebdev\Ecom\Modules\Filter\Items\Category;
use Owlwebdev\Ecom\Modules\Filter\Items\Catalog;
use Owlwebdev\Ecom\Modules\Filter\Items\PriceRange;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class Filter
{
    /* @var  \Owlwebdev\Ecom\Modules\Filter\Items\Item[] */
    public $items = [];
    public $url;

    private $_products;

    /**
     * Добавить фильтры из атрибутов
     *
     * @param $category_id
     * @param $group_id
     * @return $this
     */
    public function addProductAttributes($category_id, $group_id)
    {
        $locale = app()->getLocale();

        $attributes = Cache::remember('filter[' . $locale . '][' . $category_id . '][' . $group_id . ']', config('cache.expire'), function () use ($locale, $category_id, $group_id) {
            return FilterModel::query()
                ->where('filters.attribute_group_id', $group_id)
                ->join('attributes', 'attributes.id', '=', 'filters.attribute_id')
                ->join('attribute_translations', 'attributes.id', '=', 'attribute_translations.attribute_id')
                ->where('display', '<>', 'none')
                ->where('attribute_translations.lang', $locale)
                //->select(['attributes.id', 'attributes.name', 'attributes.slug', 'filters.*'])
                ->select(['attributes.id', 'attribute_translations.name', 'attributes.icon', 'attributes.slug', 'attributes.type', 'filters.*'])
                ->orderBy('filters.order')->orderBy('name')
                ->limit(20)
                ->with([
                    'productAttributes' => function ($q) use ($category_id) {
                        /* @var \Illuminate\Database\Eloquent\Relations\HasMany $q */
                        $q->orderBy('value');

                        if ($category_id) {
                            $q->whereExists(function ($query) use ($category_id) {
                                /* @var \Illuminate\Database\Query\Builder $query */

                                $query->from('category_product')
                                    ->where('category_id', $category_id)
                                    ->whereRaw('category_product.product_id = product_attributes.product_id');
                            });
                        }
                    }
                ])
                ->get();

        });

        foreach ($attributes as $attribute) {
            $this->items[] = new Attribute($this, $attribute);
        }

        return $this;
    }

    // TODO: FIX filtration by category
    public function addCategoryFilter($category)
    {
        $this->items[] = new Category($this, $category);

        return $this;
    }

    public function addCatalogFilter($category)
    {
        $this->items[] = new Catalog($this, $category);

        return $this;
    }

    public function addPriceRangeFilter($category = null)
    {
        $this->items[] = new PriceRange($this, 'none', $category);

        return $this;
    }

    /**
     * Установить значения фильтров
     *
     * @param $filters
     * @return Filter
     */
    public function setValues($filters = null)
    {
        if (!$filters) {
            return $this;
        }

        // filters array, format: "filters":{"necessitatibus-eos":["ab ex"]},
        if (is_array($filters)) {
            foreach ($filters as $slug => $filter_text) {
                foreach ($this->items as $filter) {
                    if ($filter->slug == $slug) {
                        foreach ($filter_text as $value) {
                            $filter->addValue($value);
                        }

                    }
                }
            }
        } else {
            $filters = explode('--', $filters);

            foreach ($filters as $filter_text) {
                foreach ($this->items as $filter) {
                    if (strpos($filter_text, $filter->slug) === 0) {
                        $filter->addValue(str_replace($filter->slug . '-', '', $filter_text));
                    }
                }
            }
        }

        return $this;
    }

    /**
     * Установить action для формы фильтра
     *
     * @param $url
     * @return Filter
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        return view('filter.filter', [
            'filter' => $this,
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render_selected()
    {
        return view('filter.selected', [
            'filter' => $this,
        ]);
    }

    /**
     * Добавить условия фильров к запросу продуктов
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function modifyProductsQuery($query)
    {
        foreach ($this->items as $filter) {
            $filter->modifyProductsQuery($query);
        }

        return $query;
    }

    /**
     * Список продуктов доступные при выбраных фильтрах кроме $this
     *
     * @param $filter
     * @return bool|array
     */
    public function getProducts($filter)
    {
        $list = [];

        foreach ($this->items as $item) {
            if ($item == $filter)
                continue;

            $_list = $item->getProducts();
            if ($_list) {
                $list[] = $_list;
            }
        }

        if (count($list) == 1) {
            return $list[0];
        } else if (count($list) > 1) {
            return array_intersect(...$list);
        } else {
            return false;
        }
    }

    /**
     * Берем options для фильров из списка продуктов
     *
     * @param $products
     */
    public function parseOptions($products)
    {
        $this->_products = $products;

        foreach ($this->items as $filter) {
            $filter->parseOptions($products);
        }
    }

    public function getFilters()
    {
        $list = [];

        foreach ($this->items as $filter) {
// dd($filter);
// dd($filter->slug);
// dd($filter->name);
// dd($filter->options());
            // $list [$filter->slug] = [
            $list [] = [ // for json as array
                'slug' => $filter->slug,
                'name' => $filter->name,
                'icon' => $filter->icon,
                'expanded' => $filter->expanded,
                'display' => $filter->display,
                'type' => $filter->type,
                'options' => $filter->options(),
            ];
        }

        return $list;
    }

    /**
     * @param boolean $to_array
     * @return Collection|Array
     */
    public function getSelectedFilters(bool $to_array = false)
    {
        $list = new Collection([]);
        $arr = new Collection([]);

        foreach ($this->items as $filter) {
            $filter->getSelectedFilters()->map(function ($item) use ($list, $to_array, $arr) {
                $list->push($item);

                if ($to_array) {
                    $arr->push($item->get());
                }
            });
        }

        if ($to_array) {
            return $arr->toArray();
        }

        return $list;
    }
}

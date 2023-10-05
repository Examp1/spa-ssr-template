<?php

namespace Owlwebdev\Ecom\Modules\Filter\Items;

use Owlwebdev\Ecom\Models\Products;
use Illuminate\Support\Collection;

/**
 * Class Item
 * @package App\Modules\Filter\Items
 *
 * @property string $slug
 * @property string $name
 */
class Item
{
    public $slug, $name, $icon, $type;
    public $display, $expanded, $logic;

    public $values = [];

    /* @var \App\Modules\Filter\Filter */
    public $filter;

    public $_options = null;

    public function __construct($filter)
    {
        $this->filter = $filter;
    }

    /**
     * Добавить значение фильтра
     *
     * @param $value
     * @return $this
     */
    public function addValue($value)
    {
        if (!in_array($value, $this->values))
            $this->values[] = $value;

        return $this;
    }

    public function value()
    {
        return count($this->values) ? $this->values[0] : null;
    }

    public function valueLabel()
    {
        $slug = count($this->values) ? $this->values[0] : null;

        $selected_item = $this->options() && isset($this->options()[$slug]) ? $this->options()[$slug] : null;

        return $selected_item ? $selected_item['text'] : null;
    }

    /**
     * Проверка выбрано ли значение
     *
     * @param $value
     * @return bool
     */
    public function isSelected($value)
    {
        return in_array($value, $this->values);
    }

    /**
     * Варианты значений для фильтра
     *
     * @return array
     */
    public function options()
    {
        if ($this->_options == null) {
            $this->parseOptions();
        }

        $options = [];

        $products = $this->filter->getProducts($this);

        // Групируем options, и фильруем продукты
        foreach ($this->_options as $option) {
            if ($products && !in_array($option['product_id'], $products))
                continue;

            if (!isset($options[$option['slug']])) {
                $options[$option['slug']] = [
                    'text'  => $option['text'],
                    'slug'  => $option['slug'],
                    'alt'  => $option['alt'] ?? null,
                    'count' => 1,
                ];
            } else {
                $options[$option['slug']]['count'] += 1;
            }
        }

        return $options;
    }

    /**
     * Добавить условия фильров к запросу продуктов
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function modifyProductsQuery($query)
    {
        return $query;
    }

    /**
     * Возвращаем список продуктов которые попадают под текущий фильтр
     *
     * @return array|null
     */
    public function getProducts()
    {
        if ($this->_options == null) {
            $this->parseOptions();
        }

        $products = [];

        if (empty($this->values)) {
            return null;
        }

        foreach ($this->_options as $row) {
            if (!isset($row['product_id'])) {
                continue;
            }

            if (empty($this->values) || $this->isSelected($row['slug']))
                $products[] = $row['product_id'];
        }

        return $products;
    }

    /**
     * @param Products[] $products
     * @return $this|void
     */
    public function parseOptions($products = null)
    {
        $this->_options = [];

        return $this;
    }

    /**
     * @return Collection
     */
    public function getSelectedFilters()
    {
        $list = new Collection([]);
        $options = $this->options();

        foreach ($this->values as $value) {
            if (!empty($options) && isset($options[$value])) {
                $list->push(new SelectedItem($this, $value, $this->options()));
            }
        }

        return $list;
    }

    public function render()
    {
        return view('filter.select', [
            'item' => $this,
        ]);
    }
}

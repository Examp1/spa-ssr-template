<?php

namespace Owlwebdev\Ecom\Modules\Filter\Items;

class SelectedItemRange
{
    /** @var Item */
    protected $item;
    protected $slug;
    protected $from;
    protected $to;
    protected $label;

    public function __construct(Item $item, $slug, $from, $to, $label)
    {
        $this->item = $item;
        $this->slug = $slug;
        $this->from = $from;
        $this->to = $to;
        $this->label = $label;
    }

    public function render()
    {
        return view('filter.item', [
            'item'  => $this->item,
            'slug'  => $this->slug,
            'from'  => $this->from,
            'to'    => $this->to,
            'label' => __($this->label, [
                'from' => $this->from,
                'to'   => $this->to,
            ]),

            //цена: от 3.5 до 10 грн
        ]);
    }
}

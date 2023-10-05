<?php

namespace Owlwebdev\Ecom\Modules\Filter\Items;

class SelectedItem
{
    /** @var Item */
    protected $item;
    protected $slug;
    protected $text;
    protected $alt;

    public function __construct(Item $item, $slug, $options)
    {
        $this->item = $item;
        $this->slug = $slug;
        $this->text = $options[$slug]['text'] ?? '';
        $this->alt  = $options[$slug]['alt'] ?? '';
    }

    public function get()
    {
        return [
            'key'  => $this->item->slug,
            'alt'  => $this->alt,
            'slug' => $this->slug,
            'text' => $this->text,
        ];
    }

    public function render()
    {
        return view('filter.item', [
            'item' => $this->item,
            'key'  => $this->item->slug,
            'slug' => $this->slug,
            'text' => $this->text,
        ]);
    }
}

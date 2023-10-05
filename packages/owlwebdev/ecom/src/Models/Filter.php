<?php

namespace Owlwebdev\Ecom\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{
    use HasFactory;

    protected $table = 'filters';
    protected $fillable = ['attribute_id', 'attribute_group_id', 'display', 'expanded', 'order'];

    public $values = [];
    public $options = [];

    static function getDisplayTypes()
    {
        return [
            'select'   => __('Показувати'),
            // 'checkbox' => __('Галочки (checkbox)'),
            // 'radio'    => __('Переключатель (radio)'),
            'none'     => __('Приховати'),
        ];
    }

    /**
     * Список атрибутов по включенным продуктам
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productAttributes()
    {
        $locale = app()->getLocale();

        return $this->hasMany(ProductAttributes::class, 'attribute_id', 'attribute_id')
            ->join('products', 'products.id', '=', 'product_attributes.product_id')
            ->join('attributes', 'attributes.id', '=', 'product_attributes.attribute_id')
            ->where('products.status', Product::STATUS_ACTIVE)
            ->where('product_attributes.lang', $locale)
            ->select(['product_attributes.*', 'attributes.slug as attr_slug']);
    }
}

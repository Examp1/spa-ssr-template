<?php

namespace Owlwebdev\Ecom\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductAttributes
 * @package App\Models
 *
 * @property integer $product_id
 * @property integer $attribute_id
 * @property string $value
 * @property string $slug
 * @property string $lang
 */
class ProductAttributes extends Model
{
    use HasFactory;

    protected $table = 'product_attributes';

    public $timestamps = false;

    protected $guarded = [];

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function attribute()
    {
        return $this->hasOne(Attribute::class, 'id', 'attribute_id');
    }

    // ProductPrices
    public function prices()
    {
        return $this->belongsToMany(ProductPrices::class, 'price_attributes', 'product_attribute_id', 'product_price_id')->active();
    }
}

<?php

namespace Owlwebdev\Ecom\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductPrices
 * @package App\Models
 *
 * @property integer $product_id
 * @property string $code
 * @property string $name
 * @property string $color
 * @property string $image
 * @property integer $count
 * @property float $price
 * @property float $old_price
 * @property integer $order
 * @property boolean $status
 * @property boolean $is_main
 */
class ProductPrices extends Model
{
    use HasFactory;

    protected $table = 'product_prices';

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'status' => 'boolean',
        'is_main' => 'boolean'
    ];

    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE     = 1;

    const UNITS = [
        'ru' => [
            '1' => 'л',
            '2' => 'шт',
            '3' => 'г',
        ],
        'uk' => [
            '1' => 'л',
            '2' => 'шт',
            '3' => 'г',
        ],
        'en' => [
            '1' => 'L',
            '2' => 'PC',
            '3' => 'gr',
        ],
    ];

    /**
     * @return array
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_NOT_ACTIVE => 'Не активно',
            self::STATUS_ACTIVE     => 'Активно'
        ];
    }

    /**
     * @param null $lang
     * @return array
     */
    public static function getUnits($lang = null): array
    {
        if (!$lang)
            $lang = config('translatable.locale');

        return self::UNITS[$lang];
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function images()
    {
        return $this->hasMany(ProductImages::class, 'price_id', 'id')
            ->orderBy('order');
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    // ProductAttributes
    public function attributes()
    {
        return $this->belongsToMany(ProductAttributes::class, 'price_attributes', 'product_price_id', 'product_attribute_id');
    }

    public function productAttributes()
    {
        $locale = app()->getLocale();

        return $this->belongsToMany(ProductAttributes::class, 'price_attributes', 'product_price_id', 'product_attribute_id')
            ->leftJoin('attributes', 'attributes.id', '=', 'product_attributes.attribute_id')
            ->leftJoin('attribute_translations', function (\Illuminate\Database\Query\JoinClause $q) {
                $q->on('attribute_translations.attribute_id', '=', 'attributes.id')
                    ->on('attribute_translations.lang', '=', 'product_attributes.lang');
            })
            ->where('product_attributes.lang', $locale)
            ->orderBy('attributes.order')
            ->select([
                'product_attributes.*',
                'attributes.slug as attr_slug',
                'attributes.type as attr_type',
                'attribute_translations.name as attr_name',
            ]);
    }
}

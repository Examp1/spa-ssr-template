<?php

namespace Owlwebdev\Ecom\Models;

use App\Interfaces\ModelSection;
use App\Models\BaseModelSection;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Cviebrock\EloquentSluggable\Sluggable;
use Owlwebdev\Ecom\Models\Translations\ProductTranslation;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

/**
 * Class Product
 * @package App\Models
 *
 * @property integer $id
 * @property string $slug
 * @property string $image
 * @property string $order
 * @property string $status
 * @property string $recommendations
 *
 * @property Category[] $categories
 * @property ProductPrices[] $prices
 */
class Product extends BaseModelSection implements ModelSection
{
    use HasFactory;

    use Sluggable;
    use Translatable;
    use SluggableScopeHelpers;

    protected $table = 'products';

    public $translationModel = 'Owlwebdev\Ecom\Models\Translations\ProductTranslation';

    public $translatedAttributes = [
        'name',
        'description',
        'excerpt',
        'meta_title',
        'meta_description',
        'meta_auto_gen',
        'image',
        'alt',
        'constructor_html',
        'status_lang',
        'main_screen',
        'info',
    ];

    protected $fillable = [
        'slug',
        'order',
        'status',
        'code',
        'price',
        'old_price',
        'cost',
        'recommendations',
        'quantity',
        'currency',
        'rating',
        'hot',
        'reviews_count',
        'preorder',
        'category_id',
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    protected $appends = [
        'path',
    ];

    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE     = 1;
    const SHOWN_NAME = 'Товари';

    const HOT_FROM_ORDER = 1;
    const HOT_FROM_REVIEW = 1;

    public function getDefaultTitleAttribute()
    {
        return $this->translateOrDefault()->name;
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'defaultTitle'
            ]
        ];
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class)->using(CategoryProduct::class);
    }

    /**
     * @return string
     */
    public function categoriesToString()
    {
        $arr = collect($this->categories()->get()->toArray())->pluck('name')->toArray();
        return implode(', ', $arr);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function prices()
    {
        return $this->hasMany(ProductPrices::class, 'product_id', 'id')
            ->orderBy('order');
    }

    /**
     * @return BelongsToMany
     */
    public function attributes()
    {
        return $this->hasMany(ProductAttributes::class, 'product_id', 'id');
        // ->where('lang', app()->getLocale());
    }

    public function productAttributes()
    {
        $locale = app()->getLocale();

        return $this->hasMany(ProductAttributes::class, 'product_id', 'id')
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

    /**
     * @return array
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_NOT_ACTIVE => [
                'title'    => 'Не активний',
                'bg_color' => '#ff3838',
                'color'    => '#fdfdfd'
            ],
            self::STATUS_ACTIVE     => [
                'title'    => 'Активний',
                'bg_color' => '#49cc00',
                'color'    => 'white'
            ]
        ];
    }

    /**
     * @return string
     */
    public function showStatus(): string
    {
        return view('admin.pieces.status', self::getStatuses()[$this->status]);
    }

    /**
     * @param Builder $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('products.status', self::STATUS_ACTIVE);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeNotActive($query)
    {
        return $query->where('products.status', self::STATUS_NOT_ACTIVE);
    }

    /**
     * Конфигурация для модели Menu
     *
     * @return array
     */
    public static function getMenuConfig(): array
    {
        return [
            'rel'        => 'product',
            'name'       => 'name',
            'url_prefix' => '',
            'slug'       => 'path',
        ];
    }

    /**
     * Ссылка на страницу из админки
     *
     * @return string
     */
    public function frontLink($prevw = false): string
    {
        $category = ($this->category->slug ?? '') . '/';

        $url = '/' . self::getMenuConfig()['url_prefix'] . $category . $this->slug . ($prevw ? ('?prevw=' . crc32($this->slug)) : '');

        if (app()->getLocale() !== config('translatable.locale')) {
            $url = '/' . app()->getLocale() . $url;
        }

        return str_replace('//', '/', $url);
    }

    /**
     * Ссылка на страницу редктирования в админке
     *
     * @param $id
     * @return string
     */
    public static function backLink($id): string
    {
        return '/admin/products/' . $id . '/edit';
    }

    public function getBreadcrumbs(string $current_category_slug = '')
    {
        $current_category = [];
        $cat = $this->category;
        if ($current_category_slug) {
            $current_category = Category::where('slug', $current_category_slug)->active()->first();

            if (!empty($current_category)) {
                $cat = $current_category;
            }
        }

        $result = !empty($cat) ? $cat->getBreadcrumbs() : [];

        $result[] = [
            'name' => $this->translateOrDefault(app()->getLocale())->name,
            'link' => '', //no need of link $this->frontLink()
        ];

        return $result;
    }

    public static function getOptionsHTML($selected = null): string
    {
        return view('ecom::admin.products.options-html', ['selected' => $selected]);
    }

    public static function showCardMenu($tag): string
    {
        return view('admin.pieces.menu.card', [
            'typeName'    => 'Products',
            'shownName'   => self::SHOWN_NAME,
            'tag'         => $tag,
            'type'        => \App\Models\Menu::TYPE_PRODUCT,
            'optionsHTML' => self::getOptionsHTML(),
        ]);
    }

    public function isEmptyMeta($lang): bool
    {
        $model = self::query()
            ->leftJoin('product_translations', 'product_translations.product_id', '=', 'products.id')
            ->where('product_translations.lang', $lang)
            ->where('products.id', $this->id)
            ->select([
                'products.*',
                'product_translations.meta_title AS mTitle',
                'product_translations.meta_description AS mDescription',
            ])
            ->first();

        if ($model->mTitle != '' || $model->mDescription != '') {
            return false;
        }

        return true;
    }

    public function getAllLanguagesNotEmpty()
    {
        return ProductTranslation::query()
            ->where('product_id', $this->id)
            ->where('name', '<>', '')
            ->orderBy('lang', 'desc')
            ->pluck('lang')
            ->toArray();
    }

    /**
     * Is there a language version
     *
     * @param $lang
     * @return bool
     */
    public function hasLang($lang): bool
    {
        return ProductTranslation::query()
            ->where('product_id', $this->id)
            ->where('lang', $lang)
            ->where('status_lang', 1)
            ->exists();
    }

    /**
     * Build query with product special prices
     *
     * @param null $date
     * @return Builder|\Illuminate\Database\Query\Builder
     */
    public static function getQueryWithPrices($date = null)
    {
        $query = self::query()->active();

        return self::_modifyQueryForPrice($query, $date);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param null $date
     * @param bool $notNull
     * @return mixed
     */
    public static function _modifyQueryForPrice($query, $date = null, $notNull = true)
    {
        $date = $date ?? date("Y-m-d");

        $query
            // ->leftJoin('product_specials as ps', function ($q) use ($date) {
            //     /* @var  JoinClause $q */

            //     $q->on('ps.id', '=', DB::raw("(SELECT id FROM product_specials WHERE " .
            //     "product_specials.product_id = products.id " .
            //     "AND ('" . $date . "' BETWEEN date_start AND date_end OR (date_start IS NULL AND date_end IS NULL)) ORDER BY priority DESC LIMIT 1)"));
            // })
            ->leftJoin('product_translations', 'product_translations.product_id', '=', 'products.id')
            ->where('product_translations.lang', app()->getLocale())
            ->where('product_translations.status_lang', 1)
            ->with([
                'prices' => function ($query) {
                    $query->active();
                }
            ])
            ->select([
                'product_translations.name',
                'product_translations.image',
                'product_translations.alt',
                'product_translations.info',
                'product_translations.excerpt',
                'products.currency',
                'products.quantity',
                'products.price',
                'products.old_price',
                'products.slug',
                'products.preorder',
                'products.category_id',
                'products.id as id', // fix id rewrite with translation id
            ]);
        // ->selectRaw(($notNull ? 'ifnull(ps.price, products.price) as special_price' : 'ps.price as special_price') . ', ' .
        //     'products.price, products.id, products.image, products.sku, products.quantity, products.status, products.order, products.category_id');

        return $query;
    }

    public function getPathAttribute()
    {
        $category = ($this->category->slug ?? '') . '/';
        return $category . $this->slug;
    }

    public function related()
    {
        return $this->belongsToMany(Product::class, 'related_products', 'product_id', 'related_id')->using(Related::class);
    }

    public function similar()
    {
        return $this->belongsToMany(Product::class, 'similar_products', 'product_id', 'similar_id')->using(Similar::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImages::class, 'product_id', 'id')
            ->orderBy('order');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id', 'id')
            ->where('parent_id', null);
    }

    public function activeReviews()
    {
        return $this->hasMany(Review::class, 'product_id', 'id')
            ->where('parent_id', null)
            ->active();
    }

    public function ordered()
    {
        return $this->hasMany(OrderProduct::class, 'product_id', 'id');
    }

    public function orderedInfo()
    {
        return $this->ordered()
            ->selectRaw('product_id, option_id,
                count(*) as aggregate,
                sum(cost*count) as sum_cost,
                sum(CASE WHEN coupon_price = 0 THEN CASE WHEN special = 0 THEN (price * count) ELSE (special * count) END ELSE (coupon_price * count) END) as sum_price,
                sum(count) as sum_count')
            ->groupBy('product_id', 'option_id');
    }

    public function getOrderedInfoCountAttribute()
    {
        // if relation is not loaded already, let's do it first
        if (!array_key_exists('orderedInfo', $this->relations))
            $this->load('orderedInfo');

        $related = $this->getRelation('orderedInfo');

        return $related;
        // then return the count directly
        // return ($related) ? (int) $related->aggregate : 0;
    }

    public function updatePriceImage()
    {
        $prices = $this->prices()->active()->get();

        $image_set = false;
        $min_quantity = 0;

        if ($prices->isEmpty()) {
            return;
        }

        foreach ($prices as $price) {
            if ($price->status && $price->count > 0) {
                if (!$image_set) { // first
                    $this->price = $price->price;
                    $this->old_price = $price->old_price;

                    $image_model = $price->images()->first();

                    if ($image_model) {
                        foreach (config('translatable.locales') as $lang => $item) {
                            $this->translate($lang)->image = $image_model->image;
                        }
                        $this->save();
                        $image_set = true;
                    }

                    $min_quantity = $price->count;
                }
                $min_quantity = $min_quantity > $price->count ? $price->count : $min_quantity;
            }
        }

        $this->quantity = $min_quantity;
        $this->save();
    }

    public function updateRating()
    {
        $reviews = $this->activeReviews;

        $this->rating = $reviews->avg('rating');
        $this->reviews_count = $reviews->count();

        $this->saveQuietly();

    }
}

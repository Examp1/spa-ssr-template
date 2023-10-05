<?php

namespace Owlwebdev\Ecom\Models;

use Carbon\Carbon;
use Owlwebdev\Ecom\Modules\Filter\Filter;
use App\Interfaces\ModelSection;
use App\Models\BaseModelSection;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Cviebrock\EloquentSluggable\Sluggable;
use Owlwebdev\Ecom\Models\Translations\CategoryTranslation;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

/**
 * Class Category
 * @package Owlwebdev\Ecom\Models
 *
 * @property string $slug
 * @property string $image
 * @property integer $parent_id
 * @property integer $order
 * @property boolean $status
 * @property string $path
 *
 * @property Attribute[] $attributes
 * @property Product[] $products
 * @property Product[] $productsInSubCategories
 */
class Category extends BaseModelSection implements ModelSection
{
    use HasFactory;

    use Sluggable;
    use Translatable;
    use SluggableScopeHelpers;

    protected $table = 'categories';

    public $translationModel = 'Owlwebdev\Ecom\Models\Translations\CategoryTranslation';

    public $translatedAttributes = [
        'name',
        'excerpt',
        'description',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'meta_auto_gen',
        'status_lang',
        'image',
        'alt',
        'ban_image',
        'ban_alt',
        'main_screen',
        'constructor_html',
    ];

    protected $fillable = [
        'slug',
        'order',
        'status',
        'parent_id',
        'attribute_group_id',
        'size_grid_id',
        'path',
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    public $filter;
    const STATUS_NOT_ACTIVE = false;
    const STATUS_ACTIVE     = true;
    const SHOWN_NAME = 'Категорії товарів';

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

    public function attributeGroup()
    {
        return $this->hasOne(AttributeGroup::class, 'id', 'attribute_group_id');
    }

    public function sizeGrid()
    {
        return $this->hasOne(SizeGrid::class, 'id', 'size_grid_id');
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class)->using(AttributeCategory::class);
    }

    /* Generate tree structure data */
    public function treeStructure($active = false)
    {
        $model = $this->query()
            ->with('translations');

        if ($active) {
            $model->active();
        }

        $collection = $model
            ->get()
            ->toArray();

        $normalize = [];

        foreach ($collection as $item) {
            $normalize[$item['parent_id']][$item['id']] = $item;
        }
        if (!empty($normalize)) {
            $node = $normalize[0];

            $this->treeNode($node, $normalize);
        } else {
            $node = $normalize;
        }

        return collect(array_values($node));
    }

    public function treeNode(&$node, $normalize)
    {
        foreach ($node as $key => $item) {
            if (!isset($item['children'])) {
                $node[$key]['children'] = [];
            }

            if (array_key_exists($key, $normalize)) {
                $node[$key]['children'] = $normalize[$key];
                $this->treeNode($node[$key]['children'], $normalize);
            }
        }
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function childrenList()
    {
        return $this->hasMany(self::class, 'parent_id', 'id')
            ->leftJoin('category_translations', 'category_translations.category_id', '=', 'categories.id')
            ->where('category_translations.lang', app()->getLocale())
            ->where('category_translations.status_lang', 1)
            ->select([
                'categories.*',
                'category_translations.*',
                'categories.id as id', // fix id rewrite with translation id
            ])
            ->active();
    }

    public function subcategories()
    {
        return $this->childrenList()->with('childrenList');
    }

    private function getCategories($cat, $res = [])
    {
        if (isset($cat->parent->name)) {
            $res[] = $cat->parent->name;
            return $this->getCategories($cat->parent, $res);
        }

        return $res;
    }

    private function getCategoriesBread($cat, $res = [])
    {
        //current
        $res[] = [
            'name' => $cat->translateOrDefault(app()->getLocale())->name,
            'link' => url($cat->frontLink()),
        ];

        //parant
        if (isset($cat->parent)) {
            return $this->getCategoriesBread($cat->parent, $res);
        }

        return array_reverse($res);
    }

    public function getNameWithPath()
    {
        $arr = $this->getCategories($this);

        $arr = array_reverse($arr);

        $res = '';

        foreach ($arr as $item) {
            $res .= $item . ' > ';
        }

        $res .= $this->name;

        return $res;
    }

    /**
     * Get category breadcrumbs
     *
     * @param string $current_category_slug
     * @return void
     */
    public function getBreadcrumbs(string $current_category_slug = '')
    {
        $current_category = [];
        $result = [];

        if ($current_category_slug) {

            $current_category = Category::where('slug', $current_category_slug)->active()->first();

            if (!empty($current_category)) {
                $result = $this->getCategoriesBread($current_category);

                $result[] = [
                    'name' => $this->translateOrDefault(app()->getLocale())->name,
                    'link' => $this->frontLink(),
                ];

                return $result;
            }
        }

        return $this->getCategoriesBread($this);
    }

    /**
     * Продукты в текущей категории, без учета вложинных подкатегория
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    /**
     * Подсчет количества продуктов в категории
     * @return int
     */
    public function countProducts(): int
    {
        return $this->products()->active()->count();
    }

    public function filters()
    {
        return $this->hasMany(CategoryFilter::class, 'category_id', 'id')->orderBy('order');
    }


    /**
     * @param Builder $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('categories.status', self::STATUS_ACTIVE);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeNotActive($query)
    {
        return $query->where('categories.status', self::STATUS_NOT_ACTIVE);
    }

    /**
     * Подсчет количества продуктов в категории с учетом всех подкатегорий
     * @return int
     */
    public function countProductsInSubCategories(): int
    {
        $self  = $this;
        $count = 0;
        $count += $this->countProducts() +
            $self->children()->get()
            ->sum(function (Category $category) {
                return $category->countProductsInSubCategories();
            });
        return $count;
    }

    public function getAllChildCategoryIds($children = null, $ids = [])
    {
        if ($children) {
            foreach ($children->get() as $child) {
                $ids[] = $child->id;
                $ids   = $this->getAllChildCategoryIds($child->children(), $ids);
            }
        }

        return $ids;
    }

    /**
     * продукты в категории с учетом всех подкатегорий
     */
    public function productsInSubCategories()
    {
        $ids   = [];
        $ids[] = $this->id;

        $ids = array_merge($ids, $this->getAllChildCategoryIds($this->children()));

        $productIds = CategoryProduct::query()->whereIn('category_id', $ids)->pluck('product_id')->toArray();

        return Product::query()
            ->leftJoin('product_translations', 'product_translations.product_id', '=', 'products.id')
            ->where('product_translations.lang', config('translatable.locale'))
            ->where('products.status', Product::STATUS_ACTIVE)
            ->whereIn('products.id', $productIds)
            ->select([
                'products.*',
                'product_translations.name'
            ])
            ->get();
    }

    /**
     * Подсчет количества категорий с учетом всех подкатегорий
     * @return int
     */
    public function countSubCategories(): int
    {
        $self  = $this;
        $count = 0;
        $count += $this->children()->count() +
            $self->children()->get()
            ->sum(function (Category $category) {
                return $category->countSubCategories();
            });
        return $count;
    }


    /**
     * @return array
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_NOT_ACTIVE => [
                'title'    => 'Inactive',
                'bg_color' => '#ff3838',
                'color'    => '#fdfdfd'
            ],
            self::STATUS_ACTIVE     => [
                'title'    => 'Active',
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
     * Конфигурация для модели Menu
     *
     * @return array
     */
    public static function getMenuConfig(): array
    {
        return [
            'rel'        => 'product_category',
            'name'       => 'name',
            'url_prefix' => '',
            'slug'       => 'slug',
        ];
    }

    /**
     * Ссылка на страницу из админки
     *
     * @return string
     */
    public function frontLink($prevw = false): string
    {
        $url = '/' . self::getMenuConfig()['url_prefix'] . $this->slug . ($prevw ? ('?prevw=' . crc32($this->slug)) : '');

        if (app()->getLocale() !== config('translatable.locale')) {
            $url = '/' . app()->getLocale() . $url;
        }

        return $url;
    }

    public static function backLink($id): string
    {
        return '/admin/categories/' . $id . '/edit';
    }

    public static function getOptionsHTML($selected = null, $active = false): string
    {
        $categories = app(self::class)->treeStructure($active);

        return view('ecom::admin.categories.options-html', ['categories' => $categories, 'selected' => $selected]);
    }

    public static function showCardMenu($tag): string
    {
        return view('admin.pieces.menu.card', [
            'typeName'    => 'ProductCategory',
            'shownName'   => self::SHOWN_NAME,
            'tag'         => $tag,
            'type'        => \App\Models\Menu::TYPE_PRODUCT_CATEGORY,
            'optionsHTML' => self::getOptionsHTML(),
        ]);
    }

    public function parseFilters($filters = null)
    {
        $this->filter = new Filter();

        //$this->filter->addPriceRangeFilter($this); //hide price filter

        // $this->filter->addCategoryFilter($this); //category filter
        $this->filter->addCatalogFilter($this); //subcategories
        $this->filter->addProductAttributes($this->id, $this->attribute_group_id);

        $this->filter->setValues($filters)->setUrl($this->getUrl());

        return $this;
    }

    /**
     * Render filters
     */
    public function renderFilters()
    {
        echo view('categories.filters', [
            'category'   => $this,
            'attributes' => $this->_product_attributes,
        ]);
    }

    /**
     * Список продуктов в данной категории (с учетом фильтра)
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getProducts(string $sort, string $order)
    {
        // disable Autoload Translations
        $model = new Product;
        $model->disableAutoloadTranslations();

        $query = $model->getQueryWithPrices()
            ->join('category_product', function ($q) {  // Продукты в выбраной категории
                /* @var  \Illuminate\Database\Query\JoinClause $q */
                $q->on('category_product.product_id', '=', 'products.id')
                    ->where('category_product.category_id', '=', $this->id);
            });

        $this->filter->modifyProductsQuery($query);

        $query->orderByRaw('(quantity = 0)');
        // $query->orderBy('created_at', 'DESC');
        $query->orderBy($sort, $order); // $query->orderByTranslation('name');

        return $query;
    }

    public function getUrl()
    {
        //return route('url-aliases', ['slug' => UrlAliases::getPatchByModel($this)]) . '/';
        //        if ($this->url_format == 1) {
        //            return route('slug-page', ['slug' => $this->seo_url]).'/';
        //        } else {
        //            return route('catalog', ['slug' => $this->seo_url]).'/';
        //        }
        return route('categories.show', ['category' => $this->path . '/' . $this->slug]);
    }

    public function isEmptyMeta($lang): bool
    {
        $model = self::query()
            ->leftJoin('category_translations', 'category_translations.category_id', '=', 'categories.id')
            ->where('category_translations.lang', $lang)
            ->where('categories.id', $this->id)
            ->select([
                'categories.*',
                'category_translations.meta_title AS mTitle',
                'category_translations.meta_description AS mDescription',
            ])
            ->first();

        if ($model->mTitle != '' || $model->mDescription != '') {
            return false;
        }

        return true;
    }

    public function getAllLanguagesNotEmpty()
    {
        return CategoryTranslation::query()
            ->where('category_id', $this->id)
            ->where('name', '<>', '')
            ->orderBy('lang', 'desc')
            ->pluck('lang')
            ->toArray();
    }

    /**
     * Есть ли языковая версия
     *
     * @param $lang
     * @return bool
     */
    public function hasLang($lang): bool
    {
        return CategoryTranslation::query()
            ->where('category_id', $this->id)
            ->where('lang', $lang)
            ->where('name', '<>', '')
            ->exists();
    }

    public function descendants()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id')->with('descendants');
    }

    /**
     * Всі id внутрішніх категорій усіх вложеностів
     * @return mixed
     */
    public function getAllDescendantIds()
    {
        $descendantIds = $this->descendants->pluck('id')->toArray();

        foreach ($this->descendants as $descendant) {
            $descendantIds = array_merge($descendantIds, $descendant->getAllDescendantIds());
        }

        return $descendantIds;
    }
}

<?php

namespace App\Models;

use App\Interfaces\ModelSection;
use App\Models\Translations\BlogCategoryTranslation;
use Astrotomic\Translatable\Translatable;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class BlogCategories
 * @package App\Models
 *
 * @property integer $id
 * @property string $slug
 * @property integer $status
 *
 */
class BlogCategories extends BaseModelSection implements ModelSection
{
    use Sluggable;
    use Translatable;
    use SluggableScopeHelpers;

    protected $table = 'blog_categories';

    public $translationModel = 'App\Models\Translations\BlogCategoryTranslation';

    public $translatedAttributes = [
        'name',
        'excerpt',
        'description',
        'meta_title',
        'meta_description',
        'meta_auto_gen',
        'meta_created_as',
        'image',
        'alt',
        'image_mob',
        'alt_mob',
    ];

    protected $fillable = [
        'slug',
        'order',
        'status',
        'parent_id',
        'path'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    const SHOWN_NAME = 'Категорії блогу';

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    /* Generate tree structure data */
    public function treeStructure()
    {
        $collection = $this->query()
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

        return collect($node);
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

    private function getCategories($cat, $res = [])
    {
        if (isset($cat->parent->name)) {
            $res[] = $cat->parent->name;
            return $this->getCategories($cat->parent, $res);
        }

        return $res;
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
     * Статьи в текущей категории, без учета вложинных подкатегория
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function articles()
    {
        return $this->belongsToMany(BlogArticles::class);
    }

    /**
     * Подсчет количества статей в категории
     * @return int
     */
    public function countArticles(): int
    {
        return $this->articles()->active()->count();
    }

    /**
     * @param Builder $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('blog_categories.status', self::STATUS_ACTIVE);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeNotActive($query)
    {
        return $query->where('blog_categories.status', self::STATUS_NOT_ACTIVE);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childs()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    /**
     * Подсчет количества статей в категории с учетом всех подкатегорий
     * @return int
     */
    public function countArticlesInSubCategories(): int
    {
        $self  = $this;
        $count = 0;
        $count += $this->countArticles() +
            $self->childs()->get()
                ->sum(function (self $category) {
                    return $category->countArticlesInSubCategories();
                });
        return $count;
    }

    public function getAllChildCategoryIds($children = null, $ids = [])
    {
        if ($children) {
            foreach ($children->get() as $child) {
                $ids[] = $child->id;
                $ids   = $this->getAllChildCategoryIds($child->childs(), $ids);
            }

        }

        return $ids;
    }

    /**
     * статьи в категории с учетом всех подкатегорий
     */
    public function articlesInSubCategories()
    {
        $ids   = [];
        $ids[] = $this->id;

        $ids = array_merge($ids, $this->getAllChildCategoryIds($this->childs()));

        $articlesIds = BlogArticleCategory::query()->whereIn('blog_category_id', $ids)->pluck('blog_article_id')->toArray();

        return BlogArticles::query()
            ->leftJoin('blog_article_translations', 'blog_article_translations.blog_articles_id', '=', 'blog_articles.id')
            ->where('blog_article_translations.lang', config('translatable.locale'))
            ->where('blog_articles.status', BlogArticles::STATUS_ACTIVE)
            ->whereIn('blog_articles.id', $articlesIds)
            ->select([
                'blog_articles.*',
                'blog_article_translations.name'
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
        $count += $this->childs()->count() +
            $self->childs()->get()
                ->sum(function (self $category) {
                    return $category->countSubCategories();
                });
        return $count;
    }

    /**
     * Конфигурация для модели Menu
     *
     * @return array
     */
    public static function getMenuConfig(): array
    {
        return [
            'rel'        => 'blog_category',
            'name'       => 'name',
            'url_prefix' => 'news/',
            'slug'       => 'path',
        ];
    }

    /**
     * Ссылка на страницу из админки
     *
     * @param $prevw
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

    /**
     * Ссылка на страницу редктирования в админке
     *
     * @param $id
     * @return string
     */
    public static function backLink($id): string
    {
        return '/admin/blog/categories/'.$id.'/edit';
    }

    public static function getOptionsHTML($selected = null): string
    {
        $categories = app(self::class)->treeStructure();

        return view('admin.blog.categories.options-html', ['categories' => $categories,'selected' => $selected]);
    }

    public static function showCardMenu($tag): string
    {
        return view('admin.pieces.menu.card', [
            'typeName'    => 'BlogCategories',
            'shownName'   => self::SHOWN_NAME,
            'tag'         => $tag,
            'type'        => \App\Models\Menu::TYPE_BLOG_CATEGORY,
            'optionsHTML' => self::getOptionsHTML(),
        ]);
    }

    public function isEmptyMeta($lang): bool
    {
        $model = self::query()
            ->leftJoin('blog_category_translations', 'blog_category_translations.blog_categories_id', '=', 'blog_categories.id')
            ->where('blog_category_translations.lang', $lang)
            ->where('blog_categories.id', $this->id)
            ->select([
                'blog_categories.*',
                'blog_category_translations.meta_title AS mTitle',
                'blog_category_translations.meta_description AS mDescription',
            ])
            ->first();

        if ($model->mTitle != '' || $model->mDescription != '') {
            return false;
        }

        return true;
    }

    public function getAllLanguagesNotEmpty()
    {
        return BlogCategoryTranslation::query()
            ->where('blog_categories_id', $this->id)
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
        return BlogCategoryTranslation::query()
            ->where('blog_categories_id', $this->id)
            ->where('lang',$lang)
            ->where('name', '<>', '')
            ->exists();
    }
}

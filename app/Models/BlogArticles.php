<?php

namespace App\Models;

use App\Interfaces\ModelSection;
use App\Models\Translations\BlogArticleTranslation;
use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class BlogArticles
 * @package App\Models
 *
 * @property integer $id
 * @property string $slug
 * @property integer $status
 * @property integer $views
 * @property integer $user_id
 * @property integer $order
 * @property Carbon $public_date
 *
 * @property BlogTags[] $tags
 * @property BlogArticles[] $categories
 * @property BlogArticles $mainCategory
 */
class BlogArticles extends BaseModelSection implements ModelSection
{
    use HasFactory;
    use Sluggable;
    use Translatable;

    protected $table = 'blog_articles';

    public $translationModel = 'App\Models\Translations\BlogArticleTranslation';

    public $translatedAttributes = [
        'name',
        'excerpt',
        'text',
        'meta_title',
        'meta_description',
        'meta_auto_gen',
        'meta_created_as',
        'image',
        'alt',
        'preview_image',
        'preview_alt',
        'image_mob',
        'alt_mob',
        'constructor_html',
        'status_lang'
    ];

    protected $fillable = [
        'slug',
        'views',
        'user_id',
        'order',
        'public_date',
        'main_category_id'
    ];

    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE     = 1;
    const SHOWN_NAME = 'Публікації';

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(BlogTags::class, 'blog_article_tag', 'blog_article_id', 'blog_tag_id');
    }

    /**
     * Отображать в категориях
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(BlogCategories::class, 'blog_article_category', 'blog_article_id', 'blog_category_id');
    }

    /**
     * Главная категория
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function mainCategory()
    {
        return $this->hasOne(BlogCategories::class, 'id', 'main_category_id');
    }

    /**
     * @return string
     */
    public function categoriesToString()
    {
        return implode(', ', $this->categories->pluck('name')->toArray());
    }

    /**
     * @param Builder $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('blog_articles.status', self::STATUS_ACTIVE)
            ->where('blog_articles.public_date', '<=', Carbon::now());
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeNotActive($query)
    {
        return $query->where('blog_articles.status', self::STATUS_NOT_ACTIVE)
            ->orWhere('blog_articles.public_date', '>', Carbon::now());
    }

    /**
     * @return string
     */
    public function showStatus(): string
    {
        if ($this->public_date > Carbon::now()) {
            return view('admin.pieces.status', self::getStatuses()[0]);
        }

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
            'rel'        => 'blog',
            'name'       => 'name',
            'url_prefix' => 'news/',
            'slug'       => 'slug',
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
        // $catSlug = isset($this->mainCategory) ? ($this->mainCategory->slug . '/') : null;
        // return '/' . self::getMenuConfig()['url_prefix'] . $catSlug . $this->slug . ($prevw ? ('?prevw=' . crc32($this->slug)) : '');

        $url = '/' . self::getMenuConfig()['url_prefix'] . $this->slug . ($prevw ? ('?prevw=' . crc32($this->slug . config('app.name'))) : '');

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
        return '/admin/blog/articles/' . $id . '/edit';
    }

    public static function getOptionsHTML($selected = null): string
    {
        return view('admin.blog.articles.options-html', ['selected' => $selected]);
    }

    public static function showCardMenu($tag): string
    {
        return view('admin.pieces.menu.card', [
            'typeName'    => 'Articles',
            'shownName'   => self::SHOWN_NAME,
            'tag'         => $tag,
            'type'        => \App\Models\Menu::TYPE_BLOG,
            'optionsHTML' => self::getOptionsHTML(),
        ]);
    }

    public function isEmptyMeta($lang): bool
    {
        $model = self::query()
            ->leftJoin('blog_article_translations', 'blog_article_translations.blog_articles_id', '=', 'blog_articles.id')
            ->where('blog_article_translations.lang', $lang)
            ->where('blog_articles.id', $this->id)
            ->select([
                'blog_articles.*',
                'blog_article_translations.meta_title AS mTitle',
                'blog_article_translations.meta_description AS mDescription',
            ])
            ->first();

        if ($model->mTitle != '' || $model->mDescription != '') {
            return false;
        }

        return true;
    }

    public function getAllLanguagesNotEmpty()
    {
        return BlogArticleTranslation::query()
            ->where('blog_articles_id', $this->id)
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
        return BlogArticleTranslation::query()
            ->where('blog_articles_id', $this->id)
            ->where('lang', $lang)
            ->where('status_lang', 1)
            ->exists();
    }
}

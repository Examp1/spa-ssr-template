<?php

namespace App\Models;

use App\Interfaces\ModelSection;
use App\Models\Translations\BlogTagTranslation;
use Astrotomic\Translatable\Translatable;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class BlogTags
 * @package App\Models
 *
 * @property integer $id
 * @property string $slug
 * @property integer $status
 * @property integer $order
 *
 * @property BlogArticles[] $articles
 */
class BlogTags extends BaseModelSection implements ModelSection
{
    use HasFactory;
    use Sluggable;
    use Translatable;

    protected $table = 'blog_tags';

    public $translationModel = 'App\Models\Translations\BlogTagTranslation';

    public $translatedAttributes = [
        'name',
        'description',
        'meta_title',
        'meta_description',
        'meta_auto_gen',
        'meta_created_as',
        'image',
        'alt'
    ];

    protected $fillable = [
        'slug',
        'status',
        'order'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    const SHOWN_NAME = 'Теги блогу';

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    /**
     * @return BelongsToMany
     */
    public function articles()
    {
        return $this->belongsToMany(BlogArticles::class, 'blog_article_tag', 'blog_article_id', 'blog_tag_id');
    }

    public function isEmptyMeta($lang): bool
    {
        $model = self::query()
            ->leftJoin('blog_tag_translations', 'blog_tag_translations.blog_tags_id', '=', 'blog_tags.id')
            ->where('blog_tag_translations.lang', $lang)
            ->where('blog_tags.id', $this->id)
            ->select([
                'blog_tags.*',
                'blog_tag_translations.meta_title AS mTitle',
                'blog_tag_translations.meta_description AS mDescription',
            ])
            ->first();

        if ($model->mTitle != '' || $model->mDescription != '') {
            return false;
        }

        return true;
    }

    public function getAllLanguagesNotEmpty()
    {
        return BlogTagTranslation::query()
            ->where('blog_tags_id', $this->id)
            ->where('name', '<>', '')
            ->orderBy('lang', 'desc')
            ->pluck('lang')
            ->toArray();
    }

    /**
     * Конфигурация для модели Menu
     *
     * @return array
     */
    public static function getMenuConfig(): array
    {
        return [
            'rel'        => 'blog_tag',
            'name'       => 'name',
            'url_prefix' => 'news/tag/',
            'slug'       => 'slug',
        ];
    }

    /**
     * Ссылка на страницу из админки
     *
     * @return string
     */
    public function frontLink(): string
    {
        $url = '/' . self::getMenuConfig()['url_prefix'] . $this->slug;

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
        return '/admin/blog/tags/' . $id . '/edit';
    }

    /**
     * @param Builder $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('blog_tags.status', self::STATUS_ACTIVE);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeNotActive($query)
    {
        return $query->where('blog_tags.status', self::STATUS_NOT_ACTIVE);
    }

    public static function getOptionsHTML($selected = null): string
    {
        return view('admin.blog.tags.options-html', ['selected' => $selected]);
    }

    public static function showCardMenu($tag): string
    {
        return view('admin.pieces.menu.card', [
            'typeName'    => 'BlogTags',
            'shownName'   => self::SHOWN_NAME,
            'tag'         => $tag,
            'type'        => \App\Models\Menu::TYPE_BLOG_TAGS,
            'optionsHTML' => self::getOptionsHTML(),
        ]);
    }

    /**
     * Есть ли языковая версия
     *
     * @param $lang
     * @return bool
     */
    public function hasLang($lang): bool
    {
        return BlogTagTranslation::query()
            ->where('blog_tags_id', $this->id)
            ->where('lang',$lang)
            ->where('name', '<>', '')
            ->exists();
    }
}

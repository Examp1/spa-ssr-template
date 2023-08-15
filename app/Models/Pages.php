<?php

namespace App\Models;

use App\Interfaces\ModelSection;
use App\Models\Translations\PageTranslation;
use Astrotomic\Translatable\Translatable;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Pages
 * @package App
 *
 * @property string $slug
 * @property integer $status
 * @property integer $template
 * @property integer $order
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property array $getTypes
 * @property array $getStatuses
 *
 */
class Pages extends BaseModelSection implements ModelSection
{
    use Sluggable;
    use Translatable;
    use HasFactory;

    protected $table = 'pages';

    public $translationModel = 'App\Models\Translations\PageTranslation';

    public $translatedAttributes = [
        'title',
        'description',
        'meta_title',
        'meta_description',
        'meta_auto_gen',
        'meta_created_as',
        'image',
        'alt',
        'image_mob',
        'alt_mob',
        'constructor_html',
        'status_lang',
        'main_screen',
    ];

    protected $fillable = [
        'slug',
        'status',
        'template',
        'order'
    ];

    protected $appends = [
        'path',
    ];

    const SHOWN_NAME = 'Сторінки';


    public function getPathAttribute()
    {
        return $this->slug;
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive($query)
    {
        return $query->where('pages.status', self::STATUS_ACTIVE);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeNotActive($query)
    {
        return $query->where('pages.status', self::STATUS_NOT_ACTIVE);
    }

    /**
     * Конфигурация для модели Menu
     *
     * @return array
     */
    public static function getMenuConfig(): array
    {
        return [
            'rel'        => 'page',
            'name'       => 'title',
            'url_prefix' => '',
            'slug'       => 'slug',
        ];
    }

    /**
     * @param $prevw
     * @return string
     *
     * Ссылка на страницу из админки
     */
    public function frontLink($prevw = false): string
    {
        $url = '/' . self::getMenuConfig()['url_prefix'] . $this->slug . ($prevw ? ('?prevw=' . crc32($this->slug)) : '');

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
        return '/admin/pages/'.$id.'/edit';
    }

    public static function getOptionsHTML($selected = null): string
    {
        return view('admin.pages.options-html', ['selected' => $selected]);
    }

    public static function showCardMenu($tag): string
    {
        return view('admin.pieces.menu.card', [
            'typeName'    => 'Pages',
            'shownName'   => self::SHOWN_NAME,
            'tag'         => $tag,
            'type'        => \App\Models\Menu::TYPE_PAGE,
            'optionsHTML' => self::getOptionsHTML(),
        ]);
    }

    public function isEmptyMeta($lang): bool
    {
        $model = self::query()
            ->leftJoin('page_translations', 'page_translations.pages_id', '=', 'pages.id')
            ->where('page_translations.lang', $lang)
            ->where('pages.id', $this->id)
            ->select([
                'pages.*',
                'page_translations.meta_title AS mTitle',
                'page_translations.meta_description AS mDescription',
            ])
            ->first();

        if ($model->mTitle != '' || $model->mDescription != '') {
            return false;
        }

        return true;
    }

    public function getAllLanguagesNotEmpty()
    {
        return PageTranslation::query()
            ->where('pages_id', $this->id)
            ->where('title', '<>', '')
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
        return PageTranslation::query()
            ->where('pages_id',$this->id)
            ->where('lang',$lang)
            ->where('status_lang',1)
            ->exists();
    }
}

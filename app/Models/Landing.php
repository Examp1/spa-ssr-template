<?php

namespace App\Models;

use App\Interfaces\ModelSection;
use App\Models\Translations\LandingTranslation;
use Astrotomic\Translatable\Translatable;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Landing
 * @package App\Models
 *
 * @property string $slug
 * @property integer $status
 * @property string $template
 * @property integer $order
 * @property integer $menu_id
 *
 * @property string $frontLink
 */
class Landing extends BaseModelSection implements ModelSection
{
    use HasFactory;

    use Sluggable;
    use Translatable;

    protected $table = 'landings';

    public $translationModel = 'App\Models\Translations\LandingTranslation';

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
        'main_screen'
    ];

    protected $fillable = [
        'slug',
        'status',
        'template',
        'order',
        'menu_id'
    ];

    const SHOWN_NAME = 'Лендінги';
    const IMAGE_RECOMMENDED_SIZE = '1200х760px';
    const IMAGE_PROPORTION = '8x9';

    /**
     * @param Builder $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('landings.status', self::STATUS_ACTIVE);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeNotActive($query)
    {
        return $query->where('landings.status', self::STATUS_NOT_ACTIVE);
    }

    /**
     * Конфигурация для модели Menu
     *
     * @return array
     */
    public static function getMenuConfig(): array
    {
        return [
            'rel'        => 'landing',
            'name'       => 'title',
            'url_prefix' => 'landing/',
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
        return '/admin/landing/'.$id.'/edit';
    }

    public static function getOptionsHTML($selected = null): string
    {
        return view('admin.landing.options-html', ['selected' => $selected]);
    }

    public static function showCardMenu($tag): string
    {
        return view('admin.pieces.menu.card', [
            'typeName'    => 'Landing',
            'shownName'   => self::SHOWN_NAME,
            'tag'         => $tag,
            'type'        => \App\Models\Menu::TYPE_LANDING,
            'optionsHTML' => self::getOptionsHTML(),
        ]);
    }

    public function isEmptyMeta($lang): bool
    {
        $model = self::query()
            ->leftJoin('landing_translations', 'landing_translations.landing_id', '=', 'landings.id')
            ->where('landing_translations.lang', $lang)
            ->where('landings.id', $this->id)
            ->select([
                'landings.*',
                'landing_translations.meta_title AS mTitle',
                'landing_translations.meta_description AS mDescription',
            ])
            ->first();

        if ($model->mTitle != '' || $model->mDescription != '') {
            return false;
        }

        return true;
    }

    public function getAllLanguagesNotEmpty()
    {
        return LandingTranslation::query()
            ->where('landing_id', $this->id)
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
        return LandingTranslation::query()
            ->where('landing_id',$this->id)
            ->where('lang',$lang)
            ->where('status_lang',1)
            ->exists();
    }
}

<?php

namespace Owlwebdev\Ecom\Models;

use Owlwebdev\Ecom\Models\Translations\SizeGridTranslation;
use App\Interfaces\ModelSection;
use App\Models\BaseModelSection;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class SizeGrid
 * @package Owlwebdev\Ecom\Models
 *
 * @property string $slug
 * @property integer $order
 * @property boolean $status
 *
 */
class SizeGrid extends BaseModelSection implements ModelSection
{
    use HasFactory;

    use Sluggable;
    use Translatable;
    use SluggableScopeHelpers;

    protected $table = 'size_grids';

    public $translationModel = 'Owlwebdev\Ecom\Models\Translations\SizeGridTranslation';

    public $translatedAttributes = [
        'name',
        'description',
        'status_lang',
        'image',
        'alt',
    ];

    protected $fillable = [
        'slug',
        'order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    public $filter;
    const STATUS_NOT_ACTIVE = false;
    const STATUS_ACTIVE     = true;
    const SHOWN_NAME = 'Розмірні сітки';

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

    /**
     * @param Builder $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('size_grids.status', self::STATUS_ACTIVE);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeNotActive($query)
    {
        return $query->where('size_grids.status', self::STATUS_NOT_ACTIVE);
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
            'rel'        => 'size_grid',
            'name'       => 'name',
            'url_prefix' => '',
            'slug'       => 'slug',
        ];
    }

    /**
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

    public static function backLink($id): string
    {
        return '/admin/size-grid/' . $id . '/edit';
    }

    public static function getOptionsHTML($selected = null): string
    {
        return view('ecom::admin.size-grids.options-html', ['selected' => $selected]);
    }

    public static function showCardMenu($tag): string
    {
        return view('admin.pieces.menu.card', [
            'typeName'    => 'SizeGrid',
            'shownName'   => self::SHOWN_NAME,
            'tag'         => $tag,
            'type'        => \App\Models\Menu::TYPE_SIZE_GRID,
            'optionsHTML' => self::getOptionsHTML(),
        ]);
    }

    public function getAllLanguagesNotEmpty()
    {
        return SizeGridTranslation::query()
            ->where('size_grid_id', $this->id)
            ->where('status_lang',1)
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
        return SizeGridTranslation::query()
            ->where('size_grid_id', $this->id)
            ->where('lang', $lang)
            ->where('status_lang',1)
            ->exists();
    }
}

<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Menu extends Model
{
    use NodeTrait;
    use Translatable;

    protected $table = "menu";

    public $translationModel = 'App\Models\Translations\MenuTranslation';

    public $translatedAttributes = [
        'name',
        'url'
    ];

    protected $fillable = [
        'visibility',
        'parent_id ',
        'tag',
        'const',
        'type',
        'model_id'
    ];

    public $timestamps = false;

    const VISIBILITY_OFF = 0;
    const VISIBILITY_ON  = 1;

    const TYPE_ARBITRARY        = 0;
    const TYPE_PAGE             = 1;
    const TYPE_BLOG             = 2;
    const TYPE_LANDING          = 3;
    const TYPE_BLOG_CATEGORY    = 4;
    const TYPE_BLOG_TAGS        = 5; //Тег блога

    public static function getVisibility(): array
    {
        return [
            self::VISIBILITY_OFF => 'Не показувати',
            self::VISIBILITY_ON  => 'Показувати'
        ];
    }

    /**
     * @return array
     */
    public static function getTags(): array
    {
        return self::query()
                ->where('const', 1)
                ->pluck('tag', 'id')
                ->toArray() ?? [];
    }

    /**
     * @return array
     */
    public static function getTagsWithId(): array
    {
        return self::query()
                ->where('const', 1)
                ->pluck('tag', 'id')
                ->toArray() ?? [];
    }

    public static function getTypes(): array
    {
        return [
            self::TYPE_PAGE             => __('Pages'),
            self::TYPE_BLOG             => __('Blog'),
            self::TYPE_BLOG_CATEGORY    => __('Blog Categories'),
            self::TYPE_BLOG_TAGS        => __('Blog Tags'),
            self::TYPE_LANDING          => __('Landings'),
            self::TYPE_ARBITRARY        => __('Arbitrary link'),
        ];
    }

    public static function getTypesModel(): array
    {
        return [
            self::TYPE_PAGE             => Pages::getMenuConfig(),
            self::TYPE_BLOG             => BlogArticles::getMenuConfig(),
            self::TYPE_LANDING          => Landing::getMenuConfig(),
            self::TYPE_BLOG_CATEGORY    => BlogCategories::getMenuConfig(),
            self::TYPE_BLOG_TAGS        => BlogTags::getMenuConfig(),
        ];
    }

    public static function getAllRel(): array
    {
        $rel = array_map(function ($item) {
            return $item['rel'];
        }, self::getTypesModel());

        return array_values($rel);
    }

    /**
     * Достать меню по имени
     *
     * @param $name
     * @param $lang
     * @return array
     */
    public static function getByName($name, $lang)
    {
        $res = [];

        $model = \App\Models\Menu::query()
            ->where('tag', $name)
            ->where('const', '<>', 1)
            ->with(\App\Models\Menu::getAllRel())
            ->defaultOrder()
            ->get()
            ->toTree()
            ->toArray();

        if (count($model)) {
            $res = self::prepareMenuModel($model, $lang);
        }

        return $res;
    }

    /**
     * Достать меню по ids
     *
     * @param $ids
     * @param $lang
     * @return array
     */
    public static function getByIds($ids, $lang)
    {
        $res = [];

        foreach ($ids as $id) {
            $main = self::query()
                ->where('id', $id)
                ->where('const', 1)
                ->first();

            if ($main) {
                $model = self::query()
                    ->where('tag', $main->tag)
                    ->where('const', '<>', 1)
                    ->with(\App\Models\Menu::getAllRel())
                    ->defaultOrder()
                    ->get()
                    ->toTree()
                    ->toArray();

                if ($model) {
//                    $res[] = [
//                        'name'  => $main->tag,
//                        'items' => self::prepareMenuModel($model, $lang),
//                    ];

                    $res[$id] = self::prepareMenuModel($model, $lang);
                }
            }
        }

        return $res;
    }

    private static function prepareMenuModel($model, $lang)
    {
        $res = [];
        $res = self::prepareChildrenMenu($model, $lang, $res);
        return $res;
    }

    public static function prepareChildrenMenu($children, $lang, $res = [])
    {
        foreach ($children as $key => $item) {
            if ($item['type'] == Menu::TYPE_ARBITRARY) {
                $res[$key]['name'] = $item['name'];
                $res[$key]['type'] = $item['type'];
                $res[$key]['url']  = $item['url'];

                foreach ($item['translations'] as $trans) {
                    if ($trans['lang'] === $lang) {
                        $res[$key]['name'] = $trans['name'];
                        $res[$key]['url']  = $trans['url'];
                        $res[$key]['slug'] = null;
                    }
                }
            } else {
                $slug     = self::getTypesModel()[$item['type']]['slug'];
                $modelRel = $item[self::getTypesModel()[$item['type']]['rel']];

                if (is_null($modelRel))
                    continue;

                $modelRelSlug = $modelRel[$slug];

                $res[$key]['url'] = self::getTypesModel()[$item['type']]['url_prefix'] . $modelRelSlug;

                if ($lang !== config('translatable.locale')) {
                    $res[$key]['url'] = '/' . $lang . '/' . $res[$key]['url'];
                } else {
                    $res[$key]['url'] = '/' . $res[$key]['url'];
                }

                // для главной страницы
                if ($res[$key]['url'] == '//') {
                    $res[$key]['url'] = '/';
                }

                if (substr($res[$key]['url'], -2) == '//') {
                    $res[$key]['url'] = substr($res[$key]['url'], 0, -1);
                }

                $res[$key]['name'] = $modelRel[\App\Models\Menu::getTypesModel()[$item['type']]['name']];
                $res[$key]['type'] = $item['type'];

                foreach ($modelRel['translations'] as $trans) {
                    if ($trans['lang'] === $lang) {
                        $res[$key]['name'] = $trans[\App\Models\Menu::getTypesModel()[$item['type']]['name']];
                        $res[$key]['slug'] = $modelRel[$slug];
                    }
                }

                foreach ($item['translations'] as $trans) {
                    if ($trans['lang'] === $lang) {
                        if (!empty($trans['name'])) {
                            $res[$key]['name'] = $trans['name'];
                        }
                    }
                }
            }

            if (count($item['children'])) {
                $res[$key]['children'] = self::prepareChildrenMenu($item['children'], $lang, []);
            } else {
                $res[$key]['children'] = [];
            }
        }

        return $res;
    }

    /** Relations ***********************************************************************/

    public function page()
    {
        return $this->hasOne(Pages::class, 'id', 'model_id');
    }

    public function blog()
    {
        return $this->hasOne(BlogArticles::class, 'id', 'model_id');
    }

    public function blog_category()
    {
        return $this->hasOne(BlogCategories::class, 'id', 'model_id');
    }

    public function landing()
    {
        return $this->hasOne(Landing::class, 'id', 'model_id');
    }

    public function blog_tag()
    {
        return $this->hasOne(BlogTags::class, 'id', 'model_id');
    }
}

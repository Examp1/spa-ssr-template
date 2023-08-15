<?php

namespace App\Modules\Widgets\Models;

use App\Modules\Constructor\Models\Constructor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Widget extends Model
{
    /**
     * @var string
     */
    protected $table = 'widgets';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'main_id',
        'instance',
        'data',
        'lang',
        'static'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];

    public function isExistsLang($lang): bool
    {
        return self::query()
            ->where('main_id',$this->main_id)
            ->where('lang',$lang)
            ->exists();
    }

    public function getLangId($lang)
    {
        $m = self::query()
            ->where('main_id',$this->main_id)
            ->where('lang',$lang)
            ->first();

        return $m->id ?? null;
    }

    public function setMainId()
    {
        $maxMain = self::query()->orderBy('main_id','desc')->first();

        if($maxMain && isset($maxMain->main_id) && $maxMain->main_id){
            $maxMainId = $maxMain->main_id;
        } else {
            $maxMainId = 0;
        }

        $this->main_id = ($maxMainId + 1);
        return $this->save();
    }

    public function getAllLanguagesNotEmpty()
    {
        return self::query()
            ->where('main_id', $this->main_id)
            ->pluck('lang')
            ->toArray();
    }

    public function showAllLanguagesNotEmpty()
    {
        return view('admin.pieces.active-languages', [
            'langs' => $this->getAllLanguagesNotEmpty()
        ]);
    }

    public function getRelModels()
    {
        $res = [];

        $findStr = '"widget":"' . $this->id . '"';

        $constructors = Constructor::query()->where('data', 'like', '%' . $findStr . '%')->get();

        if ($constructors) {
            foreach ($constructors as $constructor) {
                $modelClass      = app($constructor->constructorable_type);
                $model           = $modelClass->query()->where('id', $constructor->constructorable_id)->first();
                $originalModel   = app($model->originalModel);
                $entityAttribute = $model->entityAttribute;
                $shownName       = $originalModel::SHOWN_NAME;
                $modelAttrTitle  = $originalModel::getMenuConfig()['name'];
                $title           = $model->$modelAttrTitle;
                $link            = $originalModel::backLink($model->$entityAttribute);

                if (!isset($res[$shownName])) {
                    $res[$shownName]   = [];
                }

                $res[$shownName][] = [
                    'title'     => $title,
                    'link'      => $link,
                ];
            }
        }

        return $res;
    }

    public function getGroups()
    {
        $res = [];

        $widgetClass = config('widgets.widgets.' . $this->instance);

        if(!$widgetClass) return $res;

        try {
            $groups = $widgetClass::$groups;
        } catch (\Exception $e){
            dd($widgetClass);
        }

        foreach (config('widgets.groups') as $key => $val){
            if(in_array($key,$groups)){
                $res[$key] = $val;
            }
        }

        return $res;
    }

    public static function getGroupsIdsByInstance($instance)
    {
        $widgetClass = config('widgets.widgets.' . $instance);

        $groups = $widgetClass::$groups;

        $res = [];

        foreach (config('widgets.groups') as $key => $val){
            if(in_array($key,$groups)){
                $res[] = $key;
            }
        }

        return $res;
    }
}

<?php

namespace App\Modules\Forms\Models;

use App\Modules\Constructor\Models\Constructor;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    /**
     * @var string
     */
    protected $table = 'forms';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'title',
        'btn_name',
        'instance',
        'main_id',
        'data',
        'lang',
        'group_id',
        'image',
        'success_title',
        'success_text',
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
            ->where('main_id', $this->main_id)
            ->where('lang', $lang)
            ->exists();
    }

    public function getLangId($lang)
    {
        $m = self::query()
            ->where('main_id', $this->main_id)
            ->where('lang', $lang)
            ->first();

        return $m->id ?? null;
    }

    public function setMainId()
    {
        $maxMain = self::query()->orderBy('main_id', 'desc')->first();

        if ($maxMain && isset($maxMain->main_id) && $maxMain->main_id) {
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

        $findStr = '"form":"' . $this->id . '"';

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

    public function getData()
    {
        $data = json_decode($this->data, true);

        $res = [];

        foreach ($data as $key => $item) {
            switch ($item['type']) {
                case "input":
                case "editor":
                case "checkbox":
                case "date":
                case "time":
                case "hidden":
                    $rules = [];
                    if (isset($item['rules']) && is_array($item['rules']) && count($item['rules'])) {
                        foreach ($item['rules'] as $keyRule => $valRule) {
                            if (in_array($keyRule, ['required', 'email'])) {
                                $rules[$keyRule] = (bool)$valRule;
                            } else {
                                $rules[$keyRule] = $valRule;
                            }
                        }
                    }
                    $res[$key] = $item;
                    $res[$key]['rules'] = $rules;
                    break;
                case "select":
                    $optionsText = $item['options'];
                    $options = [];

                    $list = explode("\r\n", trim($optionsText));

                    if (count($list)) {
                        foreach ($list as $listVal) {
                            $parts = explode(":", $listVal);
                            // if(isset($options[$parts[0]]) && isset($parts[1])){
                            $options[$parts[0]] = $parts[1];
                            // }
                        }
                    }

                    $rules = [];
                    if (isset($item['rules']) && is_array($item['rules']) && count($item['rules'])) {
                        foreach ($item['rules'] as $keyRule => $valRule) {
                            if (in_array($keyRule, ['required', 'email'])) {
                                $rules[$keyRule] = (bool)$valRule;
                            } else {
                                $rules[$keyRule] = $valRule;
                            }
                        }
                    }

                    $res[$key] = $item;
                    $res[$key]['options'] = $options;
                    $res[$key]['rules'] = $rules;
                    break;
                default:
                    $res[$key] = $item;
            }
        }

        return $res;
    }
}

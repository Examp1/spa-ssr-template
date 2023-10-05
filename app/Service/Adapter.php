<?php

namespace App\Service;

use App\Models\BlogCategories;
use App\Models\Menu;
use App\Modules\Forms\Models\Form;
use App\Modules\Setting\Setting;
use App\Modules\Widgets\Models\Widget;
use Carbon\Carbon;

class Adapter
{
    private static function cmp($a, $b)
    {
        return $a['position'] - $b['position'];
    }

    public static function cmp_order($a, $b)
    {
        return $a['order'] - $b['order'];
    }

    public static function sort($a, $b)
    {
        return $a['sort'] - $b['sort'];
    }

    private function isJSON($string)
    {
        return is_string($string) && is_array(json_decode($string, true)) ? true : false;
    }

    /**
     * @param $model
     * @param $lang
     * @return array
     */
    public function prepareModelResults($model, $lang, $type = null): array
    {
        $translate = $model->getTranslation($lang);

        $constructor = $this->getConstructorData($model,$lang);

        // GET META
        try {
            $frontLink = $model->frontLink();
            $originalModel = app(get_class($model));
            $modelAttrTitle = $originalModel::getMenuConfig()['name'];
            $ogImage = app(Setting::class)->get('default_og_image');

            /*************************************** Breadcrumbs *********************************************/
            $breadcrumbs = [
                [
                    'name' => $translate[$modelAttrTitle],
                    'link'  => $frontLink,
                ],
            ];

            if(isset($translate['image']) && $translate['image'] != ''){
                $ogImage = $translate['image'];
            }

            $ogUrl = $frontLink;
            if($lang != config('translatable.locale')){
                $ogUrl = '/' . $lang . $ogUrl;
            }

            $metaTitle = !is_null($translate['meta_title']) ? $translate['meta_title'] : $translate[$modelAttrTitle];
            $metaDescription = !is_null($translate['meta_description']) ? $translate['meta_description'] : ($translate['text'] ?? '');

            $meta = [
                'title'       => strip_tags($metaTitle),
                'description' => strip_tags($metaDescription),
                'favicon'     => env('APP_URL') . '/storage/media' . app(Setting::class)->get('favicon',config('translatable.locale')),
                'og'          => [
                    'title'       => strip_tags($metaTitle),
                    'description' => strip_tags($metaDescription),
                    'image'       => env('APP_URL') . '/storage/media' .  $ogImage,
                    'url'         => env('APP_URL') . $ogUrl,
                    'type'        => 'website',
                    'locale'      => $lang,
                    'site_name'   => app(Setting::class)->get('app_name',$lang),
                ],
                'head_code' => app(Setting::class)->get('head_code',config('translatable.locale')),
            ];
        } catch (\Throwable $e){
            $meta = [];
        }

        $model = $model->toArray();

        if ($type == Menu::TYPE_BLOG_CATEGORY && isset($model['path']) && $model['path'] !== '/') {
            $prefix = BlogCategories::getMenuConfig()['url_prefix'];

            $cat_url = '/' . $prefix . $model['path'];

            if ($lang !== 'uk') {
                $cat_url = '/' . $lang . $cat_url;
            }

            $model['category_url'] = $cat_url;
            $model['url'] = $cat_url . '/' . $model['slug'];
        }

        unset($model['translations']);
        unset($translate['constructor']);

        if (isset($model['public_date'])) {
            $pd = Carbon::parse($model['public_date']);
            if ($lang == 'uk') {
                $pd->setLocale('uk_UA');
            } elseif ($lang == 'ru') {
                $pd->setLocale('ru_RU');
            }
//            $model['public_date'] = $pd->translatedFormat('d F Y, H:i');
            $model['public_date'] = $pd->format('d.m.Y');
        }

        if(isset($translate['main_screen'])){
            $main_screen = json_decode($translate['main_screen'],true);

            if(isset($main_screen['buttons'])){
                $newBtns = [];
                foreach ($main_screen['buttons'] as $btnKey => $btn){
                    $newBtns[$btnKey] = $btn;

                    if(isset($btn['type_link']) && $btn['type_link'] == 'form' && isset($btn['form_id'])){
                        $form = Form::query()->where('id', $btn['form_id'])->first();

                        $contentData = $form->getData();

                        if(is_array($contentData) && count($contentData)){
                            foreach ($contentData as $qaw => $qwe){
                                $contentData[$qaw]['type'] = 'form-' . $qwe['type'];
                            }
                        }

                        $newBtns[$btnKey]['form_data'] = $contentData;
                    }
                }
                $main_screen['buttons'] = array_values($newBtns);
            }

            $translate['main_screen'] = $main_screen;
        }

        if(isset($translate['description']) && $translate['description'] == "<p><br></p>"){
            $translate['description'] = "";
        }

        return [
            'model'       => $model,
            'translate'   => $translate,
            'constructor' => $constructor,
            'meta'        => $meta,
            'breadcrumbs'  => $breadcrumbs,
        ];
    }

    private function getConstructorData($model, $lang)
    {
        try {
            $constructor = $model->getTranslation($lang)->constructor->data;
        } catch (\Throwable $e) {
            $constructor = null;
        }

        if (!is_null($constructor)) {
            if (!is_array(current($constructor)))
                $constructor = [];
        } else {
            $constructor = [];
        }

        if (count($constructor)) {
            usort($constructor, array('App\Service\Adapter', 'cmp'));

            $allWidgets     = Widget::query()->where('lang', $lang)->pluck('data', 'id')->toArray();
            $allWidgetsName = Widget::query()->where('lang', $lang)->pluck('instance', 'id')->toArray();

            foreach ($constructor as $key => $item) {
                if ($item['component'] === 'widget') {
                    if (isset($allWidgetsName[$item['content']['widget']])) {
                        $constructor[$key]['content']['instance'] = $allWidgetsName[$item['content']['widget']];
                        $widgetClassName                          = config('widgets.widgets')[$allWidgetsName[$item['content']['widget']]];
                        $widgetClass                              = app($widgetClassName);

                        if (method_exists($widgetClass, 'adapter')) {
                            $constructor[$key]['content']['data'] = $widgetClass->adapter($allWidgets[$item['content']['widget']], $lang);
                        } else {
                            $constructor[$key]['content']['data'] = $allWidgets[$item['content']['widget']];
                        }

                        $constructor[$key]['component'] = $constructor[$key]['content']['instance'];
                        $constructor[$key]['content']   = $constructor[$key]['content']['data'];
                        $constructor[$key]['content']['top_separator'] = $item['content']['top_separator'] ?? '';
                        $constructor[$key]['content']['bottom_separator'] = $item['content']['bottom_separator'] ?? '';
                        $constructor[$key]['content']['background'] = $item['content']['background'] ?? '';
                        $constructor[$key]['content']['background_type'] = $item['content']['background_type'] ?? '';

                        if($constructor[$key]['component'] === 'tables'){
                            $constructor[$key]['component'] = 'table_component';

                            $constructor[$key]['content']['table_width'] = $constructor[$key]['content']['columns'];
                            $constructor[$key]['content']['table_mob_width'] = $constructor[$key]['content']['columns-mob'];
                            $constructor[$key]['content']['rows'] = $constructor[$key]['content']['list'];
                            $constructor[$key]['content']['mob_rows'] = $constructor[$key]['content']['list-mob'];


                            unset($constructor[$key]['content']['columns']);
                            unset($constructor[$key]['content']['columns-mob']);
                            unset($constructor[$key]['content']['list']);
                            unset($constructor[$key]['content']['list-mob']);
                        }
                        if($constructor[$key]['component'] === 'quote'){
                            $constructor[$key]['component'] = 'quotes';
                        }
                        if($constructor[$key]['component'] === 'full-width-image'){
                            $constructor[$key]['component'] = 'full-image';
                        }
                    } else {
                        unset($constructor[$key]);
                    }
                } elseif ($item['component'] === 'form') {
                    $form = Form::query()->where('id', $constructor[$key]['content']['form'])->first();

                    if($form){
                        $contentData = $form->getData();

                        if(is_array($contentData) && count($contentData)){
                            foreach ($contentData as $qaw => $qwe){
                                $contentData[$qaw]['type'] = 'form-' . $qwe['type'];
                            }
                        }
                        $constructor[$key]['content']['form_id'] = $form->id;
                        $constructor[$key]['content']['btn_name'] = $form->btn_name;
                        $constructor[$key]['content']['list'] = $contentData;
                        $constructor[$key]['component'] = "form_component";
                        $constructor[$key]['content']['top_separator'] = $item['content']['top_separator'] ?? '';
                        $constructor[$key]['content']['bottom_separator'] = $item['content']['bottom_separator'] ?? '';
                        $constructor[$key]['content']['background'] = $item['content']['background'] ?? '';
                        $constructor[$key]['content']['background_type'] = $item['content']['background_type'] ?? '';
                    }
                }
                elseif ($item['component'] === 'text-n-columns') {
                    $list = [];

                    if($item['content']['rows']){
                        foreach ($item['content']['rows'] as $it){
                            $list[] = $it;
                        }
                        $constructor[$key]['content']['rows'] = $list;
                    }
                } elseif ($item['component'] === 'blocks') {
                    $list = [];

                    if($item['content']['list']){
                        foreach ($item['content']['list'] as $it){
                            $list[] = array_merge([
                                'image' => $it['image'],
                                'title' => $it['title'] ?? '',
                                'text'  => $it['text'],
                                'btn_name' => $it['btn_name'],
                            ],get_interlink($it));
                        }
                        $constructor[$key]['content']['list'] = $list;
                    }
                } elseif ($item['component'] === 'gallery') {
                    $list = [];

                    if($item['content']['list']){
                        foreach ($item['content']['list'] as $it){
                            $list[] = array_merge([
                                'image' => $it['image'],
                                'image_title' => $it['image_title'] ?? '',
                            ],get_interlink($it));
                        }
                        $constructor[$key]['content']['list'] = $list;
                    }
                }
                elseif ($item['component'] === 'blocks-slider') {
                    $list = [];

                    if($item['content']['list']){
                        foreach ($item['content']['list'] as $it){
                            $list[] = array_merge([
                                'image' => $it['image'],
                                'text' => $it['text'] ?? '',
                            ],get_interlink($it));
                        }
                        $constructor[$key]['content']['list'] = $list;
                    }
                }
                elseif ($item['component'] === 'partners') {
                    $list = [];

                    if($item['content']['list']){
                        foreach ($item['content']['list'] as $it){
                            $list[] = array_merge([
                                'image' => $it['image']
                            ],get_interlink($it));
                        }
                        $constructor[$key]['content']['list'] = $list;
                    }
                }
                elseif (in_array($item['component'],['images-3','stages','advantages','accordion', 'numbers','gallery','team','theses','blocks-links'])) {
                    if($item['content']['list']){
                        $constructor[$key]['content']['list'] = array_values($item['content']['list']);
                    }
                } elseif ($item['component'] == 'video-and-text') {
                    $url = $constructor[$key]['content']['file'];
                    try {
                        $url_components = parse_url($url);
                        parse_str($url_components['query'], $params);
                        $video_id = $params['v'];
                        $constructor[$key]['content']['video_id'] = $video_id;
                    } catch (\Exception $e){
                        $constructor[$key]['content']['video_id'] = null;
                    }
                } elseif ($item['component'] === "table") {
                    $constructor[$key]['component'] = "table_component";
                } elseif ($item['component'] === "button") {
                    $constructor[$key]['component'] = "button_component";
                } elseif ($item['component'] == 'link-list') {
                    if ($item['content']['list']) {
                        $listSort = array_values($item['content']['list']);
                        usort($listSort, array('App\Service\Adapter', 'cmp_order'));
                        $constructor[$key]['content']['list'] = $listSort;
                    }
                }

                if(isset($item['content']['btns']) && is_array($item['content']['btns']) && count($item['content']['btns'])){
                    $newBtns = [];
                    foreach ($item['content']['btns'] as $btnKey => $btn){
                        $newBtns[$btnKey] = $btn;

                        if(isset($btn['type_link']) && $btn['type_link'] == 'form' && isset($btn['form_id'])){
                            $form = Form::query()->where('id', $btn['form_id'])->first();

                            $contentData = $form->getData();

                            if(is_array($contentData) && count($contentData)){
                                foreach ($contentData as $qaw => $qwe){
                                    $contentData[$qaw]['type'] = 'form-' . $qwe['type'];
                                }
                            }

                            $newBtns[$btnKey]['form_data'] = $contentData;
                        }
                    }
                    $constructor[$key]['content']['btns'] = array_values($newBtns);
                }

                if(isset($constructor[$key]['content']['anker_id']) && $constructor[$key]['content']['anker_id'] !=="" ){
                    $constructor[$key]['content']['anker_id'] = str_replace('#','',$constructor[$key]['content']['anker_id']);
                }
            }
        }

        return array_values($constructor);
    }

    public function renderConstructorHTML(&$model)
    {
        foreach ($model->translations as &$item){
            $constructor = $this->getConstructorData($model,$item->lang);
            $html = view('front.site.layouts.includes.constructor',['constructor' => $constructor])->render();
            $item->constructor_html = $html;
            $item->save();
        }
    }

    /**
     * @param $model
     * @param $lang
     * @return array
     */
    public function prepareModelsResults($model, $lang, $type = null): array
    {
        $models = [];

        foreach ($model as $item) {
            $models[] = $this->prepareModelResults($item, $lang, $type);
        }

        return [
            'models' => $models
        ];
    }

    public function prepareMenuResults($ids, $lang): array
    {
        $items = Menu::getByIds($ids, $lang);

        return $items ?? [];
    }

    public function prepareSettingsResults($model, $lang): array
    {
        $settings = $model[$lang];

        $res = [];

        if (config('translatable.locale') !== $lang) {
            $settingKeys = array_keys($settings);
            $settingDefaultKeys = array_keys($model[config('translatable.locale')]);
            $diffKeys = array_diff($settingDefaultKeys, $settingKeys);

            if (count($diffKeys)) {
                foreach ($model[config('translatable.locale')] as $key => $val) {
                    if (in_array($key, $diffKeys)) {
                        $settings[$key] = $val;
                    }
                }
            }
        }

        foreach ($settings as $key => $item) {
            $val = [];
            if ($this->isJSON($item[0]['value'])) {
                if ($item[0]['code'] == 'checkout') {
                    $dat = json_decode($item[0]['value'], true);
                    foreach ($dat as $type => $one) {
                        $val[$type] = $one;
                    }
                } else {
                    $val = array_values(json_decode($item[0]['value'], true));
                }
            } else {
                $val = $item[0]['value'];
                $pos = strripos($val, "%year%");
                if($pos !== false){
                    $val = str_replace('%year%',date('Y'),$val);
                }
            }

            $res[$key] = $val;
        }

        return $res;
    }
}

<?php


namespace App\Modules\Setting;


use App\Models\Settings;

class Setting
{
    private $setting;

    public function __construct()
    {
        $this->setting = Settings::query()->get();
    }

    public function get($code, $lang = null)
    {

        if(is_null($lang)){
            $lang = app()->getLocale();
        }

        foreach ( $this->setting as $item){
            if($item->code == $code && $item->lang === $lang){
                return $item->value;
            }
        }

        return  null;
    }

    public function getSocialLink($name)
    {
        $links = json_decode($this->get('links'),true);
        foreach ($links as $item){
            if(strpos($item['link'],$name) !== false){
                return $item['link'];
            }
        }

        return null;
    }
}

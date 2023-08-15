<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Landing;
use App\Models\Langs;
use App\Models\Menu;
use App\Models\Pages;
use App\Modules\Setting\Setting;
use Illuminate\Support\Facades\App;

class PageController extends Controller
{
    public function index($slug = null)
    {
        if (!$slug) $slug = '/';
        $lang = App::getLocale();

        $main_page_type = app(Setting::class)->get('main_page_type', config('translatable.locale'));

        switch ($main_page_type) {
            case Menu::TYPE_PAGE:
                $id = app(Setting::class)->get('main_page_page_id', config('translatable.locale'));
                $page = Pages::query()
                    ->where('id', $id)
                    ->active()
                    ->first();
                break;
            case Menu::TYPE_LANDING:
                $id = app(Setting::class)->get('main_page_landing_id', config('translatable.locale'));
                $page = Landing::query()
                    ->where('id', $id)
                    ->active()
                    ->first();
                break;
            default:
                abort(404);
        }

        if (!$page) return abort(404);

        $meta = $page->translate($lang)->toArray();

        if (isset($meta['title'])) {
            $meta['title'] = strip_tags($meta['title']);
        }

        if (isset($meta['description'])) {
            $meta['description'] = strip_tags($meta['description']);
        }

        if (isset($meta['meta_title'])) {
            $meta['meta_title'] = strip_tags($meta['meta_title']);
        }

        if (isset($meta['meta_description'])) {
            $meta['meta_description'] = strip_tags($meta['meta_description']);
        }

        $this->setMetaTags($meta);
        $this->setOgTags($meta);

        return view('front.home.frontend', [
            'page' => $page,
        ]);
    }

    public function index2($token = null)
    {

        $page = new Pages();

        return view('front.home.frontend', [
            'page' => $page,
        ]);
    }
}

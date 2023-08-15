<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Landing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LandingController extends Controller
{
    public function show(Request $request, $slug)
    {
        $lang = App::getLocale();

        $query = Landing::query()
            ->where('slug', $slug);

        if ($request->has('prevw')) {
            if ($request->get('prevw') == crc32($slug)) { // check
                // good
            } else {
                $query->active();
            }
        } else {
            $query->active();
        }

        $page = $query->first();

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
}

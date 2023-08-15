<?php

namespace App\Traits;

use App\Modules\Setting\Setting;
use Butschster\Head\Facades\Meta;
use Butschster\Head\MetaTags\Entities\Favicon;
use Butschster\Head\Packages\Entities\OpenGraphPackage;

trait MetaTagable
{
    public function setFavicon()
    {
        $favicon = public_path('favicon.ico'); //favicon.ico must be placed in public directory

        if ($favicon) {
            $sizes = [
                '16x16' => [
                    'name' => 'favicon-16x16',
                    'rel' => 'icon',
                ],
                '32x32' => [
                    'name' => 'favicon-32x32',
                    'rel' => 'icon',
                ],
                '192x192' => [
                    'name' => 'favicon-192x192',
                    'rel' => 'icon',
                ],
                '180x180' => [
                    'name' => 'favicon-apple-touch-180x180',
                    'rel' => 'apple-touch-icon',
                ],
            ];

            foreach ($sizes as $size => $params) {
                Meta::addTag(
                    $params['name'],
                    new Favicon(get_image_uri($favicon, $params['name']), [
                        'rel' => $params['rel'],
                        'sizes' => $size,
                    ])
                );
            }
        }
    }

    /**
     * Set page Meta tags
     *
     * @param array $data
     */
    public function setMetaTags(array $data): void
    {
        if (!empty($data['meta_title'])) {
            Meta::setTitle($data['meta_title']);
        } else {
            Meta::setTitle($data['title'] ?? '');
        }

        if (!empty($data['meta_description'])) {
            Meta::setDescription($data['meta_description']);
        }

        if (!empty($data['seo_indexes'])) {
            Meta::setRobots($data['seo_indexes']);
        }

        if (!empty($data['canonical_url'])) {
            Meta::setCanonical($data['canonical_url']);
        } else {
            Meta::removeTag('canonical');
        }

        if (isMultiLang() && !empty($data['relative_url'])) {
            foreach (getSupportedLocales() as $locale => $properties) {
                if ($data['relative_url'] == '/' && isCurrentLocale($locale)) {
                    Meta::setHrefLang($locale, url('/'));
                } elseif (isCurrentLocale($locale)) {
                    Meta::setHrefLang($locale, url($data['relative_url']));
                    //canonical
                    if(empty($data['canonical_url'])) {
                        Meta::setCanonical(url($data['relative_url']));
                    }
                } else {
                    $regex_suffix = "\//";

                    if(strlen($data['relative_url']) == 3 ) { //only lang url
                        $regex_suffix = "/";
                    }

                    Meta::setHrefLang($locale, url('/' . $locale . preg_replace("/^" . implode("|", array_keys(getSupportedLocales())) . $regex_suffix, '', $data['relative_url'], 1)));
                }
            }
        }
    }

    /**
     * Set page OGTags
     *
     * @param array $data
     */
    public function setOgTags(array $data): void
    {
        $og = new OpenGraphPackage('pages');

        $og->setSiteName(config('app.name'));
        $og->setLocale(config('translatable.locale_regionals.' . app()->getLocale(), app()->getLocale()));
        $og->addAlternateLocale(...collect(config('translatable.locale_regionals', app()->getLocale()))->except(app()->getLocale())->toArray());

        if (!empty($data['url'])) {
            $og->setUrl($data['url']);
        }

        if (!empty($data['og_type'])) {
            $og->setType($data['og_type']);
        }

        if (!empty($data['og_title'])) {
            $og->setTitle($data['og_title']);
        } elseif (!empty($data['meta_title'])) {
            $og->setTitle($data['meta_title']);
        } else {
            $og->setTitle($data['title'] ?? '');
        }

        if (!empty($data['og_description'])) {
            $og->setDescription($data['og_description']);
        } elseif (!empty($data['meta_description'])) {
            $og->setDescription($data['meta_description']);
        } else {
            $og->setDescription($data['description'] ?? '');
        }

        if (!empty($data['og_image'])) {
            $og->addImage(get_image_uri($data['og_image']));
        } else {
            $og->addImage(get_image_uri(app(Setting::class)->get('default_og_image')));
        }

        $og->toHtml();

        Meta::registerPackage($og);
    }
}

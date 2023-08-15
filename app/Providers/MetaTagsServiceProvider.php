<?php

namespace App\Providers;

use App\Modules\Setting\Setting;
use Butschster\Head\MetaTags\Entities\Favicon;
use Butschster\Head\MetaTags\Meta;
use Butschster\Head\Contracts\MetaTags\MetaInterface;
use Butschster\Head\Contracts\Packages\ManagerInterface;
use Butschster\Head\Providers\MetaTagsApplicationServiceProvider as ServiceProvider;

class MetaTagsServiceProvider extends ServiceProvider
{
    protected function packages()
    {
        // Create your own packages here
    }

    // if you don't want to change anything in this method just remove it
    protected function registerMeta(): void
    {
        $this->app->singleton(MetaInterface::class, function () {
            $meta = new Meta(
                $this->app[ManagerInterface::class],
                $this->app['config']
            );

            $favicon = get_image_uri(app(Setting::class)->get('favicon', config('translatable.locale')));
            if (!$favicon) {
                $favicon = '/favicon.ico';
            }
            $meta->addTag(
                'favicon',
                new Favicon($favicon, [
                    'rel' => 'icon',
                    'sizes' => '180x180',
                ])
            );

            // This method gets default values from config and creates tags, includes default packages, e.t.c
            // If you don't want to use default values just remove it.
            $meta->initialize();

            return $meta;
        });
    }
}

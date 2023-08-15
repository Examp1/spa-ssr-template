<?php

namespace App\Modules\Constructor;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use Illuminate\View\View;

class Constructor
{
    /**
     * @var Application
     */
    protected Application $app;

    /**
     * @var Repository
     */
    protected Repository $config;

    /**
     * Constructor constructor.
     *
     * @param Application $app
     * @param Repository|null $config
     */
    public function __construct(Application $app, Repository $config = null)
    {
        $this->app = $app;

        $this->config = $config;
    }

    /**
     * Output constructor
     *
     * @param $entity
     * @param $lang
     * @return Factory|View
     */
    public function output($entity, $lang)
    {
        $entity = $entity instanceof Model ? $entity : $this->app->make($entity);

        $placeholder = $this->config('components_placeholder', '#componentPlaceholder');

        return view('constructor::index', compact('entity','lang', 'placeholder'));
    }

    /**
     * Config constructor file
     *
     * @param string $key
     * @param null $default
     * @return mixed
     */
    protected function config(string $key, $default = null)
    {
        return $this->config->get('constructor.' . $key, $default);
    }
}

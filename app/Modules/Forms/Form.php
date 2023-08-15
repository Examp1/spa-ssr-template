<?php

namespace App\Modules\Forms;

use Illuminate\Contracts\Cache\Factory as CacheInterface;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use App\Modules\Forms\Models\Form as FormModel;

class Form
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var Repository
     */
    protected $config;

    /**
     * @var FormModel
     */
    protected $model;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * Widget constructor.
     *
     * @param Application $app
     * @param Repository $config
     * @param FormModel $model
     * @param CacheInterface $cache
     */
    public function __construct(Application $app, Repository $config, FormModel $model, CacheInterface $cache)
    {
        $this->app = $app;

        $this->config = $config;

        $this->model = $model;

        $this->cache = $cache;
    }

    /**
     * Output widgets in area
     *
     * @param int $id
     * @return false|string
     */
    public function show(?int $id)
    {
        $lang = $this->app->getLocale();

        $form = $this->cache->rememberForever('form_output_' . $lang . '_' . $id, function () use ($id, $lang) {
            $form = $this->model()
                ->where('lang', $lang)
                ->find($id);

            return $form !== null ? $form->toArray() : null;
        });

        return $form;
    }


    /**
     * Return all widgets
     *
     * @param string $lang
     * @param null $template
     * @param null $group
     * @return Collection
     */
    public function index(string $lang)
    {
        $q = $this->model()
            ->where('lang', $lang)
            ->orderBy('created_at', 'desc');

        $model = $q->get();

        return $model;
    }

    /**
     * @param int $id
     * @return Model
     */
    public function edit(int $id): Model
    {
        return $this->model()->findOrFail($id);
    }

    /**
     * @param array $data
     * @return Model|null
     */
    public function store(array $data): ?Model
    {
        return $this->model()->create($data);
    }

    /**
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function update(array $data, int $id): bool
    {
        $form = $this->model()->findOrFail($id);

        return $form->update($data);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function destroy(int $id): bool
    {
        $form = $this->model()->findOrFail($id);

        return $form->delete();
    }

    /**
     * Return empty model object
     *
     * @return FormModel
     */
    public function model(): FormModel
    {
        return clone $this->model;
    }

    public static function fieldsList()
    {
        $list = [];

        foreach (config("forms.fields") as $item){
            $list[$item['type']] = $item['label'];
        }

        return $list;
    }
}

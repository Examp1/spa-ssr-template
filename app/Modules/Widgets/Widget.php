<?php

namespace App\Modules\Widgets;

use Illuminate\Contracts\Cache\Factory as CacheInterface;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use App\Modules\Widgets\Models\Widget as WidgetModel;
use App\Modules\Widgets\Contracts\Widget as WidgetInterface;

class Widget
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
     * @var WidgetModel
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
     * @param WidgetModel $model
     * @param CacheInterface $cache
     */
    public function __construct(Application $app, Repository $config, WidgetModel $model, CacheInterface $cache)
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

        $widget = $this->cache->rememberForever('widget_output_' . $lang . '_' . $id, function () use ($id, $lang) {
            $widget = $this->model()
                ->where('lang', $lang)
                ->find($id);

            return $widget !== null ? $widget->toArray() : null;
        });

        if ($widget) {
            $instance = $this->config->get('widgets.widgets.' . $widget['instance']);

            if (class_exists($instance)) {
                $object = $this->app->make($instance, ['data' => $widget['data']]);

                if ($object instanceof WidgetInterface) {
                    return $object->execute();
                }
            }
        }

        return '';
    }

    /**
     * Return widgets list
     *
     * @return array
     */
    public function list(): array
    {
        return collect($this->config->get('widgets.widgets', []))
            ->filter(function ($class, $name) {
                return class_exists($class);
            })
            ->map(function ($class, $name) {
                return $class::$name;
            })
            ->toArray();
    }

    public function listPreview(): array
    {
        return collect($this->config->get('widgets.widgets', []))
            ->filter(function ($class) {
                return class_exists($class);
            })
            ->map(function ($class, $preview) {
                return $class::$preview ?? '';
            })
            ->toArray();
    }

    /**
     * Widget fields
     *
     * @param string $widget
     * @return array
     */
    public function fields(string $widget): array
    {
        $object = $this->object($widget);

        if (!$object) {
            return [];
        }

        $alt = [];

        foreach ($object->fields() as $item) {
            if (isset($item['type']) && $item['type'] === 'image' && array_key_exists('alt_name', $item)) {
                $alt[] = [
                    'type'  => 'alt',
                    'name'  => $item['alt_name'],
                    'rules' => $item['rules'],
                    'value' => $item['alt_value']
                ];
            }
        }

        $newArr = $object->fields();

        if (count($alt)) {
            $newArr = array_merge($newArr, $alt);
        }

        return collect($newArr)
            ->pluck('rules', 'name')
            ->filter()
            ->toArray();
    }

    public function messages(string $widget): array
    {
        $object = $this->object($widget);

        if (!$object) {
            return [];
        }

        $alt = [];

        foreach ($object->fields() as $item) {
            if (isset($item['type']) && $item['type'] === 'image' && array_key_exists('alt_name', $item)) {
                $alt[] = [
                    'type'    => 'alt',
                    'name'    => $item['alt_name'],
                    'rules'   => $item['rules'],
                    'message' => $item['message'] ?? [],
                    'value'   => $item['alt_value']
                ];
            }
        }

        $newArr = $object->fields();

        if (count($alt)) {
            $newArr = array_merge($newArr, $alt);
        }

        $messages = [];

        foreach ($newArr as $item){
            if(isset($item['message']) && count($item['message'])){
                $messages = array_merge($messages,$item['message']);
            }
        }

       return $messages;
    }

    /**
     * Return all widgets
     *
     * @param string $lang
     * @param null $template
     * @param null $group
     * @return Collection
     */
    public function index(string $lang, $template = null, $group = null)
    {
        $q = $this->model()
            ->where('lang', $lang)
            ->orderBy('instance', 'asc')
            ->orderBy('created_at', 'desc');

        if ($template) {
            $q->where('instance', $template);
        }

        $model = $q->get();

        if($group){
            $resModels = collect();

            foreach ($model as $item) {
                if(in_array($group,array_keys($item->getGroups()))){
                    $resModels->add($item);
                }
            }

            $model = $resModels;
        }

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
        $widget = $this->model()->findOrFail($id);

        return $widget->update($data);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function destroy(int $id): bool
    {
        $widget = $this->model()->findOrFail($id);

        return $widget->delete();
    }

    /**
     * Return widget object
     *
     * @param string $instance
     * @return mixed|string|null
     */
    public function object(string $instance)
    {
        $widget = $this->config->get('widgets.widgets.' . $instance);

        if (class_exists($widget)) {
            $widget = $this->app->make($widget);

            if ($widget instanceof WidgetInterface) {
                return $widget;
            }
        }

        return null;
    }

    /**
     * Return empty model object
     *
     * @return WidgetModel
     */
    public function model(): WidgetModel
    {
        return clone $this->model;
    }
}

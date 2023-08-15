<?php

namespace App\Modules\Constructor\Traits;

use App\Modules\Constructor\ComponentFactory;
use App\Modules\Constructor\Models\Constructor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Arr;

trait Constructorable
{
    /**
     * @var mixed
     */
    private $entityConstructorId;

    /**
     * Auto save metadata after saved common model
     *
     * @return void
     */
    public static function bootConstructorable(): void
    {
        static::saved(function (Model $model) {
            return $model->saveConstructor();
        });

        static::deleting(function (Model $model) {
            if (!method_exists($model, 'trashed') || (method_exists($model, 'trashed') && $model->trashed())) {
                $model->constructor()->delete();
            }
        });
    }


    /**
     * Fill attributes constructor table
     *
     * @param array $attributes
     * @return mixed
     */
    public function fillConstructorable(array $attributes = [])
    {
        if (!empty($attributes)) {
            $fieldsName = config('constructor.fields_name', 'constructor');

            $placeholder = config('constructor.components_placeholder', '#componentPlaceholder');

            $data = isset($attributes[$fieldsName])
                ? $attributes[$fieldsName]
                : $attributes;

            $data = Arr::except($data, $placeholder);

            $this->constructor->setAttribute('data', $data);
        }
    }

    /**
     * @param int|null $id
     * @return mixed
     */
    public function entityConstructorId($id = null)
    {
        if (!is_null($this->entityConstructorId)) {
            return $this->entityConstructorId;
        }

        if (!is_null($id)) {
            $this->entityConstructorId = $id;

            return $this->entityConstructorId;
        }

        if (isset($this->entityAttribute)) {
            $this->entityConstructorId = $this->{$this->entityAttribute};
        }

        return $this->entityConstructorId;
    }

    /**
     * @return MorphOne
     */
    public function constructor(): MorphOne
    {
        return $this->morphOne(Constructor::class, 'constructorable')->withDefault();
    }

    /**
     * Return constructor data
     *
     * @return mixed
     */
    public function getConstructorData()
    {
        return $this->constructor->data;
    }

    /**
     * Available constructor components
     *
     * @return array
     */
    public function availableConstructorComponents(): array
    {
        $placeholder = config('constructor.components_placeholder');

        return collect($this->constructorComponents())
            ->mapWithKeys(function ($component, $name) use($placeholder) {
                $object = $this->makeComponentObject($name, $placeholder, $component);
                $component = $object;

                return $object->isViews()
                    ? [$name => $component]
                    : [$name => null];
            })->filter()->values()->toArray();
    }

    /**
     * Defined constructor components with filled data
     *
     * @param array $input
     * @return array
     */
    public function definedConstructorComponents(array $input = []): array
    {
        $placeholder = config('constructor.components_placeholder');
        $components = empty($input) ? $this->getConstructorData() : $input;

        return collect($components)
            ->except($placeholder)
            ->map(function ($component, $key) {
                if (isset($component['component']) && in_array($component['component'], array_keys($this->constructorComponents()))) {
                    $object = $this->makeComponentObject($component['component'], $key, $this->getComponent($component['component']), $component);

                    return  $object->isViews() ? $object : null;
                }

                return null;
            })->filter()->values()->toArray();
    }

    /**
     * Component maker
     *
     * @param $name
     * @param $key
     * @param $component
     * @param array $data
     * @return ComponentFactory
     */
    private function makeComponentObject($name, $key, $component, array $data = [])
    {
        return new ComponentFactory($name, $key, $component, $data);
    }

    /**
     * Return component from available components by name
     *
     * @param string $component
     * @return array
     */
    private function getComponent(string $component): array
    {
        $components = $this->constructorComponents();

        return isset($components[$component]) ? $components[$component] : [];
    }

    /**
     * Rules validation for input component fields from constructor
     *
     * @param array $input
     * @return array
     */
    public function rulesConstructorComponentFields(array $input = []): array
    {
        $fieldsName = config('constructor.fields_name', 'constructor');
        $placeholder = config('constructor.components_placeholder', '#componentPlaceholder');

        return collect($input)
            ->except($placeholder)
            ->mapWithKeys(function ($component, $key) use($fieldsName) {
                $rules = $this->getComponent($component['component'])['rules'];

                return [$key => collect($rules)->mapWithKeys(function ($rule, $field) use($fieldsName, $key) {
                    return [$fieldsName . '.' . $key . '.' . $field => $rule];
                })];
            })->collapse()->toArray();
    }

    /**
     * Messages validation for input component fields from constructor
     *
     * @param array $input
     * @return array
     */
    public function messagesConstructorComponentFields(array $input = []): array
    {
        $fieldsName = config('constructor.fields_name', 'constructor');
        $placeholder = config('constructor.components_placeholder', '#componentPlaceholder');

        return collect($input)
            ->except($placeholder)
            ->mapWithKeys(function ($component, $key) use($fieldsName) {
                $rules = $this->getComponent($component['component'])['messages'] ?? [];

                return [$key => collect($rules)->mapWithKeys(function ($rule, $field) use($fieldsName, $key) {
                    return [$fieldsName . '.' . $key . '.' . $field => $rule];
                })];
            })->collapse()->toArray();
    }

    /**
     * Save constructor components
     *
     * @return bool
     */
    public function saveConstructor(): bool
    {
        $saved = true;

        if (!$this->relationLoaded('constructor')) {
            return $saved;
        }

        if ($saved) {
            if (!empty($connectionName = $this->getConnectionName())) {
                $this->constructor->setConnection($connectionName);
            }

            $this->constructor->setAttribute('constructorable_id', $this->getKey());
            $this->constructor->setAttribute('constructorable_type', get_class($this));

            if (empty($this->constructor->getAttribute('data'))) {
                $this->constructor->delete();
            } else {
                $this->constructor->save();
            }
        }

        return $saved;
    }
}

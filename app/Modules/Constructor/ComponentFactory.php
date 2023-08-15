<?php

namespace App\Modules\Constructor;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class ComponentFactory
{
    /**
     * @var string
     */
    protected string $name;

    /**
     * @var string
     */
    protected string $key;

    /**
     * @var array
     */
    protected array $component;

    /**
     * @var array
     */
    protected array $data;

    /**
     * ComponentFactory constructor.
     *
     * @param string $name
     * @param string $key
     * @param array $component
     * @param array $data
     */
    public function __construct(string $name, string $key, array $component, array $data = [])
    {
        $this->name = $name;

        $this->key = $key;

        $this->component = $component;

        $this->data = $data;
    }

    /**
     * Component styles
     *
     * @return Factory|View
     */
    public function styles()
    {
        return view('constructor::components.' . $this->name() . '.styles');
    }

    /**
     * Component scripts
     *
     * @return Factory|View
     */
    public function scripts($lang = null)
    {
        return view('constructor::components.' . $this->name() . '.scripts', ['lang' => $lang]);
    }

    /**
     * Return key component
     *
     * @return string
     */
    public function key(): string
    {
        return $this->key;
    }

    /**
     * Return name component
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Return label component
     *
     * @return string
     */
    public function label(): string
    {
        return $this->component['label'] ?? '';
    }

    /**
     * Return position component
     *
     * @return int
     */
    public function position(): int
    {
        return $this->data['position'] ?? 0;
    }

    /**
     * Return display or hide component
     *
     * @return int
     */
    public function visibility(): int
    {
        return (isset($this->data['visibility']) && $this->data['visibility']) == 1 || !isset($this->data['visibility']) ? 1 : 0;
    }

    /**
     * Return params component
     *
     * @return array
     */
    public function params(): array
    {
        return $this->component['params'] ?? [];
    }

    /**
     * Return content component
     *
     * @return array
     */
    public function content(): array
    {
        return $this->data['content'] ?? [];
    }

    /**
     * Check component view files
     *
     * @return bool
     */
    public function isViews(): bool
    {
        return view()->exists('constructor::components.' . $this->name() . '.html')
            && view()->exists('constructor::components.' . $this->name() . '.styles')
            && view()->exists('constructor::components.' . $this->name() . '.scripts');
    }

    /**
     * Output component html
     *
     * @return Factory|View
     */
    public function show($lang = null)
    {
        return view('constructor::components.' . $this->name() . '.html', [
            'key'        => $this->key(),
            'name'       => $this->name(),
            'label'      => $this->label(),
            'params'     => $this->params(),
            'position'   => $this->position(),
            'visibility' => $this->visibility(),
            'content'    => $this->content(),
            'lang'       => $lang
        ]);
    }
}

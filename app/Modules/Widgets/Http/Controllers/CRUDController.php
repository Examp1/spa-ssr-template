<?php

namespace App\Modules\Widgets\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Widgets\Http\Requests\WidgetRequest;
use App\Modules\Widgets\Widget;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

class CRUDController extends Controller
{
    /**
     * @var Widget
     */
    private $widget;

    /**
     * WidgetController constructor.
     *
     * @param Widget $widget
     */
    public function __construct(Widget $widget)
    {
        $this->widget = $widget;
        $this->middleware('permission:widgets_view')->only('index');
        $this->middleware('permission:widgets_create')->only('store');
        $this->middleware('permission:widgets_edit')->only('edit','update');
        $this->middleware('permission:widgets_delete')->only('destroy');
    }

    /**
     * Output widgets list
     *
     * @param Request $request
     * @return Factory|View
     * @throws AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('widgets.view');

        $lang = $request->input(config('widgets.request_lang_key'), app()->getLocale());

        $template = $request->input('template') ?? null;

        $group = $request->input('group') ?? null;

        $widgets = $this->widget->index($lang,$template,$group);

        $list = $this->widget->list();

        $listPreview = $this->widget->listPreview();

        return view('widgets::crud.index', compact('widgets', 'list', 'listPreview'));
    }

    /**
     * Store widget
     *
     * @param WidgetRequest $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(WidgetRequest $request)
    {
        $this->authorize('widgets.create');

        $widget = $this->widget->store($request->all());

        $widget->setMainId();

        return $widget
            ? redirect()
                ->route(config('widgets.route_name_prefix', 'admin.') . 'widgets.edit', ['widget' => $widget, config('widgets.request_lang_key') => $widget->lang])
                ->with('notification.success', trans('widgets::strings.notifications.save.success'))
            : redirect()
                ->back()
                ->withInput()
                ->with('notification.error', trans('widgets::strings.notifications.save.error'));
    }

    public function copy($from_id,$to_lang)
    {
        $fromWidget = $this->widget->edit($from_id);

        $newWidget = new \App\Modules\Widgets\Models\Widget();
        $newWidget->main_id = $fromWidget->main_id;
        $newWidget->name = $fromWidget->name;
        $newWidget->instance = $fromWidget->instance;
        $newWidget->data = $fromWidget->data;
        $newWidget->lang = $to_lang;
        $newWidget->static = $fromWidget->static;

        if(!$newWidget->save()){
            return redirect()->back()->with('error','Виникла помилка копіювання!');
        } else {
            return redirect('/admin/widgets/'.$newWidget->id.'/edit?lang=' . $newWidget->lang)->with('success','Віджет успішно скопійовано!');
        }
    }

    /**
     * Edit widget
     *
     * @param $id
     * @return Factory|View
     * @throws AuthorizationException
     */
    public function edit($id)
    {
        $this->authorize('widgets.update');

        $widget = $this->widget->edit($id);

        $object = $this->widget->object($widget->instance);

        if (!$object) {
            abort(404);
        }

        return view('widgets::crud.edit', compact('object', 'widget'));
    }

    /**
     * Update widget
     *
     * @param WidgetRequest $request
     * @param $id
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(WidgetRequest $request, $id)
    {
        $this->authorize('widgets.update');

        $widget = $this->widget->edit($id);

        $fields = $this->widget->fields($widget->instance);

        $messages = $this->widget->messages($widget->instance);

        $request->validate($fields,$messages);

        $data = array_merge(
            $request->except(array_keys($fields)),
            ['data' => $request->only(array_keys($fields))]
        );

        return $this->widget->update($data, $id)
            ? redirect()
                ->back()
                ->with('success', 'Віджет успішно оновлено!')
            : redirect()
                ->back()
                ->withInput()
                ->with('error', 'Помилка поновлення!');
    }

    public function copy2($id)
    {
        $fromWidget = $this->widget->edit($id);

        $newWidget = $fromWidget->replicate();
        $newWidget->name = 'Copy ' . $fromWidget->name;
        $newWidget->setMainId();

        if(!$newWidget->save()){
            return redirect()->back()->with('error','Виникла помилка копіювання!');
        } else {
            return redirect('/admin/widgets/'.$newWidget->id.'/edit?lang=' . $newWidget->lang)->with('success','Віджет успішно скопійовано!');
        }
    }

    /**
     * Delete widget
     *
     * @param $id
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy($id)
    {
        $this->authorize('widgets.delete');

        return $this->widget->destroy($id)
            ? redirect()
                ->back()
                ->with('success', 'Віджет успішно видалено!')
            : redirect()
                ->back()
                ->with('error', 'Помилка видалення!');
    }

    public function deleteSelected(Request $request)
    {
        $ids = json_decode($request->get('ids'),true);

        if(count($ids)){
            foreach ($ids as $id){
                $this->widget->destroy($id);
            }

            return redirect()->back()->with('success','Записи успішно видалено!');
        }

        return redirect()->back()->with('error','Помилка видалення!');
    }
}

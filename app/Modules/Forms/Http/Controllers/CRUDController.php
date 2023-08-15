<?php

namespace App\Modules\Forms\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Forms\Http\Requests\FormRequest;
use App\Modules\Forms\Http\Requests\FormRequestEdit;
use App\Modules\Forms\Form;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

class CRUDController extends Controller
{
    /**
     * @var Form
     */
    private $form;

    /**
     * WidgetController constructor.
     *
     * @param Form $form
     */
    public function __construct(Form $form)
    {
        $this->form = $form;
        $this->middleware('permission:forms_view')->only('index');
        $this->middleware('permission:forms_create')->only('store');
        $this->middleware('permission:forms_edit')->only('edit', 'update');
        $this->middleware('permission:forms_delete')->only('destroy');
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
        $this->authorize('forms.view');

        $lang = $request->input(config('forms.request_lang_key'), app()->getLocale());

        $forms = $this->form->index($lang);

        $groupsData = config('telegram.groups');
        $groups = [];

        foreach ($groupsData as $item) {
            $groups[$item['id']] = $item;
        }

        return view('forms::crud.index', compact('forms', 'groups'));
    }

    /**
     * Store widget
     *
     * @param FormRequest $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(FormRequest $request)
    {
        $this->authorize('forms.create');

        $form = $this->form->store($request->all());

        $form->setMainId();

        return $form
            ? redirect()
            ->route(config('forms.route_name_prefix', 'admin.') . 'forms.edit', ['form' => $form, config('forms.request_lang_key') => $form->lang])
            ->with('success', __('Created successfully!'))
            : redirect()
            ->back()
            ->withInput()
            ->with('error', __('Error!'));
    }

    public function copy($from_id, $to_lang)
    {
        $fromForm = $this->form->edit($from_id);

        $newForm = new \App\Modules\Forms\Models\Form();
        $newForm->main_id = $fromForm->main_id;
        $newForm->name = $fromForm->name;
        $newForm->data = $fromForm->data;
        $newForm->lang = $to_lang;

        if (!$newForm->save()) {
            return redirect()->back()->with('error', __('Error!'));
        } else {
            return redirect('/admin/forms/' . $newForm->id . '/edit?lang=' . $newForm->lang)->with('success', __('Сopied successfully!'));
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
        $this->authorize('forms.update');

        $form = $this->form->edit($id);

        $data = json_decode($form->data, true);

        if (is_array($data) && count($data)) {
            foreach ($data as $key => $item) {
                foreach (config("forms.fields") as $item2) {
                    if ($item2['type'] == $item['type']) {
                        $data[$key]['label'] = $item2['label'];
                    }
                }
            }
        } else {
            $data = [];
        }

        return view('forms::crud.edit', compact('form', 'data'));
    }

    /**
     * Update widget
     *
     * @param FormRequestEdit $request
     * @param $id
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(FormRequestEdit $request, $id)
    {
        $this->authorize('forms.update');

        $form = $this->form->edit($id);

        $data = $request->except(['_token', '_method']);

        $newData = [];

        if (isset($data['data']) && is_array($data['data']) && count($data['data'])) {
            foreach ($data['data'] as $item) {
                $newData[] = $item;
            }
        }

        $data['data'] = json_encode($newData, JSON_UNESCAPED_UNICODE);

        return $this->form->update($data, $id)
            ? redirect()
            ->back()
            ->with('success', __('Updated successfully!'))
            : redirect()
            ->back()
            ->withInput()
            ->with('error', __('Error!'));
    }

    public function copy2($id)
    {
        $fromForm = $this->form->edit($id);

        $newForm = $fromForm->replicate();
        $newForm->name = 'Copy ' . $fromForm->name;
        $newForm->setMainId();

        if (!$newForm->save()) {
            return redirect()->back()->with('error', __('Error!'));
        } else {
            return redirect('/admin/forms?lang=' . $newForm->lang)->with('success', __('Сopied successfully!'));
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
        $this->authorize('forms.delete');

        return $this->form->destroy($id)
            ? redirect()
            ->back()
            ->with('success', __('Deleted successfully!'))
            : redirect()
            ->back()
            ->with('error', __('Error!'));
    }

    public function deleteSelected(Request $request)
    {
        $ids = json_decode($request->get('ids'), true);

        if (count($ids)) {
            foreach ($ids as $id) {
                $this->form->destroy($id);
            }

            return redirect()->back()->with('success', __('Deleted successfully!'));
        }

        return redirect()->back()->with('error', __('Error!'));
    }

    public function addField(Request $request)
    {
        foreach (config("forms.fields") as $item) {
            if ($item['type'] == $request->get('type')) {
                $field = $item;
            }
        }

        if (isset($field)) {
            $field['visibility'] = 1;
            $field['collapse'] = 'show';
        }

        $fieldHTML = \Illuminate\Support\Facades\View::make('forms::fields.' . $request->get('type'), [
            'field' => $field ?? null,
            'is_new' => true
        ])->render();

        return $fieldHTML;
    }
}

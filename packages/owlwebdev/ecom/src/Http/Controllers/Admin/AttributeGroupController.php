<?php

namespace Owlwebdev\Ecom\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Owlwebdev\Ecom\Models\AttributeGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttributeGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:attribute_groups_view')->only('index');
        $this->middleware('permission:attribute_groups_create')->only('create', 'store');
        $this->middleware('permission:attribute_groups_edit')->only('edit', 'update');
        $this->middleware('permission:attribute_groups_delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $name = $request->get('name');

        /* Sorting */
        $sort  = 'created_at';
        $order = 'desc';
        $tableName = 'attribute_groups';

        if ($request->has('sort') && $request->has('order')) {
            $sort  = $request->get('sort');
            $order = $request->get('order');

            if (in_array($sort, ['name'])) {
                $tableName = 'attribute_group_translations';
            }
        }

        $model = AttributeGroup::query()
            ->leftJoin('attribute_group_translations', 'attribute_group_translations.attribute_group_id', '=', 'attribute_groups.id')
            ->where('attribute_group_translations.lang', config('translatable.locale'))
            ->orderBy($tableName . '.' . $sort, $order)
            ->where(function ($q) use ($name) {
                if ($name != '') {
                    $q->where('attribute_group_translations.name', 'like', '%' . $name . '%');
                }
            })
            ->select('attribute_groups.*')
            ->paginate(50);

        return view('ecom::admin.attribute_groups.index', [
            'model' => $model
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new AttributeGroup();

        $model->order = 0;

        $localizations = config('translatable.locales');

        return view('ecom::admin.attribute_groups.create', [
            'model'         => $model,
            'localizations' => $localizations
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new AttributeGroup();

        DB::beginTransaction();

        try {
            $model->fill($request->all());

            if (!$model->save()) {
                DB::rollBack();
            }

            foreach (config('translatable.locales') as $lang => $item) {
                $model->translateOrNew($lang)->fill($request->input('page_data.' . $lang, []));

                if (!$model->save()) {
                    DB::rollBack();
                }
            }
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()->route('attribute_groups.index')->with('error', __('Error!') . $e->getMessage());
        }

        DB::commit();

        return redirect()->route('attribute_groups.index')->with('success', __('Success!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * @param AttributeGroup $attribute_group
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(AttributeGroup $attribute_group)
    {
        $localizations = config('translatable.locales');

        return view('ecom::admin.attribute_groups.edit', [
            'model'         => $attribute_group,
            'data'          => $attribute_group->getTranslationsArray(),
            'localizations' => $localizations
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /* @var $model AttributeGroup */
        $model = AttributeGroup::query()->where('id', $id)->first();

        DB::beginTransaction();

        try {
            $model->fill($request->all());

            if (!$model->save()) {
                DB::rollBack();
            }

            $model->deleteTranslations();

            foreach (config('translatable.locales') as $lang => $item) {
                $model->translateOrNew($lang)->fill($request->input('page_data.' . $lang, []));

                if (!$model->save()) {
                    DB::rollBack();
                }
            }
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()->route('attribute_groups.index')->with('error', __('Error!') . $e->getMessage());
        }

        DB::commit();

        return redirect()->back()->with('success', __('Success!'));
    }

    /**
     * @param AttributeGroup $attribute
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(AttributeGroup $attribute)
    {
        $attribute->deleteTranslations();
        $attribute->delete();

        return redirect()->back()->with('success', __('Success!'));
    }

    public function sort(Request $request)
    {
        $item = AttributeGroup::query()->where('id', $request->get('id'))->first();
        $item->order = $request->get('order');
        $item->save();

        return redirect()->route('attribute_groups.index')->with('success', __('Success!'));
    }

    public function deleteSelected(Request $request)
    {
        $ids = json_decode($request->get('ids'), true);

        if (count($ids)) {
            foreach ($ids as $id) {
                $attribute = AttributeGroup::query()->where('id', $id)->first();
                $attribute->deleteTranslations();
                $attribute->delete();
            }

            return redirect()->route('attribute_groups.index')->with('success', __('Success!'));
        }

        return redirect()->route('attribute_groups.index')->with('error', __('Error!'));
    }
}

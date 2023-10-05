<?php

namespace Owlwebdev\Ecom\Http\Controllers\Admin;




use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Owlwebdev\Ecom\Models\Filter;
use Owlwebdev\Ecom\Models\Attribute;
use Owlwebdev\Ecom\Models\AttributeGroup;
use Owlwebdev\Ecom\Models\ProductAttributes;
use Owlwebdev\Ecom\Http\Requests\AttributeRequest;
use Cviebrock\EloquentSluggable\Services\SlugService;

class AttributeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:attributes_view')->only('index');
        $this->middleware('permission:attributes_create')->only('create', 'store');
        $this->middleware('permission:attributes_edit')->only('edit', 'update');
        $this->middleware('permission:attributes_delete')->only('destroy');
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
        $tableName = 'attributes';

        if ($request->has('sort') && $request->has('order')) {
            $sort  = $request->get('sort');
            $order = $request->get('order');

            if (in_array($sort, ['name'])) {
                $tableName = 'attribute_translations';
            }
        }

        $model = Attribute::query()
            ->leftJoin('attribute_translations', 'attribute_translations.attribute_id', '=', 'attributes.id')
            ->where('attribute_translations.lang', config('translatable.locale'))
            ->orderBy($tableName . '.' . $sort, $order)
            ->where(function ($q) use ($name) {
                if ($name != '') {
                    $q->where('attribute_translations.name', 'like', '%' . $name . '%');
                }
            })
            ->select('attributes.*')
            ->paginate(50);

        return view('ecom::admin.attributes.index', [
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
        $model = new Attribute();
        $groups = AttributeGroup::all()->pluck('name', 'id')->toArray();

        $model->order = 0;

        $localizations = config('translatable.locales');

        return view('ecom::admin.attributes.create', [
            'model'         => $model,
            'groups'        => $groups,
            'localizations' => $localizations
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AttributeRequest $request)
    {
        $model = new Attribute();

        DB::beginTransaction();

        try {
            if (empty($request->input('slug', ''))) {
                $request->merge(array('slug' => SlugService::createSlug(Attribute::class, 'slug', $request->input('page_data.' . config('translatable.locale') . '.name'))));
            }

            if (empty($request->input('order', ''))) {
                $request->merge(array('order' => 0));
            }

            $model->fill($request->all());

            if (!$model->save()) {
                DB::rollBack();
            }

            $model->attributeGroups()->sync($request->get('attribute_groups'));

            foreach (config('translatable.locales') as $lang => $item) {
                $model->translateOrNew($lang)->fill($request->input('page_data.' . $lang, []));

                if (!$model->save()) {
                    DB::rollBack();
                }
            }

            // sync filters
            foreach ($model->attributeGroups as $group) {
                Filter::query()->updateOrCreate([
                    'attribute_id'       => $model->id,
                    'attribute_group_id' => $group->id,
                ], [
                    'attribute_id'       => $model->id,
                    'attribute_group_id' => $group->id,
                    'display'            => 'select',
                    'expanded'           => 0,
                    'logic'              => 1,
                    'is_main'            => 0,
                    'parent_id'          => 0,
                    'order'              => 0,
                ]);
            }

            Cache::flush();
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()->route('attributes.index')->with('error', __('Error!') . $e->getMessage());
        }

        DB::commit();

        return redirect()->route('attributes.index')->with('success', __('Success!'));
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
     * @param Attribute $attribute
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Attribute $attribute)
    {
        $groups = AttributeGroup::all()->pluck('name', 'id')->toArray();
        $localizations = config('translatable.locales');

        return view('ecom::admin.attributes.edit', [
            'model'         => $attribute->load('attributeGroups'),
            'groups'        => $groups,
            'data'          => $attribute->getTranslationsArray(),
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
    public function update(AttributeRequest $request, $id)
    {
        /* @var $model Attributes */
        $model = Attribute::query()->where('id', $id)->first();

        DB::beginTransaction();

        try {
            if (empty($request->input('slug', ''))) {
                $request->merge(array('slug' => SlugService::createSlug(Attribute::class, 'slug', $request->input('page_data.' . config('translatable.locale') . '.name'))));
            }

            if (empty($request->input('order', ''))) {
                $request->merge(array('order' => 0));
            }

            $model->fill($request->all());

            if (!$model->save()) {
                DB::rollBack();
            }

            $model->deleteTranslations();
            $model->attributeGroups()->sync($request->get('attribute_groups'));

            foreach (config('translatable.locales') as $lang => $item) {
                $model->translateOrNew($lang)->fill($request->input('page_data.' . $lang, []));

                if (!$model->save()) {
                    DB::rollBack();
                }
            }
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()->route('attributes.index')->with('error', __('Error!') . $e->getMessage());
        }

        DB::commit();

        return redirect()->back()->with('success', __('Success!'));
    }

    /**
     * @param Attributes $attribute
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Attribute $attribute)
    {
        $attribute->deleteTranslations();
        $attribute->delete();

        return redirect()->route('attributes.index')->with('success', __('Success!'));
    }

    public function sort(Request $request)
    {
        $item = Attribute::query()->where('id', $request->get('id'))->first();
        $item->order = $request->get('order');
        $item->save();

        return redirect()->back()->with('success', __('Success!'));
    }

    public function deleteSelected(Request $request)
    {
        $ids = json_decode($request->get('ids'), true);

        if (count($ids)) {
            foreach ($ids as $id) {
                $attribute = Attribute::query()->where('id', $id)->first();
                $attribute->deleteTranslations();
                $attribute->delete();
            }

            return redirect()->route('attributes.index')->with('success', __('Success!'));
        }

        return redirect()->route('attributes.index')->with('error', __('Error!'));
    }

    public function valuesList(int $attribute_id, string $lang)
    {
        $hintList = ProductAttributes::query()
            ->where('lang', $lang)
            ->where('attribute_id', $attribute_id)
            ->distinct()
            ->pluck('value')
            ->toArray();

        if (empty($hintList)) {
            return response()->json([]);
        }

        return response()->json($hintList);
    }
}

<?php

namespace Owlwebdev\Ecom\Http\Controllers\Admin;


use Owlwebdev\Ecom\Models\Category;
use Owlwebdev\Ecom\Models\AttributeGroup;
use App\Service\Adapter;
use App\Http\Controllers\Controller;
use Owlwebdev\Ecom\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Owlwebdev\Ecom\Models\Translations\CategoryTranslation;
use Cviebrock\EloquentSluggable\Services\SlugService;

class CategoryController extends Controller
{
    /**
     * @var Adapter
     */
    private Adapter $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;

        $this->middleware('permission:categories_view')->only('index');
        $this->middleware('permission:categories_create')->only('create', 'store');
        $this->middleware('permission:categories_edit')->only('edit', 'update');
        $this->middleware('permission:categories_delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        $name = $request->get('name');

        /* Sorting */
        $sort  = 'path';
        $order = 'asc';
        $tableName = 'categories';

        if ($request->has('sort') && $request->has('order')) {
            $sort  = $request->get('sort');
            $order = $request->get('order');

            if (in_array($sort, ['name'])) {
                $tableName = 'category_translations';
            }
        }

        $model = Category::query()
            ->leftJoin('category_translations', 'category_translations.category_id', '=', 'categories.id')
            ->where('category_translations.lang', config('translatable.locale'))
            ->select([
                'categories.*',
                'category_translations.name',
                'category_translations.meta_title AS mTitle',
                'category_translations.meta_description AS mDescription',
                'category_translations.meta_created_as AS mCreatedAs',
                'category_translations.meta_auto_gen AS mAutoGen',
            ])
            ->orderBy($tableName . '.' . $sort, $order)
            ->where(function ($q) use ($status, $name) {
                if ($status != '') {
                    $q->where('categories.status', $status);
                }
                if ($name != '') {
                    $q->where('category_translations.name', 'like', '%' . $name . '%')
                        ->orWhere('category_translations.name', 'like', '%' . $name . '%');
                }
            })
            ->paginate(20);

        return view('ecom::admin.categories.index', [
            'model' => $model,
            'active_count' => Category::query()->active()->count(),
            'notActive_count' => Category::query()->notActive()->count(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new Category();

        $groups = AttributeGroup::all()->pluck('name', 'id')->toArray();

        $model->order = 0;

        $localizations = config('translatable.locales');

        $categories = $model->treeStructure();

        return view('ecom::admin.categories.create', [
            'model'         => $model,
            'groups'         => $groups,
            'localizations' => $localizations,
            'categories'    => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $model = new Category();

        DB::beginTransaction();

        try {
            if (empty($request->input('slug', ''))) {
                $request->merge(array('slug' => SlugService::createSlug(Category::class, 'slug', $request->input('page_data.' . config('translatable.locale') . '.name'))));
            }

            $model->status = $request->get('status') ?? false;

            $model->fill($request->all());

            if (!$model->save()) {
                DB::rollBack();
            }

            $this->setPath($model);

            $main_screen = $request->get('main_screen');

            $image = '';

            foreach (config('translatable.locales') as $lang => $item) {
                $constructorData = $request->get('constructorData');
                $data = $request->input('page_data.' . $lang, []);

                // image copy
                if (empty($image) && !empty($data['image'])) {
                    $image = $data['image'];
                }

                if (empty($data['image']) && !empty($image)) {
                    $data['image'] = $image;
                }

                $data['options'] = isset($data['options']) ? json_encode($data['options'], JSON_UNESCAPED_UNICODE) : json_encode([]);
                $data['meta_auto_gen'] = $data['meta_auto_gen'] ?? false;
                $data['status_lang'] = $data['status_lang'] ?? false;
                $data['main_screen'] = json_encode($main_screen['data'][$lang], JSON_UNESCAPED_UNICODE);
                $model->translateOrNew($lang)->fill(array_merge($data, $constructorData[$lang] ?? []));

                if (!$model->save()) {
                    DB::rollBack();
                }
            }

            $this->adapter->renderConstructorHTML($model);
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()->route('categories.index')->with('error', __('Error!') . $e->getMessage());
        }

        DB::commit();

        if ($request->get('save_method') === 'save_and_back') {
            return redirect()->route('categories.edit', $model->id)->with('success', __('Updated successfully!'));
        } else {
            return redirect()->route('categories.index')->with('success', __('Updated successfully!'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = Category::query()->where('id', $id)->firstOrFail();

        $groups = AttributeGroup::all()->pluck('name', 'id')->toArray();

        $localizations = config('translatable.locales');

        $categories = $model->treeStructure();

        $data = $model->getTranslationsArray();

        foreach ($localizations as $lang => $item) {
            if (isset($data[$lang]['main_screen'])) {
                $data[$lang]['main_screen'] = json_decode($data[$lang]['main_screen'], true);
            }
        }

        return view('ecom::admin.categories.edit', [
            'model'         => $model,
            'groups'        => $groups,
            'data'          => $data,
            'localizations' => $localizations,
            'categories'    => $categories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, $id)
    {
        /* @var $model Category */
        $model = Category::query()->where('id', $id)->first();

        DB::beginTransaction();

        try {
            $model->status = $request->get('status') ?? false;

            $model->fill($request->all());

            if (!$model->save()) {
                DB::rollBack();
            }

            $this->setPath($model);

            $model->deleteTranslations();

            $main_screen = $request->get('main_screen');

            $image = '';

            foreach (config('translatable.locales') as $lang => $item) {
                $constructorData = $request->get('constructorData');
                $data = $request->input('page_data.' . $lang, []);

                // image copy
                if (empty($image) && !empty($data['image'])) {
                    $image = $data['image'];
                }

                if (empty($data['image']) && !empty($image)) {
                    $data['image'] = $image;
                }

                $data['options'] = isset($data['options']) ? json_encode($data['options'], JSON_UNESCAPED_UNICODE) : json_encode([]);
                $data['meta_auto_gen'] = $data['meta_auto_gen'] ?? false;
                $data['status_lang'] = $data['status_lang'] ?? false;
                $data['main_screen'] = json_encode($main_screen['data'][$lang], JSON_UNESCAPED_UNICODE);
                $model->translateOrNew($lang)->fill(array_merge($data, $constructorData[$lang] ?? []));

                if (!$model->save()) {
                    DB::rollBack();
                }
            }

            // save filters
            $filters = request()->get('filters');

            $filters_ids = [];
            if (!empty($filters)) {
                foreach ($filters['id'] as $i => $id) {
                    $filt = $model->filters()->updateOrCreate(
                        [
                            'id' => $id,
                        ],
                        [
                            'link'      => $filters['link'][$i],
                            'order' => $filters['order'][$i],
                        ]
                    );

                    // translations
                    foreach (config('translatable.locales') as $lang => $item) {
                        $filt->translateOrNew($lang)->fill([
                            'name' => $filters['name_' . $lang][$i] ?? '',
                        ]);
                    }

                    $filt->save();

                    $filters_ids[] = $filt->id;
                }
            }

            $model->filters()->whereNotIn('id', $filters_ids)->delete();

            $this->adapter->renderConstructorHTML($model);
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()->route('categories.index')->with('error', __('Error!') . $e->getMessage());
        }

        DB::commit();

        if ($request->get('save_method') === 'save_and_back') {
            return redirect()->back()->with('success', __('Updated successfully!'));
        } else {
            return redirect()->route('categories.index')->with('success', __('Updated successfully!'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->deleteTranslations();
        $category->attributes()->sync([]);
        $category->delete();

        return redirect()->route('categories.index')->with('success', __('Deleted successfully!'));
    }

    public function deleteSelected(Request $request)
    {
        $ids = json_decode($request->get('ids'), true);

        if (count($ids)) {
            foreach ($ids as $id) {
                $model = Category::query()->where('id', $id)->first();
                $model->deleteTranslations();
                Category::query()->where('id', $id)->delete();
            }

            return redirect()->route('categories.index')->with('success', __('Deleted successfully!'));
        }

        return redirect()->route('categories.index')->with('error', __('Error!'));
    }

    /* Generate category path */
    public function setPath($model)
    {
        try {
            $path = '';
            $parent = 0;

            do {
                if (!$parent) {
                    $tmp_slug = $model->slug;
                    $parent = $model->parent_id;
                } else {
                    $parent_model = app(Category::class)->find((int)$parent);
                    $tmp_slug = $parent_model->slug;
                    $parent = $parent_model->parent_id;
                }
                if ($path) {
                    $path = $tmp_slug . '/' . $path;
                } else {
                    $path = $tmp_slug;
                }
            } while ($parent != 0);

            $model->path = $path;
            $model->save();

            $children = app(Category::class)->where('parent_id', '=', (int) $model->id)->get();
            if ($children) {
                foreach ($children as $child) {
                    $this->setPath($child);
                }
            }
            return $path;
        } catch (\Exception $e) {
            dd($e);
            return null;
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function copy($id)
    {
        $modelFrom = Category::query()
            ->with('translations')
            ->where('id', $id)
            ->first();

        DB::beginTransaction();

        try {
            $model = $modelFrom->replicate();
            $model->created_at = Carbon::now();
            $model->slug = SlugService::createSlug(Category::class, 'slug', $modelFrom->slug);
            $model->status = Category::STATUS_NOT_ACTIVE;

            if (!$model->save()) {
                DB::rollBack();
            }

            foreach ($model->translations as $item) {
                $trans = $item->replicate();
                $trans->category_id = $model->id;
                $trans->name = __('Copy of ') . $trans->name;

                if (!$trans->save()) {
                    DB::rollBack();
                }

                $item->load('constructor');

                $constructor = $item->constructor->replicate();
                $constructor->constructorable_id = $trans->id;

                if (!$constructor->save()) {
                    DB::rollBack();
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
        }

        DB::commit();

        return redirect()->route('categories.edit', $model->id)->with('success', __('Сopied successfully!'));
    }

    public function metaGenerate(Request $request)
    {
        $model = Category::query()
            ->active()
            ->with('translations')
            ->get();

        foreach ($model as $item) {
            foreach ($item->translations as $trans) {
                if (($trans->meta_auto_gen || $request->get('rewrite_all') == "true") && $trans->lang == $request->get('template_lang')) {
                    $trans->meta_title = str_replace($trans::META_TAGS, $trans->getMetaKeywords($trans->lang), $request->get('template_title'));
                    $trans->meta_description = str_replace($trans::META_TAGS, $trans->getMetaKeywords($trans->lang), $request->get('template_desc'));
                    $trans->meta_created_as = $trans::META_CREATED_AS_AUTO_GEN;
                    $trans->save();
                }
            }
        }

        return [
            'success' => true,
            'message' => __('Meta data successfully generated!'),
        ];
    }
}

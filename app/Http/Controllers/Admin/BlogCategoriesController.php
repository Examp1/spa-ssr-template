<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlogCategoryRequest;
use App\Models\BlogCategories;
use App\Models\Translations\BlogCategoryTranslation;
use App\Modules\Constructor\Models\Constructor;
use App\Modules\Widgets\Models\Widget;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlogCategoriesController extends Controller
{
    private BlogCategories $modelClass;
    private string $viewName;
    private string $routeName;
    private string $tableName;
    private string $tableTranslationsName;
    private string $translationEntityAttribute;
    private string $translationClassName;

    public function __construct(BlogCategories $modelClass)
    {
        $this->modelClass = $modelClass;
        $this->viewName = 'blog.categories';
        $this->routeName = 'blog.categories';
        $this->tableName = 'blog_categories';
        $this->tableTranslationsName = 'blog_category_translations';
        $this->translationEntityAttribute = 'blog_categories_id';
        $this->translationClassName = 'App\Models\Translations\BlogCategoryTranslation';

        $this->middleware('permission:blog_category_view')->only('index');
        $this->middleware('permission:blog_category_create')->only('create', 'store');
        $this->middleware('permission:blog_category_edit')->only('edit', 'update');
        $this->middleware('permission:blog_category_delete')->only('destroy');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        $name = $request->get('name');

        /* Sorting */
        $sort  = 'created_at';
        $order = 'desc';
        $tableName = $this->tableName;

        if ($request->has('sort') && $request->has('order')) {
            $sort  = $request->get('sort');
            $order = $request->get('order');

            if (in_array($sort, ['name'])) {
                $tableName = $this->tableTranslationsName;
            }
        }

        $model = $this->modelClass->query()
            ->leftJoin($this->tableTranslationsName, $this->tableTranslationsName.'.'.$this->translationEntityAttribute, '=', $this->tableName.'.id')
            ->where($this->tableTranslationsName.'.lang', $request->get('search_lang', config('translatable.locale')))
            ->select([
                $this->tableName.'.*',
                $this->tableTranslationsName.'.name',
                $this->tableTranslationsName.'.meta_title AS mTitle',
                $this->tableTranslationsName.'.meta_description AS mDescription',
                $this->tableTranslationsName.'.meta_created_as AS mCreatedAs',
                $this->tableTranslationsName.'.meta_auto_gen AS mAutoGen',
            ])
            ->orderBy($tableName . '.' . $sort, $order)
            ->where(function ($q) use ($status, $name) {
                if ($status != '') {
                    $q->where($this->tableName.'.status', $status);
                }
                if ($name != '') {
                    $q->where($this->tableTranslationsName.'.name', 'like', '%' . $name . '%');
                }
            })
            ->paginate(20);

        return view('admin.'.$this->viewName.'.index', [
            'model' => $model
        ]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $model = new $this->modelClass();

        $model->order = 0;

        $localizations = config('translatable.locales');

        $categories = $model->treeStructure();

        return view('admin.'.$this->viewName.'.create', [
            'model'         => $model,
            'localizations' => $localizations,
            'categories'    => $categories,
        ]);
    }

    /**
     * @param BlogCategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(BlogCategoryRequest $request)
    {
        $model = new $this->modelClass();

        DB::beginTransaction();

        try {
            if (empty($request->input('slug', ''))) {
                $request->merge(array('slug' => SlugService::createSlug($this->modelClass, 'slug', $request->input('page_data.' . config('translatable.locale') . '.name'))));
            }

            $model->status = $request->get('status') ?? false;

            $model->fill($request->all());

            if (!$model->save()) {
                DB::rollBack();
            }

            $this->setPath($model);

            foreach (config('translatable.locales') as $lang => $item) {
                $data = $request->input('page_data.' . $lang, []);
                $data['meta_auto_gen'] = $data['meta_auto_gen'] ?? false;

                $model->translateOrNew($lang)->fill($data);

                if (!$model->save()) {
                    DB::rollBack();
                }
            }
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()->route($this->routeName.'.index')->with('error', __('Error!') . $e->getMessage());
        }

        DB::commit();

        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');

        if ($request->get('save_method') === 'save_and_back') {
            return redirect()->route($this->routeName.'.edit', $model->id)->with('success', __('Created successfully!'));
        } else {
            return redirect()->route($this->routeName.'.index')->with('success', __('Created successfully!'));
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
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $model = $this->modelClass->query()->where('id',$id)->first();

        $localizations = config('translatable.locales');

        $categories = $model->treeStructure();

        return view('admin.'.$this->viewName.'.edit', [
            'model'         => $model,
            'data'          => $model->getTranslationsArray(),
            'localizations' => $localizations,
            'categories'    => $categories,
        ]);
    }

    /**
     * @param BlogCategoryRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(BlogCategoryRequest $request, $id)
    {
        $model = $this->modelClass->query()->where('id', $id)->first();

        DB::beginTransaction();

        try {
            $model->status = $request->get('status') ?? false;

            $model->fill($request->all());

            if (!$model->save()) {
                DB::rollBack();
            }

            $this->setPath($model);

            $model->deleteTranslations();

            foreach (config('translatable.locales') as $lang => $item) {
                $data = $request->input('page_data.' . $lang, []);
                $data['meta_auto_gen'] = $data['meta_auto_gen'] ?? false;

                $model->translateOrNew($lang)->fill($data);

                if (!$model->save()) {
                    DB::rollBack();
                }
            }
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()->route($this->routeName.'.index')->with('error', __('Error!') . $e->getMessage());
        }

        DB::commit();

        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');

        if ($request->get('save_method') === 'save_and_back') {
            return redirect()->back()->with('success', __('Updated successfully!'));
        } else {
            return redirect()->route($this->routeName.'.index')->with('success', __('Updated successfully!'));
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $model = $this->modelClass->query()->where('id',$id)->first();
        $model->deleteTranslations();
        $this->modelClass->query()->where('id', $id)->delete();

        return redirect()->route($this->routeName.'.index')->with('success', __('Deleted successfully!'));
    }

    public function deleteSelected(Request $request)
    {
        $ids = json_decode($request->get('ids'), true);

        if (count($ids)) {
            foreach ($ids as $id) {
                $model = $this->modelClass->query()->where('id', $id)->first();
                $model->deleteTranslations();
                $this->modelClass->query()->where('id', $id)->delete();
            }

            return redirect()->back()->with('success', __('Deleted successfully!'));
        }

        return redirect()->back()->with('error', __('Error!'));
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
                    $parent_model = app(BlogCategories::class)->find((int)$parent);
                    $tmp_slug = $parent_model->slug;
                    $parent = $parent_model->parent_id;
                }
                if ($path) {
                    $path = $tmp_slug . '/' . $path;
                } else {
                    $path = $tmp_slug;
                }
            } while ($parent != 0);
            //dd($path);

            $model->path = $path;
            $model->save();

            $children = app(BlogCategories::class)->where('parent_id', '=', (int) $model->id)->pluck('id')->toArray();
            if ($children) {
                //dd($children);
                foreach ($children as $child) {
                    $this->setPath((int)$child);
                }
            }
            return $path;
        } catch (\Exception $e) {
            //echo $e;
            return null;
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function copy($id)
    {
        $modelFrom = $this->modelClass->query()
            ->with('translations')
            ->where('id', $id)
            ->first();

        DB::beginTransaction();

        try {
            $model = $modelFrom->replicate();
            $model->created_at = Carbon::now();
            $model->slug = SlugService::createSlug($this->modelClass, 'slug', $modelFrom->slug);
            $model->status = $this->modelClass::STATUS_NOT_ACTIVE;

            if (!$model->save()) {
                DB::rollBack();
            }

            $translationEntityAttribute = $this->translationEntityAttribute;

            foreach ($model->translations as $item) {
                $trans = $item->replicate();
                $trans->$translationEntityAttribute = $model->id;
                $trans->name = 'Копія ' . $trans->name;

                if (!$trans->save()) {
                    DB::rollBack();
                };
            }
        } catch (\Exception $e) {
            DB::rollBack();
        }

        DB::commit();

        return redirect()->route('blog.categories.edit', $model->id)->with('success', __('Сopied successfully!'));
    }

    public function metaGenerate(Request $request)
    {
        $query = $this->modelClass->query()
            ->active()
            ->with('translations');

        if($request->has("model_id")){
            $query->where('id',$request->get("model_id"));
        }

        $model = $query->get();

        $resMeta = [];

        foreach ($model as $item) {
            foreach ($item->translations as $trans) {
                if (($trans->meta_auto_gen || $request->get('rewrite_all') == "true") && $trans->lang == $request->get('template_lang')) {
                    $trans->meta_title = str_replace($trans::META_TAGS, $trans->getMetaKeywords($trans->lang), $request->get('template_title'));
                    $trans->meta_description = str_replace($trans::META_TAGS, $trans->getMetaKeywords($trans->lang), $request->get('template_desc'));
                    $trans->meta_created_as = $trans::META_CREATED_AS_AUTO_GEN;
                    $trans->save();

                    $resMeta = [
                        'title'       => $trans->meta_title,
                        'description' => $trans->meta_description
                    ];
                }
            }
        }

        $res = [
            'success' => true,
            'message' => __('Meta data successfully generated!'),
        ];

        if($request->has("model_id")){
            $res['meta'] = $resMeta;
        }

        return $res;
    }

    public function copyLang(Request $request)
    {
        DB::beginTransaction();

        try {
            $model = $this->modelClass->query()
                ->with('translations')
                ->where('id', $request->get('model_id'))
                ->first();

            foreach ($model->translations as $item) {
                if($item->lang == $request->get('from')){
                    $modelLangFrom = $item;
                }

                if($item->lang == $request->get('to')){
                    $modelLangTo = $item;
                }
            }

            $oldConstructorTo = Constructor::query()
                ->where('constructorable_type',$this->translationClassName)
                ->where('constructorable_id',$modelLangTo->id)
                ->first();

            $modelLangTo->fill($modelLangFrom->toArray());

            if(!$modelLangTo->save()){
                DB::rollBack();
                return redirect()->back()->with('error', __('Error!'));
            }
        } catch (\Exception $e) {
            DB::rollBack();
        }

        DB::commit();

        if($request->get('constructor_replace') == 'yes'){
            $constructor = Constructor::query()
                ->where('constructorable_type',$this->translationClassName)
                ->where('constructorable_id',$modelLangFrom->id)
                ->first();

            if($constructor){
                $dataTo = [];

                if(is_array($constructor->data) && count($constructor->data)){
                    foreach ($constructor->data as $item){
                        if($item['component'] != "widget"){
                            $dataTo[] = $item;
                        } else {
                            $widgetId = $item['content']['widget'];
                            $widget = Widget::query()->where('id',$widgetId)->first();
                            $widgetTo = Widget::query()
                                ->where('main_id',$widget->main_id)
                                ->where('lang',$request->get('to'))
                                ->first();

                            if(is_null($widgetTo)){
                                $widgetTo = new Widget();
                                $widgetTo->main_id = $widget->main_id;
                                $widgetTo->name = $widget->name;
                                $widgetTo->instance = $widget->instance;
                                $widgetTo->data = $widget->data;
                                $widgetTo->lang = $request->get('to');
                                $widgetTo->static = $widget->static;
                                $widgetTo->save();
                            }

                            $item['content']['widget'] = $widgetTo->id;
                            $dataTo[] = $item;
                        }
                    }

                    $constructorTo = Constructor::query()
                        ->where('constructorable_type',$this->translationClassName)
                        ->where('constructorable_id',$modelLangTo->id)
                        ->first();

                    if($constructorTo){
                        $constructorTo->data = $dataTo;
                        $constructorTo->save();
                    }
                }
            }
        } else {
            $constructorTo = Constructor::query()
                ->where('constructorable_type',$this->translationClassName)
                ->where('constructorable_id',$modelLangTo->id)
                ->first();

            if($constructorTo){
                $constructorTo->data = $oldConstructorTo->data;
                $constructorTo->save();
            }
        }

        return redirect()->back()->with('success', __('Сopied successfully!'));
    }
}

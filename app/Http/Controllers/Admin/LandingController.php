<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LandingRequest;
use App\Models\Landing;
use App\Modules\Constructor\Models\Constructor;
use App\Modules\Widgets\Models\Widget;
use App\Service\Adapter;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Langs;

class LandingController extends Controller
{
    private Adapter $adapter;
    private Landing $modelClass;
    private string $viewName;
    private string $routeName;
    private string $tableName;
    private string $tableTranslationsName;
    private string $translationEntityAttribute;
    private string $translationClassName;

    public function __construct(Adapter $adapter, Landing $modelClass)
    {
        $this->adapter = $adapter;
        $this->modelClass = $modelClass;
        $this->viewName = 'landing';
        $this->routeName = 'landing';
        $this->tableName = 'landings';
        $this->tableTranslationsName = 'landing_translations';
        $this->translationEntityAttribute = 'landing_id';
        $this->translationClassName = 'App\Models\Translations\LandingTranslation';

        $this->middleware('permission:landing_view')->only('index');
        $this->middleware('permission:landing_create')->only('create', 'store');
        $this->middleware('permission:landing_edit')->only('edit', 'update');
        $this->middleware('permission:landing_delete')->only('destroy');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        $title = $request->get('title');

        /* Sorting */
        $sort  = 'created_at';
        $order = 'desc';
        $tableName = $this->tableName;

        if ($request->has('sort') && $request->has('order')) {
            $sort  = $request->get('sort');
            $order = $request->get('order');

            if (in_array($sort, ['title'])) {
                $tableName = $this->tableTranslationsName;
            }
        }

        $model = $this->modelClass->query()
            ->leftJoin($this->tableTranslationsName, $this->tableTranslationsName.'.'.$this->translationEntityAttribute, '=', $this->tableName.'.id')
            ->where($this->tableTranslationsName.'.lang', $request->get('search_lang', config('translatable.locale')))
            ->select([
                $this->tableName.'.*',
                $this->tableTranslationsName.'.title',
                $this->tableTranslationsName.'.meta_title AS mTitle',
                $this->tableTranslationsName.'.meta_description AS mDescription',
                $this->tableTranslationsName.'.meta_created_as AS mCreatedAs',
                $this->tableTranslationsName.'.meta_auto_gen AS mAutoGen',
            ])
            ->orderBy($tableName . '.' . $sort, $order)
            ->where(function ($q) use ($status, $title) {
                if ($status != '') {
                    $q->where($this->tableName.'.status', $status);
                }
                if ($title != '') {
                    $q->where($this->tableTranslationsName.'.title', 'like', '%' . $title . '%');
                }
            })
            ->paginate(20);

        return view('admin.'.$this->viewName.'.index', [
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
        $model = new $this->modelClass();

        $model->order = 0;

        $localizations = Langs::getLangsWithTitle();

        return view('admin.'.$this->viewName.'.create', [
            'model'         => $model,
            'localizations' => $localizations
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LandingRequest $request)
    {
        $model = new $this->modelClass();

        DB::beginTransaction();

        try {
            if (empty($request->input('slug', ''))) {
                $request->merge(array('slug' => SlugService::createSlug($this->modelClass, 'slug', $request->input('page_data.' . Langs::getDefaultLangCode() . '.title'))));
            }

            $model->fill($request->all());

            $model->status = $request->get('status') ?? false;

            if (!$model->save()) {
                DB::rollBack();
            }

            $main_screen = $request->get('main_screen');

            foreach (Langs::getLangsWithTitle() as $lang => $item) {
                $constructorData = $request->get('constructorData');
                $data = $request->input('page_data.' . $lang, []);
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

            return redirect()->back()->with('error', __('Error!') . $e->getMessage())->withInput();
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

        $localizations = Langs::getLangsWithTitle();

        $data = $model->getTranslationsArray();

        foreach (Langs::getLangsWithTitle() as $lang => $item) {
            if (isset($data[$lang]['main_screen'])) {
                $data[$lang]['main_screen'] = json_decode($data[$lang]['main_screen'], true);
            }
        }

        return view('admin.'.$this->viewName.'.edit', [
            'model'         => $model,
            'data'          => $data,
            'localizations' => $localizations
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(LandingRequest $request, $id)
    {
        $model = $this->modelClass->query()->where('id', $id)->first();

        DB::beginTransaction();

        try {
            $model->fill($request->all());

            $model->status = $request->get('status') ?? false;

            if (!$model->save()) {
                DB::rollBack();
            }

            $model->deleteTranslations();

            $main_screen = $request->get('main_screen');

            foreach (Langs::getLangsWithTitle() as $lang => $item) {
                $constructorData = $request->get('constructorData');
                $data = $request->input('page_data.' . $lang, []);

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

            return redirect()->back()->with('error', __('Error!') . $e->getMessage())->withInput();
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
                $trans->title = 'Копія ' . $trans->title;

                if (!$trans->save()) {
                    DB::rollBack();
                };

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

        return redirect()->route($this->routeName.'.edit', $model->id)->with('success', __('Сopied successfully!'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $model = $this->modelClass->query()->where('id',$id)->first();

        $model->deleteTranslations();
        $model->delete();

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

                if(is_array($constructor->data) && count($constructor->data) && !isset($constructor->data['entity_id'])){
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

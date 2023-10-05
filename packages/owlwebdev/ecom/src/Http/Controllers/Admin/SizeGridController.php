<?php

namespace Owlwebdev\Ecom\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Owlwebdev\Ecom\Http\Requests\SizeGridRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Owlwebdev\Ecom\Models\SizeGrid;
use Cviebrock\EloquentSluggable\Services\SlugService;

class SizeGridController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:size_grids_view')->only('index');
        $this->middleware('permission:size_grids_create')->only('create', 'store');
        $this->middleware('permission:size_grids_edit')->only('edit', 'update');
        $this->middleware('permission:size_grids_delete')->only('destroy');
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
        $sort  = 'created_at';
        $order = 'asc';
        $tableName = 'size_grids';

        if ($request->has('sort') && $request->has('order')) {
            $sort  = $request->get('sort');
            $order = $request->get('order');

            if (in_array($sort, ['name'])) {
                $tableName = 'size_grid_translations';
            }
        }

        $model = SizeGrid::query()
            ->leftJoin('size_grid_translations', 'size_grid_translations.size_grid_id', '=', 'size_grids.id')
            ->where('size_grid_translations.lang', config('translatable.locale'))
            ->select([
                'size_grids.*',
                'size_grid_translations.name',
            ])
            ->orderBy($tableName . '.' . $sort, $order)
            ->where(function ($q) use ($status, $name) {
                if ($status != '') {
                    $q->where('size_grids.status', $status);
                }
                if ($name != '') {
                    $q->where('size_grid_translations.name', 'like', '%' . $name . '%');
                }
            })
            ->paginate(20);

        return view('ecom::admin.size-grids.index', [
            'model' => $model,
            'active_count' => SizeGrid::query()->active()->count(),
            'notActive_count' => SizeGrid::query()->notActive()->count(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new SizeGrid();

        $model->order = 0;

        $localizations = config('translatable.locales');

        return view('ecom::admin.size-grids.create', [
            'model'         => $model,
            'localizations' => $localizations,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(SizeGridRequest $request)
    {
        $model = new SizeGrid();

        DB::beginTransaction();

        try {
            if (empty($request->input('slug', ''))) {
                $request->merge(array('slug' => SlugService::createSlug(SizeGrid::class, 'slug', $request->input('page_data.' . config('translatable.locale') . '.name'))));
            }

            $model->status = $request->get('status') ?? false;
            if (empty($request->input('order', ''))) {
                $request->merge(array('order' => 0));
            }

            $model->fill($request->all());

            if (!$model->save()) {
                DB::rollBack();
            }

            foreach (config('translatable.locales') as $lang => $item) {
                $data = $request->input('page_data.' . $lang, []);

                $data['status_lang'] = $data['status_lang'] ?? false;
                $model->translateOrNew($lang)->fill($data);

                if (!$model->save()) {
                    DB::rollBack();
                }
            }
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()->route('size-grid.index')->with('error', __('Error!') . $e->getMessage());
        }

        DB::commit();

        if ($request->get('save_method') === 'save_and_back') {
            return redirect()->route('size-grid.edit', $model->id)->with('success', __('Updated successfully!'));
        } else {
            return redirect()->route('size-grid.index')->with('success', __('Updated successfully!'));
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
        $model = SizeGrid::query()->where('id', $id)->firstOrFail();

        $localizations = config('translatable.locales');

        $data = $model->getTranslationsArray();

        return view('ecom::admin.size-grids.edit', [
            'model'         => $model,
            'data'          => $data,
            'localizations' => $localizations,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(SizeGridRequest $request, $id)
    {
        /* @var $model SizeGrid */
        $model = SizeGrid::query()->where('id', $id)->first();

        DB::beginTransaction();

        try {
            $model->status = $request->get('status') ?? false;

            $model->fill($request->all());

            if (!$model->save()) {
                DB::rollBack();
            }

            $model->deleteTranslations();

            foreach (config('translatable.locales') as $lang => $item) {
                $data = $request->input('page_data.' . $lang, []);

                $data['status_lang'] = $data['status_lang'] ?? false;
                $model->translateOrNew($lang)->fill($data);

                if (!$model->save()) {
                    DB::rollBack();
                }
            }
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()->route('size-grid.index')->with('error', __('Error!') . $e->getMessage());
        }

        DB::commit();

        if ($request->get('save_method') === 'save_and_back') {
            return redirect()->back()->with('success', __('Updated successfully!'));
        } else {
            return redirect()->route('size-grid.index')->with('success', __('Updated successfully!'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = SizeGrid::query()->where('id',$id)->first();
        $model->deleteTranslations();
        $model->delete();

        return redirect()->route('size-grid.index')->with('success', __('Deleted successfully!'));
    }

    public function deleteSelected(Request $request)
    {
        $ids = json_decode($request->get('ids'), true);

        if (count($ids)) {
            foreach ($ids as $id) {
                $model = SizeGrid::query()->where('id', $id)->first();
                $model->deleteTranslations();
                SizeGrid::query()->where('id', $id)->delete();
            }

            return redirect()->route('size-grid.index')->with('success', __('Deleted successfully!'));
        }

        return redirect()->route('size-grid.index')->with('error', __('Error!'));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function copy($id)
    {
        $modelFrom = SizeGrid::query()
            ->with('translations')
            ->where('id', $id)
            ->first();

        DB::beginTransaction();

        try {
            $model = $modelFrom->replicate();
            $model->created_at = Carbon::now();
            $model->slug = SlugService::createSlug(SizeGrid::class, 'slug', $modelFrom->slug);
            $model->status = SizeGrid::STATUS_NOT_ACTIVE;

            if (!$model->save()) {
                DB::rollBack();
            }

            foreach ($model->translations as $item) {
                $trans = $item->replicate();
                $trans->size_grid_id = $model->id;
                $trans->name = __('Copy of ') . $trans->name;

                if (!$trans->save()) {
                    DB::rollBack();
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
        }

        DB::commit();

        return redirect()->route('size-grid.edit', $model->id)->with('success', __('Сopied successfully!'));
    }
}

<?php

namespace Owlwebdev\Ecom\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Owlwebdev\Ecom\Models\Discount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Owlwebdev\Ecom\Http\Requests\DiscountRequest;


class DiscountController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:discounts_view')->only('index');
        $this->middleware('permission:discounts_create')->only('create', 'store');
        $this->middleware('permission:discounts_edit')->only('edit', 'update');
        $this->middleware('permission:discounts_delete')->only('destroy');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        /* Sorting */
        $sort  = 'created_at';
        $order = 'desc';
        $tableName = 'discounts';

        if ($request->has('sort') && $request->has('order')) {
            $sort  = $request->get('sort');
            $order = $request->get('order');

            if (in_array($sort, ['name'])) {
                $tableName = 'discount_translations';
            }
        }

        $query = Discount::query()
            ->leftJoin('discount_translations', 'discount_translations.discount_id', '=', 'discounts.id')
            ->where('discount_translations.lang', $request->get('search_lang', config('translatable.locale')))
            ->select([
                'discounts.*',
                'discount_translations.name',
            ])
            ->orderBy($tableName . '.' . $sort, $order);

        $discounts = $query->paginate(20);

        return view('ecom::admin.discounts.index', [
            'model'   => $discounts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $model = new Discount();
        $localizations = config('translatable.locales');

        return view('ecom::admin.discounts.create', compact(
            'model',
            'localizations',
        ));
    }

    public function store(DiscountRequest $request)
    {
        $model = new Discount();

        $model->fill($request->all());

        foreach (config('translatable.locales') as $lang => $item) {
            $data = $request->input('page_data.' . $lang, []);
            $model->translateOrNew($lang)->fill($data);
        }

        return $model->save()
            ? redirect()->route('discounts.edit', $model)->with('success', __('Success!')) //Order created Successfully
            : redirect()->back()->with('error', __('Error!') . __('Discount not created, error'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return abort(404);
    }

    /**
     * @param Pages $page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Discount $discount)
    {
        $model = $discount;
        $localizations = config('translatable.locales');
        $data = $model->getTranslationsArray();

        return view('ecom::admin.discounts.edit', compact(
            'model',
            'localizations',
            'data',
        ));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function copy($id)
    {
    }

    /**
     * @param PagesRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(DiscountRequest $request, $id)
    {
        $model = Discount::query()->where('id', $id)->first();

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
                $model->translateOrNew($lang)->fill($data);

                if (!$model->save()) {
                    DB::rollBack();
                }
            }
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()->route('discounts.index')->with('error', __('Error!') . $e->getMessage());
        }

        DB::commit();

        return redirect()->route('discounts.index')->with('success', __('Updated successfully!'));
    }

    public function destroy(Discount $discount)
    {
        $discount->delete();

        return redirect()->route('discounts.index')->with('success', __('Deleted successfully!'));
    }

    public function deleteSelected(Request $request)
    {
        $ids = json_decode($request->get('ids'), true);

        if (count($ids)) {
            foreach ($ids as $id) {
                Discount::query()->where('id', $id)->delete();
            }

            return redirect()->back()->with('success', __('Deleted successfully!'));
        }

        return redirect()->route('discounts.index')->with('error', __('Error!'));
    }
}

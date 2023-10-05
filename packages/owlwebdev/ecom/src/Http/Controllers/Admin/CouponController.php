<?php

namespace Owlwebdev\Ecom\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Owlwebdev\Ecom\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Owlwebdev\Ecom\Http\Requests\CouponRequest;


class CouponController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:coupons_view')->only('index');
        $this->middleware('permission:coupons_create')->only('create', 'store');
        $this->middleware('permission:coupons_edit')->only('edit', 'update');
        $this->middleware('permission:coupons_delete')->only('destroy');
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
        $tableName = 'coupons';

        if ($request->has('sort') && $request->has('order')) {
            $sort  = $request->get('sort');
            $order = $request->get('order');

            if (in_array($sort, ['name'])) {
                $tableName = 'coupon_translations';
            }
        }

        $query = Coupon::query()
            ->leftJoin('coupon_translations', 'coupon_translations.coupon_id', '=', 'coupons.id')
            ->where('coupon_translations.lang', $request->get('search_lang', config('translatable.locale')))
            ->select([
                'coupons.*',
                'coupon_translations.name',
            ])
            ->orderBy($tableName . '.' . $sort, $order);

        $coupons = $query->paginate(20);

        return view('ecom::admin.coupons.index', [
            'model'   => $coupons,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $model = new Coupon();
        $localizations = config('translatable.locales');
        $discount_types = Coupon::DISCOUNT_TYPES;

        return view('ecom::admin.coupons.create', compact(
            'model',
            'discount_types',
            'localizations',
        ));
    }

    public function store(CouponRequest $request)
    {
        $model = new Coupon();

        $model->fill($request->all());

        foreach (config('translatable.locales') as $lang => $item) {
            $data = $request->input('page_data.' . $lang, []);
            $model->translateOrNew($lang)->fill($data);
        }

        return $model->save()
            ? redirect()->route('coupons.edit', $model)->with('success', __('Success!')) //Order created Successfully
            : redirect()->back()->with('error', __('Error!') . __('Coupon not created, error'));
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
    public function edit(Coupon $coupon)
    {
        $model = $coupon;
        $localizations = config('translatable.locales');
        $discount_types = Coupon::DISCOUNT_TYPES;
        $data = $model->getTranslationsArray();

        return view('ecom::admin.coupons.edit', compact(
            'model',
            'localizations',
            'data',
            'discount_types',
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
    public function update(CouponRequest $request, $id)
    {
        $model = Coupon::query()->where('id', $id)->first();

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

            return redirect()->route('coupons.index')->with('error', __('Error!') . $e->getMessage());
        }

        DB::commit();

        return redirect()->route('coupons.index')->with('success', __('Updated successfully!'));
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('coupons.index')->with('success', __('Deleted successfully!'));
    }

    public function deleteSelected(Request $request)
    {
        $ids = json_decode($request->get('ids'), true);

        if (count($ids)) {
            foreach ($ids as $id) {
                Coupon::query()->where('id', $id)->delete();
            }

            return redirect()->route('coupons.index')->with('success', __('Deleted successfully!'));
        }

        return redirect()->route('coupons.index')->with('error', __('Error!'));
    }
}

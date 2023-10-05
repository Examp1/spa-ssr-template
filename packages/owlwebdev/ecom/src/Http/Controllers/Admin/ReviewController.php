<?php

namespace Owlwebdev\Ecom\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Owlwebdev\Ecom\Models\Product;
use App\Models\User;
use Owlwebdev\Ecom\Http\Requests\ReviewRequest;
use Owlwebdev\Ecom\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:reviews_view')->only('index');
        $this->middleware('permission:reviews_create')->only('create', 'store');
        $this->middleware('permission:reviews_edit')->only('edit', 'update');
        $this->middleware('permission:reviews_delete')->only('destroy');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $statuses = Review::getStatuses();

        $reviews = Review::query()
            ->when($request->input('status') !== null, function ($query) use ($request) {
                $query->where('status', $request->input('status'));
            })
            ->when($request->input('status') == null, function ($query) {
                return $query->orderBy('status', 'desc'); // disabled to end
            })
            ->orderBy('created_at', 'desc')
            ->when($request->input('search'), function ($query) use ($request) {
                $search_text = $request->input('search');

                $query->where(function ($query) use ($search_text) {
                    $query->orWhere('id', $search_text);
                    $query->where('author', 'like', '%' . $search_text . '%');
                    $query->orWhere('email', 'like', '%' . $search_text . '%');
                    $query->orWhere('text', 'like', '%' . $search_text . '%');
                });
            })
            ->paginate(30);

        return view('ecom::admin.reviews.index', [
            'reviews'   => $reviews,
            'statuses' => $statuses,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $model = new Review();
        $products = Product::getQueryWithPrices()->get();
        $users = User::all();

        return view('ecom::admin.reviews.create', compact(
            'products',
            'users',
            'model',
        ));
    }


    public function store(ReviewRequest $request)
    {
        $model = new Review();

        $model->fill($request->all());

        $result = $model->save();

        // update product rating
        if ((bool)$model->status) {
            $model->product->updateRating();
        }

        return $result
            ? redirect()->route('reviews.edit', $model)->with('success', __('Success!')) //Order created Successfully
            : redirect()->back()->with('error', __('Error!'));
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
     * @param Pages $page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $model = Review::query()->where('id', $id)->first();
        $products = Product::getQueryWithPrices()->get();
        $users = User::all();

        return view('ecom::admin.reviews.edit', compact(
            'model',
            'products',
            'users',
        ));
    }

    /**
     * @param PagesRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ReviewRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $model = Review::find((int) $id);
            $old_rating = $model->rating;
            $old_status = $model->status;

            $model->update($request->all());

            // update product rating
            if ((bool)$old_status != (bool)$model->status || (int)$old_rating != (int)$model->rating) {
                $model->product->updateRating();
            }

            DB::commit();

            return redirect()->route('reviews.edit', $id)->with('success', __('Success!'));
        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->back()->with('error', __('Error!') . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $model = Review::query()->where('id', $id)->first();

        $product = $model->product;

        $model->delete();

        $product->updateRating();

        return redirect()->route('reviews.index')->with('success', __('Deleted successfully!'));
    }

    public function deleteSelected(Request $request)
    {
        $ids = json_decode($request->get('ids'), true);

        if (count($ids)) {
            foreach ($ids as $id) {
                $model = Review::query()->where('id', $id)->first();
                $product = $model->product;
                // $model->images()->delete();
                $model->delete();
                $product->updateRating();
            }

            return redirect()->route('reviews.index')->with('success', __('Deleted successfully!'));
        }

        return redirect()->route('reviews.index')->with('error', __('Error!'));
    }
}

<?php

namespace Owlwebdev\Ecom\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Owlwebdev\Ecom\Models\SearchHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class SearchHistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:search_history_view')->only('index');
        $this->middleware('permission:search_history_delete')->only('destroy');
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $name = $request->get('name');

        /* Sorting */
        $sort  = 'created_at';
        $order = 'desc';
        $tableName = 'search_history';

        if ($request->has('sort') && $request->has('order')) {
            $sort  = $request->get('sort');
            $order = $request->get('order');
        }

        $model = SearchHistory::query()
            ->orderBy($tableName . '.' . $sort, $order)
            ->where(function ($q) use ($tableName,$name) {
                if ($name != '') {
                    $q->where($tableName.'.input', 'like', '%' . $name . '%')
                        ->orWhere($tableName.'.select', 'like', '%' . $name . '%')
                        ->orWhere($tableName.'.ip', 'like', '%' . $name . '%');
                }
            })
            ->paginate(50);

        return view('ecom::admin.search-history.index', [
            'model' => $model
        ]);
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id):RedirectResponse
    {
        SearchHistory::query()->where('id',$id)->delete();

        return redirect()->route('search-history.index')->with('success', __('Success!'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteSelected(Request $request):RedirectResponse
    {
        $ids = json_decode($request->get('ids'), true);

        if (count($ids)) {
            foreach ($ids as $id) {
                SearchHistory::query()->where('id', $id)->delete();
            }

            return redirect()->route('search-history.index')->with('success', __('Success!'));
        }

        return redirect()->route('search-history.index')->with('error', __('Error!'));
    }
}

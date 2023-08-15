<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MenuRequest;
use App\Models\Menu;
use App\Models\Translations\MenuTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:menu_view')->only('index');
        $this->middleware('permission:menu_create')->only('create', 'store');
        $this->middleware('permission:menu_edit')->only('edit', 'update');
        $this->middleware('permission:menu_delete')->only('destroy');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $tag = $request->get('tag');

        if (!$tag) {
            $tags = Menu::getTags();
            $tag = count($tags) ? array_shift($tags) : null;
        }

        $model = Menu::query()
            ->where('tag', $tag)
            ->where('const', '<>', 1)
            ->defaultOrder()
            ->get()
            ->toTree();


        return view('admin.menu.index', [
            'model' => json_encode($model, JSON_UNESCAPED_UNICODE),
            'tag'   => $tag
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new Menu();

        return view('admin.menu.form', [
            'model' => $model
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post = $request->all();

        Menu::create($post);

        Cache::flush();

        return redirect()->route('menu.index')->with('success', __('Created successfully!'));
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
        $model = Menu::findOrFail($id);

        $localizations = config('translatable.locales');

        return view('admin.menu.form', [
            'model'         => $model,
            'data'          => $model->getTranslationsArray(),
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
    public function update(Request $request, $id)
    {
        /* @var $model Menu */
        $model = Menu::findOrFail($id);

        $post = $request->all();

        $model->fill($post);
        $model->save();

        $model->deleteTranslations();

        foreach (config('translatable.locales') as $lang => $item) {
            $model->translateOrNew($lang)->fill($request->input('page_data.' . $lang, []));
            $model->save();
        }

        Cache::flush();

        return redirect()->route('menu.index', ['tag' => $model->tag])->with('success', __('Updated successfully!'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return array
     */
    public function destroy(Request $request, $id)
    {
        $confirm = $request->get('c') ?? 1;

        $node = Menu::query()->where('id', $id)->with('children')->first();

        $tag = $node->tag;

        if ($confirm && count($node->children)) {
            return [
                'status'      => false,
                'hasChildren' => true,
                'message'     => 'Цей пункт меню включає інші пункти, які теж будуть видалені!'
            ];
        }

        $node->deleteTranslations();

        $node->delete();

        $tree = Menu::query()
            ->where('tag', $tag)
            ->where('const', '<>', 1)
            ->defaultOrder()
            ->get()
            ->toTree();

        Cache::flush();

        return [
            'status'  => true,
            'tree'    => $tree,
            'message' => __('Deleted successfully!')
        ];
    }

    private function treeGetIds($tree, $ids = [])
    {
        foreach ($tree as $item) {
            $ids[] = $item['id'];

            if (count($item['children'])) {
                $ids = array_merge($ids, $this->treeGetIds($item['children'], $ids));
            }
        }

        return $ids;
    }

    public function rebuild(Request $request)
    {
        $ids = $this->treeGetIds($request->get('tree'));
        $ids = array_unique($ids);

        $tree = Menu::query()
            ->whereNotIn('id', $ids)
            ->defaultOrder()
            ->get()
            ->toTree()
            ->toArray();

        $isRebuild = Menu::rebuildTree(array_merge($tree, $request->get('tree')));

        if ($isRebuild) {

            $model = Menu::query()
                ->where('tag', $request->get('tag'))
                ->where('const', '<>', 1)
                ->defaultOrder()
                ->get()
                ->toTree();

            Cache::flush();

            return [
                'status'  => true,
                'message' => __('Updated successfully!'),
                'model'   => json_encode($model, JSON_UNESCAPED_UNICODE),
            ];
        }
    }

    public function addMenu(MenuRequest $request)
    {
        $post = $request->all();

        Menu::create($post);

        Cache::flush();

        return redirect()->route('menu.index', ['tag' => $request->get('tag')])->with('success', __('Created successfully!'));
    }

    public function addItem(Request $request)
    {
        $parent = Menu::query()
            ->where('const', '<>', 1)
            ->defaultOrder()
            ->get()
            ->toTree()
            ->toArray();

        $parent[] = $request->except(['_token', 'name', 'url']);

        $isRebuild = Menu::rebuildTree($parent);

        $model = Menu::query()
            ->where('tag', $request->get('tag'))
            ->orderBy('id', 'desc')
            ->first();

        $modelTrans          = new MenuTranslation();
        $modelTrans->menu_id = $model->id;
        $modelTrans->name    = $request->get('name');
        $modelTrans->url     = $request->get('url') ?? null;
        $modelTrans->lang    = config('translatable.locale');
        $modelTrans->save();

        Cache::flush();

        return redirect()->back()->with('success', __('Created successfully!'));
    }

    public function deleteMenu(Request $request)
    {
        $menuIds =  Menu::query()
            ->where('tag', $request->get('tag'))
            ->where('const', 0)
            ->pluck('id')
            ->toArray();

        Menu::query()
            ->where('tag', $request->get('tag'))
            ->delete();

        MenuTranslation::query()
            ->whereIn('menu_id', $menuIds)
            ->delete();

        Cache::flush();

        return redirect()->route('menu.index')->with('success', __('Deleted successfully!'));
    }

    public function move(Request $request)
    {
        $action = $request->get('action');
        $node   = Menu::query()->where('id', $request->get('id'))->first();
        $node->$action();

        $tree = Menu::query()
            ->where('tag', $node->tag)
            ->where('const', '<>', 1)
            ->defaultOrder()
            ->get()
            ->toTree();

        Cache::flush();

        return [
            'status'  => true,
            'tree'    => $tree,
            'message' => ''
        ];
    }
}

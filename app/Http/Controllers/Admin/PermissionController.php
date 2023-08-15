<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:permissions_view')->only('index');
        $this->middleware('permission:permissions_create')->only('create', 'store');
        $this->middleware('permission:permissions_edit')->only('edit', 'update');
        $this->middleware('permission:permissions_delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $group = $request->get('group');

        $model = Permission::query()
            ->leftJoin('permission_groups', 'permission_groups.id', '=', 'permissions.group_id')
            ->select([
                'permissions.*',
                'permission_groups.name AS groupName'
            ])
            ->orderBy('permission_groups.name', 'asc')
            ->where(function ($q) use ($group) {
                if ($group != '') {
                    $q->where('permission_groups.id', $group);
                }
            })
            ->get();

        return view('admin.access.permissions.index', [
            'model'  => $model
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new Permission();

        return view('admin.access.permissions.create', [
            'model' => $model
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermissionRequest $request)
    {
        $model = new Permission();

        DB::beginTransaction();

        try {
            $model->fill($request->all());

            if (!$model->save()) {
                DB::rollBack();
            }
        } catch (\Throwable $e) {
            dd($e);
            DB::rollBack();

            return redirect()->route('permissions.index')->with('error', __('Error!') . $e->getMessage());
        }

        DB::commit();

        return redirect()->route('permissions.index')->with('success', __('Created successfully!'));
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
        $model = Permission::query()->where('id', $id)->first();

        return view('admin.access.permissions.edit', [
            'model' => $model
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(PermissionRequest $request, $id)
    {
        $model = Permission::query()->where('id', $id)->first();

        DB::beginTransaction();

        try {
            $model->fill($request->all());

            if (!$model->save()) {
                DB::rollBack();
            }
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()->route('permissions.index')->with('error', __('Error!') . $e->getMessage());
        }

        DB::commit();

        return redirect()->back()->with('success', __('Updated successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Permission::query()->where('id', $id)->delete();

        return redirect()->route('permissions.index')->with('success', __('Deleted successfully!'));
    }
}

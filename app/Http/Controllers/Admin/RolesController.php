<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RolesRequest;
use App\Models\PermissionGroups;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:roles_view')->only('index');
        $this->middleware('permission:roles_create')->only('create', 'store');
        $this->middleware('permission:roles_edit')->only('edit', 'update');
        $this->middleware('permission:roles_delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = Role::query()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.access.roles.index', [
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
        $model = new Role();

        return view('admin.access.roles.create', [
            'model' => $model
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(RolesRequest $request)
    {
        $model = new Role();

        DB::beginTransaction();

        try {
            $model->fill($request->all());

            if (!$model->save()) {
                DB::rollBack();
            }
        } catch (\Throwable $e) {
            dd($e);
            DB::rollBack();

            return redirect()->route('roles.index')->with('error', __('Error!') . $e->getMessage());
        }

        DB::commit();

        return redirect()->route('roles.index')->with('success', __('Created successfully!'));
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
        $model = Role::query()->where('id', $id)->first();

        $permissionsSelected = $model->getAllPermissions();

        $permissionsSelectedIds = [];

        foreach ($permissionsSelected as $item) {
            $permissionsSelectedIds[] = $item->id;
        }

        $permissions = Permission::query()
            ->leftJoin('permission_groups', 'permission_groups.id', '=', 'permissions.group_id')
            ->select([
                'permissions.*',
                'permission_groups.name AS groupName'
            ])
            ->orderBy('permission_groups.name', 'asc')
            ->get();

        $groups = PermissionGroups::query()->pluck('name', 'id')->toArray();

        return view('admin.access.roles.edit', [
            'model'                  => $model,
            'permissions'            => $permissions,
            'groups'                 => $groups,
            'permissionsSelectedIds' => $permissionsSelectedIds
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(RolesRequest $request, $id)
    {
        $model = Role::query()->where('id', $id)->first();

        $permissionsIds = $request->get('permissions') ?? [];

        $permissions = Permission::query()->whereIn('id', $permissionsIds)->get();

        DB::beginTransaction();

        try {
            $model->fill($request->all());

            if (!$model->save()) {
                DB::rollBack();
            }

            $model->syncPermissions($permissions);
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()->route('roles.index')->with('error', __('Error!') . $e->getMessage());
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
        Role::query()->where('id', $id)->delete();

        return redirect()->route('roles.index')->with('success', __('Deleted successfully!'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionGroupRequest;
use App\Models\PermissionGroups;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:permission_groups_view')->only('index');
        $this->middleware('permission:permission_groups_create')->only('create', 'store');
        $this->middleware('permission:permission_groups_edit')->only('edit', 'update');
        $this->middleware('permission:permission_groups_delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = PermissionGroups::query()
            ->get();

        return view('admin.access.permission-groups.index', [
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
        $model = new PermissionGroups();

        return view('admin.access.permission-groups.create', [
            'model' => $model
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermissionGroupRequest $request)
    {
        $model = new PermissionGroups();

        DB::beginTransaction();

        try {
            $model->fill($request->all());

            if (!$model->save()) {
                DB::rollBack();
            }
        } catch (\Throwable $e) {
            dd($e);
            DB::rollBack();

            return redirect()->route('permission-groups.index')->with('error', __('Error!') . $e->getMessage());
        }

        DB::commit();

        return redirect()->route('permission-groups.index')->with('success', __('Created successfully!'));
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
        $model = PermissionGroups::query()->where('id', $id)->first();

        return view('admin.access.permission-groups.edit', [
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
    public function update(PermissionGroupRequest $request, $id)
    {
        $model = PermissionGroups::query()->where('id', $id)->first();

        DB::beginTransaction();

        try {
            $model->fill($request->all());

            if (!$model->save()) {
                DB::rollBack();
            }
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()->route('permission-groups.index')->with('error', __('Error!') . $e->getMessage());
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
        PermissionGroups::query()->where('id', $id)->delete();

        return redirect()->route('permission-groups.index')->with('success', __('Deleted successfully!'));
    }
}

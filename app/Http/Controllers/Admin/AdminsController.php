<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AdminCreateRequest;
use App\Http\Requests\AdminUpdateRequest;
use App\Models\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:admins_view')->only('index');
        $this->middleware('permission:admins_create')->only('create', 'store');
        $this->middleware('permission:admins_edit')->only('edit', 'update');
        $this->middleware('permission:admins_delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /* Sorting */
        $sort  = 'created_at';
        $order = 'desc';
        $tableName = 'admins';

        if ($request->has('sort') && $request->has('order')) {
            $sort  = $request->get('sort');
            $order = $request->get('order');
        }

        $users = Admin::query()
            ->orderBy($tableName . '.' . $sort, $order)
            ->with('roles')
            ->paginate(20);

        return view('admin.admins.index', [
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new Admin();

        return view('admin.admins.create', [
            'model' => $model
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminCreateRequest $request)
    {
        /* @var $user Admin */

        $user = Admin::create([
            'name'           => $request->get('name'),
            'email'          => $request->get('email'),
            'password'       => Hash::make($request->get('password')),
            'status'         => Admin::STATUS_REGISTER,
        ]);

        $user->assignRole($request->get('roles'));

        return redirect()->route('admins.index')->with('success', __('Created successfully!'));
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
        $model = Admin::query()->where('id', $id)->first();

        return view('admin.admins.edit', [
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
    public function update(AdminUpdateRequest $request, $id)
    {
        /* @var $model Admin */
        $model = Admin::query()->where('id', $id)->first();

        try {
            $model->name = $request->get('name');
            $model->email = $request->get('email');

            if ($request->has('password') && $request->get('password')) {
                $model->password = Hash::make($request->get('password'));
            }

            $model->save();

            $model->syncRoles($request->get('roles'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', __('Updated successfully!'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $user = Admin::query()->where('id', $id)->first();
        $user->syncRoles([]);

        Admin::query()->where('id', $id)->delete();

        return redirect()->route('admins.index')->with('success', __('Deleted successfully!'));
    }
}

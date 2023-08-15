<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:users_view')->only('index');
        $this->middleware('permission:users_create')->only('create', 'store');
        $this->middleware('permission:users_edit')->only('edit', 'update');
        $this->middleware('permission:users_delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $name = $request->get('name');

        /* Sorting */
        $sort  = 'created_at';
        $order = 'desc';
        $tableName = 'users';

        if ($request->has('sort') && $request->has('order')) {
            $sort  = $request->get('sort');
            $order = $request->get('order');
        }

        $users = User::query()
            ->orderBy($tableName . '.' . $sort, $order)
            ->where(function ($q) use ($name, $tableName) {
                if ($name != '') {
                    $q->where($tableName . '.name', 'like', '%' . $name . '%');
                    $q->orWhere($tableName . '.email', 'like', '%' . $name . '%');
                    $q->orWhere($tableName . '.phone', 'like', '%' . $name . '%');
                }
            })
            ->with('roles')
            ->paginate(20);

        return view('admin.users.index', [
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
        $model = new User();

        return view('admin.users.create', [
            'model' => $model
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserCreateRequest $request)
    {
        /* @var $user User */

        $user = User::create([
            'name'           => $request->get('name'),
            'email'          => $request->get('email'),
            'phone'          => $request->get('phone'),
            'discount_id'    => $request->get('discount_id'),
            'password'       => Hash::make($request->get('password')),
            'status'         => User::STATUS_REGISTER,
        ]);

        $user->assignRole($request->get('roles'));

        return redirect()->route('users.index')->with('success', __('Created successfully!'));
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
        $model = User::query()->where('id', $id)->first();

        return view('admin.users.edit', [
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
    public function update(UserUpdateRequest $request, $id)
    {
        /* @var $model User */
        $model = User::query()->where('id', $id)->first();

        try {
            $model->name = $request->get('name');
            $model->email = $request->get('email');
            $model->status = $request->get('status');
            $model->phone = $request->get('phone');
            $model->discount_id = $request->get('discount_id');

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
        if ($id == User::NO_USER) {
            return redirect()->route('users.index')->with('error', __('Can`t delete, User is undestroyable (◕‿◕)'));
        }

        $user = User::query()->where('id', $id)->first();

        $user->syncRoles([]);

        $user->delete();

        return redirect()->route('users.index')->with('success', __('Deleted successfully!'));
    }

    public function deleteSelected(Request $request)
    {
        $not_deleted = [];
        $ids = json_decode($request->get('ids'), true);

        if (count($ids)) {
            foreach ($ids as $id) {
                $user = User::query()->where('id', $id)->first();

                if ($id == User::NO_USER) {
                    $not_deleted[] = $id;
                    continue;
                }

                $user->syncRoles([]);

                $user->delete();
            }

            if (!empty($not_deleted)) {
                return redirect()->route('users.index')->with('error', __('Can`t delete Users with ids ' . implode(',', $not_deleted)));
            }

            return redirect()->route('users.index')->with('success', __('Success!'));
        }

        return redirect()->route('users.index')->with('error', __('Error! '));
    }
}

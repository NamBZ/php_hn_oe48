<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Enums\UserStatus;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::paginate(config('pagination.per_page'));

        return view('dashboards.admin.users.index', [
            "users" => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboards.admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->role = $request->role;
        $user->password = Hash::make($request->password);

        if ($user->save()) {
            return redirect()->route('admin.users.index')->with('success', __('Create successfully'));
        }

        return redirect()->back()->with('error', __('Failed to create'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('dashboards.admin.users.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->role = $request->role;
        $user->address = $request->address;
        if (isset($request->password)) {
            $user->password = Hash::make($request->password);
        }

        if ($user->save()) {
            return redirect()->route('admin.users.index')->with('success', __('Update successfully'));
        }

        return redirect()->back()->with('error', __('Failed to update'));
    }

    /**
     * Block the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function blockUser($id)
    {
        $user = User::findorfail($id);

        if ($user->role == UserRole::ADMIN) {
            return redirect()->back()->with('error', __('Can not block admin'));
        }
        $user->status = UserStatus::BAN;

        if ($user->save()) {
            return redirect()->route('admin.users.index')->with('success', __('User is locked'));
        }

        return redirect()->back()->with('error', __('Failed to block user'));
    }

    /**
     * Block the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function unblockUser($id)
    {
        $user = User::findorfail($id);

        $user->status = UserStatus::ACTIVE;

        if ($user->save()) {
            return redirect()->route('admin.users.index')->with('success', __('User is unlocked'));
        }

        return redirect()->back()->with('error', __('Failed to unblock user'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findorfail($id);

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', __('Delete successfuly'));
    }
}

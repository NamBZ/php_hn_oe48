<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Enums\UserRole;
use App\Repositories\User\UserRepositoryInterface;
use App\Models\User;

class UserController extends Controller
{
    protected $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->userRepo->paginate(config('pagination.per_page'));

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
        $user_data = $request->only([
            'name',
            'email',
            'phone',
            'role',
        ]);
        $user_data['password'] = Hash::make($request->password);

        if ($this->userRepo->create($user_data)) {
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
        $user = $this->userRepo->findOrFail($id);

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
        $user = $this->userRepo->findOrFail($id);
        $user_data = $request->only([
            'name',
            'phone',
            'role',
            'address',
        ]);

        if (isset($request->password)) {
            $user_data['password'] = Hash::make($request->password);
        }

        if ($this->userRepo->update($user->id, $user_data)) {
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
        $user = $this->userRepo->findorfail($id);

        if ($user->role == UserRole::ADMIN) {
            return redirect()->back()->with('error', __('Can not block admin'));
        }

        if ($this->userRepo->blockUser($user->id)) {
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
        $user = $this->userRepo->findorfail($id);

        if ($this->userRepo->unblockUser($user->id)) {
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
        $user = $this->userRepo->findorfail($id);

        $this->userRepo->delete($user->id);

        return redirect()->route('admin.users.index')
            ->with('success', __('Delete successfuly'));
    }
}

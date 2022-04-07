<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\User\UserRepositoryInterface;
use App\Http\Requests\Profile\UpdateRequest;
use App\Http\Requests\User\ChangePasswordRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    protected $userRepo;

    public function __construct(
        UserRepositoryInterface $userRepo
    ) {
        $this->userRepo = $userRepo;
    }

    public function editProfile()
    {
        $user = Auth::user();

        return view('user.profile.edit', compact('user'));
    }

    public function updateProfile(UpdateRequest $request)
    {
        $user = Auth::user();
        $dirImages = "images/profile/";
        if ($request->hasfile('avatar')) {
            $imageName = explode("/", $user->avatar);
            $destination = $dirImages . end($imageName);
            if (File::exists($destination)) {
                File::delete($destination);
            }
            $file = $request->file('avatar');
            $extension = $file->getClientOriginalExtension();
            $newImage = time() . '-' . rand(0, 255) . '-' .  $request->name . '.' . $extension;
            $file->move(public_path($dirImages), $newImage);
            $imageLink = asset($dirImages . $newImage);
        } else {
            $imageLink = $user->avatar;
        }
        $this->userRepo->update($user->id, [
            'name' => $request->name,
            'address' => $request->address,
            'avatar' => $imageLink,
        ]);

        return redirect()->back()
            ->with('success', __('Update profile successfully'));
    }

    public function editPassword()
    {
        $user = Auth::user();

        return view('user.profile.changepass', compact('user'));
    }

    public function updatePassword(ChangePasswordRequest $request)
    {
        $user = Auth::user();
        $userPassword = $user->password;
        if (!Hash::check($request->current_password, $userPassword)) {
            return back()->withErrors(['current_password' => __('Old password not match')]);
        }
        $this->userRepo->update($user->id, [
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->route('profile')
            ->with('success', __('Update password successfully'));
    }
}

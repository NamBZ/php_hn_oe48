<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RegisterRequest;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    protected $userRepo;

    public function __construct(
        UserRepositoryInterface $userRepo
    ) {
        $this->userRepo = $userRepo;
    }

    public function register(RegisterRequest $request)
    {
        try {
            $result = $this->userRepo->create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'phone' => $request->input('phone'),
            ]);
            if ($result) {
                return response()->json([
                    'status' => 'success',
                    'message' => __('Registed successfully'),
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

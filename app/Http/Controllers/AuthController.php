<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * @var AuthService
     */
    private $authService;

    /**
     * @param authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    /**
     * SignUp user
     *
     * @param  SignupRequest  $request
     *
     * @return JsonResponse
     */
    public function signup(SignupRequest $request)
    {
        $user = $this->authService->signup($request->all());

        return response()->json(['success'=>$user]);
    }

    /**
     * Login user
     *
     * @param  LoginRequest  $request
     *
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $user = $this->authService->login($request);

        return response()->json($user);
    }

    /**
     * Logout user
     *
     * @return JsonResponse
     */
    public function logout()
    {
        Auth::user()->token()->revoke();

        return response()->json([
            'message' => trans('auth.logout_success'),
        ]);
    }
}

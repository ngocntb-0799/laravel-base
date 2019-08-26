<?php

namespace App\Services;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\LaravelBaseApiException;

use App\Models\User;

class AuthService
{
    /**
     * SignUp user
     *
     * @param  array  $input
     *
     * @return array
     */
    public function signup($input)
    {
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;

        return $success;
    }

    /**
     * Login User
     *
     * @param  array  $request
     *
     * @return mixd
     */
    public function login($request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success = [
                'user_name' => $user->name,
                'email' => $user->email,
                'token' => $user->createToken('MyApp')->accessToken,
            ];

            return ['success' => $success];
        } else {
            throw new LaravelBaseApiException('unauthorized');
        }
    }
}

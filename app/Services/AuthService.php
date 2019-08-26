<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\LaravelBaseApiException;

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
        $success['name'] =  $user->name;
        $success['email'] =  $user->email;
        $success['token'] =  $user->createToken('MyApp')->accessToken;

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

<?php

namespace App\Services;

use App\Models\Role;
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
        $user = User::create($input)->assignRole(Role::MEMBER);
        $success['name'] =  $user->name;
        $success['email'] =  $user->email;

        return $success;
    }

    /**
     * Login User
     *
     * @param  array  $request
     *
     * @throws LaravelBaseApiException
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

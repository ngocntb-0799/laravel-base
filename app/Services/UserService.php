<?php

namespace App\Services;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Arr;
use App\Jobs\SendWelcomeEmail;

class UserService
{
    /**
     * Get list user.
     *
     * @return object
     */
    public function getListUser()
    {
        $users = User::all();

        return $users;
    }

    /**
     * Create a user.
     *
     * @param array $input
     *
     * @return array
     */
    public function createUser($input)
    {
        $user = User::create($input)->assignRole(Role::MEMBER);
        SendWelcomeEmail::dispatch($user);

        return $this->getCollectionUser($user);
    }

    /**
     * Show detail a member.
     *
     * @param User $member
     *
     * @return array
     */
    public function showUser($member)
    {
        return $this->getCollectionUser($member);
    }

    /**
     * Update a user.
     *
     * @param array $input
     * @param User $user
     *
     * @return bool
     */
    public function updateUser($input, $user)
    {
        $response = $user->update($input);

        return $response;
    }

    /**
     * Delete a user.
     *
     * @param User $user
     *
     * @return bool
     */
    public function deleteUser($user)
    {
        $response = $user->delete($user->id);

        return $response;
    }

    /**
     * Get collection user.
     *
     * @param User $user
     *
     * @return array
     */
    public function getCollectionUser($user)
    {
        $roleName = Arr::pluck($user->roles, 'name');

        return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $roleName
        ];
    }
}

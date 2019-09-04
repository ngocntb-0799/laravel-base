<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use App\Http\Requests\SignupRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    /**
     * @var UserService
     */
    private $userService;


    /**
     * @param userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('get_list_user', User::class);
        $users = $this->userService->getListUser();

        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  SignupRequest  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(SignupRequest $request)
    {
        $this->authorize('create_user', User::class);
        $user = $this->userService->createUser($request->all());

        return response()->json([
            'message' => trans('auth.success'),
            'data' => $user
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $member = User::find($id);
        $this->authorize('show_detail_a_user', $member);
        $member = $this->userService->showUser($member);

        return response()->json($member);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateUserRequest  $request
     * @param  User  $user
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update_user', $user);
        $response = $this->userService->updateUser($request->all(), $user);

        return response()->json([
            'success' => $response
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('delete_user', $user);
        $response = $this->userService->deleteUser($user);

        return response()->json(['success' => $response ? true : false]);
    }
}

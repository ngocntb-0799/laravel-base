<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\User;
use App\Services\AuthService;
use App\Http\Requests\LoginRequest;
use App\Exceptions\LaravelBaseApiException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthServiceTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var AuthService
     */
    protected $authService;

    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->authService = new AuthService();
    }

    /**
     * Singup a user
     *
     * @test
     *
     * @return void
     */
    public function signup()
    {
        $input = [
            'name' => 'Nguyen Ngoc',
            'email' => 'nguyenngoc.hust.97@gmail.com',
            'password' => 'Aa@1234546'
        ];
        $response = $this->authService->signup($input);
        $this->assertIsArray($response);
        $this->assertArrayHasKey('name', $response);
        $this->assertArrayHasKey('email', $response);
    }

    /**
     * Login user success
     *
     * @test
     *
     * @return void
     */
    public function login_success()
    {
        $userTest = factory(User::class)->create([
            'name' => 'Nguyen Ngoc',
            'email' => 'nguyen.thi.bich.ngoc@sun-asterisk.com',
            'password' => 'Aa@123456',
        ]);
        $request = new LoginRequest();
        $request['email'] = 'nguyen.thi.bich.ngoc@sun-asterisk.com';
        $request['password'] = 'Aa@123456';
        $response = $this->authService->login($request);
        $this->assertIsArray($response);
    }

    /**
     * Login user fail
     *
     * @test
     *
     * @expectException LaravelBaseApiException
     *
     * @return void
     */
    public function login_fail()
    {
        $userTest = factory(User::class)->create([
            'name' => 'Nguyen Ngoc',
            'email' => 'nguyen.thi.bich.ngoc@sun-asterisk.com',
            'password' => 'Aa@123456',
        ]);
        $request = new LoginRequest();
        $request['email'] = 'nguyen.thi.bich.ngoc@sun-asterisk.com';
        $request['password'] = 'Aa@12';
        $this->expectExceptionMessage('Unauthorized, please check your credentials.');
        $response = $this->authService->login($request);
    }
}

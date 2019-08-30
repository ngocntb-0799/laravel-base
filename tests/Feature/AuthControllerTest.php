<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Signup a user success.
     *
     * @test
     *
     * @return void
     */
    public function signup_a_user_success()
    {
        $userTest = [
            'name' => 'Nguyen Ngoc',
            'email' => 'nguyenngoc.hust.97@gmail.com',
            'password' => 'Aa@123456',
        ];
        $response = $this->json('POST', '/api/signup', $userTest);
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'success',
                'data' => [
                    'name' => 'Nguyen Ngoc',
                    'email' => 'nguyenngoc.hust.97@gmail.com'
                ]
            ]);
    }

    /**
     * Signup a user fail.
     *
     * @test
     *
     * @param array $userData
     * @param array $response
     *
     * @dataProvider providerTestSignupFail
     *
     * @return void
     */
    public function signup_a_user_fail($userData, $responseData)
    {
        $response = $this->post('/api/signup', $userData);

        $response->assertStatus(400)
            ->assertJson($responseData);
    }

    /**
     * Login user success.
     *
     * @test
     *
     * @return void
     */
    public function login_user_success()
    {
        $passwordTest = 'Aa@123456';
        $userTest = factory(User::class)->create(['password' => $passwordTest]);
        $response = $this->json('POST', '/api/login', [
            'email' => $userTest->email,
            'password' => $passwordTest
        ]);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success' => [
                    'user_name',
                    'email',
                    'token',
                ]
            ]);
    }

    /**
     * Login user fail.
     *
     * @test
     *
     * @param array $userDataTest
     * @param array $responseTest
     *
     * @dataProvider providerTestLoginFail
     *
     * @return void
     */
    public function login_user_fail($userDataTest, $responseTest)
    {
        $response = $this->post('/api/login', $userDataTest);
        $response->assertStatus(400)
            ->assertJson($responseTest);
    }

    /**
     * Login user.
     *
     * @test
     *
     * @return void
     */
    public function logout_user_sucess()
    {
        $user = factory(User::class)->make();
        $token = $user->createToken('MyApp')->token;
        $user->withAccessToken($token);
        $response = $this->actingAs($user, 'api')->call('POST', '/api/logout');
        $response->assertJson([
            'message' => trans('auth.logout_success'),
        ]);
    }

    /**
     * Data provider test signup fail.
     *
     * @return array
     */
    public function providerTestSignupFail()
    {
        return [
            [
                [
                    'name' => '',
                    'email' => 'nguyen.thi.bich.ngoc@sun-asterisk.com',
                    'password' => 'Aa@123456',
                ],
                [
                    'success' => false,
                    'error' => [
                        'code' => 622,
                        'message' => 'The name field is required.'
                    ]
                ]
            ],
            [
                [
                    'name' => 'Nguyen Ngoc',
                    'email' => '',
                    'password' => 'Aa@123456',
                ],
                [
                    'success' => false,
                    'error' => [
                        'code' => 622,
                        'message' => 'The email field is required.'
                    ]
                ]
            ],
            [
                [
                    'name' => 'Nguyen Ngoc',
                    'email' => 'nguyen.thi.bich.ngoc',
                    'password' => 'Aa@123456',
                ],
                [
                    'success' => false,
                    'error' => [
                        'code' => 622,
                        'message' => 'The email must be a valid email address.'
                    ]
                ]
            ],
            [
                [
                    'name' => 'Nguyen Ngoc',
                    'email' => 'nguyen.thi.bich.ngoc@sun-asterisk.com',
                    'password' => '',
                ],
                [
                    'success' => false,
                    'error' => [
                        'code' => 622,
                        'message' => 'The password field is required.',
                    ]
                ]
            ],
            [
                [
                    'name' => 'Nguyen Ngoc',
                    'email' => 'nguyen.thi.bich.ngoc@sun-asterisk.com',
                    'password' => 'Aa',
                ],
                [
                    'success' => false,
                    'error' => [
                        'code' => 622,
                        'message' => 'The password must be at least 8 characters.'
                    ]
                ]
            ],
        ];
    }

    /**
     * Data provider test login fail.
     *
     * @return array
     */
    public function providerTestLoginFail()
    {
        return [
            [
                [
                    'email' => 'nguyen.thi.bich.ngoc@sun-asterisk.com',
                    'password' => 'Aa@123456',
                ],
                [
                    'success' => false,
                    'error' => [
                        'code' => 601,
                        'message' => 'Unauthorized, please check your credentials.'
                    ]
                ]
            ],
            [
                [
                    'email' => '',
                    'password' => 'Aa@123456',
                ],
                [
                    'success' => false,
                    'error' => [
                        'code' => 622,
                        'message' => 'The email field is required.'
                    ]
                ]
            ],
            [
                [
                    'email' => 'nguyen.thi.bich.ngoc@sun-asterisk.com',
                    'password' => '',
                ],
                [
                    'success' => false,
                    'error' => [
                        'code' => 622,
                        'message' => 'The password field is required.'
                    ]
                ]
            ]
        ];
    }
}

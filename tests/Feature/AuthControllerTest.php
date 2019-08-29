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
            'email' => 'nguyenngoc1@gmail.com',
            'password' => 'Aa@123456',
        ];
        $response = $this->json('POST', '/api/signup', $userTest);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'name',
                    'email'
                ]
            ]);
    }

    /**
     * Signup a user fail.
     *
     * @test
     *
     * @param array $userDataTest
     * @param array $responseTest
     *
     * @dataProvider providerTestSignupFail
     *
     * @return void
     */
    public function signup_a_user_fail($userDataTest, $responseTest)
    {
        $response = $this->post('/api/signup', $userDataTest);

        $response->assertStatus(400)
            ->assertJson($responseTest);
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
        $user = factory(User::class)->create([
            'name' => 'Nguyen Ngoc',
            'email' => 'nguyen.thi.bich.ngoc@sun-asterisk.com',
            'password' => bcrypt('Aa@123456'),
        ]);
        $response = $this->json('POST', '/api/login', [
            'email' => 'nguyen.thi.bich.ngoc@sun-asterisk.com',
            'password' => 'Aa@123456'
        ]);
        $response
            ->assertStatus(200)
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

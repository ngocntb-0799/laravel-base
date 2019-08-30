<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    /**
     * Test set password attribute
     *
     * @test
     *
     * @return void
     */
    public function set_password_attribute()
    {
        $user = new User();
        $password = 'Aa@123456';
        $user->setPasswordAttribute($password);
        $isPasswordEqual = Hash::check('Aa@123456', $user['password']);
        $this->assertTrue($isPasswordEqual);
    }
}

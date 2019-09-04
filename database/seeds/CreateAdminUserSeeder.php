<?php

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = factory(User::class)->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => 'Aa@123456'
        ]);

        $user->assignRole(Role::ADMIN);

    }
}

<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roleAdminUser = Role::query()->where('title','super-admin')->first();
         User::query()->insert([
            'name' => 'ahmad',
            'role_id' => $roleAdminUser->id,
            'email' => 'admimohammadi@gmail.com',
            'password' => bcrypt(123456),
        ]);
    }
}

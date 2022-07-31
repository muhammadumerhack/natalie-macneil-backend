<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name'=>'admin',
            'username'=>'admin',
            'email'=>'admin@demo.com',
            'password'=>bcrypt('123456789'),
            'role_id'=>1,
            'status'=>'active',
        ]);

        $user = User::create([
            'name'=>'client',
            'username'=>'client',
            'email'=>'client@demo.com',
            'password'=>bcrypt('123456789'),
            'role_id'=>2,
            'status'=>'active',
        ]);

    }
}

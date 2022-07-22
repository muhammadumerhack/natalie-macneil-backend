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
        $user = User::create( [
            'name'=>'admin',
            'username'=>'admin',
            'email'=>'admin@demo.com',
            'password'=>  bcrypt('123456789'),
            'country'=>"US",
            'phone'=>"1234567",
            'group_id'=>null,
            'role_id'=>1,
            'fixed_fees'=>null,
            'status'=>'active',
        ] );

        $user = User::create( [
            'name'=>'verifier',
            'username'=>'verifier',
            'email'=>'verifier@demo.com',
            'password'=>  bcrypt('123456789'),
            'country'=>"US",
            'phone'=>"1234567",
            'group_id'=>1,
            'role_id'=>2,
            'fixed_fees'=>50,
            'status'=>'active',
        ] );

    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'New User',
            'email' => 'new@user.com',
            'doc' =>   '123456789123',
            'user_type' => 0,
            'password' => Hash::make('password')
        ]);
    }
}

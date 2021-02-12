<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Wallet;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'New User',
            'email' => 'new@user.com',
            'email_verified_at' => now(),
            'doc' =>   '123456789123',
            'user_type' => 0,
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ]);

        User::factory(20)
            ->has(Wallet::factory()->count(1))
            ->create();
    }
}

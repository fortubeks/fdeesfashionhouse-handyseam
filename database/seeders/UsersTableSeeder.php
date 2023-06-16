<?php
namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
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
            'name' => 'Admin Admin',
            'user_type' => 'admin',
            'email' => 'ja@ja.com',
            'phone' => '08012345678',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'user_account_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}

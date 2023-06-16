<?php
namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PackagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('packages')->insert([
            'name' => 'Free',
            'amount' => 0.00,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('packages')->insert([
            'name' => 'Silver',
            'amount' => 2500.00,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('packages')->insert([
            'name' => 'Premium',
            'amount' => 7000.00,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}

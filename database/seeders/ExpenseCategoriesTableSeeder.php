<?php
namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ExpenseCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('expense_categories')->insert([
            'name' => 'Fabrics',
            'user_id' =>0,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('expense_categories')->insert([
            'name' => 'Appliques',
            'user_id' =>0,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('expense_categories')->insert([
            'name' => 'Salaries',
            'user_id' =>0,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('expense_categories')->insert([
            'name' => 'Others',
            'user_id' =>0,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}

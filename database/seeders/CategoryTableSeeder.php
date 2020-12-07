<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('category')->insert([
            'name' => 'Dog',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('category')->insert([
            'name' => 'Cat',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('category')->insert([
            'name' => 'Parrot',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}

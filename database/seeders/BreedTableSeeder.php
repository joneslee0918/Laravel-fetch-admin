<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BreedTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('breed')->insert([
            'name' => 'Bull Dog',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('breed')->insert([
            'name' => 'Persion',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('breed')->insert([
            'name' => 'Shepter',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}

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
            'name' => 'BullDog',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('breed')->insert([
            'name' => 'Persian',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('breed')->insert([
            'name' => 'Ashenfallow Cockatiel',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}

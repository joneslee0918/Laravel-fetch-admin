<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppSettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('app_setting')->insert([
            'meta_key' => 'firebase_api_key',
            'meta_value' => 'AAAA6qkKNOQ:APA91bF_pgfBeYZxNeK7RVYvGnP7-KwZcHCUGgduOWDnwoiUZzrVx1Vr6ghe1WUJQre-nktpg64r7JkFMahdMlxo1nVNN_W7PjStPueAFCy33PcXyfp63nLZFyDhnc2wsdGgYrBsaBv5',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}

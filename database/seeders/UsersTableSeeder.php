<?php
namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder {
    /**
    * Run the database seeds.
    *
    * @return void
    */

    public function run() {
        DB::table( 'users' )->insert( [
            'name' => 'Fetch Admin',
            'email' => 'fetchadmin.com',
            'email_verified_at' => now(),
            'password' => Hash::make( 'fetch' ),
            'role' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ] );

        DB::table( 'user_meta' )->insert( [
            'id_user' => '1',
            'meta_key' => '_show_notification',
            'meta_value' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ] );

        DB::table( 'user_meta' )->insert( [
            'id_user' => '1',
            'meta_key' => '_show_phone_on_ads',
            'meta_value' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ] );
    }
}

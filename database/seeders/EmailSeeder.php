<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('email')->insert([
            'title' => 'Fetch - Your Local Pet Marketplace',
            'content' => 'Welcome to join our app! \Please change the password in profile setting after login.\ n <br> <b> Username: {username}</b> Your email: {email}, \ n Password: {password}',
            'type' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('email')->insert([
            'title' => 'Fetch - Your Local Pet Marketplace',
            'content' => 'Welcome to our app!',
            'type' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('email')->insert([
            'title' => 'Fetch - Your Local Pet Marketplace',
            'content' => 'Your password has been changed.',
            'type' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        // DB::table('email')->insert([
        //     'title' => 'Fetch - Your Local Pet Marketplace',
        //     'content' => 'Confirm your verification code.\{verify_code}',
        //     'type' => 3,
        //     'created_at' => now(),
        //     'updated_at' => now()
        // ]);
        DB::table('email')->insert([
            'title' => 'Fetch - Your Local Pet Marketplace',
            'content' => 'Your information has been modified by the administrator. \Please log back in.',
            'type' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('email')->insert([
            'title' => 'Fetch - Your Local Pet Marketplace',
            'content' => 'Your account has been {account_status} by administrator.',
            'type' => 5,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('email')->insert([
            'title' => 'Fetch - Your Local Pet Marketplace',
            'content' => 'Your account has been deleted by administrator.',
            'type' => 6,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}

<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            'name' => 'admin',
            'email' => 'admin@mail.com',
            'password' => bcrypt('secret'),
            'phoneNumber' => '+85570518873',
            'isAdmin' => true
        ]);
        // DB::table('users')->insert([
        //     'name' => 'user',
        //     'email' => 'user@mail.com',
        //     'password' => bcrypt('secret'),
        //     'phoneNumber' => '+85570518873',
        //     'isAdmin' => false
        // ]);
    }
}

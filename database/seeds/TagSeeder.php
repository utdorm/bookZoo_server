<?php

use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tags')->insert([
            'tag_name' => 'Fiction'
        ]);
        DB::table('tags')->insert([
            'tag_name' => 'Thriller'
        ]);
        DB::table('tags')->insert([
            'tag_name' => 'Romance'
        ]);
        DB::table('tags')->insert([
            'tag_name' => 'Non-Fiction'
        ]);
        DB::table('tags')->insert([
            'tag_name' => 'Self-help'
        ]);
        DB::table('tags')->insert([
            'tag_name' => 'Classic'
        ]);
        DB::table('tags')->insert([
            'tag_name' => 'Khmer Books'
        ]);
    }
}

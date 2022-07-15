<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Sastra',
            'email' => 'rsastra901@gmail.com',
            'password' => bcrypt('12345'),
            'level' => 0
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([UserSeeder::class]);
        User::create([
            'name' => 'Rianto',
            'email' => 'sastra90@gmail.com',
            'password' => bcrypt('12345'),
            'level' => 1
        ]);
    }
}

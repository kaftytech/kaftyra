<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // You can use the User model to create users
        // For example:
        // \App\Models\User::factory()->count(10)->create();
        // Or you can use the DB facade to insert directly
        DB::table('users')->insert([
            'name' => 'Kafty Tech',
            'email' => 'kaftytech@gmail.com',
            'password' => Hash::make('password'),
        ]);
    }
}

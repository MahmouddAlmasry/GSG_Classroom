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
        //Query Buider
        DB::table('users')->insert([
            'name' => 'Mahmoud Almasry',
            'email' => 'm@almasry.ps',
            'password' => Hash::make('password'),
        ]);
    }
}

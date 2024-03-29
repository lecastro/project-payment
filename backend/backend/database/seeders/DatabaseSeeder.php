<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'name'      => 'teste',
            'email'     => 'usuario@example.com',
            'password'  => Hash::make('senha'),
        ]);
    }
}

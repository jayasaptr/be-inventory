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
        DB::table('users')->insert([
            'name' => 'Admin',
            'nip' => '123456789',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'no_telp' => '08123456789',
            'alamat' => 'Jl. Raya No. 1',
            'role' => 'admin',
            'foto' => 'default.jpg',
        ]);
    }
}

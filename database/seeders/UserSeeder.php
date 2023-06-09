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
            'username' => 'vhiweb',
            'name' => 'vhiweb users',
            'email' => 'vhiweb@gmail.com',
            'password' => Hash::make('testvhiweb'),
            'role_id' => 1,
        ]);
        DB::table('users')->insert([
            'username' => 'vhiwebadmin',
            'name' => 'vhiweb admin',
            'email' => 'vhiweb_admin@gmail.com',
            'password' => Hash::make('testvhiwebadmin'),
            'role_id' => 2,
        ]);
    }
}

<?php

namespace Database\Seeders;

use Carbon\Carbon;
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
            'name' => 'Bruno Angos',
            'email' => 'brunoangos@gmail.com',
            'password' => Hash::make('12345678'),
            'created_at' => Carbon::now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Tester PHPUnit',
            'email' => 'testerphpunit@gmail.com',
            'password' => Hash::make('12345678'),
            'created_at' => Carbon::now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Rodrigo Warzak',
            'email' => 'rodrigo.warzak@terceirizados.farmarcas.com.br',
            'password' => Hash::make('12345678'),
            'created_at' => Carbon::now(),
        ]);
    }
}

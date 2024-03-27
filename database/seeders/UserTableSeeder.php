<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nama'      => 'Fika Ridaul Maulayya',
            'username'  => 'fikarm',
            'email'     => 'admin@gmail.com',
            'password'  => bcrypt('admin'),
            'no_hp'     => '088909878909'
        ]);
    }
}

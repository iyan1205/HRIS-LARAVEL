<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name'      => 'IT Develop',
            'email'      => 'it@rs-hamori.co.id',
            'password'      => Hash::make('!Hamori24$'),
        ]);
        $admin->assignRole('admin');

        $karyawan = User::create([
            'name'      => 'Iyan',
            'email'      => 'iyan.hermawan@rs-hamori.co.id',
            'password'      => Hash::make('12345678'),
        ]);
        $karyawan->assignRole('karyawan');
    }
}

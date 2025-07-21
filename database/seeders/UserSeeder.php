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
            'name'      => 'IT Dev',
            'email'      => 'it@rs-hamori.co.id',
            'password'      => Hash::make('ITh4m0r1@'),
        ]);
        $admin->assignRole('Super-Admin');

        $karyawan = User::create([
            'name'      => 'Admin HRIS',
            'email'      => 'admin.hris@rs-hamori.co.id',
            'password'      => Hash::make('!Hamori24$'),
        ]);
        $karyawan->assignRole('admin');
    }
}

<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'tambah-user']);
        Permission::create(['name' => 'edit-user']);
        Permission::create(['name' => 'hapus-user']);
        Permission::create(['name' => 'lihat-user']);

        Permission::create(['name' => 'tambah-karyawan']);
        Permission::create(['name' => 'edit-karyawan']);
        Permission::create(['name' => 'hapus-karyawan']);
        Permission::create(['name' => 'lihat-karyawan']);

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'karyawan']);

        $roleAdmin = Role::findByName('admin');
        $roleAdmin->givePermissionTo('tambah-user');
        $roleAdmin->givePermissionTo('edit-user');
        $roleAdmin->givePermissionTo('hapus-user');
        $roleAdmin->givePermissionTo('lihat-user');
        $roleAdmin->givePermissionTo('tambah-karyawan');
        $roleAdmin->givePermissionTo('edit-karyawan');
        $roleAdmin->givePermissionTo('hapus-karyawan');
        $roleAdmin->givePermissionTo('lihat-karyawan');

        $roleKaryawan = Role::findByName('karyawan');
        $roleKaryawan->givePermissionTo('lihat-karyawan');
    }
}

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
        // Create permissions
        $permissions = [
            'create user', 'edit user', 'delete user', 'view user',
            'create karyawan', 'edit karyawan', 'delete karyawan', 'view karyawan',
            'tambah permission', 'sidebar organisasi', 'sidebar masterkaryawan',
            'sidebar masteruser', 'sidebar pengajuancuti', 'resign karyawan',
            'view cuti', 'edit cuti', 'delete cuti', 'tambah cuti', 'approve cuti', 
            'pelatihan', 'view saldo', 'edit saldo', 'delete saldo', 'create saldo', 
            'sidebar saldocuti', 'approve overtime', 'view overtime', 'edit overtime', 
            'delete overtime', 'tambah overtime', 'sidebar laporan cuti', 'sidebar laporan lembur', 
            'restore departemen'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles
        $roles = ['Super-Admin', 'admin', 'karyawan', 'Approver'];
        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        // Assign permissions to roles
        $roleAdmin = Role::findByName('admin');
        $roleAdmin->givePermissionTo([
            'create karyawan', 'edit karyawan', 'delete karyawan', 'view karyawan',
            'sidebar organisasi', 'sidebar masterkaryawan', 'resign karyawan', 'pelatihan',
            'view saldo', 'edit saldo', 'delete saldo', 'create saldo', 'sidebar saldocuti',
            'sidebar laporan cuti', 'sidebar laporan lembur'
        ]);

        $roleKaryawan = Role::findByName('karyawan');
        $roleKaryawan->givePermissionTo([
            'edit user', 'sidebar pengajuancuti', 'view cuti', 'tambah cuti', 
            'delete cuti', 'view saldo', 'tambah overtime', 'view overtime'
        ]);

        $roleApprover = Role::findByName('Approver');
        $roleApprover->givePermissionTo([
            'view cuti', 'tambah cuti', 'edit cuti', 'delete cuti', 
            'approve cuti', 'approve overtime', 'view overtime', 'tambah overtime'
        ]);

        // Assign all permissions to Super-Admin
        $roleSuperAdmin = Role::findByName('Super-Admin');
        $roleSuperAdmin->syncPermissions(Permission::all());
    }
}

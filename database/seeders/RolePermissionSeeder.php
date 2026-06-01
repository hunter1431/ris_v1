<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'create ris',
            'approve ris',
            'issue stocks',
            'view inventory',
            'manage inventory',
            'view reports',
            'manage reports',
            'manage users',
            'audit trail',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web'])->syncPermissions($permissions);
        Role::firstOrCreate(['name' => 'Supply Officer', 'guard_name' => 'web'])->syncPermissions(['create ris', 'approve ris', 'issue stocks', 'manage inventory', 'view reports']);
        Role::firstOrCreate(['name' => 'Department Head', 'guard_name' => 'web'])->syncPermissions(['create ris', 'approve ris', 'view inventory', 'view reports']);
        Role::firstOrCreate(['name' => 'Division Head', 'guard_name' => 'web'])->syncPermissions(['create ris', 'approve ris', 'view inventory', 'view reports']);
        Role::firstOrCreate(['name' => 'Employee/Requester', 'guard_name' => 'web'])->syncPermissions(['create ris', 'view inventory', 'view reports']);
        Role::firstOrCreate(['name' => 'Auditor', 'guard_name' => 'web'])->syncPermissions(['view inventory', 'view reports', 'audit trail']);
    }
}

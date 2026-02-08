<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Super Admin
        $superUser = User::firstOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'name' => 'Super Admin',
                'username' => 'superadmin',
                'employee_id' => 100001,
                'is_admin' => 1,
                'password' => bcrypt('123456'),
            ]
        );

        $roleSuper = Role::firstOrCreate(['name' => 'super-admin']);
        $permissions = Permission::pluck('id', 'id')->all();
        $roleSuper->syncPermissions($permissions);
        $superUser->assignRole([$roleSuper->id]);

        // Admin
        $user = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin User',
                'username' => 'admin',
                'employee_id' => '1002',
                'password' => bcrypt('123456'),
            ]
        );

        $role = Role::firstOrCreate(['name' => 'Admin']);
        $permissions = Permission::pluck('id','id')->all();
        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);

        // Employee Role (limited self-service access)
        $employeeRole = Role::firstOrCreate(['name' => 'Employee']);
        $employeePermissions = Permission::whereIn('name', [
            'self-view-profile',
            'self-view-assets',
            'self-view-transactions',
        ])->pluck('id')->all();
        $employeeRole->syncPermissions($employeePermissions);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'product-list',
            'product-create',
            'product-edit',
            'product-delete',
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'product-type-list',
            'product-type-create',
            'product-type-edit',
            'product-type-delete',
            'suppliers-list',
            'suppliers-create',
            'suppliers-edit',
            'suppliers-delete',
            'purchase-list',
            'purchase-create',
            'purchase-edit',
            'purchase-delete',
            'department-list',
            'department-create',
            'department-edit',
            'department-delete',
            'employee-list',
            'employee-create',
            'employee-edit',
            'employee-delete',
            'inventory-list',
            'inventory-edit',
            'inventory-update-tag',
            'distribution-list',
            'distribution-create',
            'distribution-edit',
            'distribution-delete',
            'onboarding-list',
            'onboarding-create',
            'imports-list',
            'users-log'

         ];

         foreach ($permissions as $permission) {
              Permission::create(['name' => $permission]);
         }
    }
}

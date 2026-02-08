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
            // Roles & Permissions Management
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',

            // User Management
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',

            // Product Type Management
            'product-type-list',
            'product-type-create',
            'product-type-edit',
            'product-type-delete',

            // Product Management
            'product-list',
            'product-create',
            'product-edit',
            'product-delete',

            // Store/Location Management
            'store-list',
            'store-create',
            'store-edit',
            'store-delete',

            // Asset Status Management
            'status-list',
            'status-create',
            'status-edit',
            'status-delete',

            // Supplier Management
            'suppliers-list',
            'suppliers-create',
            'suppliers-edit',
            'suppliers-delete',

            // Purchase Management
            'purchase-list',
            'purchase-create',
            'purchase-edit',
            'purchase-delete',
            'purchase-addinventory',
            'purchase-approve',

            // Department Management
            'department-list',
            'department-create',
            'department-edit',
            'department-delete',

            // Employee Management
            'employee-list',
            'employee-create',
            'employee-edit',
            'employee-delete',

            // Inventory/Stock Management
            'inventory-list',
            'inventory-create',
            'inventory-edit',
            'inventory-delete',
            'inventory-update-tag',

            // Distribution/Transaction Management
            'distribution-list',
            'distribution-create',
            'distribution-edit',
            'distribution-delete',
            'distribution-return',

            // Report Management
            'report-list',
            'report-view',
            'report-employee',
            'report-product',
            'report-distribution',
            'report-purchase',
            'report-inventory',

            // QR Code & Barcode
            'qrcode-generate',
            'qrcode-print',
            'barcode-generate',
            'barcode-print',

            // Import Management
            'imports-list',
            'import-create',

            // System & Logs
            'users-log',
            'user-log-view',
            'system-settings',

            // Management
            'management-all',

            // Onboarding
            'onboarding-list',
            'onboarding-create',

            // Employee Self-Service
            'self-view-profile',
            'self-view-assets',
            'self-view-transactions',
         ];

         foreach ($permissions as $permission) {
              Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
         }
    }
}

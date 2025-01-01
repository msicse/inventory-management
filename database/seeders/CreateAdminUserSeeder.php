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
        $superUser = User::create([
            'name' => 'Super Admin', 
            'username' => 'superadmin', 
            'employee_id' => 100001, 
            'is_admin' => 1, 
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('123456')
        ]);


        $roleSuper = Role::create(['name' => 'super-admin']);
        $superUser->assignRole([$roleSuper->id]);
        

        // Admin
        $user = User::create([
            'name' => 'Admin User',
            'username' => 'admin',
            'employee_id' => '1002', 
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456')
        ]);

        $role = Role::create(['name' => 'Admin']);
        $permissions = Permission::pluck('id','id')->all();
        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);


    }
}

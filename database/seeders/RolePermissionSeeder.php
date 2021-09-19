<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\{Permission, Role};

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $arrayOfPermissionNames = [
            // Name | Description
            ['manage-company', 'Management company',],
            ['manage-employee', 'Management employee'],
            ['manage-product', 'Management product'],
            ['manage-transaction', 'Management transaction'],
            ['manage-customer', 'Management customer'],
            ['manage-category', 'Management category']
        ];

        $permissions = collect($arrayOfPermissionNames)->map(function ($permission) {
            return ['name' => $permission[0], 'description' => $permission[1], 'guard_name' => 'web'];
        });

        Permission::insert($permissions->toArray());

        // create roles Admin and assign permission manage-company
        $role = Role::create(['name' => 'Admin']);
        $role->givePermissionTo(['manage-company']);

        // create roles Company and assign permission manage-employee
        $role = Role::create(['name' => 'Company']);
        $role->givePermissionTo(['manage-employee', 'manage-product', 'manage-transaction', 'manage-customer', 'manage-category']);

        // create roles Employee and assign permissions
        $role = Role::create(['name' => 'Employee']);
        $role->syncPermissions(['manage-product', 'manage-transaction', 'manage-customer', 'manage-category']);

        // create roles Customer
        $role = Role::create(['name' => 'Customer']);
    }
}

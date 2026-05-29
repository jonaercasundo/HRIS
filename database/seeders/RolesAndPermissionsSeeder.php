<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'view reports']);
        Permission::create(['name' => 'manage users']);
        $admin = Role::create(['name' => 'Admin']);
        $admin->givePermissionTo(Permission::all());
        $hr = Role::create(['name' => 'HR']);
        $hr->givePermissionTo('view reports');
        $employee = Role::create(['name' => 'Employee']);
        $employee->givePermissionTo('view reports');

    }
}

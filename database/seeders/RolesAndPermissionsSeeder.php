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
        $role_admin = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $role_user = Role::create(['name' => 'service-center', 'guard_name' => 'web']);
        $role_user = Role::create(['name' => 'driver', 'guard_name' => 'api']);
        $role_user = Role::create(['name' => 'customer', 'guard_name' => 'api']);
    }
}

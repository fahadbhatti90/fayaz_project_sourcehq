<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddAllRoles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = Permission::pluck('id','id')->all();
        $role = Role::create(['name' => 'Owner']);
        $role->syncPermissions($permissions);
        $role = Role::create(['name' => 'Administrator']);
        $role = Role::create(['name' => 'Hiring Manager']);
        $role = Role::create(['name' => 'Project Manager']);
        $role = Role::create(['name' => 'Accounts']);
    }
}

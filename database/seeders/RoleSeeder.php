<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = Role::create(['name' => 'Super Admin']);
        $admin = Role::create(['name' => 'Admin']);
        $responseTeam = Role::create(['name' => 'Response Team']);
        $citizens = Role::create(['name' => 'Citizens']);

        $superAdmin->givePermissionTo([
            'read-access',
            'create-role',
            'edit-role',
            'delete-role',
            'create-user',
            'edit-user',
            'delete-user',
            'create-report',
            'read-userHome',
            'write-userHome',
            'read-dashboard',
            'read-incident_types',
            'write-incident_types',
            'view-responseTeam',
            'write-responseTeam',
            'view-members',
            'write-members',
            'readAndwrite-reports',
            'read-manageusers',
            'write-manageusers',
        ]);

        $admin->givePermissionTo([
            'read-dashboard',
            'read-incident_types',
            'write-incident_types',
            'view-responseTeam',
            'write-responseTeam',
            'view-members',
            'write-members',
            'readAndwrite-reports',
            'read-manageusers',
            'write-manageusers',
        ]);

        $responseTeam->givePermissionTo([
            'read-dashboard',
            'readAndwrite-routing',
        ]);

        $citizens->givePermissionTo([
            'create-report',
            'read-userHome',
            'write-userHome',
        ]);
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
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
            'readAndwrite-routing',
         ];
 
          // Looping and Inserting Array's Permissions into Permission Table
         foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
          }
    }
}
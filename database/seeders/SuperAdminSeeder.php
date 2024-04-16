<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creating Super Admin User
        $superAdmin = User::create([
            'name' => 'Raymart Paraiso', 
            'email' => 'raymart@gmail.com',
            'contact' => '09383025631',
            'password' => Hash::make('raymart1234'),
            'status' => 'approved',
        ]);
        $superAdmin->assignRole('Super Admin');

        // Creating Admin User
        $superAdmin = User::create([
            'name' => 'Jonaem Azis', 
            'email' => 'jonaem@gmail.com',
            'contact' => '09123456789',
            'password' => Hash::make('jonaem1234'),
            'status' => 'approved',
        ]);
        $superAdmin->assignRole('Super Admin');

        $superAdmin = User::create([
            'name' => 'MFaheem Azis', 
            'email' => 'mfbading@gmail.com',
            'contact' => '09133456789',
            'password' => Hash::make('badingako123'),
            'status' => 'approved',
        ]);
        $superAdmin->assignRole('Super Admin');

        $admin = User::create([
            'name' => 'Admin Only', 
            'email' => 'admin@gmail.com',
            'contact' => '09876545671',
            'password' => Hash::make('admin1234'),
            'status' => 'approved',
        ]);
        $admin->assignRole('Admin');

        $rteam = User::create([
            'name' => 'Team Member 1', 
            'email' => 'tmember1@gmail.com',
            'contact' => '09876785656',
            'password' => Hash::make('tmember1111'),
            'status' => 'approved',
        ]);
        $rteam->assignRole('Response Team');

        $rteam = User::create([
            'name' => 'Team Member 2', 
            'email' => 'tmember2@gmail.com',
            'contact' => '09872785656',
            'password' => Hash::make('tmember2222'),
            'status' => 'approved',
        ]);
        $rteam->assignRole('Response Team');
    }
}

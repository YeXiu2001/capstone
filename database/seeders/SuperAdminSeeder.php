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
        $admin = User::create([
            'name' => 'Jonaem Azis', 
            'email' => 'jonaem@gmail.com',
            'contact' => '09123456789',
            'password' => Hash::make('jonaem1234'),
            'status' => 'approved',
        ]);
        $admin->assignRole('Admin');

    }
}

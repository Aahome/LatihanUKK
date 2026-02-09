<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('role_name', 'admin')->firstOrFail();
        $staffRole = Role::where('role_name', 'staff')->firstOrFail();
        $borrowerRole = Role::where('role_name', 'borrower')->firstOrFail();

        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role_id'  => $adminRole->id,
        ]);

        User::create([
            'name'     => 'Staff',
            'email'    => 'staff@gmail.com',
            'password' => Hash::make('staff123'),
            'role_id'  => $staffRole->id,
        ]);

        User::create([
            'name'     => 'Borrower',
            'email'    => 'borrower@gmail.com',
            'password' => Hash::make('borrower123'),
            'role_id'  => $borrowerRole->id,
        ]);

        User::create([
            'name'     => 'Aziz Han XK',
            'email'    => 'azizhanxk@gmail.com',
            'password' => Hash::make('admin123'),
            'role_id'  => $borrowerRole->id,
        ]);
    }
}

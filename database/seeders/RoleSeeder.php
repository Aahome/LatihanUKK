<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        Role::insert([
            ['role_name' => 'admin'],
            ['role_name' => 'staff'],
            ['role_name' => 'borrower'],
        ]);
    }
}

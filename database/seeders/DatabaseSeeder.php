<?php

namespace Database\Seeders;

use App\Models\Tool;
use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed required base data FIRST
        $this->call([
            RoleSeeder::class,
            DefaultUserSeeder::class,
            CategorySeeder::class,
            ToolSeeder::class,
        ]);

        // // 2. Optional: test user WITHOUT admin access
        // User::factory()->create([
        //     'name'  => 'Test User',
        //     'email' => 'test@example.com',
        //     'role_id' => null, // explicitly no role
        // ]);
    }
}

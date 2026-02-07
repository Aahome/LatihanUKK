<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Tool;

class ToolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // later use
        $electronics = Category::where('category_name', 'Electronics')->first();
        $handTool    = Category::where('category_name', 'Hand Tool')->first();

        Tool::create([
            'tool_name'   => 'Laptop',
            // 'category_id' => $electronics->id,
            'category_id' => 1,
            'stock'       => 67,
            'condition'   => 'good',
        ]);

        Tool::create([
            'tool_name'   => 'Screwdriver',
            // 'category_id' => $handTool->id,
            'category_id' => 2,
            'stock'       => 20,
            'condition'   => 'good',
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Borrowing;

class BorrowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Borrowing::create([
            'user_id'          => '3',
            'tool_id'          => '1',
            'borrow_date'      => '',
            'due_date'         => '',
            'status'           => '',
            'rejection_reason' => '',
        ]);
    }
}

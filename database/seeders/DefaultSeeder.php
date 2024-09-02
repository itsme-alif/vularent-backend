<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now('Asia/Jakarta');

        DB::table('item_statuses')->insert([
            ['status_name' => 'available', 'created_at' => $now],
            ['status_name' => 'rented', 'created_at' => $now],
            ['status_name' => 'maintenance', 'created_at' => $now],
        ]);

        DB::table('transaction_statuses')->insert([
            ['status_name' => 'pending', 'created_at' => $now],
            ['status_name' => 'completed', 'created_at' => $now],
            ['status_name' => 'failed', 'created_at' => $now],
        ]);

        DB::table('order_statuses')->insert([
            ['status_name' => 'pending', 'created_at' => $now],
            ['status_name' => 'confirm', 'created_at' => $now],
            ['status_name' => 'completed', 'created_at' => $now],
            ['status_name' => 'canceled', 'created_at' => $now],
        ]);

        DB::table('categories')->insert([
            ['category_name' => 'vehicle', 'created_at' => $now],
            ['category_name' => 'property', 'created_at' => $now],
            ['category_name' => 'music', 'created_at' => $now],
            ['category_name' => 'electronic', 'created_at' => $now],
        ]);

    }
}

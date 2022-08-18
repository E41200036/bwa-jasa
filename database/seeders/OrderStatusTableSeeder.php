<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orderStatus = [
            [
                'name' => 'Approve',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Progress',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rejected',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Waiting',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        OrderStatus::insert($orderStatus);
    }
}

<?php

namespace Database\Seeders;

use App\Models\DetailUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DetailUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'users_id'   => 1,
                'photo'      => null,
                'role'       => 'Website Developer',
                'biography'  => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'users_id'   => 2,
                'photo'      => null,
                'role'       => 'UI Designer',
                'biography'  => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DetailUser::insert($users);
    }
}

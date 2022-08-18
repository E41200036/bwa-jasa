<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
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
                'name'           => 'aprilia',
                'email'          => 'aprilia@gmail.com',
                'password'       => password_hash('password' , PASSWORD_DEFAULT),
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'           => 'ifa',
                'email'          => 'ifa@gmail.com',
                'password'       => password_hash('password' , PASSWORD_DEFAULT),
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        User::insert($users);
    }
}

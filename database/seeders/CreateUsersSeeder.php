<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'=>'Admin User',
            'email'=>'admin@gmail.com',
            'is_admin' => 1,
            'password'=> bcrypt('Admin@123'),
        ]);
    }
}

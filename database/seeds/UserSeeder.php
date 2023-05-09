<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
        'username' => "admin123",
        'first_name' => 'Admin',
        'last_name' => 'User',
        'email' => 'admin@yopmail.com',
        'password' => Hash::make('Password1!'),
        'phone' => '1234567890',
        ]);
    }
}

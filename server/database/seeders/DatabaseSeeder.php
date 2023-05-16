<?php

namespace Database\Seeders;

use App\Models\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            // 'name' => "mateus",
            'email' => 'mateus@sync360.io',
            'password' => Hash::make('asdfasdf')
        ]);
    }
}

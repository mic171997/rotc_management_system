<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       User::create([
            'name' => 'Admin',
            'lastname' => 'Admin',
            'company' => 'Admin',
            'cadetno' => 'Admin',
            'username' => 'Admin',
            'password' => Hash::make('12345678'),
            'type' => 'Admin',
            'remember_token' => Str::random(60),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

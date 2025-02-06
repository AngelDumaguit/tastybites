<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Account;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Account::create([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
            'role' => 'Admin',
            'status' => 'active',
        ]);
    }
}

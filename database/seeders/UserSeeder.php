<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // =============================
        // 1. ADMIN
        // =============================
        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'password' => bcrypt('admin123'),
            ]
        );

        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        // =============================
        // 2. CASHIER
        // =============================
        $cashier = User::updateOrCreate(
            ['email' => 'cashier@gmail.com'],
            [
                'first_name' => 'Cashier',
                'last_name' => 'User',
                'password' => bcrypt('cashier123'),
            ]
        );

        if (!$cashier->hasRole('cashier')) {
            $cashier->assignRole('cashier');
        }
    }
}

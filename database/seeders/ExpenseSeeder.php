<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Expense;
use Carbon\Carbon;

class ExpenseSeeder extends Seeder
{
    public function run(): void
    {
        $expenses = [
            [
                'name' => 'Gaji Karyawan',
                'amount' => 5000000,
                'expense_date' => Carbon::now()->startOfMonth(),
                'notes' => 'Gaji bulanan karyawan'
            ],
            [
                'name' => 'Sewa Toko',
                'amount' => 2500000,
                'expense_date' => Carbon::now()->startOfMonth(),
                'notes' => 'Sewa tempat usaha'
            ],
            [
                'name' => 'Listrik & Air',
                'amount' => 750000,
                'expense_date' => Carbon::now()->subDays(10),
                'notes' => 'Pembayaran listrik dan air'
            ],
            [
                'name' => 'Internet',
                'amount' => 450000,
                'expense_date' => Carbon::now()->subDays(8),
                'notes' => 'Internet operasional'
            ],
            [
                'name' => 'Marketing',
                'amount' => 1200000,
                'expense_date' => Carbon::now()->subDays(5),
                'notes' => 'Iklan media sosial'
            ],
            [
                'name' => 'ATK',
                'amount' => 300000,
                'expense_date' => Carbon::now()->subDays(3),
                'notes' => 'Alat tulis kantor'
            ],
        ];

        foreach ($expenses as $expense) {
            Expense::create($expense);
        }
    }
}

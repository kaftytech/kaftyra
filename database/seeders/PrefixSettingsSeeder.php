<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class PrefixSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('prefix_settings')->insert([
            [
                'prefix_for' => 'Invoice',
                'prefix' => 'INV',
                'suffix' => null,
                'start_number' => 1,
                'current_number' => 1,
                'auto_increment' => true,
                'number_digits' => 5,
            ],
            [
                'prefix_for' => 'Purchase',
                'prefix' => 'PUR',
                'suffix' => null,
                'start_number' => 1,
                'current_number' => 1,
                'auto_increment' => true,
                'number_digits' => 5,
            ],
            [
                'prefix_for' => 'CreditNote',
                'prefix' => 'CRN',
                'suffix' => null,
                'start_number' => 1,
                'current_number' => 1,
                'auto_increment' => true,
                'number_digits' => 5,
            ],
            [
                'prefix_for' => 'OrderRequest',
                'prefix' => 'ORD',
                'suffix' => null,
                'start_number' => 1,
                'current_number' => 1,
                'auto_increment' => true,
                'number_digits' => 5,
            ],
            [
                'prefix_for' => 'Employee',
                'prefix' => 'EMP',
                'suffix' => null,
                'start_number' => 1,
                'current_number' => 1,
                'auto_increment' => true,
                'number_digits' => 5,
            ],
            [
                'prefix_for' => 'PurchaseBill',
                'prefix' => 'Bill',
                'suffix' => null,
                'start_number' => 1,
                'current_number' => 1,
                'auto_increment' => true,
                'number_digits' => 5,
            ],
            [
                'prefix_for' => 'VendorCredit',
                'prefix' => 'VC',
                'suffix' => null,
                'start_number' => 1,
                'current_number' => 1,
                'auto_increment' => true,
                'number_digits' => 5,
            ],
            // Add more if needed
        ]);
    }
}

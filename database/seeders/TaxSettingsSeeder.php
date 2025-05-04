<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class TaxSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tax_settings')->insert([
            [
                'name' => 'CGST',
                'rate' => 9.00,
                'type' => 'percentage',
                'is_active' => true,
            ],
            [
                'name' => 'SGST',
                'rate' => 9.00,
                'type' => 'percentage',
                'is_active' => true,
            ],
            [
                'name' => 'IGST',
                'rate' => 18.00,
                'type' => 'percentage',
                'is_active' => true,
            ],
            // Add more if needed
        ]);
    }
}

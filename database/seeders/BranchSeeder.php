<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Branch;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Branch::firstOrCreate([
            'name' => 'Main Branch',
            'address' => '123 Main St',
            'phone' => '123-456-7890',
            'email' => 'mainbranch@example.com',
            'city' => 'City A',
            'state' => 'State A',
            'country' => 'Country A',
            'zip' => '12345',
            'status' => 'active',
        ]);
    }
}

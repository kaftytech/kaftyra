<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = [
            'name'  => 'admin',
            'display_name'  =>  'Admin',
            'description'   =>  'Role for Administrators'
        ];
        Role::create($admin);

        $salesRep = [
            'name'  => 'sales',
            'display_name'  =>  'Sales Representative',
            'description'   =>  'Role for Sales Representative'
        ];
        Role::create($salesRep);

        $storeKeeper = [
            'name'  => 'store_keeper',
            'display_name'  =>  'Store Keeper',
            'description'   =>  'Role for Store Keeper'
        ];
        Role::create($storeKeeper);

        $customer = [
            'name'  => 'customer',
            'display_name'  =>  'Customer',
            'description'   =>  'Role for Customers'
        ];
        Role::create($customer);

        $account = [
            'name'  => 'accountant',
            'display_name'  =>  'Accountant',
            'description'   =>  'Role for Accountant'
        ];
        Role::create($account);

    }
}

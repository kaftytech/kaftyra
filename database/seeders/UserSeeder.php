<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@gmail.com',
                'role' => 'super_admin',
                'branch_id' => 1
            ],
            [
                'name' => 'Admin User',
                'email' => 'admin@gmail.com',
                'role' => 'admin',
                'branch_id' => 1
            ],
            [
                'name' => 'Store Keeper',
                'email' => 'storekeeper@gmail.com',
                'role' => 'store_keeper',
                'branch_id' => 1
            ],
            [
                'name' => 'Sales User',
                'email' => 'sales@gmail.com',
                'role' => 'sales_man',
                'branch_id' => 1
            ],
            [
                'name' => 'Accounts User',
                'email' => 'accounts@gmail.com',
                'role' => 'accountant',
                'branch_id' => 1
            ],
            [
                'name' => 'Customer User',
                'email' => 'customer@gmail.com',
                'role' => 'customer',
                'branch_id' => 1
            ],
        ];

        foreach ($users as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('kafty1234'),
                'email_verified_at' => Carbon::now(),
                'branch_id' => $userData['branch_id']
            ]);

            $role = Role::firstOrCreate(['name' => $userData['role']]);
            
            // Attach role using Laratrust
            $user->roles()->attach($role->id, ['user_type' => \App\Models\User::class]);

        }
    }
}

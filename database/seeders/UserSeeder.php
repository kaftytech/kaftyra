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
        // Create Admin User
        $admin = [
            'name' => "Admin User",
            'email' => "admin@gmail.com",
            'password' => Hash::make("kafty1234"),
            'email_verified_at' => Carbon::now(),
        ];

        $admin = User::create($admin);

        // Find the admin role
        $adminRole = Role::where('name', 'admin')->first();

        // Check if the admin role exists and attach it to the user with 'user_type'
        if ($adminRole) {
            // Attach the admin role to the user with the 'user_type' field
            $admin->roles()->attach($adminRole, ['user_type' => \App\Models\User::class]);
        } else {
            // If the 'admin' role doesn't exist, create it and attach it with 'user_type'
            $adminRole = Role::create(['name' => 'admin']);
            $admin->roles()->attach($adminRole, ['user_type' => \App\Models\User::class]);
        }

        // Example: Create a customer user and assign the 'customer' role
        $customer = [
            'name' => "Customer User",
            'email' => "customer@gmail.com",
            'password' => Hash::make("kafty1234"),
            'email_verified_at' => Carbon::now(),
        ];

        $customer = User::create($customer);

        // Find and assign customer role with 'user_type'
        $customerRole = Role::where('name', 'customer')->first();
        if ($customerRole) {
            $customer->roles()->attach($customerRole, ['user_type' => \App\Models\User::class]);
        } else {
            $customerRole = Role::create(['name' => 'customer']);
            $customer->roles()->attach($customerRole, ['user_type' => \App\Models\User::class]);
        }

        // Example: Create a customer user and assign the 'customer' role
        $salesUser = [
            'name' => "Sales User",
            'email' => "salesUser@gmail.com",
            'password' => Hash::make("kafty1234"),
            'email_verified_at' => Carbon::now(),
        ];

        $salesUser = User::create($salesUser);

        // Find and assign salesUser role with 'user_type'
        $salesUserRole = Role::where('name', 'sales')->first();
        if ($salesUserRole) {
            $salesUser->roles()->attach($salesUserRole, ['user_type' => \App\Models\User::class]);
        } else {
            $salesUserRole = Role::create(['name' => 'sales']);
            $salesUser->roles()->attach($salesUserRole, ['user_type' => \App\Models\User::class]);
        }

        $storeKeeper = [
            'name' => "Sales User",
            'email' => "storeKeeper@gmail.com",
            'password' => Hash::make("kafty1234"),
            'email_verified_at' => Carbon::now(),
        ];

        $storeKeeper = User::create($storeKeeper);

        // Find and assign storeKeeper role with 'user_type'
        $storeKeeperRole = Role::where('name', 'store_keeper')->first();
        if ($storeKeeperRole) {
            $storeKeeper->roles()->attach($storeKeeperRole, ['user_type' => \App\Models\User::class]);
        } else {
            $storeKeeperRole = Role::create(['name' => 'store_keeper']);
            $storeKeeper->roles()->attach($storeKeeperRole, ['user_type' => \App\Models\User::class]);
        }
    }
}

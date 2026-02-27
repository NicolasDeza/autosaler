<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            $usersData = [
                [
                    'first_name' => 'Sebastien',
                    'last_name' => 'Dupont',
                    'email' => 'seb@autosaler.com',
                    'password' => Hash::make('password'),
                    'role' => 'admin',
                ],
                [
                    'first_name' => 'Nicolas',
                    'last_name' => 'Dubois',
                    'email' => 'nico@autosaler.com',
                    'password' => Hash::make('password'),
                    'role' => 'admin',
                ],
                [
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'email' => 'john@johngarage.com',
                    'password' => Hash::make('password'),
                    'role' => 'seller',
                    // 'company' => [
                    //     'name' => 'John\'s Garage',
                    // ],
                ],
                [
                    'first_name' => 'Jane',
                    'last_name' => 'Smith',
                    'email' => 'jane@janegarage.com',
                    'password' => Hash::make('password'),
                    'role' => 'seller',
                    // 'company' => [
                    //     'name' => 'Jane\'s Garage',
                    // ],
                ],
                [
                    'first_name' => 'Alice',
                    'last_name' => 'Johnson',
                    'email' => 'alice@example.com',
                    'password' => Hash::make('password'),
                    'role' => 'user',
                ],
            ];

            foreach ($usersData as $userData) {

                $company = null;


                // if (isset($userData['company'])) {
                // $this->command->info("--- Creating Company: {$userData['company']['name']} ---");
                //     $company = Company::create([
                //         'name' => $userData['company']['name'],
                //     ]);

                //     $this->command->info("Created Company: {$company->name} with email: {$company->email}");
                // } else {
                //     $company = null;
                // }

                $user = User::create([
                    'first_name' => $userData['first_name'],
                    'last_name' => $userData['last_name'],
                    'company_id' => $company ? $company->id : null,
                    'email' => $userData['email'],
                    'password' => Hash::make('password'),
                ]);

                if (isset($userData['role'])) {
                    $user->assignRole($userData['role']);
                    $this->command->info("Created User: {$user->email} with role '{$userData['role']}'");
                } else {
                    $this->command->info("Created User: {$user->email} with no specific role");
                }
            }
        });
    }
}

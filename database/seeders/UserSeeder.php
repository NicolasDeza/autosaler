<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
                    'first_name' => 'Rony',
                    'last_name' => 'Quoidbach',
                    'email' => 'autosalerpro@gmail.com',
                    'password' => Hash::make('password'),
                    'role' => 'admin',
                ],
                [
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'email' => 'john@johngarage.com',
                    'password' => Hash::make('password'),
                    'role' => 'dealer',
                    'company' => [
                        'name' => 'John\'s Garage',
                        'tva_number' => '123456789',
                        'email' => 'contact@johngarage.com',
                        'phone' => '123456789',
                        'address' => '240 Avenue Louise',
                        'country_id' => 1,
                        'city_id' => 73,
                    ],
                ],
                [
                    'first_name' => 'Jane',
                    'last_name' => 'Smith',
                    'email' => 'jane@janegarage.com',
                    'password' => Hash::make('password'),
                    'role' => 'dealer',
                    'company' => [
                        'name' => 'Jane\'s Garage',
                        'tva_number' => '123456789',
                        'email' => 'contact@janegarage.com',
                        'phone' => '123456789',
                        'address' => '123 Chaussee de Bruxelles',
                        'country_id' => 1,
                        'city_id' => 171,
                    ],
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

                // $company = null;

                if (isset($userData['company'])) {
                    $this->command->info("--- Creating Company: {$userData['company']['name']} ---");
                    $company = Company::create([
                        'name' => $userData['company']['name'],
                        'tva_number' => $userData['company']['tva_number'],
                        'email' => $userData['company']['email'],
                        'phone' => $userData['company']['phone'],
                        'address' => $userData['company']['address'],
                        'country_id' => $userData['company']['country_id'],
                        'city_id' => $userData['company']['city_id'],
                    ]);

                    $this->command->info("Created Company: {$company->name} with email: {$company->email}");
                } else {
                    $company = null;
                }

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

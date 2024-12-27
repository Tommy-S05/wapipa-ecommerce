<?php

namespace Database\Seeders;

use App\Enums\RolesEnum;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Tommy Sanchez',
            'email' => 'tommy-s05@hotmail.com'
        ])->assignRole(RolesEnum::Admin->value);

        User::factory()->create([
            'name' => 'Elber Galarga',
            'email' => 'elberg@hotmail.com'
        ])->assignRole(RolesEnum::Vendor->value);

        User::factory()->create([
            'name' => 'User Example',
            'email' => 'user@example.com'
        ])->assignRole(RolesEnum::User->value);
    }
}

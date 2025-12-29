<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'typeRole' => 'admin',
                'statutRole' => true,
            ],
            [
                'typeRole' => 'client',
                'statutRole' => true,
            ],
            [
                'typeRole' => 'assure',
                'statutRole' => true,
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}

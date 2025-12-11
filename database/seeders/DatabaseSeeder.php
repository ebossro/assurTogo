<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            FormuleSeeder::class,
        ]);

        // Création de l'admin
        User::factory()->create([
            'prenom' => 'Emmanuel',
            'name' => 'Admin',
            'email' => 'emmanuel@assurconnect.com',
            'telephone' => '70297284',
            'password' => Hash::make('password'),
            'role_id' => 1,
        ]);
    }
}

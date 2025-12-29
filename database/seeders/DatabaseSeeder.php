<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\Police;
use App\Models\Beneficiaire;
use App\Models\Sinistre;

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
        User::firstOrCreate(
            ['email' => 'emmanuel@assurconnect.com'],
            [
                'prenom' => 'Emmanuel',
                'name' => 'Admin',
                'telephone' => '70297284',
                'password' => Hash::make('password'),
                'role_id' => 1,
            ]
        );
        User::factory(500)->create()->each(function ($user) {
            $polices = Police::factory(rand(1, 2))->create(['user_id' => $user->id]);

            foreach ($polices as $police) {
                $beneficiaires = Beneficiaire::factory(rand(0, 50))->create(['police_id' => $police->id]);

                Sinistre::factory(rand(1, 50))->create([
                    'police_id' => $police->id,
                    'beneficiaire_id' => $beneficiaires->isNotEmpty() && fake()->boolean() ? $beneficiaires->random()->id : null,
                ]);
            }
        });
    }
}

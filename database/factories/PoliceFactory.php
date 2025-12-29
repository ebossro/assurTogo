<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Formule;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Police>
 */
class PoliceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $formule = Formule::inRandomOrder()->first();
        $dateDebut = fake()->dateTimeBetween('-1 year', 'now');
        $dateFin = Carbon::instance($dateDebut)->addYear();

        return [
            'user_id' => User::factory(),
            'numeroPolice' => 'POL-' . strtoupper(Str::random(10)),
            'typePolice' => 'Assurance Santé ' . $formule?->nom,
            'formule_id' => $formule?->id ?? 1,
            'couverture' => 'Gamme ' . ($formule?->nom ?? 'Standard'),
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin,
            'primeMensuelle' => $formule?->prix_mensuel ?? 30000,
            'statut' => fake()->randomElement(['actif', 'en_attente', 'suspendu', 'expire']),

            // Médical
            'antecedents_medicaux' => fake()->optional()->sentence(),
            'medicaments_actuels' => fake()->optional()->sentence(),
            'allergies' => fake()->optional()->word(),
            'habitudes_vie' => fake()->optional()->sentence(),
            'date_rendez_vous' => fake()->optional()->dateTimeBetween('now', '+1 month'),
        ];
    }
}

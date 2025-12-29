<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Police;
use App\Models\Beneficiaire;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sinistre>
 */
class SinistreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'police_id' => Police::factory(),
            'beneficiaire_id' => null,
            'reference' => 'SIN-' . strtoupper(Str::random(10)),
            'type_sinistre' => fake()->randomElement(['maladie', 'accident', 'hospitalisation', 'maternite', 'chirurgie']),
            'lieu_sinistre' => fake()->city(),
            'ville_pays' => fake()->city() . ', Togo',
            'date_sinistre' => fake()->dateTimeBetween('-1 year', 'now'),
            'premiere_consultation' => fake()->boolean(),
            'gravite' => fake()->randomElement(['leger', 'moyen', 'grave']),
            'description' => fake()->paragraph(),
            'diagnostic' => fake()->sentence(),
            'medecin_traitant' => 'Dr. ' . fake()->lastName(),
            'traitement_prescrit' => fake()->sentence(),
            'montant_total' => fake()->numberBetween(5000, 200000),
            'statut' => fake()->randomElement(['en_attente', 'en_analyse', 'approuve', 'rejete']),
            'commentaires' => fake()->optional()->sentence(),
        ];
    }
}

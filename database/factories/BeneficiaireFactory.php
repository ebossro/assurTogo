<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Police;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Beneficiaire>
 */
class BeneficiaireFactory extends Factory
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
            'nomBeneficiaire' => fake()->lastName(),
            'prenomBeneficiaire' => fake()->firstName(),
            'relationBeneficiaire' => fake()->randomElement(['Conjoint', 'Enfant', 'Parent', 'Autre']),
            'dateNaissanceBeneficiaire' => fake()->dateTimeBetween('-60 years', '-1 year'),
            'genreBeneficiaire' => fake()->randomElement(['masculin', 'feminin']),
            'statutBeneficiaire' => 'actif',
            'telephoneBeneficiaire' => fake()->optional()->numerify('## ## ## ##'),
        ];
    }
}

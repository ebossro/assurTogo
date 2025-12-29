<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->lastName(),
            'prenom' => fake()->firstName(),
            'email' => fake()->unique()->safeEmail(),
            'telephone' => fake()->numerify('## ## ## ##'),
            'role_id' => fake()->numberBetween(2, 3), // Client or Assure
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),

            // Infos perso
            'date_naissance' => fake()->dateTimeBetween('-60 years', '-18 years'),
            'sexe' => fake()->randomElement(['M', 'F']),
            'photo_profil' => null,
            'type_piece' => fake()->randomElement(['CNI', 'Passeport', 'Permis']),
            'numero_piece' => fake()->bothify('###-####-####'),
            'date_expiration_piece' => fake()->dateTimeBetween('now', '+5 years'),

            // Adresse
            'adresse' => fake()->streetAddress(),
            'ville' => fake()->city(),
            'quartier' => fake()->streetName(),

            // Situation familiale
            'statut_matrimonial' => fake()->randomElement(['celibataire', 'marie', 'divorce', 'veuf']),
            'nombre_enfants' => fake()->numberBetween(0, 5),

            // Pro
            'profession' => fake()->jobTitle(),
            'employeur' => fake()->company(),
            'revenu_mensuel' => fake()->numberBetween(50000, 1000000),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}

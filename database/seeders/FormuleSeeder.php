<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Formule;

class FormuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $formules = [
            [
                'nom' => 'Basique',
                'prix_mensuel' => 15000,
                'description' => 'Couverture essentielle pour les besoins quotidiens.',
            ],
            [
                'nom' => 'Standard',
                'prix_mensuel' => 30000,
                'description' => 'Une protection équilibrée pour vous et votre famille.',
            ],
            [
                'nom' => 'Confort',
                'prix_mensuel' => 45000,
                'description' => 'Tranquillité d\'esprit avec une couverture étendue.',
            ],
            [
                'nom' => 'Premium',
                'prix_mensuel' => 60000,
                'description' => 'La couverture complète pour une sécurité maximale.',
            ],
        ];

        foreach ($formules as $formule) {
            Formule::create($formule);
        }
    }
}

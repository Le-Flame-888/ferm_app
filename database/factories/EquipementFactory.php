<?php

namespace Database\Factories;

use App\Models\Equipement;
use App\Models\Fournisseur;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Equipement>
 */
class EquipementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'categorie' => $this->faker->randomElement(['Moteur', 'Pompe', 'Ventilateur', 'Compresseur']),
            'type_emplacement' => $this->faker->randomElement(['Extracteur', 'Tapis', 'Convoyeur', 'Ventilateur']),
            'site' => $this->faker->randomElement(['Ain Aouda PS', 'Zhiliga', 'Site Principal', 'Site Secondaire']),
            'emplacement' => $this->faker->randomElement(['Bâtiment A', 'Magasin', 'Atelier', 'Zone de production']),
            'lot' => $this->faker->randomElement(['C C C', 'Z1', 'Z2', 'Z3']),
            'code_ref' => $this->faker->unique()->bothify('EQ-####'),
            'photo' => $this->faker->optional()->imageUrl(),
            'description' => $this->faker->optional()->sentence(),
            'type_huile' => $this->faker->optional()->randomElement(['ISO 32', 'ISO 46', 'ISO 68', 'ISO 100']),
            'duree_controle' => $this->faker->optional()->numberBetween(30, 365),
            'ref_courroie' => $this->faker->optional()->bothify('CR-###'),
            'ref_roulement' => $this->faker->optional()->bothify('R-##-###'),
            'numero_serie' => $this->faker->optional()->bothify('SERIE-####'),
            'marque' => $this->faker->optional()->company,
            'date_achat' => $this->faker->optional(0.7, function() {
                return null;
            })->passthrough(
                $this->faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d')
            ),
            'fournisseur_id' => $this->faker->optional(0.7, null)->randomDigitNotNull,
            'date_mise_service' => $this->faker->optional(0.7, function() {
                return null;
            })->passthrough(
                $this->faker->dateTimeBetween('-4 years', 'now')->format('Y-m-d')
            ),
            'prix_achat' => $this->faker->optional(0.7, null)->randomFloat(2, 100, 10000),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Fournisseur;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Fournisseur>
 */
class FournisseurFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom_societe' => $this->faker->company,
            'contact' => $this->faker->name,
            'telephone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->companyEmail,
            'adresse' => $this->faker->address,
            'delai_livraison' => $this->faker->numberBetween(1, 30),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

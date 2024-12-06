<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Product Factory
 * 
 * This factory helps create test data for pharmaceutical products.
 * It uses Faker to generate realistic-looking data for testing and development.
 */
class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Common pharmaceutical ingredients for realistic data
        $ingredients = [
            'Acetaminophen', 'Ibuprofen', 'Aspirin', 'Caffeine',
            'Diphenhydramine', 'Dextromethorphan', 'Pseudoephedrine',
            'Loratadine', 'Cetirizine', 'Amoxicillin'
        ];

        // Common dosage units
        $units = ['mg', 'g', 'mcg', 'mL'];

        // Generate 1-3 random ingredients with dosages
        $activeIngredients = collect(range(1, fake()->numberBetween(1, 3)))
            ->map(function () use ($ingredients, $units) {
                $ingredient = fake()->randomElement($ingredients);
                $amount = fake()->numberBetween(10, 1000);
                $unit = fake()->randomElement($units);
                return "{$ingredient} {$amount}{$unit}";
            })
            ->join(', ');

        // Manufacturing date between 6 months ago and today
        $manufacturingDate = fake()->dateTimeBetween('-6 months', 'now');
        
        // Expiration date between 1 and 5 years after manufacturing
        $expirationDate = fake()->dateTimeBetween(
            $manufacturingDate->modify('+1 year'),
            $manufacturingDate->modify('+5 years')
        );

        return [
            'name' => fake()->unique()->words(fake()->numberBetween(2, 4), true),
            'category' => fake()->randomElement(['tablet', 'capsule', 'injection']),
            'active_ingredients' => $activeIngredients,
            'batch_number' => strtoupper(fake()->unique()->bothify('BAT##??###')),
            'research_status' => fake()->randomElement([
                'under development',
                'in clinical trials',
                'completed'
            ]),
            'manufacturing_date' => $manufacturingDate,
            'expiration_date' => $expirationDate,
        ];
    }

    /**
     * Indicate that the product is under development.
     */
    public function underDevelopment(): static
    {
        return $this->state(fn (array $attributes) => [
            'research_status' => 'under development',
        ]);
    }

    /**
     * Indicate that the product is in clinical trials.
     */
    public function inClinicalTrials(): static
    {
        return $this->state(fn (array $attributes) => [
            'research_status' => 'in clinical trials',
        ]);
    }

    /**
     * Indicate that the product is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'research_status' => 'completed',
        ]);
    }

    /**
     * Create a tablet product.
     */
    public function tablet(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'tablet',
        ]);
    }

    /**
     * Create a capsule product.
     */
    public function capsule(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'capsule',
        ]);
    }

    /**
     * Create an injection product.
     */
    public function injection(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'injection',
        ]);
    }

    /**
     * Create a product that expires soon (within 3 months).
     */
    public function expiringSoon(): static
    {
        return $this->state(function (array $attributes) {
            $manufacturingDate = fake()->dateTimeBetween('-2 years', '-1 year');
            $expirationDate = fake()->dateTimeBetween('now', '+3 months');
            
            return [
                'manufacturing_date' => $manufacturingDate,
                'expiration_date' => $expirationDate,
            ];
        });
    }

    /**
     * Create a product with long shelf life (5+ years).
     */
    public function longShelfLife(): static
    {
        return $this->state(function (array $attributes) {
            $manufacturingDate = fake()->dateTimeBetween('-1 month', 'now');
            $expirationDate = fake()->dateTimeBetween('+5 years', '+10 years');
            
            return [
                'manufacturing_date' => $manufacturingDate,
                'expiration_date' => $expirationDate,
            ];
        });
    }
}

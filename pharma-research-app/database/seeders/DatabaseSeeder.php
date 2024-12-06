<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with sample data.
     * 
     * This seeder creates a variety of pharmaceutical products in different
     * stages of research and development, with realistic data for testing
     * and development purposes.
     */
    public function run(): void
    {
        // Create products under development
        Product::factory()
            ->count(5)
            ->underDevelopment()
            ->create();

        // Create products in clinical trials
        Product::factory()
            ->count(3)
            ->inClinicalTrials()
            ->create();

        // Create completed products
        Product::factory()
            ->count(2)
            ->completed()
            ->create();

        // Create some products with specific categories
        Product::factory()
            ->count(3)
            ->tablet()
            ->create();

        Product::factory()
            ->count(2)
            ->capsule()
            ->create();

        Product::factory()
            ->count(2)
            ->injection()
            ->create();

        // Create some products with specific characteristics
        Product::factory()
            ->count(2)
            ->expiringSoon()
            ->create();

        Product::factory()
            ->count(2)
            ->longShelfLife()
            ->create();

        // Create a few specific products for testing
        Product::factory()->create([
            'name' => 'Paracetamol Plus',
            'category' => 'tablet',
            'active_ingredients' => 'Paracetamol 500mg, Caffeine 65mg',
            'batch_number' => 'BAT2024001',
            'research_status' => 'completed',
            'manufacturing_date' => '2024-01-01',
            'expiration_date' => '2026-01-01',
        ]);

        Product::factory()->create([
            'name' => 'Antibio-X',
            'category' => 'capsule',
            'active_ingredients' => 'Amoxicillin 500mg',
            'batch_number' => 'BAT2024002',
            'research_status' => 'in clinical trials',
            'manufacturing_date' => '2024-01-15',
            'expiration_date' => '2025-01-15',
        ]);

        Product::factory()->create([
            'name' => 'Pain Relief Injectable',
            'category' => 'injection',
            'active_ingredients' => 'Morphine 10mg/mL',
            'batch_number' => 'BAT2024003',
            'research_status' => 'under development',
            'manufacturing_date' => '2024-02-01',
            'expiration_date' => '2025-02-01',
        ]);

        // Log seeding completion
        \Log::info('Database seeded successfully with sample pharmaceutical products.');
    }
}

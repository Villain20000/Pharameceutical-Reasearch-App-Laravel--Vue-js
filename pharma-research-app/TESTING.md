# Testing Guide

This guide explains how to test the Pharmaceutical Research Product Management application.

## Testing Overview

The application uses multiple testing approaches:

1. PHP Unit Tests for Laravel backend
2. Feature Tests for API endpoints
3. Browser Tests for frontend (optional)

## Setting Up Testing Environment

### 1. Database Setup

```bash
# Create testing database
mysql -u root -p
CREATE DATABASE pharma_research_testing;

# Configure .env.testing
DB_CONNECTION=mysql
DB_DATABASE=pharma_research_testing
```

### 2. PHPUnit Configuration

The `phpunit.xml` file is already configured for testing:

```xml
<php>
    <env name="APP_ENV" value="testing"/>
    <env name="DB_DATABASE" value="pharma_research_testing"/>
</php>
```

## Writing Tests

### 1. Backend Tests

#### Model Tests (`tests/Unit/ProductTest.php`)

```php
use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_product()
    {
        $product = Product::factory()->create([
            'name' => 'Test Medicine',
            'category' => 'tablet'
        ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Test Medicine',
            'category' => 'tablet'
        ]);
    }

    public function test_product_requires_name()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        Product::create([
            'category' => 'tablet',
            // missing name
        ]);
    }
}
```

#### API Tests (`tests/Feature/ProductApiTest.php`)

```php
use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_products()
    {
        // Arrange
        Product::factory()->count(3)->create();

        // Act
        $response = $this->getJson('/api/products');

        // Assert
        $response->assertStatus(200)
                ->assertJsonCount(3);
    }

    public function test_can_create_product()
    {
        $productData = [
            'name' => 'New Medicine',
            'category' => 'tablet',
            'active_ingredients' => 'Test Ingredient',
            'batch_number' => 'BATCH001',
            'research_status' => 'under development',
            'manufacturing_date' => '2024-01-09',
            'expiration_date' => '2025-01-09'
        ];

        $response = $this->postJson('/api/products', $productData);

        $response->assertStatus(201)
                ->assertJson(['name' => 'New Medicine']);
    }

    public function test_cannot_create_product_with_invalid_data()
    {
        $response = $this->postJson('/api/products', [
            // Missing required fields
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['name', 'category']);
    }

    public function test_can_update_product()
    {
        $product = Product::factory()->create();

        $response = $this->putJson("/api/products/{$product->id}", [
            'name' => 'Updated Name',
            'category' => 'tablet',
            'active_ingredients' => 'Test Ingredient',
            'batch_number' => 'BATCH001',
            'research_status' => 'under development',
            'manufacturing_date' => '2024-01-09',
            'expiration_date' => '2025-01-09'
        ]);

        $response->assertStatus(200)
                ->assertJson(['name' => 'Updated Name']);
    }

    public function test_can_delete_product()
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
```

### 2. Frontend Tests (Optional)

If you want to add frontend tests, you can use Vue Test Utils:

```javascript
// tests/js/ProductForm.test.js
import { mount } from "@vue/test-utils";
import ProductForm from "@/components/ProductForm.vue";

describe("ProductForm", () => {
    test("validates required fields", async () => {
        const wrapper = mount(ProductForm);

        await wrapper.find("form").trigger("submit");

        expect(wrapper.text()).toContain("Name is required");
    });

    test("submits form with valid data", async () => {
        const wrapper = mount(ProductForm);

        await wrapper.find('input[name="name"]').setValue("Test Product");
        await wrapper.find('select[name="category"]').setValue("tablet");
        // ... set other required fields

        await wrapper.find("form").trigger("submit");

        // Assert form submission
    });
});
```

## Running Tests

### Running All Tests

```bash
# Run all PHP tests
php artisan test

# Run all JavaScript tests (if configured)
npm run test
```

### Running Specific Tests

```bash
# Run specific test file
php artisan test --filter ProductApiTest

# Run specific test method
php artisan test --filter test_can_create_product
```

### Test Coverage

```bash
# Generate test coverage report
php artisan test --coverage

# Generate detailed HTML coverage report
php artisan test --coverage-html coverage
```

## Test Data Factory

### Product Factory

```php
// database/factories/ProductFactory.php
namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'name' => $this->faker->words(3, true),
            'category' => $this->faker->randomElement(['tablet', 'capsule', 'injection']),
            'active_ingredients' => $this->faker->sentence,
            'batch_number' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{4}'),
            'research_status' => $this->faker->randomElement([
                'under development',
                'in clinical trials',
                'completed'
            ]),
            'manufacturing_date' => $this->faker->date(),
            'expiration_date' => $this->faker->dateTimeBetween('+1 year', '+5 years'),
        ];
    }

    /**
     * Indicate that the product is completed.
     */
    public function completed()
    {
        return $this->state(function (array $attributes) {
            return [
                'research_status' => 'completed',
            ];
        });
    }
}
```

## Testing Best Practices

### 1. Test Organization

-   Group related tests together
-   Use descriptive test names
-   Follow the Arrange-Act-Assert pattern

```php
public function test_can_create_product()
{
    // Arrange
    $productData = [...];

    // Act
    $response = $this->postJson('/api/products', $productData);

    // Assert
    $response->assertStatus(201);
}
```

### 2. Test Data

-   Use factories for test data
-   Don't rely on database state
-   Clean up after tests

### 3. Testing Validation

```php
public function test_validates_required_fields()
{
    $response = $this->postJson('/api/products', []);

    $response->assertStatus(422)
             ->assertJsonValidationErrors([
                 'name',
                 'category',
                 'batch_number'
             ]);
}
```

### 4. Testing Edge Cases

```php
public function test_handles_duplicate_batch_number()
{
    // Create first product
    $product = Product::factory()->create();

    // Try to create another product with same batch number
    $response = $this->postJson('/api/products', [
        'batch_number' => $product->batch_number,
        // ... other fields
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['batch_number']);
}
```

## Continuous Integration

### GitHub Actions Example

```yaml
name: Tests

on: [push, pull_request]

jobs:
    tests:
        runs-on: ubuntu-latest

        services:
            mysql:
                image: mysql:8.0
                env:
                    MYSQL_DATABASE: pharma_research_testing
                    MYSQL_ROOT_PASSWORD: password
                ports:
                    - 3306:3306

        steps:
            - uses: actions/checkout@v2

            - name: Setup PHP
              uses: shivammathur/setup-php@v2
              with:
                  php-version: "8.2"

            - name: Install Dependencies
              run: composer install -q --no-ansi --no-interaction --no-scripts --no-progress --prefer-dist

            - name: Generate key
              run: php artisan key:generate

            - name: Run Tests
              env:
                  DB_CONNECTION: mysql
                  DB_HOST: 127.0.0.1
                  DB_PORT: 3306
                  DB_DATABASE: pharma_research_testing
                  DB_USERNAME: root
                  DB_PASSWORD: password
              run: php artisan test
```

## Debugging Tests

### 1. Viewing SQL Queries

```php
\DB::listen(function($query) {
    var_dump($query->sql, $query->bindings);
});
```

### 2. Debugging Response

```php
$response = $this->getJson('/api/products');
dd($response->json());
```

### 3. Testing Specific Scenarios

```php
public function test_handles_server_error()
{
    // Mock the Product model to simulate an error
    $this->mock(Product::class)
         ->shouldReceive('create')
         ->andThrow(new \Exception('Server error'));

    $response = $this->postJson('/api/products', [
        // ... product data
    ]);

    $response->assertStatus(500)
             ->assertJson(['error' => 'Failed to create product']);
}
```

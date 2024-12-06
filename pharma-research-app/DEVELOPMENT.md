# Development Guide

This guide provides detailed information for developers working on the Pharmaceutical Research Product Management application.

## Architecture Overview

The application follows a modern full-stack architecture:

```
Frontend (Vue.js)                    Backend (Laravel)
┌─────────────────┐                 ┌─────────────────┐
│  Vue Components │ ◄──── API ────► │  Controllers    │
│  State Management│                │  Models         │
│  Router         │                 │  Validation     │
└─────────────────┘                 └─────────────────┘
```

## Development Environment Setup

1. **Prerequisites**

    - PHP 8.2+
    - Composer
    - Node.js 16+
    - MySQL 8.0+

2. **First-time setup**

    ```bash
    # Clone repository
    git clone <repository-url>
    cd pharma-research-app

    # Install PHP dependencies
    composer install

    # Install Node.js dependencies
    npm install

    # Set up environment
    cp .env.example .env
    php artisan key:generate

    # Configure database in .env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=pharma_research
    DB_USERNAME=root
    DB_PASSWORD=your_password

    # Run migrations
    php artisan migrate

    # Start development servers
    php artisan serve
    npm run dev
    ```

## Code Organization

### Laravel Backend

1. **Models (`app/Models/`)**

    - `Product.php`: Defines product attributes and validation rules

    ```php
    // Example: Adding a new attribute
    protected $fillable = [
        'name',
        'new_attribute', // Add new attribute here
    ];
    ```

2. **Controllers (`app/Http/Controllers/Api/`)**

    - `ProductController.php`: Handles API endpoints

    ```php
    // Example: Adding a new endpoint
    public function search(Request $request)
    {
        $query = $request->get('q');
        return Product::where('name', 'like', "%{$query}%")->get();
    }
    ```

3. **Routes (`routes/api.php`)**
    ```php
    // Example: Adding a new route
    Route::get('products/search', [ProductController::class, 'search']);
    ```

### Vue.js Frontend

1. **Components (`resources/js/components/`)**

    - `ProductList.vue`: Main product listing
    - `ProductForm.vue`: Create/edit form

    ```vue
    <!-- Example: Adding a new component -->
    <script setup>
    const props = defineProps({
        title: String,
    });
    </script>
    ```

2. **Router (`resources/js/app.js`)**
    ```javascript
    // Example: Adding a new route
    {
        path: '/products/search',
        name: 'product-search',
        component: ProductSearch
    }
    ```

## Common Development Tasks

### Adding a New Product Field

1. **Create Migration**

    ```bash
    php artisan make:migration add_field_to_products_table
    ```

    ```php
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('new_field');
        });
    }
    ```

2. **Update Model**

    ```php
    protected $fillable = [
        'new_field'
    ];

    protected $casts = [
        'new_field' => 'string'
    ];
    ```

3. **Update Form Component**
    ```vue
    <template>
        <div class="form-group">
            <label>New Field</label>
            <input v-model="form.new_field" type="text" />
        </div>
    </template>
    ```

### Adding Validation

1. **Backend Validation (Model)**

    ```php
    public static function rules()
    {
        return [
            'new_field' => 'required|string|max:255'
        ];
    }
    ```

2. **Frontend Validation (Component)**
    ```javascript
    const validateForm = () => {
        if (!form.value.new_field?.trim()) {
            errors.value.new_field = ["New field is required"];
        }
    };
    ```

## Testing

### PHP Tests

1. **Create Test**

    ```bash
    php artisan make:test ProductTest
    ```

2. **Write Test**

    ```php
    public function test_can_create_product()
    {
        $response = $this->postJson('/api/products', [
            'name' => 'Test Product',
            // ... other fields
        ]);

        $response->assertStatus(201);
    }
    ```

3. **Run Tests**
    ```bash
    php artisan test
    ```

### Vue Tests (if configured)

1. **Component Test**

    ```javascript
    import { mount } from "@vue/test-utils";
    import ProductForm from "./ProductForm.vue";

    test("creates product", async () => {
        const wrapper = mount(ProductForm);
        // ... test implementation
    });
    ```

## API Documentation

### Products Endpoints

```
GET    /api/products      - List all products
POST   /api/products      - Create new product
GET    /api/products/{id} - Get single product
PUT    /api/products/{id} - Update product
DELETE /api/products/{id} - Delete product
```

### Request/Response Examples

1. **Create Product**

    ```http
    POST /api/products
    Content-Type: application/json

    {
      "name": "Test Medicine",
      "category": "tablet",
      "active_ingredients": "Paracetamol 500mg",
      "batch_number": "BATCH001",
      "research_status": "under development",
      "manufacturing_date": "2024-01-09",
      "expiration_date": "2025-01-09"
    }
    ```

2. **Response**
    ```json
    {
      "id": 1,
      "name": "Test Medicine",
      "category": "tablet",
      ...
    }
    ```

## Best Practices

### Laravel Best Practices

1. **Use Resource Controllers**

    - Keep controllers focused and organized
    - Use resource methods (index, show, store, etc.)

2. **Model Best Practices**

    - Define relationships clearly
    - Use proper attribute casting
    - Implement validation rules

3. **API Best Practices**
    - Use proper HTTP status codes
    - Implement consistent error responses
    - Use API resources for response formatting

### Vue Best Practices

1. **Component Organization**

    - Keep components small and focused
    - Use composition API for better organization
    - Implement proper prop validation

2. **State Management**

    - Use refs for reactive data
    - Implement computed properties for derived data
    - Handle loading and error states

3. **Form Handling**
    - Implement proper validation
    - Show clear error messages
    - Handle API errors gracefully

## Troubleshooting

### Common Issues

1. **API 404 Errors**

    - Check route definitions
    - Verify API prefix
    - Check file naming and case sensitivity

2. **Database Issues**

    - Verify connection settings
    - Run migrations
    - Check for missing fields

3. **Frontend Issues**
    - Clear browser cache
    - Check console for errors
    - Verify API endpoint URLs

### Development Tools

1. **Laravel Tools**

    ```bash
    # Clear cache
    php artisan cache:clear
    php artisan config:clear
    php artisan route:clear

    # List routes
    php artisan route:list

    # Database refresh
    php artisan migrate:fresh
    ```

2. **Vue Development**
    - Vue DevTools for debugging
    - Network tab for API requests
    - Console for error messages

## Contributing

1. Create feature branch
2. Write tests
3. Follow coding standards
4. Submit pull request

## Security

1. **Input Validation**

    - Always validate user input
    - Use Laravel's validation system
    - Implement CSRF protection

2. **API Security**
    - Use proper authentication
    - Implement rate limiting
    - Validate request data

## Performance

1. **Backend Optimization**

    - Use eager loading for relationships
    - Implement caching where appropriate
    - Optimize database queries

2. **Frontend Optimization**
    - Implement lazy loading
    - Use proper key attributes
    - Optimize component updates

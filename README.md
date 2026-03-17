# Pharmaceutical Research Product Management

A full-stack Laravel + Vue.js application for managing pharmaceutical research products.

## Project Structure Overview

### Backend (Laravel)

```
app/
├── Http/
│   └── Controllers/
│       └── Api/
│           └── ProductController.php    # Handles API endpoints for products
├── Models/
│   └── Product.php                      # Product model with validation rules
└── Providers/
    └── RouteServiceProvider.php         # Configures API and web routes
```

### Frontend (Vue.js)

```
resources/
└── js/
    ├── app.js                           # Vue application entry point
    ├── bootstrap.js                     # Axios configuration
    ├── App.vue                          # Root Vue component
    └── components/
        ├── ProductList.vue              # Lists all products
        └── ProductForm.vue              # Form for creating/editing products
```

## Key Features & Implementation Details

### 1. Laravel Backend

#### Product Model (`app/Models/Product.php`)

```php
// The Product model uses Laravel's built-in features:
protected $fillable = [
    'name',
    'category',
    'active_ingredients',
    // ... more fields
];

// Date casting for proper handling
protected $casts = [
    'manufacturing_date' => 'date',
    'expiration_date' => 'date'
];
```

#### API Controller (`app/Http/Controllers/Api/ProductController.php`)

```php
// RESTful API endpoints with validation
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'category' => 'required|string|in:tablet,capsule,injection',
        // ... more validation rules
    ]);
}
```

### 2. Vue.js Frontend

#### Component Communication

-   Uses Vue Router for navigation
-   Props for passing data
-   Events for child-to-parent communication

#### Form Handling (`ProductForm.vue`)

```javascript
// Example of form submission with axios
const handleSubmit = async () => {
    try {
        const response = await axios.post("/api/products", form.value);
        // Handle success
    } catch (error) {
        // Handle validation errors
    }
};
```

## Getting Started

1. Clone the repository
2. Install dependencies:

```bash
composer install
npm install
```

3. Set up your environment:

```bash
cp .env.example .env
php artisan key:generate
```

4. Configure your database in `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pharma_research
DB_USERNAME=root
DB_PASSWORD=your_password
```

5. Run migrations:

```bash
php artisan migrate
```

6. Start development servers:

```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite development server
npm run dev
```

## Development Tips

### Laravel Tips

1. **Route Model Binding**

```php
// Using implicit route model binding in routes/api.php
Route::apiResource('products', ProductController::class);
```

2. **Validation**

```php
// Custom validation rules in Product model
public static function rules($id = null)
{
    return [
        'batch_number' => 'required|string|unique:products,batch_number,' . $id,
        // ... more rules
    ];
}
```

### Vue.js Tips

1. **Composition API Usage**

```javascript
// Using refs for reactive data
const form = ref({
    name: "",
    category: "",
    // ... more fields
});

// Using computed properties
const isEditing = computed(() => route.params.id !== undefined);
```

2. **API Integration**

```javascript
// Axios configuration in bootstrap.js
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
window.axios.defaults.baseURL = "/";
```

## Common Tasks

### Adding a New Field

1. Add migration:

```bash
php artisan make:migration add_field_to_products_table
```

2. Update model:

```php
protected $fillable = ['new_field'];
```

3. Update controller validation:

```php
'new_field' => 'required|string'
```

4. Update Vue components to include the new field

### Adding New Features

1. Create new controller methods if needed
2. Add routes in `routes/api.php`
3. Create or update Vue components
4. Update tests

## Testing

```bash
# Run PHP tests
php artisan test

# Run JavaScript tests (if configured)
npm run test
```

## Best Practices

1. **Laravel**

    - Use resource controllers for consistent API endpoints
    - Implement form requests for complex validation
    - Use model observers for side effects
    - Leverage Laravel's built-in security features

2. **Vue.js**
    - Keep components small and focused
    - Use composition API for better code organization
    - Implement proper error handling
    - Use TypeScript for better type safety

## Troubleshooting

Common issues and solutions:

1. **API 404 errors**

    - Check route definitions
    - Verify API prefix in RouteServiceProvider
    - Check axios base URL configuration

2. **Validation errors**

    - Check Laravel validation rules
    - Verify form data structure
    - Check browser console for detailed errors

3. **Database issues**
    - Verify database credentials
    - Run migrations
    - Check model relationships

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## License

MIT License

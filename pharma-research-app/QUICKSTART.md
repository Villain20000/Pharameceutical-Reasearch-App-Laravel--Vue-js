# Quick Start Guide

Welcome to the Pharmaceutical Research Product Management application! This guide will help you get started quickly.

## 🚀 Getting Started in 5 Minutes

### Prerequisites

-   PHP 8.2 or higher
-   MySQL
-   Node.js
-   Composer
-   Git

### Step 1: Clone and Install

```bash
# Clone the repository
git clone <repository-url>
cd pharma-research-app

# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### Step 2: Set Up Environment

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Edit .env file with your database details
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pharma_research
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Step 3: Set Up Database

```bash
# Run migrations
php artisan migrate
```

### Step 4: Start Development Servers

```bash
# Terminal 1: Start Laravel server
php artisan serve

# Terminal 2: Start Vite development server
npm run dev
```

Visit http://localhost:8000 in your browser!

## 🎯 Basic Usage

### Creating a Product

1. Click "Add New Product" button
2. Fill in the required fields:
    - Name (e.g., "Aspirin Plus")
    - Category (select from dropdown)
    - Active Ingredients (e.g., "Acetylsalicylic acid 500mg")
    - Batch Number (unique identifier)
    - Research Status
    - Manufacturing Date
    - Expiration Date
3. Click "Create Product"

### Editing a Product

1. Find the product in the list
2. Click "Edit" button
3. Modify fields as needed
4. Click "Update Product"

### Deleting a Product

1. Find the product in the list
2. Click "Delete" button
3. Confirm deletion

## 🔍 Understanding the Code

### Frontend (Vue.js)

Key files you'll work with:

```
resources/js/
├── components/
│   ├── ProductList.vue    # Main product listing
│   └── ProductForm.vue    # Create/edit form
├── App.vue               # Root component
└── app.js               # Application entry point
```

### Backend (Laravel)

Key files you'll work with:

```
app/
├── Http/Controllers/Api/
│   └── ProductController.php    # API endpoints
├── Models/
│   └── Product.php             # Product model
└── routes/
    └── api.php                 # API routes
```

## 💡 Common Tasks

### Adding a New Field

1. Create migration:

```bash
php artisan make:migration add_field_to_products_table
```

2. Edit migration file:

```php
public function up()
{
    Schema::table('products', function (Blueprint $table) {
        $table->string('new_field');
    });
}
```

3. Update Product model (`app/Models/Product.php`):

```php
protected $fillable = [
    'name',
    'new_field',  // Add this line
];
```

4. Update form component (`resources/js/components/ProductForm.vue`):

```vue
<template>
    <div class="form-group">
        <label>New Field</label>
        <input v-model="form.new_field" type="text" />
    </div>
</template>
```

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter ProductTest
```

## 🐛 Troubleshooting

### Common Issues

1. **Page Not Found (404)**

    - Make sure both Laravel and Vite servers are running
    - Check if you're using the correct URL

2. **Database Connection Error**

    - Verify database credentials in `.env`
    - Make sure MySQL is running
    - Run `php artisan config:clear`

3. **API Errors**
    - Check browser console for error messages
    - Verify API endpoints in Network tab
    - Make sure CSRF token is properly set

### Quick Fixes

```bash
# Clear all Laravel caches
php artisan optimize:clear

# Restart servers
# Terminal 1:
php artisan serve

# Terminal 2:
npm run dev
```

## 📚 Learning Resources

### Laravel Resources

-   [Laravel Documentation](https://laravel.com/docs)
-   [Laracasts](https://laracasts.com)
-   [Laravel News](https://laravel-news.com)

### Vue.js Resources

-   [Vue.js Documentation](https://vuejs.org/guide/introduction.html)
-   [Vue School](https://vueschool.io)
-   [Vue Mastery](https://www.vuemastery.com)

## 🤝 Getting Help

1. Check the error message in:

    - Browser console
    - Laravel logs (`storage/logs/laravel.log`)
    - PHP error logs

2. Common commands for debugging:

```bash
# View Laravel logs
tail -f storage/logs/laravel.log

# List all routes
php artisan route:list

# Check Laravel version
php artisan --version
```

## 🎨 Customization Tips

### Styling

-   The application uses Tailwind CSS
-   Edit `resources/css/app.css` for custom styles
-   Modify component classes for specific styling

### Adding Features

1. Plan your feature
2. Create necessary database migrations
3. Update the model
4. Add controller methods
5. Create or update Vue components
6. Test thoroughly

## 🔒 Security Best Practices

1. Always validate user input
2. Keep dependencies updated
3. Use HTTPS in production
4. Never commit sensitive data

## 📱 Mobile Testing

The application is responsive by default:

1. Use browser dev tools for mobile testing
2. Test on real devices when possible
3. Check different screen sizes

## 🚀 Next Steps

1. Explore the codebase
2. Try adding a new feature
3. Write some tests
4. Contribute improvements

Remember: The best way to learn is by doing. Don't be afraid to experiment and make changes!

# Code Examples and Patterns

This document provides practical code examples and explanations of patterns used in the application.

## Laravel Examples

### 1. Model Definition with Validation

```php
// app/Models/Product.php

class Product extends Model
{
    /**
     * Example of mass assignment protection
     * Only these fields can be filled using create() or update()
     */
    protected $fillable = [
        'name',
        'category',
        'active_ingredients'
    ];

    /**
     * Example of attribute casting
     * Automatically converts database values to PHP types
     */
    protected $casts = [
        'manufacturing_date' => 'date',
        'expiration_date' => 'date'
    ];

    /**
     * Example of custom validation rules
     * Reusable validation logic
     */
    public static function rules($id = null)
    {
        return [
            'name' => 'required|string|max:255',
            'batch_number' => "required|unique:products,batch_number,{$id}",
        ];
    }
}
```

### 2. Controller with CRUD Operations

```php
// app/Http/Controllers/Api/ProductController.php

class ProductController extends Controller
{
    /**
     * Example of listing resources with error handling
     */
    public function index()
    {
        try {
            $products = Product::latest()->get();
            return response()->json($products);
        } catch (\Exception $e) {
            Log::error('Failed to fetch products: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch products'], 500);
        }
    }

    /**
     * Example of creating a resource with validation
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate(Product::rules());
            $product = Product::create($validated);
            return response()->json($product, 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }
}
```

### 3. API Routes Definition

```php
// routes/api.php

Route::prefix('api')->group(function () {
    // Example of resource routes
    Route::apiResource('products', ProductController::class);

    // Example of custom route
    Route::get('products/search/{query}', [ProductController::class, 'search']);
});
```

## Vue.js Examples

### 1. Component with Form Handling

```vue
<!-- resources/js/components/ProductForm.vue -->

<template>
    <!-- Example of form with validation -->
    <form @submit.prevent="handleSubmit">
        <!-- Example of input with v-model -->
        <input v-model="form.name" :class="{ 'border-red-500': errors.name }" />

        <!-- Example of error display -->
        <p v-if="errors.name" class="text-red-500">
            {{ errors.name[0] }}
        </p>
    </form>
</template>

<script setup>
import { ref, computed } from "vue";

// Example of reactive state
const form = ref({
    name: "",
    category: "",
});

// Example of computed property
const isValid = computed(() => {
    return form.value.name && form.value.category;
});

// Example of form submission
const handleSubmit = async () => {
    try {
        const response = await axios.post("/api/products", form.value);
        // Handle success
    } catch (error) {
        // Handle error
    }
};
</script>
```

### 2. List Component with Data Fetching

```vue
<!-- resources/js/components/ProductList.vue -->

<template>
    <!-- Example of conditional rendering -->
    <div v-if="loading">Loading...</div>

    <!-- Example of list rendering -->
    <div v-else>
        <div v-for="product in products" :key="product.id">
            {{ product.name }}
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";

// Example of state management
const products = ref([]);
const loading = ref(true);

// Example of lifecycle hook
onMounted(async () => {
    await fetchProducts();
});

// Example of data fetching
const fetchProducts = async () => {
    try {
        const response = await axios.get("/api/products");
        products.value = response.data;
    } catch (error) {
        console.error("Failed to fetch products:", error);
    } finally {
        loading.value = false;
    }
};
</script>
```

### 3. Router Configuration

```javascript
// resources/js/app.js

import { createRouter, createWebHistory } from "vue-router";

// Example of route definitions
const routes = [
    {
        path: "/",
        component: ProductList,
    },
    {
        path: "/products/create",
        component: ProductForm,
    },
];

// Example of router creation
const router = createRouter({
    history: createWebHistory(),
    routes,
});
```

## Common Patterns

### 1. Error Handling

```javascript
// Frontend error handling
try {
  const response = await axios.post('/api/products', data);
  // Success handling
} catch (error) {
  if (error.response?.data?.errors) {
    // Validation errors
    errors.value = error.response.data.errors;
  } else {
    // General error
    console.error('API Error:', error);
  }
}

// Backend error handling
try {
    $product = Product::create($validated);
    return response()->json($product, 201);
} catch (\Exception $e) {
    Log::error('Failed to create product: ' . $e->getMessage());
    return response()->json(['error' => 'Failed to create product'], 500);
}
```

### 2. Form Validation

```javascript
// Frontend validation
const validateForm = () => {
  const errors = {};

  if (!form.name) errors.name = ['Name is required'];
  if (!form.category) errors.category = ['Category is required'];

  return Object.keys(errors).length === 0;
};

// Backend validation
$validated = $request->validate([
    'name' => 'required|string|max:255',
    'category' => 'required|in:tablet,capsule,injection'
]);
```

### 3. State Management

```javascript
// Component state
const loading = ref(false);
const error = ref(null);
const data = ref(null);

// Computed properties
const isValid = computed(() => {
    return form.value.name && form.value.category;
});

// Watchers
watch(selectedId, async (newId) => {
    if (newId) {
        await fetchDetails(newId);
    }
});
```

### 4. API Integration

```javascript
// API service
const ProductAPI = {
    async getAll() {
        const response = await axios.get("/api/products");
        return response.data;
    },

    async create(data) {
        const response = await axios.post("/api/products", data);
        return response.data;
    },
};

// Usage in component
const products = await ProductAPI.getAll();
```

## Testing Examples

### 1. PHP Unit Tests

```php
class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_product()
    {
        $response = $this->postJson('/api/products', [
            'name' => 'Test Product',
            'category' => 'tablet'
        ]);

        $response->assertStatus(201)
                 ->assertJson(['name' => 'Test Product']);
    }
}
```

### 2. Vue Component Tests

```javascript
import { mount } from "@vue/test-utils";
import ProductForm from "./ProductForm.vue";

test("validates required fields", async () => {
    const wrapper = mount(ProductForm);

    await wrapper.find("form").trigger("submit");

    expect(wrapper.text()).toContain("Name is required");
});
```

## Best Practices

### 1. Component Organization

```vue
<!-- Single responsibility principle -->
<template>
    <div>
        <product-header />
        <product-list />
        <product-footer />
    </div>
</template>

<script setup>
// Composables for reusable logic
const useProducts = () => {
    const products = ref([]);

    const fetchProducts = async () => {
        // Implementation
    };

    return {
        products,
        fetchProducts,
    };
};
</script>
```

### 2. API Response Format

```php
// Consistent response structure
return response()->json([
    'data' => $products,
    'meta' => [
        'total' => $products->count(),
        'page' => $request->page
    ]
], $statusCode);
```

### 3. Error Handling Structure

```php
// Backend error handling
try {
    // Main logic
} catch (ValidationException $e) {
    // Handle validation errors
} catch (ModelNotFoundException $e) {
    // Handle not found errors
} catch (\Exception $e) {
    // Handle unexpected errors
}

// Frontend error handling
const handleError = (error) => {
  if (error.response?.status === 422) {
    // Validation errors
  } else if (error.response?.status === 404) {
    // Not found
  } else {
    // General error
  }
};
```

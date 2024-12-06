<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

/**
 * ProductController handles all API endpoints for managing pharmaceutical products.
 * 
 * This controller follows Laravel's RESTful controller pattern and implements
 * the standard CRUD operations (Create, Read, Update, Delete).
 * 
 * @package App\Http\Controllers\Api
 */
class ProductController extends Controller
{
    /**
     * Display a listing of all products.
     * 
     * This endpoint supports the main product listing page in the frontend.
     * Products are ordered by latest first using the created_at timestamp.
     * 
     * @return JsonResponse Array of all products
     */
    public function index(): JsonResponse
    {
        try {
            // Using Laravel's Eloquent ORM to fetch all products
            // The 'latest()' scope orders by created_at DESC
            $products = Product::latest()->get();
            
            // Return JSON response with 200 status code
            return response()->json($products);
        } catch (\Exception $e) {
            // Log any unexpected errors
            Log::error('Failed to fetch products: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch products'], 500);
        }
    }

    /**
     * Store a newly created product.
     * 
     * Validates the incoming request data and creates a new product record.
     * Uses Laravel's built-in validation system to ensure data integrity.
     * 
     * @param Request $request The incoming HTTP request containing product data
     * @return JsonResponse The newly created product or validation errors
     */
    public function store(Request $request): JsonResponse
    {
        Log::info('Creating new product', $request->all());

        try {
            // Validate incoming request data
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'category' => 'required|string|in:tablet,capsule,injection',
                'active_ingredients' => 'required|string',
                'batch_number' => 'required|string|unique:products',
                'research_status' => 'required|string|in:under development,in clinical trials,completed',
                'manufacturing_date' => 'required|date',
                'expiration_date' => 'required|date|after:manufacturing_date',
            ]);

            // Create new product using mass assignment
            // This is safe because we've validated the data above
            $product = Product::create($validated);
            
            Log::info('Product created successfully', ['id' => $product->id]);
            
            // Return 201 Created status code with the new product
            return response()->json($product, 201);
        } catch (ValidationException $e) {
            // Handle validation errors
            Log::warning('Validation failed when creating product', ['errors' => $e->errors()]);
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Handle unexpected errors
            Log::error('Failed to create product: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to create product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified product.
     * 
     * Uses Laravel's Route Model Binding to automatically fetch the product
     * based on the ID parameter. If the product doesn't exist, Laravel
     * will automatically return a 404 response.
     * 
     * @param Product $product The product model instance
     * @return JsonResponse The requested product
     */
    public function show(Product $product): JsonResponse
    {
        try {
            return response()->json($product);
        } catch (\Exception $e) {
            Log::error('Failed to fetch product: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch product'], 500);
        }
    }

    /**
     * Update the specified product.
     * 
     * Validates the update data and updates the product record.
     * Uses unique validation rule with ignore to allow updating
     * without triggering unique constraint on the current record.
     * 
     * @param Request $request The incoming HTTP request
     * @param Product $product The product to update
     * @return JsonResponse The updated product or validation errors
     */
    public function update(Request $request, Product $product): JsonResponse
    {
        Log::info('Updating product', ['id' => $product->id, 'data' => $request->all()]);

        try {
            // Validate update data
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'category' => 'required|string|in:tablet,capsule,injection',
                'active_ingredients' => 'required|string',
                'batch_number' => 'required|string|unique:products,batch_number,' . $product->id,
                'research_status' => 'required|string|in:under development,in clinical trials,completed',
                'manufacturing_date' => 'required|date',
                'expiration_date' => 'required|date|after:manufacturing_date',
            ]);

            // Update the product using mass assignment
            $product->update($validated);
            Log::info('Product updated successfully', ['id' => $product->id]);
            
            return response()->json($product);
        } catch (ValidationException $e) {
            Log::warning('Validation failed when updating product', [
                'id' => $product->id,
                'errors' => $e->errors()
            ]);
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to update product: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to update product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified product.
     * 
     * Deletes the product from the database.
     * Returns 204 No Content on success as per REST conventions.
     * 
     * @param Product $product The product to delete
     * @return JsonResponse Empty response with 204 status code
     */
    public function destroy(Product $product): JsonResponse
    {
        try {
            $id = $product->id;
            $product->delete();
            Log::info('Product deleted successfully', ['id' => $id]);
            
            // Return 204 No Content status code
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Failed to delete product: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to delete product',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

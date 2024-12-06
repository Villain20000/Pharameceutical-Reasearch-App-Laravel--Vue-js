<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Product Model
 * 
 * This model represents a pharmaceutical research product in the database.
 * It uses Laravel's Eloquent ORM features for database interactions.
 * 
 * Key Eloquent Features Used:
 * - Mass Assignment Protection ($fillable)
 * - Attribute Casting ($casts)
 * - Custom Validation Rules (rules() method)
 * - Factory Pattern for Testing (HasFactory trait)
 */
class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * 
     * Mass assignment is a convenient way to populate model attributes,
     * but we need to explicitly specify which attributes can be filled
     * to prevent mass assignment vulnerabilities.
     * 
     * Example usage:
     * Product::create($validatedData);
     * 
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'category',
        'active_ingredients',
        'batch_number',
        'research_status',
        'manufacturing_date',
        'expiration_date'
    ];

    /**
     * The attributes that should be cast.
     * 
     * Laravel can automatically cast attributes to common data types.
     * Here we're casting dates to Carbon instances for easier manipulation.
     * 
     * Example usage:
     * $product->manufacturing_date->format('Y-m-d');
     * $product->expiration_date->diffForHumans();
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'manufacturing_date' => 'date',
        'expiration_date' => 'date'
    ];

    /**
     * Get the validation rules for the model.
     * 
     * This method provides centralized validation rules that can be used
     * both for creating and updating products. The $id parameter is used
     * to exclude the current record from unique validation when updating.
     * 
     * Example usage in controller:
     * $request->validate(Product::rules($product->id));
     * 
     * @param int|null $id The product ID when updating, null when creating
     * @return array<string, string> Validation rules
     */
    public static function rules($id = null): array
    {
        return [
            // Basic validation for required string fields
            'name' => 'required|string|max:255',
            
            // Enum-like validation using 'in' rule
            'category' => 'required|string|in:tablet,capsule,injection',
            
            // Text field validation
            'active_ingredients' => 'required|string',
            
            // Unique field validation with optional exclusion for updates
            'batch_number' => 'required|string|unique:products,batch_number,' . $id,
            
            // Status validation with predefined values
            'research_status' => 'required|string|in:under development,in clinical trials,completed',
            
            // Date validation
            'manufacturing_date' => 'required|date',
            
            // Advanced date validation with comparison
            'expiration_date' => 'required|date|after:manufacturing_date',
        ];
    }

    /**
     * Get the formatted manufacturing date.
     * 
     * This is an example of an accessor - a way to define
     * derived attributes that don't exist in the database.
     * 
     * Example usage:
     * $product->formatted_manufacturing_date
     * 
     * @return string
     */
    public function getFormattedManufacturingDateAttribute(): string
    {
        return $this->manufacturing_date->format('F j, Y');
    }

    /**
     * Get the formatted expiration date.
     * 
     * @return string
     */
    public function getFormattedExpirationDateAttribute(): string
    {
        return $this->expiration_date->format('F j, Y');
    }

    /**
     * Check if the product is expired.
     * 
     * Example of a helper method that uses the date casting feature.
     * 
     * Example usage:
     * if ($product->isExpired()) { ... }
     * 
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->expiration_date->isPast();
    }

    /**
     * Get the days until expiration.
     * 
     * Example of using Carbon date methods provided by the date casting.
     * 
     * @return int
     */
    public function daysUntilExpiration(): int
    {
        return max(0, $this->expiration_date->diffInDays(now()));
    }

    /**
     * Scope a query to only include active products.
     * 
     * This is an example of a query scope - a way to encapsulate
     * common query constraints into reusable methods.
     * 
     * Example usage:
     * Product::active()->get();
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('expiration_date', '>', now());
    }

    /**
     * Boot the model.
     * 
     * This method demonstrates how to use Eloquent events
     * to automatically perform actions when certain events occur.
     * 
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // Example of using model events
        static::creating(function ($product) {
            // Log product creation
            \Log::info('Creating new product', [
                'name' => $product->name,
                'batch_number' => $product->batch_number
            ]);
        });
    }
}

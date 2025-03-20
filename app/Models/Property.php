<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Property extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * This array specifies which attributes can be assigned in bulk (mass assignment).
     *
     * @var array
     */
    protected $fillable = [
        'ubication_id',   // ID for the location (ubication) of the property
        'zone_id',         // ID for the zone the property belongs to
        'development',     // The development name or project name for the property
        'classification',  // Classification of the property (e.g., Luxury, Premium)
        'type',            // Type of the property (e.g., House, Department, Office)
        'description',     // Description of the property
        'price',           // Price of the property
        'currency',        // Currency used for the price (e.g., USD, MXN)
        'area_m2',         // Area of the property in square meters
        'contruction_m2',  // Construction area of the property in square meters
        'price_m2',        // Price per square meter
        'rooms',           // Number of rooms in the property
        'bathrooms',       // Number of bathrooms in the property
        'amenities',       // List of amenities available in the property
        'pet_friendly',    // Whether the property is pet friendly
        'family',          // Target family type (e.g., children, young couple, family)
        'view',            // View from the property (e.g., sea, city, forest)
        'operation',       // Operation type (e.g., Sale, Rent, Transfer)
        'contact',         // Contact name for the property
        'contact_type',    // Type of contact (e.g., owner, broker)
        'contact_data',    // Contact information (phone, email, etc.)
        'comission',       // Commission for the transaction
        'maps',            // Maps related to the property
        'airbnb_rent',     // Whether the property is available for Airbnb rental
        'content',         // Additional content or description
        'pdf',             // PDF document associated with the property (e.g., contract, details)
        'status',          // Status of the property (e.g., active, inactive)
    ];

    /**
     * The attributes that should be cast to native types.
     * This array specifies how certain attributes should be cast to native types like integers, decimals, or booleans.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',           // Cast the ID to an integer
        'ubication_id' => 'integer', // Cast the ubication ID to an integer
        'zone_id' => 'integer',      // Cast the zone ID to an integer
        'price' => 'decimal:2',      // Cast the price to a decimal with 2 decimal places
        'area_m2' => 'decimal:2',   // Cast the area to a decimal with 2 decimal places
        'contruction_m2' => 'decimal:2', // Cast the construction area to a decimal with 2 decimal places
        'price_m2' => 'decimal:2',   // Cast the price per square meter to a decimal with 2 decimal places
        'rooms' => 'integer',        // Cast the rooms to an integer
        'bathrooms' => 'integer',    // Cast the bathrooms to an integer
        'comission' => 'decimal:2',  // Cast the commission to a decimal with 2 decimal places
        'status' => 'boolean',       // Cast the status to a boolean (true/false)
    ];

    /**
     * Define the relationship between Property and Ubication.
     * This method defines the inverse of a one-to-many relationship between Property and Ubication.
     * A Property belongs to one Ubication.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ubication(): BelongsTo
    {
        return $this->belongsTo(Ubication::class); // A property belongs to one ubication
    }

    /**
     * Define the relationship between Property and Zone.
     * This method defines the inverse of a one-to-many relationship between Property and Zone.
     * A Property belongs to one Zone.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class); // A property belongs to one zone
    }
}
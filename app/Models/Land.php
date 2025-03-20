<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Land extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * This array specifies which attributes can be assigned in bulk (mass assignment).
     *
     * @var array
     */
    protected $fillable = [
        'ubication_id',  // ID for the location (ubication)
        'zone_id',       // ID for the zone the land belongs to
        'classification', // Classification of the land (e.g., Residential, Industrial)
        'description',    // Description of the land
        'price',          // Price of the land
        'currency',       // Currency used for the price (e.g., USD, MXN)
        'area',           // Area of the land in square meters
        'front',          // Front width of the land
        'bottom',         // Bottom depth of the land
        'density',        // Density of the land (e.g., urban, rural)
        'soil',           // Soil type of the land (e.g., clay, sand)
        'view',           // View from the land (e.g., city, sea)
        'operation',      // Operation type (e.g., sale, rent)
        'contact',        // Contact name for the land
        'contact_type',   // Type of contact (e.g., owner, broker)
        'contact_data',   // Contact data (phone, email, etc.)
        'comission',      // Commission for the transaction
        'maps',           // Maps related to the land
        'content',        // Additional content or description
        'pdf',            // PDF document associated with the land (e.g., contract, details)
        'status',         // Status of the land (e.g., active, inactive)
    ];

    /**
     * The attributes that should be cast to native types.
     * This array specifies how certain attributes should be cast to native types like integers, decimals, or booleans.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',          // Cast the ID to an integer
        'ubication_id' => 'integer', // Cast the ubication ID to an integer
        'zone_id' => 'integer',     // Cast the zone ID to an integer
        'price' => 'decimal:2',     // Cast the price to a decimal with 2 decimal places
        'area' => 'decimal:2',      // Cast the area to a decimal with 2 decimal places
        'front' => 'decimal:2',     // Cast the front measurement to a decimal with 2 decimal places
        'bottom' => 'decimal:2',    // Cast the bottom measurement to a decimal with 2 decimal places
        'comission' => 'decimal:2', // Cast the commission to a decimal with 2 decimal places
        'status' => 'boolean',      // Cast the status to a boolean (true/false)
    ];

    /**
     * Define the relationship between Land and Ubication.
     * This method defines the inverse of a one-to-many relationship between Land and Ubication.
     * A Land belongs to one Ubication.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ubication(): BelongsTo
    {
        return $this->belongsTo(Ubication::class); // A land belongs to one ubication
    }

    /**
     * Define the relationship between Land and Zone.
     * This method defines the inverse of a one-to-many relationship between Land and Zone.
     * A Land belongs to one Zone.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class); // A land belongs to one zone
    }
}
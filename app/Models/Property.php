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
     *
     * @var array
     */
    protected $fillable = [
        'ubication_id',
        'zone_id',
        'development',
        'classification',
        'type',
        'description',
        'price',
        'currency',
        'area_m2',
        'contruction_m2',
        'price_m2',
        'rooms',
        'bathrooms',
        'amenities',
        'pet_friendly',
        'family',
        'view',
        'operation',
        'contact',
        'contact_type',
        'contact_data',
        'comission',
        'maps',
        'airbnb_rent',
        'content',
        'pdf',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'ubication_id' => 'integer',
        'zone_id' => 'integer',
        'price' => 'decimal:2',
        'area_m2' => 'decimal:2',
        'contruction_m2' => 'decimal:2',
        'price_m2' => 'decimal:2',
        'rooms' => 'integer',
        'bathrooms' => 'integer',
        'comission' => 'decimal:2',
        'status' => 'boolean',
    ];

    public function ubication(): BelongsTo
    {
        return $this->belongsTo(Ubication::class);
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }
}

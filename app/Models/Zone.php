<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Property;
use App\Models\Land;

class Zone extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * This array specifies which attributes can be mass assigned (mass assignment protection).
     *
     * @var array
     */
    protected $fillable = [
        'name', // The name of the zone
    ];

    /**
     * The attributes that should be cast to native types.
     * This method specifies how the model's attributes should be cast when retrieved.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer', // Ensure the 'id' attribute is cast to an integer
    ];

    /**
     * Define the relationship between Zone and Property.
     * A zone can have many properties associated with it.
     * This is the "one-to-many" relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class, 'zone_id'); // Defines the inverse of the relationship (zone -> properties)
    }

    /**
     * Define the relationship between Zone and Land.
     * A zone can have many lands associated with it.
     * This is the "one-to-many" relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lands(): HasMany
    {
        return $this->hasMany(Land::class, 'zone_id'); // Defines the inverse of the relationship (zone -> lands)
    }
}
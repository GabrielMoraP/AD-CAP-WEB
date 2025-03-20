<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Property;
use App\Models\Land;

class Ubication extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * This array specifies which attributes can be assigned in bulk (mass assignment).
     *
     * @var array
     */
    protected $fillable = [
        'name',  // Name of the Ubication
    ];

    /**
     * The attributes that should be cast to native types.
     * This array specifies how certain attributes should be cast to native types like integers.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',  // Cast the ID to an integer
    ];

    /**
     * Define the relationship between Ubication and Property.
     * This method defines the one-to-many relationship between Ubication and Property.
     * A Ubication can have many properties.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class, 'ubication_id'); // A ubication can have many properties
    }

    /**
     * Define the relationship between Ubication and Land.
     * This method defines the one-to-many relationship between Ubication and Land.
     * A Ubication can have many lands.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lands(): HasMany
    {
        return $this->hasMany(Land::class, 'ubication_id'); // A ubication can have many lands
    }
}
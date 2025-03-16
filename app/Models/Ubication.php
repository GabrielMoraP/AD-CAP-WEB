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
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class, 'zone_id');
    }

    public function lands(): HasMany
    {
        return $this->hasMany(Land::class, 'zone_id');
    }
}

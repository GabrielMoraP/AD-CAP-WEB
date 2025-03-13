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
     *
     * @var array
     */
    protected $fillable = [
        'ubication_id',
        'zone_id',
        'classification',
        'description',
        'price',
        'currency',
        'area',
        'front',
        'bottom',
        'density',
        'soil',
        'view',
        'operation',
        'contact',
        'contact_type',
        'contact_data',
        'comission',
        'maps',
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
        'area' => 'decimal:2',
        'front' => 'decimal:2',
        'bottom' => 'decimal:2',
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

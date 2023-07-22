<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Modelo extends Model
{
    use HasFactory;
    protected $fillable = [
        'marca_id',
        'nome',
        'imagem',
        'numero_portas',
        'lugares',
        'air_bag',
        'abs'
    ];

    /**
     * Get the marca that owns the Modelo
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class, 'marca_id');
    }

    /**
     * Get all of the carros for the Modelo
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function carros(): HasMany
    {
        return $this->hasMany(Carro::class, 'modelo_id', 'id');
    }
}

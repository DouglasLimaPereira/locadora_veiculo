<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\Rules\Unique;

class Marca extends Model
{
    use HasFactory;
    protected $fillable = [
        'nome',
        'imagem',
    ];

    public function rules(): array
    {
        return [
            'nome' => 'unique:marcas,nome,'.$this->id,
            'imagem' => 'required',
        ];
    }
    
    public function feedback(): array
    {
        return [
            'nome.required' => 'Necessário informar o nome da marca',
            'nome.unique' => 'Nome da marca já cadastrado',

            'imagem.required' => 'Necessário informar a imagem da marca',
        ];
    }

    /**
     * Get all of the modelos for the Marca
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function modelos(): HasMany
    {
        return $this->hasMany(Modelo::class, 'marca_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locacao extends Model
{
    use HasFactory;

    protected $table = 'locacoes';
    protected $fillable = [
        'carro_id',
        'cliente_id',
        'created_at',
        'data_final_previsto_periodo',
        'data_final_realizado_periodo',
        'data_inicio_periodo',
        'km_final',
        'km_inicial',
        'updated_at',
        'valor_diaria',
    ];
}

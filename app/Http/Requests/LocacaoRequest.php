<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocacaoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'carro_id' => 'required',
            'cliente_id' => 'required',
            'data_final_previsto_periodo' => 'required',
            'data_final_realizado_periodo' => 'required',
            'data_inicio_periodo' => 'required',
            'km_final' => 'required',
            'km_inicial' => 'required',
            'valor_diaria' => 'required',
        ];
    }
}

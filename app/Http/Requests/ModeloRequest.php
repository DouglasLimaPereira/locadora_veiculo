<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModeloRequest extends FormRequest
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
            'marca_id' => 'exists:marcas,id',
            'nome' => 'unique:modelos,nome,'.$this->id,
            'imagem' => 'required|file|mimes:png,jpeg,jpg',
            'numero_portas' => 'required|integer|digits_between:1,5',
            'lugares' => 'required|integer|digits_between:1,5',
            'air_bag' => 'required|boolean',
            'abs' => 'required|boolean',
        ];
    }
}

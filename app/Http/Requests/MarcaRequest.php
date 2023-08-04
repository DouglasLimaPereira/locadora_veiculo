<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MarcaRequest extends FormRequest
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
                'nome' => 'required|unique:marcas,nome,'.$this->id,
                'imagem' => 'file|mimes:png',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nome.required' => 'Necessário informar o nome da marca',
            'nome.unique' => 'Nome da marca já cadastrado',
            'imagem.mines' => 'O arquivo deve ser de extenssão ( .png )',
        ];
    }
}

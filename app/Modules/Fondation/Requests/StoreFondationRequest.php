<?php

namespace App\Modules\Fondation\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class StoreFondationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ids_barres'   => 'required|array|min:1',
            'ids_barres.*' => 'integer|distinct|min:1',
            'poid_fondu'   => 'required|numeric|min:0',
            'carat_moyen'  => 'required|numeric|min:0',
            'poids_dubai'  => 'nullable|numeric|min:0',
            'carrat_dubai' => 'nullable|numeric|min:0',
            
        ];
    }

    public function messages(): array
    {
        return [
            'ids_barres.required'   => 'Les identifiants des barres sont obligatoires.',
            'ids_barres.array'      => 'Les identifiants des barres doivent être un tableau.',
            'ids_barres.*.integer'  => 'Chaque identifiant de barre doit être un entier.',
            'ids_barres.*.distinct' => 'Les identifiants des barres doivent être uniques.',
            'poid_fondu.required'   => 'Le poids fondu est obligatoire.',
            'carat_moyen.required'  => 'Le carat moyen est obligatoire.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, response()->json([
            'status'  => 422,
            'message' => 'Erreur de validation',
            'errors'  => $validator->errors(),
        ], 422));
    }
}

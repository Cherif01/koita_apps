<?php

namespace App\Modules\Comptabilite\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class StoreTypeOperationRequest extends FormRequest
{
    /**
     * Autoriser la requête.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation.
     */
    public function rules(): array
    {
        return [
            'libelle' => 'required|string|max:150|unique:type_operations,libelle',
            'nature'  => 'required|in:entree,sortie',
        ];
    }

    /**
     * Messages d’erreur personnalisés.
     */
    public function messages(): array
    {
        return [
            'libelle.required' => 'Le libellé est obligatoire.',
            'libelle.unique'   => 'Ce libellé existe déjà.',
            'nature.required'  => 'La nature est obligatoire.',
            'nature.in'        => 'La nature doit être soit "entree" soit "sortie".',
        ];
    }

    /**
     * 🔹 Réponse JSON en cas d’échec de validation.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, response()->json([
            'status'  => 'error',
            'message' => 'Erreur de validation',
            'errors'  => $validator->errors(),
        ], 422));
    }
}

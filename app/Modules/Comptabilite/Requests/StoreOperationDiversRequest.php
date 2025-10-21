<?php

namespace App\Modules\Comptabilite\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class StoreOperationDiversRequest extends FormRequest
{
    /**
     * Autoriser la requête
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation
     */
    public function rules(): array
    {
        return [
            'id_type_operation' => 'required|integer|exists:type_operations,id',
            'id_divers'         => 'nullable|integer|exists:divers,id',
            'id_devise'         => 'required|integer|exists:devises,id',
            'montant'           => 'required|numeric|min:0',
            'commentaire'       => 'nullable|string|max:255',
        ];
    }

    /**
     * Messages personnalisés
     */
    public function messages(): array
    {
        return [
            'id_type_operation.required' => 'Le type d’opération est obligatoire.',
            'id_type_operation.exists'   => 'Le type d’opération est invalide.',
            'id_divers.exists'           => 'Le champ Divers est invalide.',
            'id_devise.required'         => 'La devise est obligatoire.',
            'id_devise.exists'           => 'La devise sélectionnée est invalide.',
            'montant.required'           => 'Le montant est obligatoire.',
            'montant.numeric'            => 'Le montant doit être un nombre valide.',
            'commentaire.string'         => 'Le commentaire doit être une chaîne valide.',
        ];
    }

    /**
     * 🔹 Gestion des erreurs en JSON
     */
    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, response()->json([
            'status'  => 'error',
            'message' => 'Erreur de validation.',
            'errors'  => $validator->errors(),
        ], 422));
    }
}

<?php

namespace App\Modules\Fixing\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class StoreFixingClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_client'      => 'required|integer|exists:clients,id',
            'id_devise'      => 'required|integer|exists:devises,id',
            'poids_pro'      => 'nullable|numeric|min:0',
            'carrat_moyen'   => 'nullable|numeric|min:0',
            'discompte'      => 'nullable|numeric|min:0',
            'bourse'         => 'required|numeric|min:0',
            'prix_unitaire'  => 'nullable|numeric|min:0',
            
        ];
    }

    public function messages(): array
    {
        return [
            'id_client.required'  => 'Le client est obligatoire.',
            'id_client.exists'    => 'Le client sélectionné est invalide.',

            'id_devise.required'  => 'La devise est obligatoire.',
            'id_devise.exists'    => 'La devise sélectionnée est invalide.',

            'bourse.required'     => 'Le cours de la bourse est obligatoire.',

            'poids_pro.numeric'   => 'Le poids doit être un nombre valide.',
            'carrat_moyen.numeric'=> 'Le carat moyen doit être un nombre valide.',
            'discompte.numeric'   => 'Le discompte doit être un nombre valide.',
            'prix_unitaire.numeric'=> 'Le prix unitaire doit être un nombre valide.',

            'status.in'           => 'Le statut sélectionné est invalide.',
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

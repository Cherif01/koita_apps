<?php

namespace App\Modules\Settings\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class StoreClientRequest extends FormRequest
{
    /**
     * 🔹 Autoriser la requête.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * 🔹 Règles de validation.
     */
    public function rules(): array
    {
        return [
            // Nom complet (obligatoire)
            'nom_complet'    => 'required|string|max:150',

            // Type de client (obligatoire)
            'type_client'    => 'required|string|max:50',

            // Raison sociale (facultative)
            'raison_sociale' => 'nullable|string|max:150',

            // Localisation
            'pays'           => 'nullable|string|max:100',
            'ville'          => 'nullable|string|max:100',
            'adresse'        => 'nullable|string|max:255',

            // Contact
            'telephone'      => 'nullable|string|max:20|unique:clients,telephone',
            'email'          => 'nullable|email|max:150|unique:clients,email',
        ];
    }

    /**
     * 🔹 Messages personnalisés.
     */
    public function messages(): array
    {
        return [
            'nom_complet.required' => 'Le nom complet du client est obligatoire.',
            'nom_complet.string'   => 'Le nom complet doit être une chaîne de caractères.',
            'type_client.required' => 'Le type de client est obligatoire.',
            'type_client.string'   => 'Le type de client doit être une chaîne de caractères.',
            'raison_sociale.string'=> 'La raison sociale doit être une chaîne valide.',
            'email.email'          => 'L’adresse email n’est pas valide.',
            'email.unique'         => 'Cet email est déjà utilisé.',
            'telephone.unique'     => 'Ce numéro de téléphone existe déjà.',
        ];
    }

    /**
     * 🔹 Réponse JSON en cas d’erreur de validation.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException(
            $validator,
            response()->json([
                'status'  => 'error',
                'message' => 'Erreur de validation des données envoyées.',
                'errors'  => $validator->errors(),
            ], 422)
        );
    }
}

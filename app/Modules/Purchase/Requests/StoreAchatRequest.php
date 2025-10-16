<?php

namespace App\Modules\Purchase\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAchatRequest extends FormRequest
{
    public function authorize() : bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reference' => ['nullable', 'string', 'max:100', Rule::unique('achats')->ignore($this->route()->parameter('achat'))],
            'fournisseur_id' => ['required', 'exists:fournisseurs,id'],
            'lot_id' => ['required', 'exists:lots,id'],
            'commentaire' => ['nullable', 'string'],
            'status' => ['nullable', 'in:encours,terminer'],
        ];
    }

    public function messages(): array
    {
        return [
            'reference.string' => 'La référence doit être une chaîne de caractères.',
            'reference.max' => 'La référence ne peut pas dépasser 100 caractères.',
            'reference.unique' => 'Cette référence existe déjà dans le système.',

            'fournisseur_id.required' => 'Le fournisseur est obligatoire.',
            'fournisseur_id.exists' => 'Le fournisseur sélectionné est invalide ou n’existe pas.',

            'lot_id.required' => 'Le lot est obligatoire.',
            'lot_id.exists' => 'Le lot sélectionné est invalide ou n’existe pas.',

            'commentaire.string' => 'Le commentaire doit être une chaîne de caractères.',

            'status.in' => 'Le statut doit être soit "encours" soit "terminer".',
        ];
    }
}

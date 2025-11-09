<?php

namespace App\Modules\Fixing\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class StoreFixingVenduRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_client'   => 'required|integer|exists:clients,id',
            'id_fixings'  => 'required|array|min:1',
            'id_fixings.*' => 'integer|distinct|exists:fixing_clients,id',
        ];
    }

    public function messages(): array
    {
        return [
            'id_client.required'  => 'Le client est obligatoire.',
            'id_client.exists'    => 'Le client sélectionné est invalide.',

            'id_fixings.required' => 'Vous devez sélectionner au moins un fixing à vendre.',
            'id_fixings.array'    => 'Les fixings doivent être envoyés sous forme de tableau.',
            'id_fixings.*.exists' => 'Certains fixings sélectionnés sont invalides.',
            'id_fixings.*.distinct' => 'Chaque fixing doit être unique.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, response()->json([
            'status'  => 422,
            'message' => 'Erreur de validation.',
            'errors'  => $validator->errors(),
        ], 422));
    }
}

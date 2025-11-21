<?php

namespace App\Modules\Fondation\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class UpdatePureteCorrectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'corrections' => 'required|array|min:1',
            'corrections.*.id' => 'required|integer|exists:fondations,id',

            // 🔥 NOUVEAU : p_purete obligatoire
            'corrections.*.p_purete' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'corrections.required' => 'La liste des corrections est obligatoire.',

            'corrections.*.id.required' => 'L’ID de la fondation est requis.',
            'corrections.*.id.exists'   => 'Certaines fondations sont introuvables.',

            // 🔥 MESSAGES PERSONNALISÉS POUR p_purete
            'corrections.*.p_purete.required' => 'Le pourcentage de pureté est requis.',
            'corrections.*.p_purete.numeric'  => 'Le pourcentage de pureté doit être un nombre.',
            'corrections.*.p_purete.min'      => 'Le pourcentage de pureté doit être supérieur ou égal à 0.',
           
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException(
            $validator,
            response()->json([
                'status'  => 'error',
                'message' => 'Erreur de validation.',
                'errors'  => $validator->errors(),
            ], 422)
        );
    }
}

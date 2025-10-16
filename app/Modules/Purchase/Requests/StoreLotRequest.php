<?php

namespace App\Modules\Purchase\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLotRequest extends FormRequest
{
    public function authorize() : bool
    {
        return true;
    }

    public function rules() : array
    {
        return [
            'libelle' => ['required', 'string', 'min:2', Rule::unique('lots')->ignore($this->route()->parameter('lot'))],
            'commentaire' => ['nullable', 'min:2'],
            'date' => ['nullable', 'date'],
            'status' => ['nullable', 'in:encours,terminer'],
        ];
    }

    public function messages()
    {
        return [
            'libelle.required' => "Le libelle est obligatoire",
            'libelle.string' => "Le libelle dois etre une chaine de caractère",
            'libelle.min' => "Le libelle dois contenir minimum 2 caractères",
            'libelle.unique' => "Ce libelle existe déjà",

            'commentaire' => "Le commentaire dois contenir au minimum 2 caractères",

            'date' => "La date dois etre valide",

            'status.in' => "Le status est soit: encours OU terminer",
        ];
    }
}

<?php

namespace App\Modules\Administration\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFournisseurRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules() : array
    {
        return [
            'name' => ['required', 'string', 'min:3'],
            'telephone' => ['required', 'min:9', 'max:30', 'regex:/^[0-9]+$/', Rule::unique('users')->ignore($this->route()->parameter('fournisseur'))],
            'adresse' => ['nullable', 'string', 'min:3'],
            'image' => ['nullable', 'mimes:jpeg,jpg,png', 'max:1024'],
        ];
    }

    public function messages()
    {
        return [
            'name' => 'Le nom dois composer au moins de trois caractères',
            'telephone' => 'Le numéro est obligatoire et dois avoir au minimum 9 digits ex: 600000000',
            'telephone.unique' => 'Ce numéro de telephone a été déjà utilisé',
            'adresse' => "L'adresse doit composer au moins de trois caractères",
            'image' => "L'image dois etre en jpg jpeg ou png et max: 1024",
        ];
    }
}

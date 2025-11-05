<?php

namespace App\Modules\Comptabilite\Resources;

use App\Traits\Helper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompteResource extends JsonResource
{
    use Helper;

    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'solde_initial' => $this->solde_initial,
            'numero_compte' => $this->numero_compte,
            'solde' => $this->getAccountBalanceByDevise($this->id),

            'devise' => [
                'id' => $this->devise->id,
                'libelle' => $this->devise->libelle,
                'symbole' => $this->devise->symbole,
            ],

            'banque' => [
                'id' => $this->banque->id,
                'libelle' => $this->banque->libelle,
                'api' => $this->banque->api ?? null,
                'commentaire' => $this->banque->commentaire ?? null,
            ],

            'createdBy' => $this->createdBy ? [
                'id' => $this->createdBy->id ?? null,
                'name' => $this->createdBy->name ?? null,
                'email' => $this->createdBy->email ?? null,
                'telephone' => $this->createdBy->telephone ?? null,
                'adresse' => $this->createdBy->adresse ?? null,
                'role' => $this->createdBy->role ?? null,
            ] : null,

            'updatedBy' => $this->updatedBy ? [
                'id' => $this->updatedBy->id ?? null,
                'name' => $this->updatedBy->name ?? null,
                'email' => $this->updatedBy->email ?? null,
                'telephone' => $this->updatedBy->telephone ?? null,
                'adresse' => $this->updatedBy->adresse ?? null,
                'role' => $this->updatedBy->role,
            ] : null,

            'created_at'  => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'  => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
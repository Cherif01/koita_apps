<?php

namespace App\Modules\Purchase\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LotResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'libelle' => $this->libelle,
            'commentaire' => $this->commentaire,
            'date' => $this->date,
            'lot_status' => $this->status,

            // ✅ Achats relationship
            'achats' => $this->whenLoaded('achats', function () {
                return AchatResource::collection($this->achats);
            }),

            'createdBy' => $this->createdBy ? [
                'id' => $this->createdBy->id,
                'name' => $this->createdBy->name,
                'email' => $this->createdBy->email ?? null,
                'telephone' => $this->createdBy->telephone ?? null,
                'adresse' => $this->createdBy->adresse ?? null,
                'role' => $this->createdBy->role,
            ] : null,

            'updatedBy' => $this->updatedBy ? [
                'id' => $this->updatedBy->id,
                'name' => $this->updatedBy->name,
                'email' => $this->updatedBy->email ?? null,
                'telephone' => $this->updatedBy->telephone ?? null,
                'adresse' => $this->updatedBy->adresse ?? null,
                'role' => $this->updatedBy->role,
            ] : null,

            'created_at' => $this->created_at->format('d-m-Y H:i:s'),
            'updated_at' => $this->updated_at->format('d-m-Y H:i:s'),
        ];
    }
}

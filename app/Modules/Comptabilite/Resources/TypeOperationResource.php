<?php

namespace App\Modules\Comptabilite\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TypeOperationResource extends JsonResource
{
    /**
     * Transforme la ressource en tableau JSON.
     */
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'libelle'     => $this->libelle,
            'nature'      => $this->nature,
            'libelle_formate' => $this->libelle_formate,
            'created_by'  => $this->createur?->name,
            'modify_by'   => $this->modificateur?->name,
            'created_at'  => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'  => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}

<?php

namespace App\Modules\Settings\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Comptabilite\Resources\OperationDiversResource;

class DiversResource extends JsonResource
{
    /**
     * Transforme la ressource en tableau JSON propre (sans valeurs nulles)
     */
    public function toArray(Request $request): array
    {
        return array_filter([
            'id'              => $this->id,
            'name'            => $this->name,
            'raison_sociale'  => $this->raison_sociale,
            'telephone'       => $this->telephone,
            'adresse'         => $this->adresse,
            'type'            => $this->type,

            // 🔹 Relations principales
            'operations'      => OperationDiversResource::collection(
                $this->whenLoaded('operationsDivers')
            ),

            // 🔹 Audit
            'created_by'      => $this->createur?->name,
            'updated_by'      => $this->modificateur?->name,

            // 🔹 Dates formatées
            'created_at'      => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'      => $this->updated_at?->format('Y-m-d H:i:s'),
        ], fn($value) => !is_null($value));
    }
}

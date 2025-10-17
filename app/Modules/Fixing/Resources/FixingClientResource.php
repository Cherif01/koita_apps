<?php

namespace App\Modules\Fixing\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Settings\Resources\ClientResource;
use App\Modules\Settings\Resources\DeviseResource;
use App\Modules\Fondation\Resources\FondationResource;

class FixingClientResource extends JsonResource
{
    /**
     * 🔹 Transformation JSON de FixingClient
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'poids_pro'     => (float) $this->poids_pro,
            'carrat_moyen'  => (float) $this->carrat_moyen,
            'discompte'     => (float) $this->discompte,
            'bourse'        => (float) $this->bourse,
            'prix_unitaire' => (float) $this->prix_unitaire,
            'status'        => $this->status ?? 'en attente',

            // 🔹 Relations principales
            'client'  => new ClientResource($this->whenLoaded('client')),
            'devise'  => new DeviseResource($this->whenLoaded('devise')),

            // 🔹 Fondations liées à ce fixing client
            'fondations' => FondationResource::collection(
                $this->whenLoaded('fondations')
            ),

            // 🔹 Informations d’audit
            'created_by' => $this->createur?->name,
            'updated_by' => $this->modificateur?->name,

            // 🔹 Dates formatées
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}

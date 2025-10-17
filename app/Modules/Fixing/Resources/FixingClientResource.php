<?php

namespace App\Modules\Fixing\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Settings\Resources\ClientResource;
use App\Modules\Settings\Resources\DeviseResource;
use App\Modules\Fondation\Resources\FondationResource;
use App\Modules\Fixing\Services\FixingClientService;

class FixingClientResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // 🔹 Récupération des données calculées
        $calculs = app(FixingClientService::class)->calculerFacture($this->id);

        // 🔹 On extrait les valeurs pour une lecture claire
        $prixUnitaire  = $calculs['prix_unitaire'] ?? 0;
        $totalFacture  = $calculs['total_facture'] ?? 0;
        $details       = $calculs['fondations'] ?? [];

        return [
            'id'            => $this->id,
            'poids_pro'     => (float) $this->poids_pro,
            'carrat_moyen'  => (float) $this->carrat_moyen,
            'discompte'     => (float) $this->discompte,
            'bourse'        => (float) $this->bourse,
            'prix_unitaire' => (float) $prixUnitaire,
            'status'        => $this->status ?? 'en attente',

            // 🔹 Relations principales
            'client'  => new ClientResource($this->whenLoaded('client')),
            'devise'  => new DeviseResource($this->whenLoaded('devise')),

            // 🔹 Fondations liées à ce fixing client
            'fondations' => FondationResource::collection(
                $this->whenLoaded('fondations')
            ),

            // 🔹 Données issues du calcul complet
            'calculs' => [
                'prix_unitaire' => $prixUnitaire,
                'total_facture' => $totalFacture,
                'details'       => $details,
            ],

            // 🔹 Audit
            'created_by' => $this->createur?->name,
            'updated_by' => $this->modificateur?->name,

            // 🔹 Dates formatées
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}

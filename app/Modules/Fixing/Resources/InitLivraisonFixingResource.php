<?php

namespace App\Modules\Fixing\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Fixing\Services\ExpeditionService;

class InitLivraisonFixingResource extends JsonResource
{
    public function toArray($request)
    {
        // 🔥 Appel de ta fonction calculerPoidsEtCarat
        $calcul = app(ExpeditionService::class)
            ->calculerPoidsEtCarat($this->id);

        return [
            'id'              => $this->id,
            'reference'       => $this->reference ?? null,

           
            

            // 🔥 Info spéciale pour le fixing
            'poids_total'    => $calcul['poids_total'],
            'carrat_moyen'   => $calcul['carrat_moyen'],
            'purete_totale'  => $calcul['purete_totale'],
            'poids_fixing'   => $calcul['poids_fixing'],
            'poids_restant'  => $calcul['poids_restant'],
            'statut'         => $calcul['statut'], // ajouté dans ta fonction
            // Infos de création
            'created_at'     => $this->created_at?->format('Y-m-d H:i:s'),
            'created_by'     => $this->createur?->nom ?? null,
        ];
    }
}

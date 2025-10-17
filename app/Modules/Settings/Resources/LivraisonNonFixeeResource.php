<?php

namespace App\Modules\Settings\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LivraisonNonFixeeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'reference' => $this->reference ?? '',

            // 🔹 Liste des fondations non fixées avec leur référence de livraison
            'barres' => $this->fondations
                ->whereNull('id_fixing')
                ->map(function ($fondation) {
                    return [
                        'id'                   => $fondation->id,
                        'reference_livraison'  => $this->reference ?? '',
                        'poids_fondu'          => (float) $fondation->poids_fondu,
                        'carrat_fondu'         => (float) $fondation->carrat_fondu,
                    ];
                })
                ->values(),

            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}

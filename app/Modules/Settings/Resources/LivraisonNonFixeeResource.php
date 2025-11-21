<?php

namespace App\Modules\Settings\Resources;

use App\Traits\Helper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LivraisonNonFixeeResource extends JsonResource
{
    use Helper;

    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'reference' => $this->reference ?? '',

            // 🔹 Liste des fondations non encore fixées
            'barres' => $this->fondations
                ->whereNull('id_fixing')
                ->map(function ($fondation) {
                    $poidsFondu = (float) ($fondation->poids_fondu ?? 0);
                    $carratFondu = (float) ($fondation->carrat_fondu ?? 0);

                    return [
                        'id'                  => $fondation->id,
                        'reference_livraison' => $this->reference ?? '',
                        'poids_fondu'         => $poidsFondu,
                        'carrat_fondu'        => $carratFondu,

                        // ✅ Pureté locale arrondie à 2 décimales
                        'purete_locale'       => $this->arroundir(
                            2,
                            $this->pureter($poidsFondu, $carratFondu)
                        ),
                    ];
                })
                ->values(),

            'created_at' => optional($this->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}

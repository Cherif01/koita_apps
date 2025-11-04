<?php

namespace App\Modules\Comptabilite\Services;

use App\Modules\Comptabilite\Models\OperationClient;
use App\Modules\Comptabilite\Models\OperationDivers;
use App\Modules\Comptabilite\Models\Caisse;
use App\Modules\Settings\Models\Devise;
use Illuminate\Support\Facades\DB;

class CompteService
{
    /**
     * 🔹 Calcule le solde réel d’un compte dans une devise donnée.
     *
     * @param  int  $id_compte
     * @param  int  $id_devise
     * @return float
     */
    public static function calculerSoldeParDevise(int $id_compte, int $id_devise): float
    {
        $solde = 0;

        // 🔸 Récupérer le symbole de la devise
        $symbole = Devise::find($id_devise)?->symbole;

        if (! $symbole) {
            return 0.0;
        }

        // 🧮 Helper interne pour le total par nature (1 = entrée, 0 = sortie)
        $getTotal = function ($model, int $nature) use ($id_compte, $id_devise) {
            return $model::where('id_compte', $id_compte)
                ->whereHas('typeOperation', fn($q) => $q->where('nature', $nature))
                ->where('id_devise', $id_devise)
                ->sum('montant');
        };

        // ✅ Somme de toutes les entrées/sorties dans les 3 tables
        $entrees =
            $getTotal(OperationClient::class, 1) +
            $getTotal(OperationDivers::class, 1) +
            $getTotal(Caisse::class, 1);

        $sorties =
            $getTotal(OperationClient::class, 0) +
            $getTotal(OperationDivers::class, 0) +
            $getTotal(Caisse::class, 0);

        $solde = $entrees - $sorties;

        return round($solde, 2);
    }

    /**
     * 🔹 Vérifie si le solde du compte est suffisant avant d’effectuer une opération.
     *
     * @param  int  $id_compte
     * @param  int  $id_devise
     * @param  float  $montant
     * @return array
     */
    public static function verifierSoldeAvantOperation(int $id_compte, int $id_devise, float $montant): array
    {
        $solde = self::calculerSoldeParDevise($id_compte, $id_devise);

        if ($solde < $montant) {
            return [
                'status'  => false,
                'message' => "Solde insuffisant pour effectuer cette opération. 
                              Solde disponible : {$solde}",
                'solde'   => $solde,
            ];
        }

        return [
            'status'  => true,
            'message' => "Solde suffisant. Opération autorisée.",
            'solde'   => $solde,
        ];
    }
}

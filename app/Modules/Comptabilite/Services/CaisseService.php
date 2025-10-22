<?php

namespace App\Modules\Comptabilite\Services;

use App\Modules\Comptabilite\Models\Caisse;
use App\Modules\Comptabilite\Models\TypeOperation;
use App\Modules\Comptabilite\Resources\CaisseResource;
use App\Modules\Settings\Models\Devise;
use Illuminate\Support\Facades\Auth;
use Exception;

class CaisseService
{
    /**
     * 🔹 Enregistrer une nouvelle opération de caisse
     */
    public function store(array $data)
    {
        try {
            // Charger l'opération et sa nature (entrée ou sortie)
            $typeOperation = TypeOperation::find($data['id_type_operation']);

            
            // 🔸 Si c’est une sortie (décaissement), vérifier le solde disponible
            if ($typeOperation->nature === 0) {
                $devise = Devise::find($data['id_devise']);

               

                // Calcul du solde actuel (entrées - sorties) pour cette devise
                $entrees = Caisse::whereHas('typeOperation', function ($q) {
                    $q->where('nature', 'entree');
                })
                ->where('id_devise', $data['id_devise'])
                ->sum('montant');

                $sorties = Caisse::whereHas('typeOperation', function ($q) {
                    $q->where('nature', 'sortie');
                })
                ->where('id_devise', $data['id_devise'])
                ->sum('montant');

                $soldeDisponible = $entrees - $sorties;

                // Vérification du solde avant décaissement
                if ($soldeDisponible < $data['montant']) {
                    return response()->json([
                        'status'  => 400,
                        'message' => "Solde insuffisant pour effectuer ce décaissement.",
                        'data'    => [
                            'solde_disponible' => round($soldeDisponible, 2),
                            'montant_demande'  => round($data['montant'], 2),
                            'devise'           => $devise->symbole ?? '',
                        ],
                    ]);
                }
            }

            // ✅ Si tout est bon, on enregistre l’opération
            $data['created_by'] = Auth::id();
            $caisse = Caisse::create($data);

            return response()->json([
                'status'  => 200,
                'message' => 'Opération de caisse enregistrée avec succès.',
                'data'    => new CaisseResource(
                    $caisse->load(['devise', 'typeOperation', 'createur'])
                ),
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Erreur lors de l’enregistrement de la caisse.',
                'error'   => $e->getMessage(),
            ]);
        }
    }

    /**
     * 🔹 Liste complète des opérations de caisse
     */
    public function getAll()
    {
        try {
            $caisses = Caisse::with(['devise', 'typeOperation', 'createur'])
                ->orderByDesc('created_at')
                ->get();

            return response()->json([
                'status'  => 200,
                'message' => 'Liste des opérations de caisse récupérée avec succès.',
                'data'    => CaisseResource::collection($caisses),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Erreur lors de la récupération des opérations de caisse.',
                'error'   => $e->getMessage(),
            ]);
        }
    }

    /**
     * 🔹 Supprimer une opération de caisse
     */
    public function delete(int $id)
    {
        try {
            $caisse = Caisse::findOrFail($id);
            $caisse->delete();

            return response()->json([
                'status'  => 200,
                'message' => 'Opération de caisse supprimée avec succès.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Erreur lors de la suppression de l’opération de caisse.',
                'error'   => $e->getMessage(),
            ]);
        }
    }
}

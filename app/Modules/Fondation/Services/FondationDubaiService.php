<?php
namespace App\Modules\Fondation\Services;

use App\Modules\Fondation\Models\Fondation;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FondationDubaiService
{
    public function updateCorrections(array $payload)
    {
        DB::beginTransaction();

        try {
            $updated = [];

            foreach ($payload['corrections'] as $item) {
                $fondation = Fondation::with(['expedition.initLivraison'])->find($item['id']);

                if ($fondation) {
                    // ✅ Mise à jour de la fondation
                    $fondation->update([
                        'poids_dubai'  => $item['poids_dubai'],
                        'carrat_dubai' => $item['carrat_dubai'],
                        'statut'       => 'corriger',
                        'modify_by'    => Auth::id(),
                    ]);

                    // ✅ Mise à jour du statut de la livraison liée
                    if ($fondation->expedition && $fondation->expedition->initLivraison) {
                        $fondation->expedition->initLivraison->update([
                            'statut' => 'terminer',
                        ]);
                    }

                    $updated[] = $fondation;
                }
            }

            DB::commit();

            return response()->json([
                'status'  => 200,
                'message' => 'Corrections de Dubaï appliquées avec succès. Les livraisons associées ont été terminées.',
                'total'   => count($updated),
                'data'    => $updated,
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => 500,
                'message' => 'Erreur lors de la mise à jour des corrections de Dubaï.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function updatePureteCorrections(array $payload)
    {
        DB::beginTransaction();

        try {
            $updated = [];

            foreach ($payload['corrections'] as $item) {

                $fondation = Fondation::find($item['id']);

                if ($fondation) {

                    // 🔹 Mise à jour SEULEMENT de p_purete (envoyé dans le payload)
                    $fondation->update([
                        'p_purete'  => $item['p_purete'], // 🔥 valeur envoyée
                        'modify_by' => Auth::id(),
                    ]);

                    $updated[] = $fondation;
                }
            }

            DB::commit();

            return response()->json([
                'status'  => 200,
                'message' => 'Corrections de pureté appliquées avec succès.',
                'total'   => count($updated),
                'data'    => $updated,
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => 500,
                'message' => 'Erreur lors de la mise à jour de la pureté.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

}

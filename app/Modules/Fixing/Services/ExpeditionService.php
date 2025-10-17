<?php

namespace App\Modules\Fixing\Services;

use App\Modules\Fixing\Models\Expedition;
use App\Modules\Fixing\Models\InitLivraison;
use App\Modules\Fondation\Models\Fondation;
use App\Modules\Fixing\Resources\ExpeditionResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class ExpeditionService
{
    /**
     * 🔹 Créer une expédition complète
     * - Génère automatiquement une InitLivraison
     * - Crée plusieurs expéditions liées
     */
    public function store(array $payload)
    {
        DB::beginTransaction();

        try {
            // ✅ Vérification du client
            if (empty($payload['id_client'])) {
                return response()->json([
                    'status'  => 422,
                    'message' => 'Le champ id_client est obligatoire.',
                ], 422);
            }

            // ✅ Vérification du tableau de fondations
            if (empty($payload['id_barre_fondu']) || !is_array($payload['id_barre_fondu'])) {
                return response()->json([
                    'status'  => 422,
                    'message' => 'Le champ id_barre_fondu doit être un tableau d’identifiants de fondations.',
                ], 422);
            }

            // ==========================================
            // 🔹 1️⃣ Création de l’init livraison
            // ==========================================
            $initLivraison = InitLivraison::create([
                'id_client'  => $payload['id_client'],
                'status'     => 'encours',
                'created_by' => Auth::id(),
            ]);

            // ==========================================
            // 🔹 2️⃣ Création des expéditions liées
            // ==========================================
            $resultats = [];

            foreach ($payload['id_barre_fondu'] as $idFondation) {
                $fondation = Fondation::find($idFondation);

                if (!$fondation) {
                    DB::rollBack();
                    return response()->json([
                        'status'  => 404,
                        'message' => "Fondation introuvable (ID: {$idFondation}).",
                    ], 404);
                }

                $expedition = Expedition::create([
                    'id_barre_fondu'    => $idFondation,
                    'id_init_livraison' => $initLivraison->id,
                    'created_by'        => Auth::id(),
                ]);

                $resultats[] = new ExpeditionResource($expedition);
            }

            DB::commit();

            return response()->json([
                'status'  => 201,
                'message' => 'Expédition(s) créée(s) avec succès.',
                'data'    => [
                    'init_livraison' => [
                        'id'        => $initLivraison->id,
                        'reference' => $initLivraison->reference,
                        'id_client' => $initLivraison->id_client,
                        'status'    => $initLivraison->status,
                    ],
                    'expeditions' => $resultats,
                ],
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => 500,
                'message' => 'Erreur lors de la création de l’expédition.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 🔹 Lister toutes les expéditions
     */
    public function getAll()
    {
        try {
            $expeditions = Expedition::with(['fondation', 'initLivraison', 'createur', 'modificateur'])
                ->orderByDesc('id')
                ->get();

            return response()->json([
                'status'  => 200,
                'message' => 'Liste des expéditions récupérée avec succès.',
                'data'    => ExpeditionResource::collection($expeditions),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Erreur lors de la récupération des expéditions.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 🔹 Récupérer une expédition spécifique
     */
    public function getOne(int $id)
    {
        try {
            $expedition = Expedition::with(['fondation', 'initLivraison', 'createur', 'modificateur'])
                ->find($id);

            if (!$expedition) {
                return response()->json([
                    'status'  => 404,
                    'message' => 'Expédition non trouvée.',
                ], 404);
            }

            return response()->json([
                'status'  => 200,
                'message' => 'Expédition récupérée avec succès.',
                'data'    => new ExpeditionResource($expedition),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Erreur lors de la récupération de l’expédition.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 🔹 Supprimer une expédition
     */
    public function delete(int $id)
    {
        try {
            $expedition = Expedition::find($id);

            if (!$expedition) {
                return response()->json([
                    'status'  => 404,
                    'message' => 'Expédition non trouvée.',
                ], 404);
            }

            $expedition->delete();

            return response()->json([
                'status'  => 200,
                'message' => 'Expédition supprimée avec succès.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Erreur lors de la suppression de l’expédition.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}

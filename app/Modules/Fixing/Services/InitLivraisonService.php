<?php
namespace App\Modules\Fixing\Services;

use App\Modules\Fixing\Models\InitLivraison;
use App\Modules\Fixing\Resources\InitLivraisonFixingResource;
use App\Modules\Fixing\Resources\InitLivraisonResource;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InitLivraisonService
{
    /**
     * 🔹 Créer une nouvelle livraison
     */
    public function store(array $data)
    {
        DB::beginTransaction();

        try {
            $data['created_by'] = Auth::id();

            $livraison = InitLivraison::create($data);

            DB::commit();

            return response()->json([
                'status'  => 200,
                'message' => 'Livraison initialisée avec succès.',
                'data'    => new InitLivraisonResource(
                    $livraison->load(['client', 'createur', 'modificateur', 'fondations'])
                ),
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => 500,
                'message' => 'Erreur lors de la création de la livraison.',
                'error'   => $e->getMessage(),
            ]);
        }
    }

    /**
     * 🔹 Récupérer toutes les livraisons
     */
    public function getAll()
    {
        try {
            $livraisons = InitLivraison::with([
                'client',
                'createur',
                'modificateur',
                'fondations',
            ])
                ->orderByDesc('id')
                ->get();

            return response()->json([
                'status'  => 200,
                'message' => 'Liste des livraisons récupérée avec succès.',
                'data'    => InitLivraisonResource::collection($livraisons),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Erreur lors de la récupération des livraisons.',
                'error'   => $e->getMessage(),
            ]);
        }
    }

    /**
     * 🔹 Récupérer une seule livraison
     */
    public function getOne(int $id)
    {
        try {
            $livraison = InitLivraison::with([
                'client',
                'createur',
                'modificateur',
                'fondations',
            ])
                ->find($id);

            if (! $livraison) {
                return response()->json([
                    'status'  => 404,
                    'message' => 'Livraison introuvable.',
                ]);
            }

            return response()->json([
                'status'  => 200,
                'message' => 'Livraison récupérée avec succès.',
                'data'    => new InitLivraisonResource($livraison),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Erreur lors de la récupération de la livraison.',
                'error'   => $e->getMessage(),
            ]);
        }
    }

    /**
     * 🔹 Supprimer une livraison (soft delete)
     */
    public function delete(int $id)
    {
        DB::beginTransaction();

        try {
            $livraison = InitLivraison::find($id);

            if (! $livraison) {
                return response()->json([
                    'status'  => 404,
                    'message' => 'Livraison introuvable.',
                ]);
            }

            $livraison->delete();

            DB::commit();

            return response()->json([
                'status'  => 200,
                'message' => 'Livraison supprimée avec succès.',
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => 500,
                'message' => 'Erreur lors de la suppression de la livraison.',
                'error'   => $e->getMessage(),
            ]);
        }
    }

/**
 * 🔹 Récupérer toutes les livraisons d’un client
 */
   public function getByClient(int $id_client)
{
    try {
        // 🔹 Récupération des livraisons du client
        $livraisons = InitLivraison::with([
                'client',
                'createur',
                'modificateur',
                'fondations',
            ])
            ->where('id_client', $id_client)
            ->orderByDesc('id')
            ->get();

        // 🔥 Filtrer pour ne garder que les livraisons NON totalement fixées
        $livraisonsNonFixees = $livraisons->filter(function ($livraison) {
            $calcul = app(ExpeditionService::class)
            ->calculerPoidsEtCarat($livraison->id);

            return $calcul['poids_fixing'] < $calcul['purete_totale']; // non totalement fixée
        });

        return response()->json([
            'status'  => 200,
            'message' => 'Livraisons non totalement fixées récupérées avec succès.',
            'data'    => InitLivraisonFixingResource::collection($livraisonsNonFixees),
        ]);

    } catch (Exception $e) {
        return response()->json([
            'status'  => 500,
            'message' => 'Erreur lors de la récupération des livraisons du client.',
            'error'   => $e->getMessage(),
        ]);
    }
}


}

<?php

namespace App\Modules\Fondation\Services;

use App\Modules\Fondation\Models\Fondation;
use App\Modules\Fondation\Resources\FondationResource;
use App\Modules\Purchase\Models\Barre;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class FondationService
{
    /**
     * 🔹 Créer une nouvelle fondation (avec gestion des statuts barres).
     */
    public function store(array $data)
    {
        DB::beginTransaction();

        try {
            $ids = $data['ids_barres'];
            $barres = Barre::whereIn('id', $ids)->get();

            if ($barres->isEmpty()) {
                return response()->json([
                    'status'  => 404,
                    'message' => 'Aucune barre trouvée pour la fondation.',
                ]);
            }

            // 🔹 Mise à jour du statut des barres
            if (count($ids) === 1) {
                // Une seule barre → fondue
                Barre::where('id', $ids[0])->update(['status' => 'fondu']);
            } else {
                // Plusieurs barres → fusionner
                Barre::whereIn('id', $ids)->update(['status' => 'fusionner']);
            }

            // 🔹 Création de la fondation
            $data['created_by'] = Auth::id();
            $data['ids_barres'] = implode(',', $ids); // conversion en chaîne

            $fondation = Fondation::create($data);

            DB::commit();

            return response()->json([
                'status'  => 200,
                'message' => 'Fondation créée avec succès.',
                'data'    => new FondationResource($fondation),
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => 500,
                'message' => 'Erreur lors de la création de la fondation.',
                'error'   => $e->getMessage(),
            ]);
        }
    }

    /**
     * 🔹 Récupérer toutes les fondations.
     */
    public function getAll()
    {
        try {
            $fondations = Fondation::orderByDesc('id')->get();

            return response()->json([
                'status'  => 200,
                'message' => 'Liste des fondations récupérée avec succès.',
                'data'    => FondationResource::collection($fondations),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Erreur lors de la récupération des fondations.',
                'error'   => $e->getMessage(),
            ]);
        }
    }

    /**
     * 🔹 Récupérer une seule fondation.
     */
    public function getOne(int $id)
    {
        try {
            $fondation = Fondation::findOrFail($id);

            return response()->json([
                'status'  => 200,
                'message' => 'Fondation récupérée avec succès.',
                'data'    => new FondationResource($fondation),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 404,
                'message' => 'Fondation non trouvée.',
                'error'   => $e->getMessage(),
            ]);
        }
    }

    /**
     * 🔹 Supprimer une fondation (soft delete).
     */
    public function delete(int $id)
    {
        try {
            $fondation = Fondation::findOrFail($id);
            $fondation->delete();

            return response()->json([
                'status'  => 200,
                'message' => 'Fondation supprimée avec succès.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Erreur lors de la suppression de la fondation.',
                'error'   => $e->getMessage(),
            ]);
        }
    }
}

<?php

namespace App\Modules\Fixing\Services;

use App\Modules\Fixing\Models\FixingClient;
use App\Modules\Fixing\Resources\FixingClientResource;
use App\Modules\Fondation\Models\Fondation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class FixingClientService
{
    /**
     * 🔹 Enregistrer un nouveau Fixing Client
     */
    public function store(array $payload)
    {
        DB::beginTransaction();

        try {
            $payload['created_by'] = Auth::id();

            // ✅ Création du fixing client
            $fixing = FixingClient::create($payload);

            // ✅ Mise à jour des fondations associées (si fournies)
            if (!empty($payload['id_barre_fondu']) && is_array($payload['id_barre_fondu'])) {
                Fondation::whereIn('id', $payload['id_barre_fondu'])
                    ->update(['id_fixing' => $fixing->id]);
            }

            DB::commit();

            return response()->json([
                'status'  => 200,
                'message' => 'Fixing client créé avec succès.',
                'data'    => new FixingClientResource(
                    $fixing->load(['client', 'devise', 'fondations', 'createur', 'modificateur'])
                ),
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => 500,
                'message' => 'Erreur lors de la création du fixing client.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 🔹 Récupérer la liste de tous les fixings clients
     */
    public function getAll()
    {
        try {
            $fixings = FixingClient::with(['client', 'devise', 'fondations', 'createur', 'modificateur'])
                ->orderByDesc('id')
                ->get();

            return response()->json([
                'status'  => 200,
                'message' => 'Liste des fixings clients récupérée avec succès.',
                'data'    => FixingClientResource::collection($fixings),
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Erreur lors de la récupération des fixings clients.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 🔹 Récupérer un fixing client spécifique
     */
    public function getOne(int $id)
    {
        try {
            $fixing = FixingClient::with(['client', 'devise', 'fondations', 'createur', 'modificateur'])
                ->find($id);

            if (!$fixing) {
                return response()->json([
                    'status'  => 404,
                    'message' => 'Fixing client introuvable.',
                ], 404);
            }

            return response()->json([
                'status'  => 200,
                'message' => 'Fixing client récupéré avec succès.',
                'data'    => new FixingClientResource($fixing),
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Erreur lors de la récupération du fixing client.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 🔹 Supprimer un fixing client
     */
    public function delete(int $id)
    {
        DB::beginTransaction();

        try {
            $fixing = FixingClient::find($id);

            if (!$fixing) {
                return response()->json([
                    'status'  => 404,
                    'message' => 'Fixing client introuvable.',
                ], 404);
            }

            $fixing->delete();
            DB::commit();

            return response()->json([
                'status'  => 200,
                'message' => 'Fixing client supprimé avec succès.',
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => 500,
                'message' => 'Erreur lors de la suppression du fixing client.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}

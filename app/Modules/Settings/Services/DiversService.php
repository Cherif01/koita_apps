<?php

namespace App\Modules\Settings\Services;

use App\Modules\Comptabilite\Models\OperationDivers;
use App\Modules\Settings\Models\Divers;
use App\Modules\Settings\Resources\DiversResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Exception;

class DiversService
{
    /**
     * 🔹 Créer un nouvel enregistrement Divers
     */
    public function store(array $data)
    {
        try {
            $data['created_by'] = Auth::id();
            $divers = Divers::create($data);

            return response()->json([
                'status'  => 200,
                'message' => 'Enregistrement Divers créé avec succès.',
                'data'    => new DiversResource(
                    $divers->load(['createur', 'modificateur', 'operationsDivers'])
                ),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Erreur lors de la création de l’enregistrement Divers.',
                'error'   => $e->getMessage(),
            ]);
        }
    }

    /**
     * 🔹 Mettre à jour un enregistrement Divers
     */
    public function update(int $id, array $data)
    {
        try {
            $divers = Divers::findOrFail($id);
            $data['updated_by'] = Auth::id();
            $divers->update($data);

            return response()->json([
                'status'  => 200,
                'message' => 'Enregistrement Divers mis à jour avec succès.',
                'data'    => new DiversResource(
                    $divers->load(['createur', 'modificateur', 'operationsDivers'])
                ),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Erreur lors de la mise à jour de l’enregistrement Divers.',
                'error'   => $e->getMessage(),
            ]);
        }
    }

    /**
     * 🔹 Supprimer un enregistrement Divers
     */
    public function delete(int $id)
    {
        try {
            $divers = Divers::findOrFail($id);
            $divers->delete();

            return response()->json([
                'status'  => 200,
                'message' => 'Enregistrement Divers supprimé avec succès.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Erreur lors de la suppression de l’enregistrement Divers.',
                'error'   => $e->getMessage(),
            ]);
        }
    }

    /**
     * 🔹 Récupérer tous les enregistrements Divers
     */
    public function getAll()
    {
        try {
            $divers = Divers::with(['createur', 'modificateur', 'operationsDivers'])
                ->orderByDesc('created_at')
                ->get();

            return response()->json([
                'status'  => 200,
                'message' => 'Liste des enregistrements Divers récupérée avec succès.',
                'data'    => DiversResource::collection($divers),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Erreur lors de la récupération des enregistrements Divers.',
                'error'   => $e->getMessage(),
            ]);
        }
    }

    /**
     * 🔹 Récupérer un enregistrement Divers spécifique
     */
    public function getOne(int $id)
    {
        try {
            $divers = Divers::with(['createur', 'modificateur', 'operationsDivers'])
                ->findOrFail($id);

            return response()->json([
                'status'  => 200,
                'message' => 'Détails du Divers récupérés avec succès.',
                'data'    => new DiversResource($divers),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 404,
                'message' => 'Enregistrement Divers introuvable.',
                'error'   => $e->getMessage(),
            ]);
        }
    }



    public function calculerSoldeDivers(int $id_divers, int $cacheMinutes = 5): array
    {
        return Cache::remember("solde_divers_{$id_divers}", now()->addMinutes($cacheMinutes), function () use ($id_divers) {
            $operations = OperationDivers::with(['typeOperation', 'devise'])
                ->where('id_divers', $id_divers)
                ->get();

            $soldes = [];

            foreach ($operations as $op) {
                $devise = strtoupper($op->devise?->symbole ?? 'GNF');
                $nature = $op->typeOperation?->nature ?? 1;
                $montant = (float) $op->montant;

                if (!isset($soldes[$devise])) $soldes[$devise] = 0;
                $soldes[$devise] += $nature == 1 ? $montant : -$montant;
            }

            return collect($soldes)
                ->mapWithKeys(fn($s, $d) => [strtolower($d) => round($s, 2)])
                ->toArray();
        });
    }
}

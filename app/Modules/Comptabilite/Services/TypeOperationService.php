<?php

namespace App\Modules\Comptabilite\Services;

use App\Modules\Comptabilite\Models\TypeOperation;
use App\Modules\Comptabilite\Http\Resources\TypeOperationResource;
use Illuminate\Support\Facades\Auth;
use Exception;

class TypeOperationService
{
    /**
     * 🔹 Créer un type d’opération
     */
    public function store(array $data)
    {
        try {
            $data['created_by'] = Auth::id();

            $typeOperation = TypeOperation::create($data);

            return response()->json([
                'status'  => 200,
                'message' => 'Type d’opération créé avec succès.',
                'data'    => new TypeOperationResource($typeOperation),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Erreur lors de la création du type d’opération.',
                'error'   => $e->getMessage(),
            ]);
        }
    }

    /**
     * 🔹 Récupérer tous les types d’opérations
     */
    public function getAll()
    {
        try {
            $typeOperations = TypeOperation::with(['createur', 'modificateur'])
                ->orderBy('id', 'desc')
                ->get();

            return response()->json([
                'status' => 200,
                'message' => 'Liste des types d’opérations récupérée avec succès.',
                'data'   => TypeOperationResource::collection($typeOperations),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Erreur lors du chargement des types d’opérations.',
                'error'   => $e->getMessage(),
            ]);
        }
    }

    /**
     * 🔹 Récupérer un type d’opération spécifique
     */
    public function getOne(int $id)
    {
        try {
            $typeOperation = TypeOperation::with(['createur', 'modificateur'])
                ->findOrFail($id);

            return response()->json([
                'status'  => 200,
                'message' => 'Type d’opération trouvé avec succès.',
                'data'    => new TypeOperationResource($typeOperation),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 404,
                'message' => 'Type d’opération introuvable.',
                'error'   => $e->getMessage(),
            ]);
        }
    }

    /**
     * 🔹 Supprimer un type d’opération
     */
    public function delete(int $id)
    {
        try {
            $typeOperation = TypeOperation::findOrFail($id);
            $typeOperation->delete();

            return response()->json([
                'status'  => 200,
                'message' => 'Type d’opération supprimé avec succès.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Erreur lors de la suppression du type d’opération.',
                'error'   => $e->getMessage(),
            ]);
        }
    }
}

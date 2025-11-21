<?php
namespace App\Modules\Comptabilite\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Comptabilite\Requests\StoreTypeOperationRequest;
use App\Modules\Comptabilite\Services\TypeOperationService;

class TypeOperationController extends Controller
{
    protected TypeOperationService $typeOperationService;

    public function __construct(TypeOperationService $typeOperationService)
    {
        $this->typeOperationService = $typeOperationService;
    }

    /**
     * 🔹 Enregistrer un nouveau type d’opération
     */
    public function store(StoreTypeOperationRequest $request)
    {
        return $this->typeOperationService->store($request->validated());
    }

    /**
     * 🔹 Récupérer la liste de tous les types d’opérations
     */
    public function index()
    {
        return $this->typeOperationService->getAll();
    }

    /**
     * 🔹 Récupérer un type d’opération spécifique
     */
    public function show(int $id)
    {
        return $this->typeOperationService->getOne($id);
    }

    /**
     * 🔹 Supprimer un type d’opération
     */
    public function destroy(int $id)
    {
        return $this->typeOperationService->delete($id);
    }
}

<?php

namespace App\Modules\Comptabilite\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Comptabilite\Requests\StoreOperationDiversRequest;
use App\Modules\Comptabilite\Services\OperationDiversService;

class OperationDiversController extends Controller
{
    protected OperationDiversService $operationDiversService;

    public function __construct(OperationDiversService $operationDiversService)
    {
        $this->operationDiversService = $operationDiversService;
    }

    /**
     * 🔹 Enregistrer une nouvelle opération divers
     */
    public function store(StoreOperationDiversRequest $request)
    {
        return $this->operationDiversService->store($request->validated());
    }

    /**
     * 🔹 Mettre à jour une opération divers
     */
    public function update(StoreOperationDiversRequest $request, int $id)
    {
        return $this->operationDiversService->update($id, $request->validated());
    }

    /**
     * 🔹 Supprimer une opération divers
     */
    public function delete(int $id)
    {
        return $this->operationDiversService->delete($id);
    }

    /**
     * 🔹 Récupérer toutes les opérations divers
     */
    public function index()
    {
        return $this->operationDiversService->getAll();
    }

    /**
     * 🔹 Récupérer une opération divers spécifique
     */
    public function show(int $id)
    {
        return $this->operationDiversService->getOne($id);
    }
}

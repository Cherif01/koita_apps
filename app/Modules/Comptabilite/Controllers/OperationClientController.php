<?php

namespace App\Modules\Comptabilite\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Comptabilite\Requests\StoreOperationClientRequest;
use App\Modules\Comptabilite\Services\OperationClientService;

class OperationClientController extends Controller
{
    protected OperationClientService $operationClientService;

    public function __construct(OperationClientService $operationClientService)
    {
        $this->operationClientService = $operationClientService;
    }

    /**
     * 🔹 Enregistrer une nouvelle opération client
     */
    public function store(StoreOperationClientRequest $request)
    {
        return $this->operationClientService->store($request->validated());
    }

    /**
     * 🔹 Mettre à jour une opération client
     */
    public function update(StoreOperationClientRequest $request, int $id)
    {
        return $this->operationClientService->update($id, $request->validated());
    }

    /**
     * 🔹 Supprimer une opération client
     */
    public function delete(int $id)
    {
        return $this->operationClientService->delete($id);
    }

    /**
     * 🔹 Récupérer toutes les opérations clients
     */
    public function index()
    {
        return $this->operationClientService->getAll();
    }

    /**
     * 🔹 Récupérer une opération client spécifique
     */
    public function show(int $id)
    {
        return $this->operationClientService->getOne($id);
    }
}

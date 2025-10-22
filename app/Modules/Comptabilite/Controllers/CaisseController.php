<?php

namespace App\Modules\Comptabilite\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Comptabilite\Requests\StoreCaisseRequest;
use App\Modules\Comptabilite\Services\CaisseService;

class CaisseController extends Controller
{
    protected $service;

    public function __construct(CaisseService $service)
    {
        $this->service = $service;
    }

    /**
     * 🔹 Enregistrer une nouvelle opération de caisse
     */
    public function store(StoreCaisseRequest $request)
    {
        return $this->service->store($request->validated());
    }

    /**
     * 🔹 Lister toutes les opérations de caisse
     */
    public function index()
    {
        return $this->service->getAll();
    }

    /**
     * 🔹 Supprimer une opération de caisse
     */
    public function destroy(int $id)
    {
        return $this->service->delete($id);
    }
}

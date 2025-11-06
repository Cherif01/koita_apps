<?php
namespace App\Modules\Settings\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Settings\Requests\StoreDiversRequest;
use App\Modules\Settings\Services\DiversService;

class DiversController extends Controller
{
    protected DiversService $diversService;

    public function __construct(DiversService $diversService)
    {
        $this->diversService = $diversService;
    }

    public function store(StoreDiversRequest $request)
    {
        return $this->diversService->store($request->validated());
    }

    public function update(StoreDiversRequest $request, int $id)
    {
        return $this->diversService->update($id, $request->validated());
    }

    public function delete(int $id)
    {
        return $this->diversService->delete($id);
    }

    public function index()
    {
        return $this->diversService->getAll();
    }

    public function show(int $id)
    {
        return $this->diversService->getOne($id);
    }
    public function soldeGlobal()
    {
        $resultat = $this->diversService->calculerSoldeGlobalDivers();

        return response()->json([
            'status'  => 200,
            'message' => 'Solde global de tous les comptes divers récupéré avec succès.',
            'data'    => $resultat,
        ], 200);
    }

}

<?php

namespace App\Modules\Purchase\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Purchase\Models\Lot;
use App\Modules\Purchase\Requests\StoreLotRequest;
use App\Modules\Purchase\Resources\LotResource;
use App\Traits\ApiResponses;
use Illuminate\Support\Facades\Auth;

class LotController extends Controller
{
    use ApiResponses;

    public function index()
    {
        $lots = Lot::with('achats', 'createdBy', 'updatedBy')->orderBy('created_at', 'desc')->get();

        return $this->successResponse(LotResource::collection($lots), "Liste de tous les lots disponible.");
    }

    public function show(string $id)
    {
        $lot = Lot::with('achats', 'createdBy', 'updatedBy')->find($id);

        if(! $lot){
            return $this->errorResponse("Lot introuvable");
        }

        return $this->successResponse(new LotResource($lot), "Lot demandé bien chargé.");
    }

    public function status(string $id)
    {
        $lot = Lot::find($id);

        if(! $lot){
            return $this->errorResponse("Lot introuvable");
        }

        $lot->status = ($lot->status == 'encours') ? "terminer" : "encours";
        $lot->save();

        return $this->deleteSuccessResponse("Status du lot mis a jour avec succès.");
    }

    public function store(StoreLotRequest $request)
    {
        $fields = $request->validated();
        $fields['created_by'] = Auth::id();

        $lot = Lot::create($fields);

        return $this->successResponse($lot, "Lot créer avec succès.");
    }

    public function update(StoreLotRequest $request, string $id)
    {
        $lot = Lot::find($id);

        if(! $lot){
            return $this->errorResponse("Lot introuvable");
        }

        $fields = $request->validated();
        $fields['updated_by'] = Auth::id();

        $lot->update($fields);

        return $this->successResponse($lot, "Lot mis a jour avec succès");
    }

    public function destroy(string $id)
    {
        $lot = Lot::find($id);

        if(! $lot){
            return $this->errorResponse("Lot introuvable");
        }

        $lot->delete();

        return $this->deleteSuccessResponse("Lot déplacé vers la corbeille avec succès.");
    }

    public function restore(string $id)
    {
        $lot = Lot::withTrashed()->find($id);

        if(! $lot){
            return $this->errorResponse("Lot introuvable");
        }

        $lot->restore();

        return $this->deleteSuccessResponse("Lot restoré avec succès.");
    }

    public function forceDelete(string $id)
    {
        $lot = Lot::withTrashed()->find($id);

        if(! $lot){
            return $this->errorResponse("Lot introuvable");
        }

        $lot->forceDelete();

        return $this->deleteSuccessResponse("Lot supprimé définitivement avec succès.");
    }
}

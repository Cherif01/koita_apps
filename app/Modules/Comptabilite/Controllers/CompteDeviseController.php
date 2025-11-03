<?php

namespace App\Modules\Comptabilite\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Comptabilite\Models\CompteDevise;
use App\Modules\Comptabilite\Requests\CompteDeviseRequest;
use App\Modules\Settings\Models\Devise;
use App\Traits\ApiResponses;
use Illuminate\Support\Facades\Auth;

class CompteDeviseController extends Controller
{
    use ApiResponses;

    public function index()
    {
        // Code here...
    }

    public function show(string $id)
    {
        // Code here...
    }

    public function comptes(string $id)
    {
        $devise = Devise::find($id);

        if(! $devise){
            return $this->errorResponse("Devise introuvable");
        }

        $compte_devises = CompteDevise::with('devise', 'compte')->where('devise_id', $devise->id)->get();

        if(! $compte_devises){
            return $this->errorResponse("Cette devise n'est lié a aucun compte.");
        }

        return $this->successResponse($compte_devises, "Liste de tous les compte lié a la devise ont été bien chargé.");
    }

    public function store(CompteDeviseRequest $request)
    {
        $fields = $request->validated();

        $cpt_devise = CompteDevise::where('compte_id', $fields['compte_id'])->where('devise_id', $fields['devise_id'])->first();

        if($cpt_devise){
            return $this->errorResponse("Ce compte est déjà affecté a cette devise");
        }

        $fields['solde_courant'] = $fields['solde_initial'];
        $fields['created_by'] = Auth::id();

        $compte_devise = CompteDevise::create($fields);

        return $this->successResponse($compte_devise, "Liaison du compte a la devise éffectué avec succès.");
    }

    public function update(CompteDeviseRequest $request, string $id)
    {
        $compte_devise = CompteDevise::find($id);

        if(! $compte_devise){
            return $this->errorResponse("Liaison de compte a devise est introuvable.");
        }

        $fields = $request->validated();
        $cpt_devise = CompteDevise::where('id', '!=', $id)->where('compte_id', $fields['compte_id'])->where('devise_id', $fields['devise_id'])->first();

        if($cpt_devise){
            return $this->errorResponse("Ce compte est déjà affecté a cette devise");
        }

        $fields['updated_by'] = Auth::id();

        $compte_devise->update($fields);

        return $this->successResponse($compte_devise, "Liaison de compte a devise mise a jou avec succès.");
    }

    public function destroy(string $id)
    {
        $compte_devise = CompteDevise::find($id);

        if(! $compte_devise){
            return $this->errorResponse("Liaison de compte a devise est introuvable.");
        }

        $compte_devise->delete();

        return $this->deleteSuccessResponse("Liaison de compte et devise supprimé avec succès.");
    }
}

<?php

namespace App\Modules\Purchase\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BarreResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'poid_pure' => $this->poid_pure,
            'carrat_pure' => $this->carrat_pure,
            'densite' => $this->densite,
            'status' => $this->status,
            'is_fixed' => $this->is_fixed,

            'achat' => $this->achat ? new AchatResource($this->achat) : null,

            'createdBy' => $this->createdBy ? [
                'id' => $this->createdBy->id,
                'name' => $this->createdBy->name,
                'email' => $this->createdBy->email ?? null,
                'telephone' => $this->createdBy->telephone ?? null,
                'adresse' => $this->createdBy->adresse ?? null,
                'role' => $this->createdBy->role ?? null,
            ] : null,

            'updatedBy' => $this->updatedBy ? [
                'id' => $this->updatedBy->id,
                'name' => $this->updatedBy->name,
                'email' => $this->updatedBy->email ?? null,
                'telephone' => $this->updatedBy->telephone ?? null,
                'adresse' => $this->updatedBy->adresse ?? null,
                'role' => $this->updatedBy->role ?? null,
            ] : null,

            'created_at' => optional($this->created_at)->format('d-m-Y H:i:s'),
            'updated_at' => optional($this->updated_at)->format('d-m-Y H:i:s'),
        ];
    }
}

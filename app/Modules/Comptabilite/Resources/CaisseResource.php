<?php

namespace App\Modules\Comptabilite\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Settings\Resources\DeviseResource;
use App\Modules\Comptabilite\Resources\TypeOperationResource;

class CaisseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return array_filter([
            'id'               => $this->id,
            'reference'        => $this->reference,
            'date_operation'   => $this->date_operation?->format('Y-m-d'),
            'montant'          => (float) $this->montant,
            'commentaire'      => $this->commentaire,
            'type_operation'   => new TypeOperationResource($this->whenLoaded('typeOperation')),
            'devise'           => new DeviseResource($this->whenLoaded('devise')),
            'created_by'       => $this->createur?->name,
            'updated_by'       => $this->modificateur?->name,
            'created_at'       => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'       => $this->updated_at?->format('Y-m-d H:i:s'),
        ], fn($value) => !is_null($value));
    }
}

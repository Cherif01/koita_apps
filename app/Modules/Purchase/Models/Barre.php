<?php

namespace App\Modules\Purchase\Models;

use App\Modules\Administration\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barre extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'achat_id',
        'poid_pure',
        'carrat_pure',
        'densite',
        'status',
        'is_fixed',
        'created_by',
        'updated_by'
    ];

    public function achat() : BelongsTo
    {
        return $this->belongsTo(Achat::class)->whereNull('achats.deleted_at');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}

<?php

namespace App\Modules\Fixing\Models;

use App\Modules\Administration\Models\User;
use App\Modules\Settings\Models\Client;
use App\Modules\Settings\Models\Devise;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FixingClient extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fixing_clients';

    protected $fillable = [
        'id_client',
        'id_devise',
        'poids_pro',
        'carrat_moyen',
        'discompte',
        'bourse',
        'prix_unitaire',
        'status',
        'created_by',
        'updated_by',
    ];

    // ===============================
    // 🔹 RELATIONS
    // ===============================

    public function client()
    {
        return $this->belongsTo(Client::class, 'id_client');
    }

    public function devise()
    {
        return $this->belongsTo(Devise::class, 'id_devise');
    }

    public function createur()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function modificateur()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // ===============================
    // 🔹 SCOPES
    // ===============================

    public function scopeVendus($query)
    {
        return $query->where('status', 'vendu');
    }

    public function scopeProvisoires($query)
    {
        return $query->where('status', 'provisoire');
    }

    // ===============================
    // 🔹 LOGIQUE AUTOMATIQUE DU STATUT
    // ===============================

    protected static function booted()
    {
        static::saving(function ($fixing) {
            // Si le prix_unitaire ou le discompte est absent → fixing provisoire
            if (is_null($fixing->prix_unitaire) || is_null($fixing->discompte)) {
                $fixing->status = 'provisoire';
            } else {
                $fixing->status = 'vendu';
            }
        });
    }
}

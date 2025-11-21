<?php

namespace App\Modules\Settings\Models;

use App\Modules\Administration\Models\User;
use App\Modules\Comptabilite\Models\OperationDivers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Divers extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'divers';

    protected $fillable = [
        'name',
        'telephone',
        'raison_sociale',
        'adresse',
        'type',
        'created_by',
        'updated_by',
    ];

    // ==============================
    // 🔹 RELATIONS
    // ==============================

    /**
     * Utilisateur ayant créé l’entrée Divers
     */
    public function createur()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Utilisateur ayant modifié l’entrée Divers
     */
    public function modificateur()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * 🔹 Liste des opérations associées à cet élément Divers
     */
    public function operationsDivers()
    {
        return $this->hasMany(OperationDivers::class, 'id_divers');
    }

}

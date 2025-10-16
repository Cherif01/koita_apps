<?php

namespace App\Modules\Comptabilite\Models;

use App\Modules\Administration\Models\Fournisseur;
use App\Modules\Administration\Models\User;
use App\Modules\Settings\Models\{
    Banque,
    Partenaire,
    Client,
    Devise,
    Monetaire
};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Operation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'operations';

    protected $fillable = [
        'id_fournisseur',
        'id_partenaire',
        'id_client',
        'id_banque',
        'id_monetaire',
        'id_type_operation',
        'id_devise',
        'montant',
        'commentaire',
        'created_by',
        'modify_by',
    ];

    // ==============================
    // 🔹 RELATIONS PRINCIPALES
    // ==============================

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class, 'id_fournisseur');
    }

    public function partenaire()
    {
        return $this->belongsTo(Partenaire::class, 'id_partenaire');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'id_client');
    }

    public function banque()
    {
        return $this->belongsTo(Banque::class, 'id_banque');
    }

    public function monetaire()
    {
        return $this->belongsTo(Monetaire::class, 'id_monetaire');
    }

    public function typeOperation()
    {
        return $this->belongsTo(TypeOperation::class, 'id_type_operation');
    }

    public function devise()
    {
        return $this->belongsTo(Devise::class, 'id_devise');
    }

    // ==============================
    // 🔹 RELATIONS D’AUDIT
    // ==============================

    public function createur()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function modificateur()
    {
        return $this->belongsTo(User::class, 'modify_by');
    }

    // ==============================
    // 🔹 ACCESSORS MODERNES (Laravel 12)
    // ==============================

    /**
     * 🔹 Formater le montant avec deux décimales.
     */
    protected function montantFormate(): Attribute
    {
        return Attribute::make(
            get: fn () => number_format($this->montant, 2, ',', ' ')
        );
    }
}

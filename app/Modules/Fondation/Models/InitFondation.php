<?php

namespace App\Modules\Fondation\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Administration\Models\User;
use Illuminate\Support\Str;

class InitFondation extends Model
{
    use HasFactory;

    protected $table = 'init_fondations';

    protected $fillable = [
        'reference',
        'created_by',
        'modify_by',
    ];

    // ==============================
    // 🔹 RELATIONS
    // ==============================

    public function createur()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function modificateur()
    {
        return $this->belongsTo(User::class, 'modify_by');
    }

    public function fondations()
    {
        return $this->hasMany(Fondation::class, 'id_init_fondation');
    }

    // ==============================
    // 🔹 GÉNÉRATION AUTO DE LA RÉFÉRENCE
    // ==============================

    protected static function booted()
    {
        static::creating(function ($initFondation) {
            // Générer uniquement si la référence n'est pas fournie
            if (empty($initFondation->reference)) {
                $initFondation->reference = self::generateUniqueReference();
            }
        });
    }

    /**
     * Génère une référence unique du type : FND-20251016-0001
     */
    public static function generateUniqueReference(): string
    {
        $prefix = 'FND-' . now()->format('Ymd') . '-';
        $lastId = self::max('id') + 1;
        return $prefix . str_pad($lastId, 4, '0', STR_PAD_LEFT);
    }
}

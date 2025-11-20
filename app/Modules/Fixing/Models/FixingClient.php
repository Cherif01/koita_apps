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
        // 'id_init_livraison',  // supprimé dans migration
        'reference',
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
    // 🔥 LOGIQUE AUTOMATIQUE :
    //    Référence + Statut Fixing
    // ===============================

    protected static function booted()
    {
        static::creating(function ($fixing) {

            // =======================================================
            // 📌 Génération automatique de la référence
            // =======================================================
            $lastId            = self::withTrashed()->max('id') ?? 0;
            $fixing->reference = 'FIX-' . str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);

            // =======================================================
            // 📌 Détermination automatique du statut
            // =======================================================

            $client = Client::find($fixing->id_client);

            if ($client) {

                // =============================
                // 🔵 CLIENT EXTRA
                // statut dépend de bourse
                // =============================
                if (strtolower($client->type_client) === 'extrat') {

                    if (is_null($fixing->bourse)) {
                        $fixing->status = 'provisoire';
                    } else {
                        $fixing->status = 'vendu';
                    }

                } else {

                    // =============================
                    // 🟢 CLIENT LOCAL
                    // statut dépend de discompte
                    // =============================
                    if (is_null($fixing->discompte)) {
                        $fixing->status = 'provisoire';
                    } else {
                        $fixing->status = 'vendu';
                    }
                }
            }
        });

        static::saving(function ($fixing) {

            $client = Client::find($fixing->id_client);

            if ($client) {

                // =============================
                // 🔵 CLIENT EXTRA
                // =============================
                if (strtolower($client->type_client) === 'extrat') {

                    if (is_null($fixing->bourse)) {
                        $fixing->status = 'provisoire';
                    } else {
                        $fixing->status = 'vendu';
                    }

                } else {

                    // =============================
                    // 🟢 CLIENT LOCAL
                    // =============================
                    if (is_null($fixing->discompte)) {
                        $fixing->status = 'provisoire';
                    } else {
                        $fixing->status = 'vendu';
                    }
                }
            }
        });
    }
}

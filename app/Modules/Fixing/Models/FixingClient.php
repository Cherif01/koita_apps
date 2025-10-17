<?php

namespace App\Modules\Fixing\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Settings\Models\Client;
use App\Modules\Settings\Models\Devise;
use App\Modules\Administration\Models\User;
use App\Modules\Fondation\Models\Fondation;

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

    public function fondations()
    {
        return $this->hasMany(Fondation::class, 'id_fixing');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécution de la migration.
     */
    public function up(): void
    {
        Schema::table('fondations', function (Blueprint $table) {
            // ✅ Renommer les colonnes
            $table->renameColumn('poid_fondu', 'poids_fondu');
            $table->renameColumn('carrat_moyen', 'carrat_fondu');
        });
    }

    /**
     * Annulation de la migration.
     */
    public function down(): void
    {
        Schema::table('fondations', function (Blueprint $table) {
            // 🔁 Revenir en arrière si nécessaire
            $table->renameColumn('poids_fondu', 'poid_fondu');
            $table->renameColumn('carrat_fondu', 'carrat_moyen');
        });
    }
};

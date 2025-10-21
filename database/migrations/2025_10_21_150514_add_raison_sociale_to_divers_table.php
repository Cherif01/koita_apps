<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécuter la migration : ajouter le champ `raison_sociale` à la table `divers`.
     */
    public function up(): void
    {
        Schema::table('divers', function (Blueprint $table) {
            // 🔹 On ajoute le champ après 'name' pour garder un ordre logique
            $table->string('raison_sociale', 150)->nullable()->after('name');
        });
    }

    /**
     * Annuler la migration : supprimer le champ `raison_sociale`.
     */
    public function down(): void
    {
        Schema::table('divers', function (Blueprint $table) {
            $table->dropColumn('raison_sociale');
        });
    }
};

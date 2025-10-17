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
            // 🔹 Ajout de la clé étrangère vers init_fondations
            $table->foreignId('id_init_fondation')
                ->nullable()
                ->constrained('init_fondations')
                ->cascadeOnDelete();
        });
    }

    /**
     * Annulation de la migration.
     */
    public function down(): void
    {
        Schema::table('fondations', function (Blueprint $table) {
            // 🔁 Suppression propre de la contrainte et de la colonne
            $table->dropForeign(['id_init_fondation']);
            $table->dropColumn('id_init_fondation');
        });
    }
};

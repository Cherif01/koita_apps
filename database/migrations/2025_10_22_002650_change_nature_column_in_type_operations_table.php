<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('type_operations', function (Blueprint $table) {
            // On supprime l'ancienne colonne si elle existe déjà
            if (Schema::hasColumn('type_operations', 'nature')) {
                $table->dropColumn('nature');
            }

            // 🔹 Nouvelle colonne : nature (0 = sortie, 1 = entrée)
            $table->tinyInteger('nature')
                  ->default(0)
                  ->comment('0 = sortie, 1 = entrée')
                  ->after('libelle');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('type_operations', function (Blueprint $table) {
            // Retour à l’ancienne définition enum
            $table->enum('nature', ['entree', 'sortie'])
                  ->default('entree')
                  ->change();
        });
    }
};

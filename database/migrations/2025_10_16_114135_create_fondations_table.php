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
        Schema::create('fondations', function (Blueprint $table) {
            $table->id();

            // 🔹 Liste des barres fondues (IDs séparés par des virgules)
            $table->text('ids_barres')->nullable();

            // 🔹 Informations de la fonte locale
            $table->decimal('poid_fondu', 10, 5)->default(0.00);
            $table->decimal('carrat_moyen', 10, 5)->default(0.00);
            
            // 🔹 Informations après évaluation à Dubaï
            $table->decimal('poids_dubai', 10, 5)->default(0.00);
            $table->decimal('carrat_dubai', 10, 5)->default(0.00);

            // 🔹 Indicateur de fixation
            $table->boolean('is_fixed')->default(false);
            
            // 🔹 Champs d’audit
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('modify_by')->nullable()->constrained('users')->onDelete('set null');

            // 🔹 Suivi du temps et suppression logique
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Annulation de la migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('fondations');
    }
};

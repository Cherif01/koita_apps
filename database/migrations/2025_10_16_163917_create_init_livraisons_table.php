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
        Schema::create('init_livraisons', function (Blueprint $table) {
            $table->id();

            // 🔹 Référence unique de la livraison
            $table->string('reference', 100)->unique();

            // 🔹 Client concerné
            $table->foreignId('id_client')
                ->constrained('clients')
                ->cascadeOnDelete();

            // 🔹 Commentaire optionnel
            $table->text('commentaire')->nullable();

            // 🔹 Statut de la livraison
            $table->enum('statut', ['encours', 'terminer'])->default('encours');

            // 🔹 Colonnes d’audit
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('modify_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // 🔹 Dates de création / mise à jour / suppression logique
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Annulation de la migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('init_livraisons');
    }
};

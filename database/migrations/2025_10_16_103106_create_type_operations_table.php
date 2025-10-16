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
        Schema::create('type_operations', function (Blueprint $table) {
            $table->id();

            // 🔹 Champs principaux
            $table->string('libelle'); // ex : Achat, Vente, Dépôt, etc.
            $table->enum('nature', ['entree', 'sortie'])->default('entree');

            // 🔹 Champs d’audit
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->foreignId('modify_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            // 🔹 Timestamps + soft delete
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Annulation de la migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('type_operations');
    }
};

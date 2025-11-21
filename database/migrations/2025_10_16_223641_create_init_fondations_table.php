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
        Schema::create('init_fondations', function (Blueprint $table) {
            $table->id();

            // 🔹 Référence unique mais nullable
            $table->string('reference', 100)->unique()->nullable();

            // 🔹 Colonnes d’audit
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('modify_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // 🔹 Dates de création et de mise à jour
            $table->timestamps();
        });
    }

    /**
     * Annulation de la migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('init_fondations');
    }
};

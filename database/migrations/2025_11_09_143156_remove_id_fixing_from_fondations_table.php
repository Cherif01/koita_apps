<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fondations', function (Blueprint $table) {
            // Vérifie d’abord si la colonne existe avant de la supprimer
            if (Schema::hasColumn('fondations', 'id_fixing')) {
                $table->dropForeign(['id_fixing']); // 🔹 Supprime la contrainte
                $table->dropColumn('id_fixing');    // 🔹 Supprime la colonne
            }
        });
    }

    public function down(): void
    {
        Schema::table('fondations', function (Blueprint $table) {
            // 🔹 En cas de rollback, on recrée la colonne et la clé étrangère
            $table->foreignId('id_fixing')
                ->nullable()
                ->constrained('fixing_clients')
                ->cascadeOnDelete();
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fondations', function (Blueprint $table) {
            // 🔹 Ajout de la clé étrangère vers fixing_clients (nullable)
            $table->foreignId('id_fixing')
                ->nullable()
                ->constrained('fixing_clients')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('fondations', function (Blueprint $table) {
            $table->dropForeign(['id_fixing']);
            $table->dropColumn('id_fixing');
        });
    }
};

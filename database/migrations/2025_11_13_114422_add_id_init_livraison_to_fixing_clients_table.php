<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::table('fixing_clients', function (Blueprint $table) {

            $table->foreignId('id_init_livraison')
                  ->nullable()
                  ->constrained('init_livraisons')
                  ->cascadeOnDelete();   // 🔥 Comme tu l’as demandé
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fixing_clients', function (Blueprint $table) {
            $table->dropForeign(['id_init_livraison']);
            $table->dropColumn('id_init_livraison');
        });
    }
};

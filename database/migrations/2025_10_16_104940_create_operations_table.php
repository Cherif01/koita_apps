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
        Schema::create('operations', function (Blueprint $table) {
            $table->id();

            // 🔹 Relations principales (en cascade)
            $table->foreignId('id_fournisseur')->nullable()->constrained('fournisseurs')->onDelete('cascade');
            $table->foreignId('id_partenaire')->nullable()->constrained('partenaires')->onDelete('cascade');
            $table->foreignId('id_client')->nullable()->constrained('clients')->onDelete('cascade');
            $table->foreignId('id_banque')->nullable()->constrained('banques')->onDelete('cascade');
            $table->foreignId('id_monetaire')->nullable()->constrained('monetaires')->onDelete('cascade');
            $table->foreignId('id_type_operation')->nullable()->constrained('type_operations')->onDelete('cascade');
            $table->foreignId('id_devise')->nullable()->constrained('devises')->onDelete('cascade');

            // 🔹 Champs spécifiques
            $table->decimal('montant', 15, 2)->default(0);
            $table->text('commentaire')->nullable();

            // 🔹 Champs d’audit
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('modify_by')->nullable()->constrained('users')->onDelete('cascade');

            // 🔹 Timestamps et soft delete
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Annulation de la migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('operations');
    }
};

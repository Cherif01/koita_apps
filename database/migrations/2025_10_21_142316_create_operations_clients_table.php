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
        Schema::create('operations_clients', function (Blueprint $table) {
            $table->id();

            // 🔹 Référence vers le client concerné
            $table->foreignId('id_client')
                ->constrained('clients')
                ->cascadeOnDelete();

            // 🔹 Type d’opération (achat, paiement, remboursement, etc.)
            $table->foreignId('id_type_operation')
                ->constrained('type_operations')
                ->cascadeOnDelete();

            // 🔹 Devise utilisée
            $table->foreignId('id_devise')
                ->constrained('devises')
                ->cascadeOnDelete();

            // 🔹 Montant de l’opération
            $table->decimal('montant', 15, 5)->default(0);

            // 🔹 Commentaire ou description
            $table->string('commentaire', 255)->nullable();

            // 🔹 Audit (créateur / modificateur)
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // 🔹 Timestamps + corbeille
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operations_clients');
    }
};

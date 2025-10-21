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
        Schema::create('operations_divers', function (Blueprint $table) {
            $table->id();

            // 🔹 Type d’opération (ex : versement, retrait, etc.)
            $table->foreignId('id_type_operation')
                ->constrained('type_operations')
                ->cascadeOnDelete();

            // 🔹 Référence vers la table divers
            $table->foreignId('id_divers')
                ->nullable()
                ->constrained('divers')
                ->nullOnDelete();

            // 🔹 Devise utilisée
            $table->foreignId('id_devise')
                ->constrained('devises')
                ->cascadeOnDelete();

            // 🔹 Montant de l’opération
            $table->decimal('montant', 15, 2)->default(0);

            // 🔹 Commentaire ou description
            $table->string('commentaire', 255)->nullable();

            // 🔹 Audit
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
        Schema::dropIfExists('operations_divers');
    }
};

<?php

use App\Modules\Administration\Models\Fournisseur;
use App\Modules\Purchase\Models\Lot;
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
        Schema::create('achats', function (Blueprint $table) {
            $table->id();
            $table->string('reference', length: 100)->unique();
            $table->foreignIdFor(Fournisseur::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Lot::class)->constrained()->cascadeOnDelete();
            $table->text('commentaire')->nullable();
            $table->enum('status', ['encours', 'terminer'])->default('encours');
            $table->foreignId('created_by')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achats');
    }
};

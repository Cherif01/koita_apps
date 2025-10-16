<?php

use App\Modules\Purchase\Models\Achat;
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
        Schema::create('barres', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Achat::class)->constrained()->cascadeOnDelete();
            $table->decimal('poid_pure', 10, 5)->default(0.00);
            $table->decimal('carrat_pure', 10, 5)->default(0.00);
            $table->decimal('densite', 10, 5)->default(22);
            $table->enum('status', ['non fondue', 'fondue', 'fusionner'])->default('non fondue');
            $table->boolean('is_fixed')->default(false);
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
        Schema::dropIfExists('barres');
    }
};

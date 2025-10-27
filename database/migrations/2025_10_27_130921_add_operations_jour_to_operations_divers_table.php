<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('operations_divers', function (Blueprint $table) {
            $table->decimal('taux_jour', 10, 4)
                ->default(1.0000)
                ->after('montant');
        });

    }

    public function down(): void
    {
        Schema::table('operations_divers', function (Blueprint $table) {
            $table->dropColumn('taux_jour');
        });
    }
};

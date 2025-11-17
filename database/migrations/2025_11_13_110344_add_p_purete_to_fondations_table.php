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
        Schema::table('fondations', function (Blueprint $table) {
            $table->decimal('p_purete', 6, 4)
                  ->default(0)
                  ->after('carrat_fondu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fondations', function (Blueprint $table) {
            $table->dropColumn('p_purete');
        });
    }
};

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
        Schema::table('operations_clients', function (Blueprint $table) {
            $table->string('reference', 100)->nullable()->after('id_client');
            $table->date('date_operation')->nullable()->after('reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operations_clients', function (Blueprint $table) {
            $table->dropColumn(['reference', 'date_operation']);
        });
    }
};

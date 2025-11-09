<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fixing_clients', function (Blueprint $table) {
            $table->enum('status', ['provisoire', 'vendu'])
                  ->default('provisoire')
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('fixing_clients', function (Blueprint $table) {
            $table->enum('status', ['en attente', 'confirmer', 'valider'])
                  ->default('en attente')
                  ->change();
        });
    }
};

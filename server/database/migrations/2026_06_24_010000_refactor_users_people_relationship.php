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
        // 1. Agregar person_id a users
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('person_id')->nullable()->constrained('people')->onDelete('cascade');
        });

        // 2. Eliminar user_id de people (después de ajustar los datos si fuera necesario)
        Schema::table('people', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // Eliminar restricción FK primero
            $table->dropColumn('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir (en caso de rollback)
        Schema::table('people', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['person_id']);
            $table->dropColumn('person_id');
        });
    }
};

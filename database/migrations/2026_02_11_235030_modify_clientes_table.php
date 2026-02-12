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
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('cliente_telefono')->nullable()->change();
            $table->string('cliente_email')->nullable()->change();
            $table->string('cliente_direccion')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('cliente_telefono')->nullable(false)->change();
            $table->string('cliente_email')->nullable(false)->change();
            $table->string('cliente_direccion')->nullable(false)->change();
        });
    }
};

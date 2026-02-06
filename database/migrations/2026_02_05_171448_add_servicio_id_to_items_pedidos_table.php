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
        Schema::table('items_pedidos', function (Blueprint $table) {
            // Nullable por si borras el servicio original de la base de datos,
            // el historial del pedido no se rompa.
            $table->foreignId('servicio_id')->nullable()->constrained('services')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items_pedidos', function (Blueprint $table) {
            //
        });
    }
};

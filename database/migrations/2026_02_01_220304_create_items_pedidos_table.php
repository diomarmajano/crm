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
        Schema::create('items_pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('pedido_id')->nullable()->constrained('pedidos')->cascadeOnDelete();
            $table->string('nombre_servicio'); // Por si borras el servicio en el futuro, queda el registro
            $table->integer('cantidad')->default(1);
            $table->decimal('precio_unitario', 10, 2); // El precio AL MOMENTO de la venta
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items_pedidos');
    }
};

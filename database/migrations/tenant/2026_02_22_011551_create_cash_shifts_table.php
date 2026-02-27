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
        Schema::create('cash_shifts', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('tenant_id')->nullable()->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('user_id')->nullable();

            $table->enum('estado', ['abierta', 'cerrada'])->default('abierta');
            $table->decimal('saldo_inicial', 10, 2)->default(0);
            $table->decimal('total_ingresos', 10, 2)->default(0);
            $table->decimal('total_egresos', 10, 2)->default(0);
            $table->decimal('saldo_esperado', 10, 2)->default(0);

            $table->decimal('saldo_fisico', 10, 2)->nullable();
            $table->decimal('diferencia', 10, 2)->nullable();

            $table->timestamp('fecha_apertura')->useCurrent();
            $table->timestamp('fecha_cierre')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_shifts');
    }
};

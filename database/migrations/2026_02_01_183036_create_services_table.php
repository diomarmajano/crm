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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('id_category')->nullable()->constrained('category')->cascadeOnDelete();
            $table->boolean('is_active')->nullable();
            $table->string('sku')->nullable()->unique();
            $table->string('codigo')->nullable();
            $table->string('service_name', 50)->nullable();
            $table->string('detalles')->nullable();
            $table->decimal('service_precio', 10, 2)->nullable();
            $table->decimal('precio_promocion', 10, 2)->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->longText('service_icon')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};

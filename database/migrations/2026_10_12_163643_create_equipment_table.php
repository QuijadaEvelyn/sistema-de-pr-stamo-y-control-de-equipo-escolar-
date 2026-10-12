<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                ->constrained('categories')
                ->restrictOnDelete();

            $table->string('name', 150);

            $table->string('code', 50)->unique();

            $table->text('description')->nullable();

            $table->unsignedInteger('total_quantity');

            $table->unsignedInteger('available_quantity');

            $table->enum('status', [
                'activo',
                'mantenimiento',
                'baja'
            ])->default('activo');

            $table->timestamps();

            $table->index(['category_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
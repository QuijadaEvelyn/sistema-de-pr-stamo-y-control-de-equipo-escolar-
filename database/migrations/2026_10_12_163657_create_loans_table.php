<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('handled_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->date('start_date');

            $table->date('due_date');

            $table->enum('status', [
                'pendiente',
                'aprobado',
                'rechazado',
                'entregado',
                'devuelto'
            ])->default('pendiente');

            $table->text('notes')->nullable();

            $table->timestamp('delivered_at')->nullable();

            $table->timestamp('returned_at')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
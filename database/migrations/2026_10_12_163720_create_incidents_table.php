<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('equipment_id')
                ->constrained('equipment')
                ->restrictOnDelete();

            $table->foreignId('loan_id')
                ->nullable()
                ->constrained('loans')
                ->nullOnDelete();

            $table->string('title', 150);

            $table->text('description');

            $table->enum('status', [
                'abierta',
                'en_proceso',
                'resuelta'
            ])->default('abierta');

            $table->text('resolution')->nullable();

            $table->timestamps();

            $table->index(['equipment_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
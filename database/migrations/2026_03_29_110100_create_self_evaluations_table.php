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
        Schema::create('self_evaluations', function (Blueprint $table) {
            $table->id('evaluation_id');
            $table->foreignId('user_id')
                  ->constrained('users', 'user_id')
                  ->onDelete('cascade');
            $table->enum('period_type', ['day', 'week', 'month', 'year']);
            $table->date('period_date'); 
            $table->tinyInteger('score')->unsigned(); 
            $table->text('comment')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'period_type', 'period_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('self_evaluations');
    }
};

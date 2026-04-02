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
        Schema::create('habit_tracking', function (Blueprint $table) {
            $table->id('tracking_id');
            $table->foreignId('habit_id')
                  ->constrained('habits', 'habit_id')
                  ->onDelete('cascade');
            $table->date('date');
            $table->boolean('completed')->default(false);
            $table->text('note')->nullable(); 
            $table->timestamps();
            $table->unique(['habit_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habit_tracking');
    }
};

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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id('task_id');
            $table->foreignId('user_id')
                  ->constrained('users', 'user_id')
                  ->onDelete('cascade');
            $table->foreignId('goal_id')
                  ->nullable()
                  ->constrained('goals', 'goal_id')
                  ->onDelete('set null');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('priority', ['low' , 'medium' , 'high'])->default('medium');
            $table->enum('status', ['todo' , 'in_progress' , 'done' ,'cancelled'])->default('todo');
            $table->enum('period' , ['day' , 'week' , 'month' ,'year'])->default('day');
            $table->json('repeat_days')->nullable();
            $table->date('due_date')->nullable();
            $table->date('scheduled_date')->nullable();
            $table->time('scheduled_time')->nullable();
            $table->dateTime('remind_at')->nullable();
            $table->boolean('reminder_sent')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn([
                'scheduled_date',
                'scheduled_time',
                'remind_at',
                'reminder_sent',
            ]);
        });
    }
};

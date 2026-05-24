<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('todos', function (Blueprint $table) {
            $table->boolean('is_recurring')->default(false)->after('completed');
        });

        Schema::create('todo_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('todo_id')->constrained()->cascadeOnDelete();
            $table->date('completed_date');
            $table->timestamps();

            $table->unique(['todo_id', 'completed_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('todo_logs');
        Schema::table('todos', function (Blueprint $table) {
            $table->dropColumn('is_recurring');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'user'])->default('user')->after('email');
        });

        Schema::table('todos', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete()->after('id');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('todos', fn($t) => $t->dropConstrainedForeignId('user_id'));
        Schema::table('categories', fn($t) => $t->dropConstrainedForeignId('user_id'));
        Schema::table('users', fn($t) => $t->dropColumn('role'));
    }
};

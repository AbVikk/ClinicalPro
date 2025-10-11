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
        // Check if table exists before creating
        if (!Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email', 191)->primary();
                $table->string('token', 191);
                $table->timestamp('created_at')->nullable();
                $table->timestamp('expires_at')->nullable(); // Add expires_at column
            });
        } else {
            // If table exists, check if expires_at column exists
            if (!Schema::hasColumn('password_reset_tokens', 'expires_at')) {
                Schema::table('password_reset_tokens', function (Blueprint $table) {
                    $table->timestamp('expires_at')->nullable()->after('created_at');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
    }
};
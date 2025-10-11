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
        Schema::table('departments', function (Blueprint $table) {
            $table->dropColumn(['description', 'head_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->text('description')->nullable();
            $table->unsignedBigInteger('head_id')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            
            // Foreign key constraint
            $table->foreign('head_id')->references('id')->on('users')->onDelete('set null');
        });
    }
};
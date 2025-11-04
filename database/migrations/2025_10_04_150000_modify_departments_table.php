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
            
            // --- THIS IS THE FIX ---
            // Step 1: Drop the foreign key constraint first.
            // The error log told us the exact name: 'departments_head_id_foreign'
            $table->dropForeign('departments_head_id_foreign');
            
            // Step 2: Now it's safe to drop the columns.
            $table->dropColumn(['description', 'head_id', 'status']);
            // --- END OF FIX ---

        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            
            // Re-create the columns
            $table->text('description')->nullable();
            $table->unsignedBigInteger('head_id')->nullable();
            
            // Your original 'down' method had 'enum' which is perfect
            $table->enum('status', ['active', 'inactive'])->default('active');
            
            // Re-create the foreign key constraint
            $table->foreign('head_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }
};
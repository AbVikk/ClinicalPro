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
        Schema::table('doctors_new', function (Blueprint $table) {
            $table->unsignedBigInteger('department_id')->nullable()->after('specialization');
            $table->unsignedBigInteger('category_id')->nullable()->after('department_id');
            
            // Foreign key constraints
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors_new', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropForeign(['category_id']);
            $table->dropColumn(['department_id', 'category_id']);
        });
    }
};
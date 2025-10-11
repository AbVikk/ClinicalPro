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
        // 1. Update the Users table to link staff (HOD, Matron, Doctor, Nurse) to a Department
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->after('role');
            
            $table->foreign('department_id')
                  ->references('id')
                  ->on('departments')
                  ->onDelete('set null'); 
        });
        
        // 2. Update the Departments table to track the assigned HOD
        Schema::table('departments', function (Blueprint $table) {
            $table->foreignId('department_head_id')
                  ->nullable()
                  ->after('name');
                  
            $table->foreign('department_head_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropForeign(['department_head_id']);
            $table->dropColumn('department_head_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        });
    }
};
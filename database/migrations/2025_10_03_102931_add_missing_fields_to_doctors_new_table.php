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
            $table->string('medical_school')->nullable();
            $table->string('residency')->nullable();
            $table->string('fellowship')->nullable();
            $table->integer('years_of_experience')->nullable();
            $table->text('bio')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors_new', function (Blueprint $table) {
            $table->dropColumn(['medical_school', 'residency', 'fellowship', 'years_of_experience', 'bio']);
        });
    }
};
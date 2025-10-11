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
            $table->text('about')->nullable()->after('name');
            $table->text('history')->nullable()->after('about');
            $table->text('goals')->nullable()->after('history');
            $table->string('location')->nullable()->after('goals');
            $table->string('contact')->nullable()->after('location');
            $table->string('email')->nullable()->after('contact');
            $table->text('description')->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropColumn(['about', 'history', 'goals', 'location', 'contact', 'email', 'description']);
        });
    }
};

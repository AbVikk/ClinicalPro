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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('user_id')->nullable()->after('phone');
            $table->string('gender')->nullable()->after('user_id');
            $table->text('address')->nullable()->after('gender');
            $table->date('date_of_birth')->nullable()->after('address');
            $table->string('status')->default('pending')->after('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'user_id', 'gender', 'address', 'date_of_birth', 'status']);
        });
    }
};
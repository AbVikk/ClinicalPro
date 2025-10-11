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
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->string('token', 100)->unique();
            $table->string('email');
            $table->enum('role', [
                'admin',
                'doctor',
                'clinic_staff',
                'patient',
                'donor',
                'primary_pharmacist',
                'senior_pharmacist',
                'clinic_pharmacist',
                'billing_staff'
            ]);
            $table->boolean('used')->default(false);
            $table->timestamp('expires_at');
            $table->timestamps();
            
            $table->index(['token', 'used', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
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
        Schema::create('disbursements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipient_id')->constrained('users');
            $table->foreignId('clinic_id')->constrained('clinics');
            $table->decimal('amount', 10, 2);
            $table->enum('type', ['commission', 'salary', 'vendor_expense']);
            $table->enum('status', ['processed', 'pending', 'failed']);
            $table->timestamp('disbursement_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disbursements');
    }
};
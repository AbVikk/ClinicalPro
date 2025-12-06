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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Who did it?
            $table->string('action');       // e.g., "Created", "Updated", "Deleted"
            $table->string('model_type')->nullable(); // e.g., "App\Models\Payment"
            $table->unsignedBigInteger('model_id')->nullable(); // e.g., Payment ID 50
            $table->text('details')->nullable(); // JSON snapshot of what changed
            $table->string('ip_address')->nullable(); // Security tracking
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};

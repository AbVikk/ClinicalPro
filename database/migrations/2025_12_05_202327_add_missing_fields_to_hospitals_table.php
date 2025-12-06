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
        Schema::table('hospitals', function (Blueprint $table) {
            // Check if columns exist before adding them
            if (!Schema::hasColumn('hospitals', 'domain_prefix')) {
                $table->string('domain_prefix', 50)->unique()->nullable()->after('name');
            }
            
            if (!Schema::hasColumn('hospitals', 'address')) {
                $table->text('address')->nullable()->after('domain_prefix');
            }
            
            if (!Schema::hasColumn('hospitals', 'contact_email')) {
                $table->string('contact_email')->nullable()->after('address');
            }
            
            if (!Schema::hasColumn('hospitals', 'phone')) {
                $table->string('phone')->nullable()->after('contact_email');
            }
            
            if (!Schema::hasColumn('hospitals', 'subscription_plan')) {
                $table->string('subscription_plan')->default('basic')->after('phone');
            }
            
            if (!Schema::hasColumn('hospitals', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('subscription_plan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hospitals', function (Blueprint $table) {
            // Only drop columns that we know we added
            $columnsToDrop = [];
            
            if (Schema::hasColumn('hospitals', 'domain_prefix')) {
                $columnsToDrop[] = 'domain_prefix';
            }
            
            if (Schema::hasColumn('hospitals', 'address')) {
                $columnsToDrop[] = 'address';
            }
            
            if (Schema::hasColumn('hospitals', 'contact_email')) {
                $columnsToDrop[] = 'contact_email';
            }
            
            if (Schema::hasColumn('hospitals', 'phone')) {
                $columnsToDrop[] = 'phone';
            }
            
            if (Schema::hasColumn('hospitals', 'subscription_plan')) {
                $columnsToDrop[] = 'subscription_plan';
            }
            
            if (Schema::hasColumn('hospitals', 'is_active')) {
                $columnsToDrop[] = 'is_active';
            }
            
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
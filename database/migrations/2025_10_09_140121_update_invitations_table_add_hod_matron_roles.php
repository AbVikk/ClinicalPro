<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update the role enum to include hod and matron
        DB::statement("ALTER TABLE invitations MODIFY COLUMN role ENUM('admin','doctor','clinic_staff','patient','donor','primary_pharmacist','senior_pharmacist','clinic_pharmacist','billing_staff','hod','matron')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert the role enum to exclude hod and matron
        DB::statement("ALTER TABLE invitations MODIFY COLUMN role ENUM('admin','doctor','clinic_staff','patient','donor','primary_pharmacist','senior_pharmacist','clinic_pharmacist','billing_staff')");
    }
};
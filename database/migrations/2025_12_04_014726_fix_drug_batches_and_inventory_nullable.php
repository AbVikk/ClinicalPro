<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Fix Drug Batches
        Schema::table('drug_batches', function (Blueprint $table) {
            // Make supplier_id nullable because we use text names for suppliers often
            if (Schema::hasColumn('drug_batches', 'supplier_id')) {
                $table->unsignedBigInteger('supplier_id')->nullable()->change();
            }
            
            // Ensure cost_price exists
            if (!Schema::hasColumn('drug_batches', 'cost_price')) {
                $table->decimal('cost_price', 10, 2)->default(0)->after('received_quantity');
            }
        });

        // 2. Fix Clinic Inventories
        Schema::table('clinic_inventories', function (Blueprint $table) {
            // Make reorder_point nullable or default to 0
            if (Schema::hasColumn('clinic_inventories', 'reorder_point')) {
                $table->integer('reorder_point')->default(10)->change();
            } else {
                $table->integer('reorder_point')->default(10);
            }
        });
    }

    public function down(): void
    {
        // No down needed for relaxation
    }
};
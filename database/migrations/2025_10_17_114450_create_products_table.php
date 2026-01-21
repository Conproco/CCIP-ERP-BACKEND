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
        // Check if table already exists (from old system)
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                // Add new columns if they don't exist
                if (!Schema::hasColumn('products', 'primary_code')) {
                    $table->string('primary_code')->nullable()->unique()->after('description');
                }
                if (!Schema::hasColumn('products', 'secondary_code')) {
                    $table->string('secondary_code')->nullable()->after('primary_code');
                }
                if (!Schema::hasColumn('products', 'sc_type')) {
                    $table->string('sc_type')->nullable()->after('secondary_code');
                }
                if (!Schema::hasColumn('products', 'unit_id')) {
                    $table->foreignId('unit_id')->nullable()->after('sc_type')->constrained('units')->nullOnDelete();
                }
                
                // Drop old column if exists
                if (Schema::hasColumn('products', 'sap_code')) {
                    $table->dropColumn('sap_code');
                }
            });
            
            // Add unique constraint if it doesn't exist
            try {
                Schema::table('products', function (Blueprint $table) {
                    $table->unique(['name', 'secondary_code', 'sc_type'], 'unique_name_secondary_code_sc_type');
                });
            } catch (\Exception $e) {
                // Index might already exist
            }
        } else {
            // Create new table
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('description')->nullable();
                $table->string('primary_code')->nullable()->unique();
                $table->string('secondary_code')->nullable();
                $table->string('sc_type')->nullable(); //cicsa | huawei
                $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();
                $table->timestamps();

                $table->unique(['name', 'secondary_code', 'sc_type'], 'unique_name_secondary_code_sc_type');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

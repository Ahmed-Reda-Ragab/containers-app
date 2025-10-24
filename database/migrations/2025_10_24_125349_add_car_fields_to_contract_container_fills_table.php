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
        Schema::table('contract_container_fills', function (Blueprint $table) {
            $table->foreignId('deliver_car_id')->nullable()->constrained('cars')->onDelete('set null');
            $table->foreignId('discharge_car_id')->nullable()->constrained('cars')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contract_container_fills', function (Blueprint $table) {
            $table->dropForeign(['deliver_car_id']);
            $table->dropForeign(['discharge_car_id']);
            $table->dropColumn(['deliver_car_id', 'discharge_car_id']);
        });
    }
};

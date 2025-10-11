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
        Schema::table('cars', function (Blueprint $table) {
            $table->string('vehicle_id')->nullable()->after('type');
            $table->index('vehicle_id');
            // normalize expires_at to timestamp
            $table->timestamp('expires_at')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropIndex(['vehicle_id']);
            $table->dropColumn('vehicle_id');
            // revert expires_at to text
            $table->text('expires_at')->nullable()->change();
        });
    }
};

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
        Schema::create('discharges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained('contracts')->onDelete('cascade');
            $table->foreignId('container_id')->constrained('containers');
            $table->foreignId('customer_id')->constrained('customers');
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('pickup_address');
            $table->string('city');
            $table->foreignId('driver_id')->constrained('users');
            $table->date('discharge_date');
            $table->time('discharge_time')->nullable();
            $table->enum('status', ['scheduled', 'in_transit', 'discharged', 'failed'])->default('scheduled');
            $table->text('discharge_notes')->nullable();
            $table->string('disposal_location')->nullable();
            $table->string('photo_proof')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discharges');
    }
};

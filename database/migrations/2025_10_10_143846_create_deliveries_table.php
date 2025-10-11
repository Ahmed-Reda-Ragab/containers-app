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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained('contracts')->onDelete('cascade');
            $table->foreignId('container_id')->constrained('containers');
            $table->foreignId('customer_id')->constrained('customers');
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('delivery_address');
            $table->string('city');
            $table->foreignId('driver_id')->constrained('users');
            $table->date('delivery_date');
            $table->time('delivery_time')->nullable();
            $table->enum('status', ['scheduled', 'in_transit', 'delivered', 'failed'])->default('scheduled');
            $table->text('delivery_notes')->nullable();
            $table->text('customer_signature')->nullable();
            $table->string('photo_proof')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};

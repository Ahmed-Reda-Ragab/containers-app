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
        Schema::create('daily_bookings', function (Blueprint $table) {
            $table->id();
            $table->date('booking_date');
            $table->foreignId('contract_id')->constrained('contracts')->onDelete('cascade');
            $table->foreignId('container_id')->constrained('containers');
            $table->foreignId('customer_id')->constrained('customers');
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('delivery_address');
            $table->string('city');
            $table->foreignId('driver_id')->constrained('users');
            $table->enum('status', ['pending', 'confirmed', 'delivered', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_bookings');
    }
};

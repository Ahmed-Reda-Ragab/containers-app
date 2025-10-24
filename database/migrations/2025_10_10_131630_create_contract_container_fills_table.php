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
        Schema::create('contract_container_fills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained('contracts')->onDelete('cascade');
            $table->integer('no'); // container number
            $table->foreignId('deliver_id')->constrained('users'); // who delivered
            $table->date('deliver_at'); // delivery date
            $table->foreignId('container_id')->constrained('containers');
            $table->date('expected_discharge_date'); // calculated from contract start_date + contract_period
            $table->date('discharge_date')->nullable(); // actual discharge date
            $table->foreignId('discharge_id')->nullable()->constrained('users'); // who discharged
            $table->decimal('price', 10, 2)->nullable(); // or inherit from contract.container_price
            $table->foreignId('client_id')->constrained('customers');
            $table->string('city'); // auto-filled from contract
            $table->string('address'); // auto-filled from contract
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_container_fills');
    }
};

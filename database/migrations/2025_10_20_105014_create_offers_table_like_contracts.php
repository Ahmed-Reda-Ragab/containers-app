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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('offers');
        Schema::enableForeignKeyConstraints();
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->json('customer')->nullable();
            $table->foreignId('type_id')->constrained('types');
            $table->decimal('container_price', 10, 2);
            $table->integer('no_containers');
            $table->decimal('monthly_dumping_cont', 10, 2);
            $table->decimal('monthly_total_dumping_cost', 10, 2);
            $table->integer('contract_period');
            $table->decimal('tax_value', 5, 2)->default(14.00);
            $table->decimal('total_price', 10, 2);
            $table->decimal('total', 10, 2)->default(0.00);
            $table->decimal('total_paid', 10, 2)->default(0.00);
            $table->decimal('total_payed', 10, 2)->default(0.00);
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['pending', 'active', 'expired', 'canceled'])->default('pending');
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->text('agreement_terms')->nullable();
            $table->text('material_restrictions')->nullable();
            $table->text('delivery_terms')->nullable();
            $table->text('payment_policy')->nullable();
            $table->date('valid_until')->nullable();
            $table->decimal('additional_trip_cost', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};

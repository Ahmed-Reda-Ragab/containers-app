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
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number')->unique();
            $table->foreignId('contract_id')->constrained('contracts')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers');
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_address');
            $table->string('city');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['issued', 'collected', 'overdue', 'cancelled'])->default('issued');
            $table->date('issue_date');
            $table->date('due_date');
            $table->date('collection_date')->nullable();
            $table->foreignId('issued_by')->constrained('users');
            $table->foreignId('collected_by')->nullable()->constrained('users');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};

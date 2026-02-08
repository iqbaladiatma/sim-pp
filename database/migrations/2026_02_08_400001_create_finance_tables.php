<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bill_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // e.g., "SPP Bulanan", "Uang Pangkal", "Seragam"
            $table->decimal('default_amount', 15, 2)->default(0);
            $table->enum('type', ['monthly', 'one_time'])->default('monthly');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('student_bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bill_category_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 15, 2);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->date('due_date')->nullable();
            $table->string('period_month', 10)->nullable(); // e.g., "2026-02"
            $table->enum('status', ['unpaid', 'partially_paid', 'paid'])->default('unpaid');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'status']);
            $table->index(['student_id', 'period_month']);
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_bill_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 15, 2);
            $table->string('payment_method'); // e.g., "manual_transfer", "cash", "gateway"
            $table->string('reference_number')->nullable();
            $table->dateTime('payment_date');
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('completed');
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'payment_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('student_bills');
        Schema::dropIfExists('bill_categories');
    }
};

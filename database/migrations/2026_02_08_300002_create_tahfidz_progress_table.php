<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tahfidz_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('surah_name');
            $table->integer('start_verse')->nullable();
            $table->integer('end_verse')->nullable();
            $table->enum('type', ['ziyadah', 'murajaah'])->default('ziyadah');
            $table->enum('quality', ['A', 'B', 'C', 'D'])->default('A');
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('recorded_at');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tahfidz_progress');
    }
};

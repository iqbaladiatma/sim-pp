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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('nis', 20)->unique(); // Nomor Induk Santri
            $table->string('nisn', 20)->nullable(); // Nomor Induk Siswa Nasional
            $table->string('name');
            $table->enum('gender', ['L', 'P']);
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->text('address')->nullable();
            $table->string('phone_number', 20)->nullable();

            // Relationships
            $table->foreignId('parent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('classroom_id')->nullable()->constrained('classrooms')->nullOnDelete();
            $table->foreignId('dormitory_room_id')->nullable()->constrained('dormitory_rooms')->nullOnDelete();

            $table->date('entry_date')->nullable();
            $table->enum('status', ['active', 'alumni', 'dropped_out', 'transferred'])->default('active');
            $table->string('photo')->nullable();
            $table->integer('discipline_points')->default(100);

            $table->timestamps();

            $table->index(['tenant_id', 'status']);
            $table->index('nis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};

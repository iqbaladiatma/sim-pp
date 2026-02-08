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
        Schema::table('users', function (Blueprint $table) {
            // Add tenant_id (NULL for super_admin)
            $table->foreignId('tenant_id')->nullable()->after('id')
                ->constrained('tenants')->cascadeOnDelete();

            // Add role field
            $table->enum('role', [
                'super_admin',      // Platform administrator
                'admin_pondok',     // Pondok administrator
                'ustadz',           // Teacher
                'wali_santri',      // Parent/Guardian
                'staff'             // Staff
            ])->default('staff')->after('email');

            // Add additional fields
            $table->string('phone_number', 20)->nullable()->after('email');
            $table->string('avatar')->nullable()->after('password');
            $table->boolean('is_active')->default(true)->after('avatar');
            $table->timestamp('last_login_at')->nullable()->after('is_active');

            // Indexes
            $table->index(['tenant_id', 'role']);
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn([
                'tenant_id',
                'role',
                'phone_number',
                'avatar',
                'is_active',
                'last_login_at'
            ]);
        });
    }
};

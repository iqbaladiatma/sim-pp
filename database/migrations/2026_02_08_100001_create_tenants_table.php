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
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();

            // Basic Information
            $table->string('name'); // Nama Pondok
            $table->string('slug')->unique(); // subdomain identifier (e.g., 'alikhlas')
            $table->string('domain')->nullable(); // custom domain (optional)

            // Contact Information
            $table->text('address')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();

            // Branding
            $table->string('logo')->nullable();

            // Subscription Details
            $table->enum('subscription_plan', ['basic', 'pro', 'enterprise'])->default('basic');
            $table->enum('subscription_status', ['active', 'inactive', 'trial', 'expired'])->default('trial');
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('subscribed_at')->nullable();

            // Limits based on plan
            $table->integer('max_students')->default(100);

            // Metadata
            $table->json('settings')->nullable(); // Additional settings

            $table->timestamps();
            $table->softDeletes(); // Soft delete for data retention

            // Indexes
            $table->index('slug');
            $table->index('subscription_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};

<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Super Admin
        User::create([
            'tenant_id' => null,
            'name' => 'Super Administrator',
            'email' => 'superadmin@simpp.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'phone_number' => '081234567890',
            'is_active' => true,
        ]);

        // Create Demo Tenant 1: Pondok Al-Ikhlas
        $tenant1 = Tenant::create([
            'name' => 'Pondok Pesantren Al-Ikhlas',
            'slug' => 'alikhlas',
            'address' => 'Jl. Pesantren No. 123, Yogyakarta',
            'phone' => '0274123456',
            'email' => 'info@alikhlas.com',
            'subscription_plan' => 'pro',
            'subscription_status' => 'active',
            'subscribed_at' => now(),
            'max_students' => 500,
        ]);

        // Create users for Tenant 1
        User::create([
            'tenant_id' => $tenant1->id,
            'name' => 'Admin Al-Ikhlas',
            'email' => 'admin@alikhlas.com',
            'password' => Hash::make('password'),
            'role' => 'admin_pondok',
            'phone_number' => '081234567891',
            'is_active' => true,
        ]);

        User::create([
            'tenant_id' => $tenant1->id,
            'name' => 'Ustadz Ahmad Fauzi',
            'email' => 'ahmad@alikhlas.com',
            'password' => Hash::make('password'),
            'role' => 'ustadz',
            'phone_number' => '081234567892',
            'is_active' => true,
        ]);

        User::create([
            'tenant_id' => $tenant1->id,
            'name' => 'Bapak Iqbal (Wali Santri)',
            'email' => 'wali@alikhlas.com',
            'password' => Hash::make('password'),
            'role' => 'wali_santri',
            'phone_number' => '081234567893',
            'is_active' => true,
        ]);

        // Create Demo Tenant 2: Pondok Al-Manar
        $tenant2 = Tenant::create([
            'name' => 'Pondok Pesantren Al-Manar',
            'slug' => 'almanar',
            'address' => 'Jl. Pendidikan No. 456, Semarang',
            'phone' => '024123456',
            'email' => 'info@almanar.com',
            'subscription_plan' => 'basic',
            'subscription_status' => 'trial',
            'trial_ends_at' => now()->addDays(14),
            'max_students' => 100,
        ]);

        // Create users for Tenant 2
        User::create([
            'tenant_id' => $tenant2->id,
            'name' => 'Admin Al-Manar',
            'email' => 'admin@almanar.com',
            'password' => Hash::make('password'),
            'role' => 'admin_pondok',
            'phone_number' => '081234567894',
            'is_active' => true,
        ]);

        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('');
        $this->command->info('🔐 Login Credentials:');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('Super Admin:');
        $this->command->info('  Email: superadmin@simpp.com');
        $this->command->info('  Password: password');
        $this->command->info('');
        $this->command->info('Pondok Al-Ikhlas (alikhlas.sim-pp.test):');
        $this->command->info('  Admin: admin@alikhlas.com / password');
        $this->command->info('  Ustadz: ahmad@alikhlas.com / password');
        $this->command->info('  Wali: wali@alikhlas.com / password');
        $this->command->info('');
        $this->command->info('Pondok Al-Manar (almanar.sim-pp.test):');
        $this->command->info('  Admin: admin@almanar.com / password');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    }
}

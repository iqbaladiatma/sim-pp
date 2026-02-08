# 🚀 SIM-PP Quick Start Guide

## Prerequisites Checklist

- [x] Laravel 12 installed
- [x] Svelte + Inertia.js configured
- [x] Tailwind CSS setup
- [ ] MySQL database (switch from SQLite for production features)
- [ ] Multi-tenancy package
- [ ] Payment gateway accounts (Midtrans/Xendit)
- [ ] WhatsApp gateway account (Fonnte/Watzap)

---

## Step 1: Switch to MySQL Database

### Update `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sim_pp
DB_USERNAME=root
DB_PASSWORD=
```

### Create database:

```bash
# Using Laragon's MySQL
# Open HeidiSQL or phpMyAdmin and create database: sim_pp
```

### Run migrations:

```bash
php artisan migrate:fresh
```

---

## Step 2: Install Multi-Tenancy Package

```bash
composer require stancl/tenancy
php artisan tenancy:install
php artisan migrate
```

### Publish configuration:

```bash
php artisan vendor:publish --provider="Stancl\Tenancy\TenancyServiceProvider" --tag=config
```

---

## Step 3: Create First Migrations (Fase 1)

### Generate migration files:

```bash
# Core tables
php artisan make:migration create_tenants_table
php artisan make:migration add_tenant_id_to_users_table

# Kesantrian module
php artisan make:migration create_students_table
php artisan make:migration create_classrooms_table
php artisan make:migration create_dormitory_rooms_table
php artisan make:migration create_violations_table
php artisan make:migration create_permissions_table

# Tahfidz module
php artisan make:migration create_subjects_table
php artisan make:migration create_schedules_table
php artisan make:migration create_attendances_table
php artisan make:migration create_grades_table
php artisan make:migration create_memorizations_table

# Finance module
php artisan make:migration create_fee_types_table
php artisan make:migration create_bills_table
php artisan make:migration create_transactions_table
php artisan make:migration create_student_wallets_table
php artisan make:migration create_wallet_transactions_table
php artisan make:migration create_expenses_table

# HR module
php artisan make:migration create_teachers_table
php artisan make:migration create_teacher_attendances_table
php artisan make:migration create_payrolls_table

# Notifications
php artisan make:migration create_notifications_table
php artisan make:migration create_whatsapp_logs_table
```

---

## Step 4: Create Models

```bash
# Core
php artisan make:model Tenant
php artisan make:model User # Already exists, just update

# Kesantrian
php artisan make:model Student
php artisan make:model Classroom
php artisan make:model DormitoryRoom
php artisan make:model Violation
php artisan make:model Permission

# Akademik & Tahfidz
php artisan make:model Subject
php artisan make:model Schedule
php artisan make:model Attendance
php artisan make:model Grade
php artisan make:model Memorization

# Finance
php artisan make:model FeeType
php artisan make:model Bill
php artisan make:model Transaction
php artisan make:model StudentWallet
php artisan make:model WalletTransaction
php artisan make:model Expense

# HR
php artisan make:model Teacher
php artisan make:model TeacherAttendance
php artisan make:model Payroll

# Notifications
php artisan make:model Notification
php artisan make:model WhatsappLog
```

---

## Step 5: Create Controllers

```bash
# Super Admin
php artisan make:controller SuperAdmin/TenantController --resource

# Kesantrian
php artisan make:controller StudentController --resource
php artisan make:controller ClassroomController --resource
php artisan make:controller PermissionController --resource
php artisan make:controller ViolationController --resource

# Tahfidz
php artisan make:controller MemorizationController --resource

# Finance
php artisan make:controller BillController --resource
php artisan make:controller TransactionController --resource
php artisan make:controller WalletController --resource

# HR
php artisan make:controller TeacherController --resource
php artisan make:controller PayrollController --resource
```

---

## Step 6: Setup Routes

### `routes/web.php` structure:

```php
<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Public routes
Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

Route::get('/login', function () {
    return Inertia::render('Auth/Login');
})->name('login');

// Super Admin routes (admin.simpp.com)
Route::domain('admin.' . config('app.domain'))->group(function () {
    Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [SuperAdmin\DashboardController::class, 'index'])->name('admin.dashboard');
        Route::resource('tenants', SuperAdmin\TenantController::class);
    });
});

// Tenant routes ({tenant}.simpp.com)
Route::middleware(['tenant'])->group(function () {
    Route::middleware(['auth'])->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Kesantrian
        Route::resource('students', StudentController::class);
        Route::resource('classrooms', ClassroomController::class);
        Route::resource('permissions', PermissionController::class);
        Route::resource('violations', ViolationController::class);

        // Tahfidz
        Route::resource('memorizations', MemorizationController::class);
        Route::get('/tahfidz/progress/{student}', [MemorizationController::class, 'progress'])->name('tahfidz.progress');

        // Finance
        Route::resource('bills', BillController::class);
        Route::resource('transactions', TransactionController::class);
        Route::resource('wallets', WalletController::class);
        Route::post('/wallets/{wallet}/topup', [WalletController::class, 'topup'])->name('wallets.topup');

        // HR
        Route::resource('teachers', TeacherController::class);
        Route::resource('payrolls', PayrollController::class);
    });
});
```

---

## Step 7: Create Svelte Pages Structure

```
resources/js/Pages/
├── Auth/
│   ├── Login.svelte
│   └── Register.svelte
├── SuperAdmin/
│   ├── Dashboard.svelte
│   └── Tenants/
│       ├── Index.svelte
│       ├── Create.svelte
│       └── Edit.svelte
├── Dashboard.svelte
├── Students/
│   ├── Index.svelte
│   ├── Show.svelte
│   ├── Create.svelte
│   └── Edit.svelte
├── Tahfidz/
│   ├── Index.svelte
│   ├── Input.svelte
│   └── Progress.svelte
├── Finance/
│   ├── Bills/
│   │   ├── Index.svelte
│   │   └── Show.svelte
│   ├── Transactions/
│   │   └── Index.svelte
│   └── Wallets/
│       ├── Index.svelte
│       └── Topup.svelte
└── Teachers/
    ├── Index.svelte
    └── Payroll.svelte
```

---

## Step 8: Create Reusable Svelte Components

```
resources/js/Components/
├── Button.svelte
├── Input.svelte
├── Select.svelte
├── Modal.svelte
├── Card.svelte
├── Table.svelte
├── Pagination.svelte
├── SearchBar.svelte
├── DatePicker.svelte
├── FileUpload.svelte
├── StatCard.svelte (for dashboard)
├── Chart.svelte (for graphs)
└── Notifications/
    ├── Toast.svelte
    └── NotificationBell.svelte
```

---

## Step 9: Install Additional Dependencies

```bash
# Chart library for Tahfidz progress
npm install chart.js svelte-chartjs

# Date picker
npm install flatpickr

# Icons (already have lucide-svelte)
# npm install lucide-svelte (already installed)

# PDF generation
composer require barryvdh/laravel-dompdf

# Excel export
composer require maatwebsite/excel

# Image optimization
composer require intervention/image
```

---

## Step 10: Development Workflow

### Terminal 1: Laravel dev server

```bash
php artisan serve
```

### Terminal 2: Vite dev server

```bash
npm run dev
```

### Terminal 3: Queue worker (for notifications)

```bash
php artisan queue:work
```

---

## Development Tips

### 1. Use Inertia's `useForm` helper in Svelte:

```svelte
<script>
import { useForm } from '@inertiajs/svelte';

let form = useForm({
    name: '',
    email: '',
});

function submit() {
    $form.post('/students', {
        onSuccess: () => {
            // Handle success
        },
    });
}
</script>
```

### 2. Global Tenant Scope in Models:

```php
// app/Models/Student.php
protected static function booted()
{
    static::addGlobalScope('tenant', function ($query) {
        if (auth()->check() && auth()->user()->tenant_id) {
            $query->where('tenant_id', auth()->user()->tenant_id);
        }
    });
}
```

### 3. Share data globally with Inertia:

```php
// app/Http/Middleware/HandleInertiaRequests.php
public function share(Request $request): array
{
    return array_merge(parent::share($request), [
        'auth' => [
            'user' => $request->user(),
            'tenant' => $request->user()?->tenant,
        ],
        'flash' => [
            'success' => fn () => $request->session()->get('success'),
            'error' => fn () => $request->session()->get('error'),
        ],
    ]);
}
```

---

## Testing Your Setup

### 1. Create a test tenant:

```bash
php artisan tinker
```

```php
$tenant = \App\Models\Tenant::create([
    'name' => 'Pondok Al-Ikhlas',
    'slug' => 'alikhlas',
    'email' => 'admin@alikhlas.com',
    'subscription_plan' => 'pro',
    'subscription_status' => 'active',
]);
```

### 2. Create a test user:

```php
$user = \App\Models\User::create([
    'tenant_id' => $tenant->id,
    'name' => 'Admin Pondok',
    'email' => 'admin@alikhlas.com',
    'password' => bcrypt('password'),
    'role' => 'admin_pondok',
]);
```

### 3. Access tenant subdomain:

```
http://alikhlas.sim-pp.test/dashboard
```

---

## Troubleshooting

### Issue: Subdomain not working locally

**Solution:** Add to `hosts` file:

```
127.0.0.1 sim-pp.test
127.0.0.1 alikhlas.sim-pp.test
127.0.0.1 admin.sim-pp.test
```

### Issue: Vite not loading assets

**Solution:** Check `vite.config.js`:

```js
export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        svelte(),
    ],
    server: {
        host: "0.0.0.0",
        hmr: {
            host: "localhost",
        },
    },
});
```

---

## Next Actions

1. ✅ Review `IMPLEMENTATION_PLAN.md`
2. ⬜ Setup MySQL database
3. ⬜ Install multi-tenancy package
4. ⬜ Create first migration (tenants table)
5. ⬜ Build super admin panel
6. ⬜ Start Fase 1 development

---

**Ready to start building? Let's create the future of pesantren management! 🚀**

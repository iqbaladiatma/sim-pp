# 📂 SIM-PP Project Structure

## Directory Overview

```
sim-pp/
├── 📁 app/
│   ├── 📁 Actions/ (Jetstream/Fortify Actions)
│   ├── 📁 Console/
│   ├── 📁 Http/
│   │   ├── 📁 Controllers/
│   │   │   ├── 📁 Finance/
│   │   │   │   ├── MerchantController.php
│   │   │   │   ├── StudentBillController.php
│   │   │   │   └── TransactionController.php
│   │   │   ├── 📁 Academic/
│   │   │   │   ├── DormitoryController.php
│   │   │   │   ├── ScheduleController.php
│   │   │   │   └── AttendanceController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── StudentController.php
│   │   │   ├── ReportController.php
│   │   │   └── WaliController.php
│   │   ├── 📁 Middleware/
│   │   │   ├── HandleInertiaRequests.php
│   │   │   └── RoleMiddleware.php
│   │   └── Kernel.php
│   ├── 📁 Models/
│   │   ├── User.php
│   │   ├── Tenant.php
│   │   ├── Student.php
│   │   ├── Classroom.php
│   │   ├── DormitoryRoom.php
│   │   ├── Violation.php
│   │   ├── Permission.php
│   │   ├── Subject.php
│   │   ├── Schedule.php
│   │   ├── Attendance.php
│   │   ├── Grade.php
│   │   ├── Memorization.php
│   │   ├── FeeType.php
│   │   ├── Bill.php
│   │   ├── Transaction.php
│   │   ├── StudentWallet.php
│   │   ├── WalletTransaction.php
│   │   ├── Expense.php
│   │   ├── Teacher.php
│   │   ├── TeacherAttendance.php
│   │   ├── Payroll.php
│   │   ├── Notification.php
│   │   └── WhatsappLog.php
│   ├── 📁 Services/
│   │   ├── TenantService.php
│   │   ├── WhatsappService.php
│   │   ├── PaymentGatewayService.php
│   │   ├── MemorizationService.php
│   │   └── NotificationService.php
│   └── 📁 Providers/
│       ├── AppServiceProvider.php
│       └── RouteServiceProvider.php
│
├── 📁 bootstrap/
│   ├── app.php
│   └── providers.php
│
├── 📁 config/
│   ├── app.php
│   ├── auth.php
│   ├── database.php
│   ├── tenancy.php (after install)
│   └── services.php
│
├── 📁 database/
│   ├── 📁 factories/
│   │   ├── UserFactory.php
│   │   ├── TenantFactory.php
│   │   └── StudentFactory.php
│   ├── 📁 migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 2026_02_08_000001_create_tenants_table.php
│   │   ├── 2026_02_08_000002_add_tenant_id_to_users_table.php
│   │   ├── 2026_02_08_000003_create_students_table.php
│   │   ├── 2026_02_08_000004_create_classrooms_table.php
│   │   ├── 2026_02_08_000005_create_dormitory_rooms_table.php
│   │   ├── 2026_02_08_000006_create_violations_table.php
│   │   ├── 2026_02_08_000007_create_permissions_table.php
│   │   ├── 2026_02_08_000008_create_subjects_table.php
│   │   ├── 2026_02_08_000009_create_schedules_table.php
│   │   ├── 2026_02_08_000010_create_attendances_table.php
│   │   ├── 2026_02_08_000011_create_grades_table.php
│   │   ├── 2026_02_08_000012_create_memorizations_table.php
│   │   ├── 2026_02_08_000013_create_fee_types_table.php
│   │   ├── 2026_02_08_000014_create_bills_table.php
│   │   ├── 2026_02_08_000015_create_transactions_table.php
│   │   ├── 2026_02_08_000016_create_student_wallets_table.php
│   │   ├── 2026_02_08_000017_create_wallet_transactions_table.php
│   │   ├── 2026_02_08_000018_create_expenses_table.php
│   │   ├── 2026_02_08_000019_create_teachers_table.php
│   │   ├── 2026_02_08_000020_create_teacher_attendances_table.php
│   │   ├── 2026_02_08_000021_create_payrolls_table.php
│   │   ├── 2026_02_08_000022_create_notifications_table.php
│   │   └── 2026_02_08_000023_create_whatsapp_logs_table.php
│   └── 📁 seeders/
│       ├── DatabaseSeeder.php
│       ├── TenantSeeder.php
│       ├── UserSeeder.php
│       └── DemoDataSeeder.php
│
├── 📁 public/
│   ├── 📁 icons/ (PWA icons)
│   ├── 📁 images/
│   ├── favicon.ico
│   └── index.php
│
├── 📁 resources/
│   ├── 📁 css/
│   │   └── app.css
│   ├── 📁 js/
│   │   ├── 📁 Components/
│   │   │   ├── 📁 UI/
│   │   │   │   ├── Button.svelte
│   │   │   │   ├── Input.svelte
│   │   │   │   ├── Select.svelte
│   │   │   │   ├── Textarea.svelte
│   │   │   │   ├── Checkbox.svelte
│   │   │   │   ├── Radio.svelte
│   │   │   │   ├── Modal.svelte
│   │   │   │   ├── Card.svelte
│   │   │   │   ├── Badge.svelte
│   │   │   │   ├── Alert.svelte
│   │   │   │   └── Loading.svelte
│   │   │   ├── 📁 Table/
│   │   │   │   ├── Table.svelte
│   │   │   │   ├── TableHeader.svelte
│   │   │   │   ├── TableRow.svelte
│   │   │   │   └── Pagination.svelte
│   │   │   ├── 📁 Form/
│   │   │   │   ├── FormGroup.svelte
│   │   │   │   ├── FormLabel.svelte
│   │   │   │   ├── FormError.svelte
│   │   │   │   └── FileUpload.svelte
│   │   │   ├── 📁 Charts/
│   │   │   │   ├── LineChart.svelte
│   │   │   │   ├── BarChart.svelte
│   │   │   │   └── ProgressChart.svelte
│   │   │   ├── 📁 Dashboard/
│   │   │   │   ├── StatCard.svelte
│   │   │   │   └── ActivityLog.svelte
│   │   │   ├── 📁 Notifications/
│   │   │   │   ├── Toast.svelte
│   │   │   │   └── NotificationBell.svelte
│   │   │   └── SearchBar.svelte
│   │   ├── 📁 Layouts/
│   │   │   ├── AppLayout.svelte
│   │   │   ├── AuthLayout.svelte
│   │   │   ├── SuperAdminLayout.svelte
│   │   │   └── GuestLayout.svelte
│   │   ├── 📁 Pages/
│   │   │   ├── 📁 Auth/
│   │   │   │   ├── Login.svelte
│   │   │   │   ├── Register.svelte
│   │   │   │   └── ForgotPassword.svelte
│   │   │   ├── 📁 SuperAdmin/
│   │   │   │   ├── Dashboard.svelte
│   │   │   │   └── 📁 Tenants/
│   │   │   │       ├── Index.svelte
│   │   │   │       ├── Create.svelte
│   │   │   │       ├── Edit.svelte
│   │   │   │       └── Show.svelte
│   │   │   ├── Home.svelte
│   │   │   ├── Dashboard.svelte
│   │   │   ├── 📁 Students/
│   │   │   │   ├── Index.svelte
│   │   │   │   ├── Create.svelte
│   │   │   │   ├── Edit.svelte
│   │   │   │   └── Show.svelte
│   │   │   ├── 📁 Classrooms/
│   │   │   │   ├── Index.svelte
│   │   │   │   └── Manage.svelte
│   │   │   ├── 📁 Permissions/
│   │   │   │   ├── Index.svelte
│   │   │   │   ├── Create.svelte
│   │   │   │   └── Approve.svelte
│   │   │   ├── 📁 Violations/
│   │   │   │   ├── Index.svelte
│   │   │   │   └── Record.svelte
│   │   │   ├── 📁 Tahfidz/
│   │   │   │   ├── Index.svelte
│   │   │   │   ├── Input.svelte
│   │   │   │   ├── Progress.svelte
│   │   │   │   └── Report.svelte
│   │   │   ├── 📁 Finance/
│   │   │   │   ├── Dashboard.svelte
│   │   │   │   ├── 📁 Bills/
│   │   │   │   │   ├── Index.svelte
│   │   │   │   │   ├── Create.svelte
│   │   │   │   │   └── Show.svelte
│   │   │   │   ├── 📁 Transactions/
│   │   │   │   │   ├── Index.svelte
│   │   │   │   │   └── Record.svelte
│   │   │   │   ├── 📁 Wallets/
│   │   │   │   │   ├── Index.svelte
│   │   │   │   │   ├── Topup.svelte
│   │   │   │   │   └── POS.svelte
│   │   │   │   └── Reports.svelte
│   │   │   ├── 📁 Academic/
│   │   │   │   ├── Schedules.svelte
│   │   │   │   ├── Attendance.svelte
│   │   │   │   ├── Grades.svelte
│   │   │   │   └── Rapor.svelte
│   │   │   └── 📁 Teachers/
│   │   │       ├── Index.svelte
│   │   │       ├── Attendance.svelte
│   │   │       └── Payroll.svelte
│   │   ├── app.js
│   │   └── bootstrap.js
│   └── 📁 views/
│       └── app.blade.php
│
├── 📁 routes/
│   ├── web.php
│   ├── api.php
│   └── console.php
│
├── 📁 storage/
│   ├── 📁 app/
│   │   ├── 📁 public/
│   │   │   ├── 📁 photos/
│   │   │   ├── 📁 documents/
│   │   │   └── 📁 receipts/
│   │   └── 📁 private/
│   ├── 📁 framework/
│   └── 📁 logs/
│
├── 📁 tests/
│   ├── 📁 Feature/
│   │   ├── Auth/
│   │   ├── Tenancy/
│   │   ├── Student/
│   │   └── Finance/
│   └── 📁 Unit/
│       ├── Models/
│       └── Services/
│
├── .env
├── .env.example
├── .gitignore
├── artisan
├── composer.json
├── package.json
├── phpunit.xml
├── postcss.config.js
├── tailwind.config.js
├── vite.config.js
├── README.md
├── IMPLEMENTATION_PLAN.md
├── QUICK_START.md
└── PROJECT_STRUCTURE.md (this file)
```

---

## Key Directories Explained

### 📁 app/Http/Controllers/

Contains all controller logic organized by module:

- **SuperAdmin/** - Tenant management for platform admin
- **Auth/** - Authentication controllers
- **Root level** - Main application controllers (Students, Finance, etc.)

### 📁 app/Models/

Eloquent models representing database tables. Each model includes:

- Relationships
- Scopes (especially tenant scope)
- Accessors/Mutators
- Validation rules

### 📁 app/Services/

Business logic separated from controllers:

- **TenantService** - Tenant creation, subscription management
- **WhatsappService** - WhatsApp API integration
- **PaymentGatewayService** - Midtrans/Xendit integration
- **MemorizationService** - Hafalan calculation & progress tracking
- **NotificationService** - Multi-channel notifications

### 📁 resources/js/Components/

Reusable Svelte components organized by category:

- **UI/** - Basic UI elements (Button, Input, Modal, etc.)
- **Table/** - Table components with pagination
- **Form/** - Form-related components
- **Charts/** - Chart components for visualizations
- **Dashboard/** - Dashboard-specific components
- **Notifications/** - Toast & notification components

### 📁 resources/js/Pages/

Svelte pages (Inertia.js components) organized by module:

- Each module has its own folder
- Standard CRUD pages: Index, Create, Edit, Show
- Special pages for specific features (e.g., Tahfidz/Progress)

### 📁 database/migrations/

Database schema definitions in chronological order:

- Prefix: `2026_02_08_XXXXXX_`
- Core tables first (tenants, users)
- Module tables grouped together
- Foreign keys defined properly

---

## Naming Conventions

### Controllers

```php
// Resource controllers (CRUD)
StudentController.php
MemorizationController.php

// Namespaced controllers
SuperAdmin\TenantController.php
Auth\LoginController.php
```

### Models

```php
// Singular, PascalCase
Student.php
StudentWallet.php
TeacherAttendance.php
```

### Migrations

```php
// Format: YYYY_MM_DD_XXXXXX_action_table_name.php
2026_02_08_000001_create_tenants_table.php
2026_02_08_000002_add_tenant_id_to_users_table.php
```

### Svelte Components

```svelte
<!-- PascalCase for components -->
Button.svelte
StatCard.svelte
ProgressChart.svelte

<!-- PascalCase for pages -->
Index.svelte
Create.svelte
Dashboard.svelte
```

### Routes

```php
// Kebab-case for URLs
/students
/tahfidz/progress
/finance/bills
/super-admin/tenants
```

---

## File Responsibilities

### Models

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    // 1. Table name (if not following convention)
    protected $table = 'students';

    // 2. Fillable/Guarded
    protected $fillable = [...];

    // 3. Casts
    protected $casts = [...];

    // 4. Boot method (for global scopes)
    protected static function booted() {...}

    // 5. Relationships
    public function tenant() {...}
    public function parent() {...}

    // 6. Scopes
    public function scopeActive($query) {...}

    // 7. Accessors/Mutators
    public function getFullNameAttribute() {...}

    // 8. Business logic methods
    public function calculateProgress() {...}
}
```

### Controllers

```php
<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class StudentController extends Controller
{
    // Keep controllers thin
    // Delegate business logic to Services
    // Return Inertia responses

    public function index()
    {
        $students = Student::with('classroom')
            ->paginate(20);

        return Inertia::render('Students/Index', [
            'students' => $students,
        ]);
    }
}
```

### Services

```php
<?php

namespace App\Services;

class MemorizationService
{
    // Complex business logic here
    // Keep it testable
    // Single responsibility

    public function calculateProgress(Student $student)
    {
        // Complex calculation logic
    }

    public function sendNotification(Memorization $memorization)
    {
        // WhatsApp notification logic
    }
}
```

### Svelte Pages

```svelte
<script>
  // 1. Imports
  import AppLayout from '@/Layouts/AppLayout.svelte';
  import Button from '@/Components/UI/Button.svelte';

  // 2. Props (from Inertia)
  export let students;

  // 3. State
  let searchQuery = '';

  // 4. Functions
  function handleSearch() {...}
</script>

<!-- 5. Template -->
<AppLayout>
  <!-- Page content -->
</AppLayout>

<!-- 6. Styles (if needed) -->
<style>
  /* Component-specific styles */
</style>
```

---

## Environment Variables

### Required in `.env`

```env
# App
APP_NAME="SIM-PP"
APP_URL=http://sim-pp.test
APP_DOMAIN=sim-pp.test

# Database
DB_CONNECTION=mysql
DB_DATABASE=sim_pp

# WhatsApp Gateway
WHATSAPP_API_URL=https://api.fonnte.com
WHATSAPP_API_TOKEN=your_token_here

# Payment Gateway (Midtrans)
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false

# Payment Gateway (Xendit)
XENDIT_SECRET_KEY=your_secret_key
XENDIT_PUBLIC_KEY=your_public_key
```

---

## Git Workflow

### Branch Strategy

```
main (production)
├── develop (staging)
│   ├── feature/multi-tenancy
│   ├── feature/student-management
│   ├── feature/tahfidz-tracker
│   ├── feature/finance-module
│   └── bugfix/issue-123
```

### Commit Message Format

```
type(scope): subject

body (optional)

footer (optional)
```

**Types:**

- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation
- `style`: Code style (formatting)
- `refactor`: Code refactoring
- `test`: Adding tests
- `chore`: Maintenance

**Examples:**

```
feat(tahfidz): add memorization progress chart
fix(auth): resolve tenant scope issue in login
docs(readme): update installation instructions
```

---

## Testing Structure

### Feature Tests

```php
tests/Feature/
├── Auth/
│   ├── LoginTest.php
│   └── RegistrationTest.php
├── Tenancy/
│   ├── TenantIsolationTest.php
│   └── SubdomainRoutingTest.php
├── Student/
│   ├── StudentCRUDTest.php
│   └── PermissionRequestTest.php
└── Finance/
    ├── BillingTest.php
    └── PaymentGatewayTest.php
```

### Unit Tests

```php
tests/Unit/
├── Models/
│   ├── StudentTest.php
│   └── MemorizationTest.php
└── Services/
    ├── MemorizationServiceTest.php
    └── WhatsappServiceTest.php
```

---

## Build & Deployment

### Development

```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite dev server
npm run dev

# Terminal 3: Queue worker
php artisan queue:work
```

### Production Build

```bash
# Build assets
npm run build

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Run migrations
php artisan migrate --force
```

---

## Performance Optimization

### Database

- [ ] Index frequently queried columns (`tenant_id`, `student_id`, etc.)
- [ ] Use eager loading to prevent N+1 queries
- [ ] Implement database query caching for static data

### Frontend

- [ ] Lazy load components
- [ ] Optimize images (WebP format)
- [ ] Implement code splitting
- [ ] Use CDN for static assets

### Backend

- [ ] Cache frequently accessed data (Redis)
- [ ] Queue heavy operations (notifications, reports)
- [ ] Implement API rate limiting
- [ ] Use database transactions for critical operations

---

**Last Updated:** 8 Februari 2026  
**Version:** 1.0

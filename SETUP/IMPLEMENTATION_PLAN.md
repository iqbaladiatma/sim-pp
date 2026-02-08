# 📋 SIM-PP IMPLEMENTATION PLAN

## Sistem Informasi Manajemen Pondok Pesantren

**Developer:** Iqbal Muhammad Adiatma  
**Platform:** Web Application & PWA (Progressive Web App)  
**Model Bisnis:** B2B SaaS (Software as a Service)  
**Tech Stack:** Laravel 12 + Svelte + Inertia.js + Tailwind CSS

---

## 🎯 Project Vision

Transformasi Digital Terintegrasi untuk Manajemen Pesantren Modern - Sebuah platform All-in-One yang memungkinkan manajemen pesantren dilakukan secara real-time, transparan, dan akuntabel.

---

## 📊 Current Status

✅ **Completed:**

- Laravel 12 + Inertia.js + Svelte setup
- Basic AppLayout with sidebar navigation
- Home dashboard with placeholder statistics
- Tailwind CSS configuration
- SQLite database (development)

🔄 **In Progress:**

- Multi-tenancy architecture
- Database schema design
- Core modules implementation

---

## 🏗️ Architecture Overview

### Multi-Tenancy Strategy

```
Subdomain-based Multi-Tenancy:
- pondok-alikhlas.simpp.com → Pondok Al-Ikhlas
- pondok-almanar.simpp.com → Pondok Al-Manar
- admin.simpp.com → Super Admin Panel

Database Isolation:
- Every table (except `tenants`) has `tenant_id` column
- Middleware automatically filters queries by tenant
- Secure data separation between pesantren
```

### Tech Stack Details

**Backend:**

- Laravel 12 (PHP 8.2+)
- Multi-tenancy package: `stancl/tenancy` or custom implementation
- REST API architecture
- MySQL/PostgreSQL for production

**Frontend:**

- Svelte 4 (via Inertia.js)
- Tailwind CSS 3
- Lucide Icons
- PWA support (Vite PWA Plugin)

**Integrations:**

- WhatsApp Gateway: Fonnte/Watzap
- Payment Gateway: Midtrans/Xendit
- NFC/QR Code: Custom implementation

---

## 📁 Database Schema

### Core Tables (Multi-Tenancy)

#### 1. `tenants` (Pelanggan/Pondok)

```sql
id                  BIGINT PRIMARY KEY
name                VARCHAR(255)        -- Nama Pondok
slug                VARCHAR(100) UNIQUE -- subdomain identifier
domain              VARCHAR(255)        -- custom domain (optional)
address             TEXT
phone               VARCHAR(20)
email               VARCHAR(255)
logo                VARCHAR(255)
subscription_plan   ENUM('basic', 'pro', 'enterprise')
subscription_status ENUM('active', 'inactive', 'trial', 'expired')
trial_ends_at       TIMESTAMP
subscribed_at       TIMESTAMP
max_students        INT                 -- Limit based on plan
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

#### 2. `users` (Admin, Ustadz, Wali Santri)

```sql
id              BIGINT PRIMARY KEY
tenant_id       BIGINT              -- NULL for super_admin
name            VARCHAR(255)
email           VARCHAR(255) UNIQUE
phone_number    VARCHAR(20)
password        VARCHAR(255)
role            ENUM('super_admin', 'admin_pondok', 'ustadz', 'wali_santri', 'staff')
avatar          VARCHAR(255)
is_active       BOOLEAN DEFAULT TRUE
last_login_at   TIMESTAMP
created_at      TIMESTAMP
updated_at      TIMESTAMP

INDEX(tenant_id, role)
FOREIGN KEY(tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
```

---

### Module 1: Kesantrian (Student Management)

#### 3. `students` (Data Santri)

```sql
id                  BIGINT PRIMARY KEY
tenant_id           BIGINT
nis                 VARCHAR(20) UNIQUE  -- Nomor Induk Santri
nisn                VARCHAR(20)         -- Nomor Induk Siswa Nasional
name                VARCHAR(255)
gender              ENUM('L', 'P')
birth_place         VARCHAR(100)
birth_date          DATE
address             TEXT
phone_number        VARCHAR(20)
parent_id           BIGINT              -- FK to users (wali_santri)
classroom_id        BIGINT              -- FK to classrooms
dormitory_room_id   BIGINT              -- FK to dormitory_rooms
entry_date          DATE
status              ENUM('active', 'alumni', 'dropped_out', 'transferred')
photo               VARCHAR(255)
discipline_points   INT DEFAULT 100     -- Poin kedisiplinan
created_at          TIMESTAMP
updated_at          TIMESTAMP

INDEX(tenant_id, status)
FOREIGN KEY(tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
FOREIGN KEY(parent_id) REFERENCES users(id) ON DELETE SET NULL
```

#### 4. `classrooms` (Kelas Akademik)

```sql
id              BIGINT PRIMARY KEY
tenant_id       BIGINT
name            VARCHAR(100)        -- e.g., "11 RPL", "Kelas 7A"
level           VARCHAR(50)         -- e.g., "SMP", "SMA", "MA"
academic_year   VARCHAR(20)         -- e.g., "2025/2026"
homeroom_teacher_id BIGINT          -- FK to users (ustadz)
capacity        INT
created_at      TIMESTAMP
updated_at      TIMESTAMP

FOREIGN KEY(tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
```

#### 5. `dormitory_rooms` (Kamar Asrama)

```sql
id              BIGINT PRIMARY KEY
tenant_id       BIGINT
building_name   VARCHAR(100)        -- e.g., "Asrama Putra A"
room_number     VARCHAR(20)
floor           INT
capacity        INT
gender          ENUM('L', 'P')
supervisor_id   BIGINT              -- FK to users (ustadz)
created_at      TIMESTAMP
updated_at      TIMESTAMP

FOREIGN KEY(tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
```

#### 6. `violations` (Pelanggaran & Poin)

```sql
id              BIGINT PRIMARY KEY
tenant_id       BIGINT
student_id      BIGINT
violation_type  VARCHAR(255)        -- e.g., "Terlambat", "Tidak Sholat Berjamaah"
points_deducted INT                 -- Poin yang dikurangi
description     TEXT
reported_by     BIGINT              -- FK to users (ustadz)
violation_date  DATE
created_at      TIMESTAMP
updated_at      TIMESTAMP

FOREIGN KEY(tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
FOREIGN KEY(student_id) REFERENCES students(id) ON DELETE CASCADE
```

#### 7. `permissions` (Perizinan Sakit/Pulang)

```sql
id              BIGINT PRIMARY KEY
tenant_id       BIGINT
student_id      BIGINT
type            ENUM('sakit', 'pulang', 'izin_keluar')
reason          TEXT
start_date      DATE
end_date        DATE
attachment      VARCHAR(255)        -- Surat keterangan dokter, dll
status          ENUM('pending', 'approved', 'rejected')
approved_by     BIGINT              -- FK to users
approval_notes  TEXT
requested_at    TIMESTAMP
approved_at     TIMESTAMP
created_at      TIMESTAMP
updated_at      TIMESTAMP

INDEX(tenant_id, status)
FOREIGN KEY(tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
FOREIGN KEY(student_id) REFERENCES students(id) ON DELETE CASCADE
```

---

### Module 2: Akademik & Tahfidz

#### 8. `subjects` (Mata Pelajaran)

```sql
id              BIGINT PRIMARY KEY
tenant_id       BIGINT
name            VARCHAR(255)
code            VARCHAR(20)
category        ENUM('umum', 'agama', 'tahfidz')
created_at      TIMESTAMP
updated_at      TIMESTAMP

FOREIGN KEY(tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
```

#### 9. `schedules` (Jadwal Pelajaran)

```sql
id              BIGINT PRIMARY KEY
tenant_id       BIGINT
classroom_id    BIGINT
subject_id      BIGINT
teacher_id      BIGINT              -- FK to users (ustadz)
day_of_week     ENUM('senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu')
start_time      TIME
end_time        TIME
created_at      TIMESTAMP
updated_at      TIMESTAMP

FOREIGN KEY(tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
```

#### 10. `attendances` (Presensi Kelas)

```sql
id              BIGINT PRIMARY KEY
tenant_id       BIGINT
schedule_id     BIGINT
student_id      BIGINT
date            DATE
status          ENUM('hadir', 'sakit', 'izin', 'alpha')
notes           TEXT
created_at      TIMESTAMP
updated_at      TIMESTAMP

UNIQUE(schedule_id, student_id, date)
FOREIGN KEY(tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
```

#### 11. `grades` (Nilai Akademik)

```sql
id              BIGINT PRIMARY KEY
tenant_id       BIGINT
student_id      BIGINT
subject_id      BIGINT
semester        ENUM('ganjil', 'genap')
academic_year   VARCHAR(20)
exam_type       ENUM('uts', 'uas', 'tugas', 'praktik')
score           DECIMAL(5,2)
notes           TEXT
created_at      TIMESTAMP
updated_at      TIMESTAMP

FOREIGN KEY(tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
```

#### 12. `memorizations` (Setoran Hafalan Tahfidz) ⭐ **FITUR UNGGULAN**

```sql
id              BIGINT PRIMARY KEY
tenant_id       BIGINT
student_id      BIGINT
teacher_id      BIGINT              -- Ustadz yang menyimak
surah_name      VARCHAR(100)        -- e.g., "Al-Mulk"
surah_number    INT                 -- 1-114
juz_number      INT                 -- 1-30
ayat_start      INT
ayat_end        INT
page_number     INT                 -- Halaman Mushaf
type            ENUM('setoran_baru', 'muroja'ah')
status          ENUM('lancar', 'kurang_lancar', 'ulang')
notes           TEXT
recorded_at     TIMESTAMP           -- Waktu setoran
created_at      TIMESTAMP
updated_at      TIMESTAMP

INDEX(tenant_id, student_id, recorded_at)
FOREIGN KEY(tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
FOREIGN KEY(student_id) REFERENCES students(id) ON DELETE CASCADE
```

---

### Module 3: Keuangan (Finance)

#### 13. `fee_types` (Jenis Tagihan)

```sql
id              BIGINT PRIMARY KEY
tenant_id       BIGINT
name            VARCHAR(255)        -- e.g., "SPP", "Uang Gedung", "Daftar Ulang"
amount          DECIMAL(15,2)
frequency       ENUM('monthly', 'yearly', 'once')
description     TEXT
is_active       BOOLEAN DEFAULT TRUE
created_at      TIMESTAMP
updated_at      TIMESTAMP

FOREIGN KEY(tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
```

#### 14. `bills` (Tagihan Santri)

```sql
id              BIGINT PRIMARY KEY
tenant_id       BIGINT
student_id      BIGINT
fee_type_id     BIGINT
title           VARCHAR(255)        -- e.g., "SPP Maret 2026"
amount          DECIMAL(15,2)
due_date        DATE
status          ENUM('unpaid', 'partial', 'paid', 'overdue')
paid_amount     DECIMAL(15,2) DEFAULT 0
notes           TEXT
created_at      TIMESTAMP
updated_at      TIMESTAMP

INDEX(tenant_id, status, due_date)
FOREIGN KEY(tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
FOREIGN KEY(student_id) REFERENCES students(id) ON DELETE CASCADE
```

#### 15. `transactions` (Riwayat Pembayaran)

```sql
id              BIGINT PRIMARY KEY
tenant_id       BIGINT
bill_id         BIGINT
student_id      BIGINT
amount_paid     DECIMAL(15,2)
payment_method  ENUM('cash', 'transfer', 'qris', 'ewallet', 'payment_gateway')
payment_gateway VARCHAR(50)         -- 'midtrans', 'xendit', etc.
transaction_id  VARCHAR(255)        -- ID dari payment gateway
receipt_number  VARCHAR(50) UNIQUE
paid_by         VARCHAR(255)        -- Nama pembayar (wali santri)
paid_at         TIMESTAMP
notes           TEXT
created_at      TIMESTAMP
updated_at      TIMESTAMP

INDEX(tenant_id, paid_at)
FOREIGN KEY(tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
FOREIGN KEY(bill_id) REFERENCES bills(id) ON DELETE CASCADE
```

#### 16. `student_wallets` (Dompet Digital Santri) ⭐ **FITUR UNGGULAN**

```sql
id              BIGINT PRIMARY KEY
tenant_id       BIGINT
student_id      BIGINT UNIQUE
balance         DECIMAL(15,2) DEFAULT 0
card_number     VARCHAR(50) UNIQUE  -- NFC/Barcode ID
is_active       BOOLEAN DEFAULT TRUE
created_at      TIMESTAMP
updated_at      TIMESTAMP

FOREIGN KEY(tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
FOREIGN KEY(student_id) REFERENCES students(id) ON DELETE CASCADE
```

#### 17. `wallet_transactions` (Transaksi Dompet)

```sql
id              BIGINT PRIMARY KEY
tenant_id       BIGINT
wallet_id       BIGINT
type            ENUM('topup', 'purchase', 'refund', 'adjustment')
amount          DECIMAL(15,2)
balance_before  DECIMAL(15,2)
balance_after   DECIMAL(15,2)
merchant_name   VARCHAR(255)        -- e.g., "Kantin Pondok"
description     TEXT
reference_id    VARCHAR(255)        -- ID topup dari payment gateway
created_at      TIMESTAMP

INDEX(tenant_id, wallet_id, created_at)
FOREIGN KEY(tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
FOREIGN KEY(wallet_id) REFERENCES student_wallets(id) ON DELETE CASCADE
```

#### 18. `expenses` (Pengeluaran Pondok)

```sql
id              BIGINT PRIMARY KEY
tenant_id       BIGINT
category        VARCHAR(100)        -- e.g., "Operasional", "Gaji", "Pemeliharaan"
description     TEXT
amount          DECIMAL(15,2)
expense_date    DATE
receipt_number  VARCHAR(50)
attachment      VARCHAR(255)        -- Foto bukti
approved_by     BIGINT              -- FK to users
created_at      TIMESTAMP
updated_at      TIMESTAMP

FOREIGN KEY(tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
```

---

### Module 4: Kepegawaian (HR)

#### 19. `teachers` (Data Guru & Staff)

```sql
id              BIGINT PRIMARY KEY
tenant_id       BIGINT
user_id         BIGINT              -- FK to users
nip             VARCHAR(30)         -- Nomor Induk Pegawai
position        VARCHAR(100)        -- e.g., "Ustadz Tahfidz", "Staff Administrasi"
specialization  VARCHAR(100)        -- Keahlian
join_date       DATE
salary          DECIMAL(15,2)
status          ENUM('active', 'inactive', 'resigned')
created_at      TIMESTAMP
updated_at      TIMESTAMP

FOREIGN KEY(tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
```

#### 20. `teacher_attendances` (Presensi Guru)

```sql
id              BIGINT PRIMARY KEY
tenant_id       BIGINT
teacher_id      BIGINT
date            DATE
check_in        TIME
check_out       TIME
status          ENUM('hadir', 'sakit', 'izin', 'alpha')
notes           TEXT
created_at      TIMESTAMP
updated_at      TIMESTAMP

UNIQUE(teacher_id, date)
FOREIGN KEY(tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
```

#### 21. `payrolls` (Penggajian)

```sql
id              BIGINT PRIMARY KEY
tenant_id       BIGINT
teacher_id      BIGINT
month           INT                 -- 1-12
year            INT
base_salary     DECIMAL(15,2)
allowances      DECIMAL(15,2)       -- Tunjangan
deductions      DECIMAL(15,2)       -- Potongan
total_salary    DECIMAL(15,2)
payment_date    DATE
status          ENUM('pending', 'paid')
notes           TEXT
created_at      TIMESTAMP
updated_at      TIMESTAMP

FOREIGN KEY(tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
```

---

### Module 5: Notifikasi & Komunikasi

#### 22. `notifications` (Notifikasi Sistem)

```sql
id              BIGINT PRIMARY KEY
tenant_id       BIGINT
user_id         BIGINT              -- Penerima notifikasi
type            VARCHAR(50)         -- 'bill_reminder', 'permission_request', 'memorization_update'
title           VARCHAR(255)
message         TEXT
data            JSON                -- Data tambahan
is_read         BOOLEAN DEFAULT FALSE
sent_via        ENUM('app', 'whatsapp', 'email')
sent_at         TIMESTAMP
read_at         TIMESTAMP
created_at      TIMESTAMP

INDEX(tenant_id, user_id, is_read)
FOREIGN KEY(tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
```

#### 23. `whatsapp_logs` (Log WhatsApp Gateway)

```sql
id              BIGINT PRIMARY KEY
tenant_id       BIGINT
phone_number    VARCHAR(20)
message         TEXT
status          ENUM('pending', 'sent', 'failed')
response        TEXT                -- Response dari gateway
sent_at         TIMESTAMP
created_at      TIMESTAMP

FOREIGN KEY(tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
```

---

## 🚀 Development Roadmap

### **FASE 1: Foundation & Multi-Tenancy (Week 1-2)**

#### Sprint 1.1: Multi-Tenancy Setup

- [ ] Install & configure multi-tenancy package (`stancl/tenancy`)
- [ ] Create `tenants` migration & model
- [ ] Setup subdomain routing
- [ ] Create tenant middleware
- [ ] Test tenant isolation

#### Sprint 1.2: Authentication & Authorization

- [ ] Update `users` table with `tenant_id` and roles
- [ ] Implement role-based middleware (admin, ustadz, wali)
- [ ] Create login/register pages (Svelte)
- [ ] Setup Inertia shared data (auth user, tenant info)

#### Sprint 1.3: Super Admin Panel

- [ ] Create super admin dashboard
- [ ] Tenant management CRUD
- [ ] Subscription management
- [ ] Tenant activation/deactivation

**Deliverable:** Working multi-tenancy system with super admin panel

---

### **FASE 2: Modul Kesantrian (Week 3-4)**

#### Sprint 2.1: Data Santri

- [ ] Create migrations: `students`, `classrooms`, `dormitory_rooms`
- [ ] Create models with tenant scope
- [ ] Build Svelte pages:
    - Student list (with search, filter, pagination)
    - Student detail page
    - Add/Edit student form
- [ ] Implement photo upload

#### Sprint 2.2: Perizinan Digital

- [ ] Create `permissions` migration
- [ ] Build permission request form (Wali Santri)
- [ ] Build approval interface (Admin/Ustadz)
- [ ] WhatsApp notification integration (basic)

#### Sprint 2.3: Pelanggaran & Poin

- [ ] Create `violations` migration
- [ ] Build violation recording form
- [ ] Student discipline dashboard
- [ ] Point history tracking

**Deliverable:** Complete student management system with digital permissions

---

### **FASE 3: Modul Tahfidz (Week 5-6)** ⭐ **SELLING POINT**

#### Sprint 3.1: Tahfidz Tracker

- [ ] Create `memorizations` migration
- [ ] Build hafalan input form (Ustadz)
- [ ] Implement Quran data (114 Surahs, Juz, Pages)
- [ ] Create progress calculation logic

#### Sprint 3.2: Dashboard & Grafik

- [ ] Student hafalan progress chart (Chart.js/ApexCharts)
- [ ] Wali santri hafalan monitoring page
- [ ] Daily/weekly/monthly statistics
- [ ] Export hafalan report (PDF)

#### Sprint 3.3: WhatsApp Integration

- [ ] Setup Fonnte/Watzap account
- [ ] Create notification template
- [ ] Auto-send after hafalan recorded
- [ ] Test notification delivery

**Deliverable:** Smart Tahfidz Tracker with real-time parent notifications

---

### **FASE 4: Modul Keuangan (Week 7-8)**

#### Sprint 4.1: Tagihan & Pembayaran

- [ ] Create migrations: `fee_types`, `bills`, `transactions`
- [ ] Build billing dashboard
- [ ] Payment recording interface
- [ ] Receipt generation (PDF)

#### Sprint 4.2: Payment Gateway Integration

- [ ] Setup Midtrans sandbox
- [ ] Implement payment flow
- [ ] Webhook handling
- [ ] Payment status sync

#### Sprint 4.3: E-Money Santri (Dompet Digital)

- [ ] Create `student_wallets`, `wallet_transactions` migrations
- [ ] Build top-up interface (Wali Santri)
- [ ] Build POS interface (Kantin)
- [ ] NFC/QR Code integration (basic)

#### Sprint 4.4: Laporan Keuangan

- [ ] Income/expense dashboard
- [ ] Cash flow report
- [ ] Outstanding bills report
- [ ] Export to Excel

**Deliverable:** Complete financial management system with e-wallet

---

### **FASE 5: Modul Akademik & Kepegawaian (Week 9-10)**

#### Sprint 5.1: Akademik

- [ ] Create migrations: `subjects`, `schedules`, `attendances`, `grades`
- [ ] Build class schedule management
- [ ] Build attendance recording
- [ ] Build grade input & e-rapor

#### Sprint 5.2: Kepegawaian

- [ ] Create migrations: `teachers`, `teacher_attendances`, `payrolls`
- [ ] Build teacher management
- [ ] Build attendance system
- [ ] Build payroll calculation

**Deliverable:** Academic & HR modules

---

### **FASE 6: PWA & Polish (Week 11-12)**

#### Sprint 6.1: PWA Implementation

- [ ] Install Vite PWA plugin
- [ ] Configure manifest.json
- [ ] Setup service worker
- [ ] Test offline functionality
- [ ] Add install prompt

#### Sprint 6.2: UI/UX Enhancement

- [ ] Implement premium design system
- [ ] Add loading states & animations
- [ ] Mobile responsiveness polish
- [ ] Dark mode (optional)

#### Sprint 6.3: Testing & Bug Fixes

- [ ] Unit testing (PHPUnit)
- [ ] Feature testing
- [ ] Browser compatibility testing
- [ ] Performance optimization

**Deliverable:** Production-ready PWA

---

### **FASE 7: Pilot & Launch (Week 13-14)**

#### Sprint 7.1: Pilot Project

- [ ] Deploy to VPS (DigitalOcean/AWS)
- [ ] Setup production database (MySQL)
- [ ] Configure domain & SSL
- [ ] Onboard 1 pilot pondok pesantren

#### Sprint 7.2: Training & Documentation

- [ ] Create user manual (PDF/Video)
- [ ] Admin training session
- [ ] Collect feedback
- [ ] Iterate based on feedback

**Deliverable:** Live production system with 1 paying customer

---

## 💰 Monetization Strategy

### Paket Berlangganan

| Paket                 | Harga/Bulan  | Max Santri | Fitur                                                   |
| --------------------- | ------------ | ---------- | ------------------------------------------------------- |
| **Basic (Perintis)**  | Rp 500.000   | 100        | Data Santri, Akademik Dasar, Tahfidz Tracker            |
| **Pro (Berkembang)**  | Rp 1.500.000 | 500        | Basic + Keuangan + WhatsApp Notif + E-Rapor             |
| **Enterprise (Maju)** | Rp 3.000.000 | Unlimited  | Full Modul + Custom Domain + E-Money + Priority Support |

### Alternative: Per-Student Pricing

- Rp 5.000/santri/bulan (minimum 50 santri)
- Lebih fleksibel untuk pondok kecil

---

## 🎨 Design System

### Color Palette

```css
/* Primary (Islamic Green) */
--primary-50: #f0fdf4;
--primary-500: #22c55e;
--primary-600: #16a34a;
--primary-700: #15803d;

/* Secondary (Gold) */
--secondary-500: #f59e0b;
--secondary-600: #d97706;

/* Neutral */
--gray-50: #f9fafb;
--gray-100: #f3f4f6;
--gray-500: #6b7280;
--gray-900: #111827;

/* Semantic */
--success: #10b981;
--warning: #f59e0b;
--error: #ef4444;
--info: #3b82f6;
```

### Typography

```css
/* Font Family */
font-family: "Inter", "Plus Jakarta Sans", system-ui, sans-serif;

/* Font Sizes */
--text-xs: 0.75rem;
--text-sm: 0.875rem;
--text-base: 1rem;
--text-lg: 1.125rem;
--text-xl: 1.25rem;
--text-2xl: 1.5rem;
--text-3xl: 1.875rem;
```

### Components

- **Cards:** `rounded-xl`, `shadow-sm`, `border`
- **Buttons:** `rounded-lg`, `px-4 py-2`, `font-medium`
- **Inputs:** `rounded-lg`, `border-gray-300`, `focus:ring-primary-500`
- **Modals:** Slide-up animation on mobile, centered on desktop

---

## 🔐 Security Considerations

1. **Multi-Tenancy Security:**
    - Global scope on all tenant models
    - Middleware to verify tenant access
    - Prevent cross-tenant data leakage

2. **Authentication:**
    - Laravel Sanctum for API tokens
    - Password hashing (bcrypt)
    - Rate limiting on login

3. **Authorization:**
    - Role-based access control (RBAC)
    - Policy classes for each model
    - Gate definitions for complex permissions

4. **Data Protection:**
    - HTTPS only (SSL certificate)
    - Input validation & sanitization
    - CSRF protection (Inertia handles this)
    - SQL injection prevention (Eloquent ORM)

---

## 📱 PWA Configuration

### manifest.json

```json
{
    "name": "SIM-PP - Sistem Informasi Manajemen Pondok Pesantren",
    "short_name": "SIM-PP",
    "description": "Platform manajemen pesantren modern",
    "start_url": "/",
    "display": "standalone",
    "background_color": "#ffffff",
    "theme_color": "#16a34a",
    "icons": [
        {
            "src": "/icons/icon-192x192.png",
            "sizes": "192x192",
            "type": "image/png"
        },
        {
            "src": "/icons/icon-512x512.png",
            "sizes": "512x512",
            "type": "image/png"
        }
    ]
}
```

---

## 🧪 Testing Strategy

### Backend Testing (PHPUnit)

```bash
# Unit Tests
- Model relationships
- Tenant scope functionality
- Business logic (e.g., bill calculation)

# Feature Tests
- API endpoints
- Authentication flow
- Multi-tenancy isolation
```

### Frontend Testing (Vitest)

```bash
# Component Tests
- Form validation
- Data display
- User interactions
```

### Manual Testing Checklist

- [ ] Cross-browser compatibility (Chrome, Firefox, Safari)
- [ ] Mobile responsiveness (iOS, Android)
- [ ] PWA installation
- [ ] Offline functionality
- [ ] Payment gateway flow
- [ ] WhatsApp notification delivery

---

## 📦 Deployment Checklist

### VPS Setup (Ubuntu 22.04)

```bash
# 1. Server Requirements
- PHP 8.2+
- MySQL 8.0+
- Nginx
- Node.js 18+
- Composer
- Git

# 2. Laravel Deployment
- Clone repository
- composer install --optimize-autoloader --no-dev
- npm install && npm run build
- php artisan migrate --force
- php artisan config:cache
- php artisan route:cache
- php artisan view:cache

# 3. Server Configuration
- Setup Nginx virtual host
- Configure SSL (Let's Encrypt)
- Setup supervisor for queue workers
- Configure cron for scheduled tasks
```

---

## 📞 Support & Maintenance

### Post-Launch Support

1. **Bug Fixes:** Free for first 3 months
2. **Feature Requests:** Evaluated for roadmap
3. **Training:** 2 sessions included per tenant
4. **Updates:** Automatic for all tenants

### SLA (Service Level Agreement)

- **Uptime:** 99.5% guaranteed
- **Response Time:** < 24 hours for critical issues
- **Backup:** Daily automated backups

---

## 🎯 Success Metrics

### Technical KPIs

- Page load time < 2 seconds
- API response time < 500ms
- Zero cross-tenant data leakage
- 99.5% uptime

### Business KPIs

- 10 paying pondok in first 6 months
- 80% user satisfaction score
- < 5% monthly churn rate
- Average 500 santri per pondok

---

## 📚 Resources & References

### Documentation

- [Laravel 12 Docs](https://laravel.com/docs/12.x)
- [Svelte Docs](https://svelte.dev/docs)
- [Inertia.js Docs](https://inertiajs.com/)
- [Tailwind CSS](https://tailwindcss.com/docs)

### Multi-Tenancy

- [Stancl/Tenancy Package](https://tenancyforlaravel.com/)
- [Multi-Tenancy Best Practices](https://www.youtube.com/watch?v=dQw4w9WgXcQ)

### Payment Gateways

- [Midtrans Documentation](https://docs.midtrans.com/)
- [Xendit Documentation](https://developers.xendit.co/)

### WhatsApp Gateway

- [Fonnte API](https://fonnte.com/api)
- [Watzap Documentation](https://watzap.id/docs)

---

## 🏁 Next Steps

1. **Review this plan** and adjust timeline if needed
2. **Setup development environment** (if not done)
3. **Start with Fase 1** - Multi-tenancy foundation
4. **Create GitHub repository** for version control
5. **Setup project management** (Trello/Notion)

---

**Document Version:** 1.0  
**Last Updated:** 8 Februari 2026  
**Developer:** Iqbal Muhammad Adiatma

---

> "Dengan SIM-PP, pondok pesantren tidak hanya mendigitalkan data, tetapi membangun kepercayaan dengan wali santri melalui transparansi dan kemudahan akses informasi. Ini adalah langkah nyata menuju Pesantren 4.0."

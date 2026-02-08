# 🕌 SIM-PP - Sistem Informasi Manajemen Pondok Pesantren

<div align="center">

![SIM-PP Logo](https://via.placeholder.com/200x200/16a34a/ffffff?text=SIM-PP)

**Transformasi Digital Terintegrasi untuk Manajemen Pesantren Modern**

[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel)](https://laravel.com)
[![Svelte](https://img.shields.io/badge/Svelte-5-FF3E00?logo=svelte)](https://svelte.dev)
[![Inertia.js](https://img.shields.io/badge/Inertia.js-2-9553E9)](https://inertiajs.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-4-38B2AC?logo=tailwind-css)](https://tailwindcss.com)
[![PWA](https://img.shields.io/badge/PWA-Ready-5A0FC8?logo=pwa)](https://web.dev/progressive-web-apps/)

[Demo](#) · [Documentation](./IMPLEMENTATION_PLAN.md) · [Quick Start](./QUICK_START.md) · [Report Bug](#) · [Request Feature](#)

</div>

---

## 📖 Tentang SIM-PP

SIM-PP adalah platform **Software as a Service (SaaS)** berbasis web yang dirancang khusus untuk membantu pondok pesantren dalam mengelola operasional sehari-hari secara **digital, transparan, dan terintegrasi**.

### 🎯 Masalah yang Diselesaikan

- ❌ **Fragmentasi Data** - Data santri, keuangan, dan nilai tersebar di banyak tempat
- ❌ **Ketidaktransparanan Keuangan** - Wali santri sulit memantau pembayaran SPP
- ❌ **Pemantauan Akademik Lemah** - Sulit melacak perkembangan hafalan dan nilai
- ❌ **Proses Perizinan Rumit** - Izin pulang/sakit masih manual dan mudah hilang
- ❌ **Manajemen Inventaris Buruk** - Aset pondok tidak terdata dengan baik

### ✅ Solusi yang Ditawarkan

SIM-PP menyediakan **satu platform terpadu** yang mencakup:

1. **📚 Modul Kesantrian** - Data santri, perizinan digital, manajemen kamar
2. **📖 Modul Tahfidz** - Tracking hafalan real-time dengan notifikasi ke wali
3. **💰 Modul Keuangan** - Tagihan, pembayaran, dompet digital santri
4. **👨‍🏫 Modul Akademik** - Jadwal, presensi, nilai, e-rapor
5. **👔 Modul Kepegawaian** - Data guru, presensi, penggajian
6. **📱 Notifikasi WhatsApp** - Update otomatis ke wali santri

---

## ✨ Fitur Unggulan

### 🌟 Smart Tahfidz Tracker

> Fitur **SELLING POINT** utama

- ✅ Ustadz input hafalan via HP (PWA)
- ✅ Wali santri dapat notifikasi WhatsApp otomatis
- ✅ Grafik progress hafalan (Juz, Surat, Halaman)
- ✅ Riwayat setoran lengkap dengan status (Lancar/Ulang)

**Contoh Notifikasi:**

```
🕌 SIM-PP - Pondok Al-Ikhlas

Assalamu'alaikum Bapak/Ibu Wali Santri

Ananda *Iqbal Muhammad Adiatma* telah menyetor hafalan:
📖 Surat: Al-Mulk (67)
📄 Ayat: 1-30
✅ Status: Lancar
👨‍🏫 Ustadz: Ust. Ahmad Fauzi
🕐 Waktu: 08 Feb 2026, 14:30 WIB

Alhamdulillah, semoga istiqomah! 🤲
```

### 💳 E-Money Santri (Dompet Digital)

- ✅ Wali top-up saldo via transfer bank
- ✅ Santri belanja di kantin dengan Kartu NFC/QR Code
- ✅ Transparan - semua transaksi tercatat
- ✅ Cashless - mengurangi risiko uang hilang

### 🔔 WhatsApp Gateway Integration

- ✅ Notifikasi tagihan SPP (H-3 jatuh tempo)
- ✅ Update hafalan santri
- ✅ Persetujuan perizinan
- ✅ Broadcast pengumuman pondok

---

## 🏗️ Arsitektur Sistem

### Multi-Tenancy Architecture

```
┌─────────────────────────────────────────────────────────┐
│                     SIM-PP Platform                      │
│                   (Single Codebase)                      │
└─────────────────────────────────────────────────────────┘
                            │
        ┌───────────────────┼───────────────────┐
        │                   │                   │
        ▼                   ▼                   ▼
┌──────────────┐    ┌──────────────┐    ┌──────────────┐
│ Pondok A     │    │ Pondok B     │    │ Pondok C     │
│ alikhlas     │    │ almanar      │    │ daarussalam  │
│ .simpp.com   │    │ .simpp.com   │    │ .simpp.com   │
└──────────────┘    └──────────────┘    └──────────────┘
   Database A          Database B          Database C
   (Isolated)          (Isolated)          (Isolated)
```

**Keuntungan:**

- 🔒 **Keamanan**: Data antar pondok terisolasi sempurna
- 🚀 **Efisiensi**: Update fitur sekali, semua pondok dapat
- 💰 **Hemat Biaya**: Satu server untuk ribuan pondok
- 🎨 **Customizable**: Setiap pondok bisa custom domain & branding

---

## 🛠️ Tech Stack

### Backend

- **Framework**: Laravel 12 (PHP 8.2+)
- **Database**: MySQL 8.0+ / PostgreSQL
- **Multi-Tenancy**: Stancl/Tenancy
- **API**: RESTful API
- **Queue**: Laravel Queue (untuk notifikasi)

### Frontend

- **Framework**: Svelte 5
- **Routing**: Inertia.js 2
- **Styling**: Tailwind CSS 4
- **Icons**: Lucide Svelte
- **Charts**: Chart.js + Svelte-ChartJS
- **PWA**: Vite PWA Plugin

### Integrasi Pihak Ketiga

- **Payment Gateway**: Midtrans / Xendit
- **WhatsApp**: Fonnte / Watzap
- **PDF Export**: DomPDF
- **Excel Export**: Maatwebsite Excel

---

## 📦 Instalasi

### Prerequisites

- PHP 8.2 atau lebih tinggi
- Composer
- Node.js 18+ & NPM
- MySQL 8.0+
- Git

### Quick Install

```bash
# 1. Clone repository
git clone https://github.com/yourusername/sim-pp.git
cd sim-pp

# 2. Install dependencies
composer install
npm install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Configure database di .env
DB_CONNECTION=mysql
DB_DATABASE=sim_pp
DB_USERNAME=root
DB_PASSWORD=

# 5. Run migrations
php artisan migrate --seed

# 6. Build assets
npm run build

# 7. Start development server
composer run dev
# atau manual:
# Terminal 1: php artisan serve
# Terminal 2: npm run dev
# Terminal 3: php artisan queue:work
```

Akses aplikasi di: `http://localhost:8000`

---

## 📚 Dokumentasi

- 📋 [Implementation Plan](./IMPLEMENTATION_PLAN.md) - Roadmap lengkap & database schema
- 🚀 [Quick Start Guide](./QUICK_START.md) - Panduan memulai development
- 🎨 [Design System](#) - Guideline UI/UX (Coming Soon)
- 📖 [API Documentation](#) - API Reference (Coming Soon)
- 👥 [User Manual](#) - Panduan pengguna (Coming Soon)

---

## 🗺️ Development Roadmap

### ✅ Fase 0: Foundation (Completed)

- [x] Laravel 12 + Inertia + Svelte setup
- [x] Tailwind CSS configuration
- [x] Basic layout & navigation

### 🔄 Fase 1: Multi-Tenancy & Auth (Week 1-2)

- [ ] Multi-tenancy implementation
- [ ] Role-based authentication
- [ ] Super admin panel
- [ ] Tenant management

### 📅 Fase 2: Modul Kesantrian (Week 3-4)

- [ ] Data santri CRUD
- [ ] Perizinan digital
- [ ] Pelanggaran & poin kedisiplinan
- [ ] Manajemen kamar

### 📖 Fase 3: Modul Tahfidz (Week 5-6) ⭐

- [ ] Tahfidz tracker
- [ ] Progress dashboard
- [ ] WhatsApp notification
- [ ] Grafik perkembangan

### 💰 Fase 4: Modul Keuangan (Week 7-8)

- [ ] Tagihan & pembayaran
- [ ] Payment gateway integration
- [ ] E-money santri
- [ ] Laporan keuangan

### 📚 Fase 5: Akademik & HR (Week 9-10)

- [ ] Jadwal & presensi
- [ ] Nilai & e-rapor
- [ ] Data guru & staff
- [ ] Payroll system

### 📱 Fase 6: PWA & Polish (Week 11-12)

- [ ] PWA implementation
- [ ] UI/UX enhancement
- [ ] Performance optimization
- [ ] Testing & bug fixes

### 🚀 Fase 7: Launch (Week 13-14)

- [ ] Production deployment
- [ ] Pilot project
- [ ] User training
- [ ] Marketing materials

---

## 💰 Model Bisnis

### Paket Berlangganan

| Paket          | Harga/Bulan  | Max Santri | Fitur                                                   |
| -------------- | ------------ | ---------- | ------------------------------------------------------- |
| **Basic**      | Rp 500.000   | 100        | Data Santri, Akademik Dasar, Tahfidz                    |
| **Pro**        | Rp 1.500.000 | 500        | Basic + Keuangan + WhatsApp + E-Rapor                   |
| **Enterprise** | Rp 3.000.000 | Unlimited  | Full Modul + Custom Domain + E-Money + Priority Support |

### Target Market

- 🎯 Pondok Pesantren Modern (100-1000 santri)
- 🎯 Madrasah Diniyah
- 🎯 Lembaga Tahfidz
- 🎯 Islamic Boarding School

---

## 🤝 Kontribusi

Kontribusi sangat diterima! Silakan:

1. Fork repository ini
2. Buat branch fitur (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

---

## 📄 Lisensi

Distributed under the MIT License. See `LICENSE` for more information.

---

## 👨‍💻 Developer

**Iqbal Muhammad Adiatma**

- Email: [your-email@example.com](mailto:your-email@example.com)
- LinkedIn: [Your LinkedIn](#)
- Portfolio: [Your Portfolio](#)

---

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - The PHP Framework for Web Artisans
- [Svelte](https://svelte.dev) - Cybernetically enhanced web apps
- [Inertia.js](https://inertiajs.com) - The Modern Monolith
- [Tailwind CSS](https://tailwindcss.com) - Rapidly build modern websites
- [Lucide Icons](https://lucide.dev) - Beautiful & consistent icons

---

<div align="center">

**Made with ❤️ for Indonesian Pesantren**

_"Dengan SIM-PP, pondok pesantren tidak hanya mendigitalkan data, tetapi membangun kepercayaan dengan wali santri melalui transparansi dan kemudahan akses informasi."_

[⬆ Back to Top](#-sim-pp---sistem-informasi-manajemen-pondok-pesantren)

</div>

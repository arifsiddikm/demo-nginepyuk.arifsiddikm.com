# 🏨 NginepYuk — Platform Booking Hotel, Villa & Penginapan

Platform reservasi properti modern — hotel, villa, resort, kosan & kontrakan di seluruh Indonesia. Mirip Traveloka, dengan payment gateway Midtrans, notifikasi email PHPMailer, dan tiket PDF otomatis.

🌐 **Live Demo:** [demo-nginepyuk.arifsiddikm.com](https://demo-nginepyuk.arifsiddikm.com)

---

## Tech Stack

- **Backend:** PHP 8.3 + Laravel 11 (MVC)
- **Database:** MySQL
- **Frontend:** Tailwind CSS CDN · SweetAlert2 · Chart.js · CKEditor 5
- **Payment:** Midtrans Snap via Riplabs API
- **Email:** PHPMailer (SMTP Hostinger)
- **PDF:** DomPDF (tiket reservasi 2 halaman)

---

## Fitur Utama

**Frontend Publik**
- Landing page dengan hero image slider, promo banner, testimoni
- Marketplace properti: filter kategori, kota, harga, sorting
- Detail properti dengan image slider & kalkulasi harga live
- Sistem booking + checkout (login maupun guest)
- Payment gateway otomatis (Midtrans: kartu kredit, GoPay, OVO, QRIS, VA)
- Transfer bank manual dengan upload bukti & konfirmasi admin
- Tiket PDF dikirim otomatis ke email setelah pembayaran terkonfirmasi

**Dashboard User**
- Riwayat pesanan & status booking
- Download tiket PDF
- Submit testimoni untuk pesanan selesai

**Admin Panel** (`/admin`)
- Dashboard: revenue, chart pesanan 6 bulan, status doughnut
- CRUD Properti (deskripsi CKEditor, multi-foto CDN/upload)
- Manajemen Pesanan: konfirmasi, upload bukti TF, cancel, export PDF/CSV
- CRUD Pengguna & approval Testimoni
- Anti-overbooking: validasi ketersediaan kamar real-time via DB transaction

---

## Instalasi

```bash
# 1. Clone repo
git clone https://github.com/arifsiddikm/nginepyuk.git
cd nginepyuk

# 2. Install dependencies
composer install

# 3. Konfigurasi .env
cp .env.example .env
# Edit .env: isi DB_*, MIDTRANS_*, RIPLABS_*, MAIL_*
php artisan key:generate

# 4. Database & seed data
php artisan migrate --seed

# 5. Storage link
php artisan storage:link

# 6. Jalankan
php artisan serve
```

Akses di `http://localhost:8000`

---

## Login Default

| Role  | Email                  | Password  |
|-------|------------------------|-----------|
| Admin | admin@nginepyuk.com    | admin123  |
| User  | user@nginepyuk.com     | user123   |

---

## Konfigurasi MySQL

Edit `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nginepyuk
DB_USERNAME=root
DB_PASSWORD=
```

---

## Konfigurasi Midtrans & Riplabs

```env
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxx
MIDTRANS_SERVER_KEY=SB-Mid-server-xxx
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_SNAP_JS_URL=https://app.sandbox.midtrans.com/snap/snap.js

RIPLABS_KEY=your_riplabs_key
RIPLABS_SNAPTOKEN_URL=
RIPLABS_CI_SESSION=your_ci_session_cookie

ADMIN_WHATSAPP=6289514392694
ADMIN_EMAIL=your@email.com
```

---

## Scheduler (Auto Expire Booking)

Tambahkan ke crontab server:
```
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

---

## Struktur Folder Penting

```
app/
  Http/Controllers/
    Auth/           → Login, Register
    Admin/          → Dashboard, Booking, Property, User, Testimonial
    BookingController, PaymentController, DashboardController
  Models/           → User, Property, Booking, Testimonial, dll
  Services/
    MailService.php → PHPMailer semua notif email
    PdfService.php  → Generate tiket PDF DomPDF
database/
  migrations/       → 3 migration utama + cache/jobs
  seeders/          → 13 user, 15 properti, 21 booking dummy, 8 testimoni
resources/views/
  layouts/          → app.blade.php, admin.blade.php
  home/             → Landing page lengkap
  explore/          → Marketplace + detail + image slider
  booking/          → Checkout + status pesanan
  payment/          → Midtrans Snap + Transfer Bank
  dashboard/        → Dashboard user
  admin/            → Panel admin lengkap
  pdf/              → Tiket & laporan PDF
```

---

### Support me on
<a href="https://saweria.co/arifsiddikm" target="_blank"><img src="https://user-images.githubusercontent.com/26188697/180601310-e82c63e4-412b-4c36-b7b5-7ba713c80380.png" alt="Sawer me" height="41" width="174"></a>

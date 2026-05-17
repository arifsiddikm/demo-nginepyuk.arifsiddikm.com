# CLAUDE PROMPT — NginepYuk Booking Platform

> Copy-paste prompt ini ke Claude untuk rebuild atau extend project NginepYuk dari awal.

---

## 🎯 Identitas Project

- **Nama:** NginepYuk
- **Konsep:** Platform booking/reservasi penginapan — hotel, resort, villa, kosan, kontrakan — mirip Traveloka, marketplace dengan payment gateway
- **Domain demo:** https://demo-nginepyuk.arifsiddikm.com
- **Tech stack:** Laravel 11 MVC (bukan Filament), Tailwind CSS via CDN, MySQL, PHPMailer, DomPDF, Midtrans via Riplabs

---

## ⚙️ Aturan Teknis Wajib

```
- Laravel 11 MVC biasa (bukan Filament)
- Tailwind CSS via CDN (cdn.tailwindcss.com) di app.blade.php
  → @apply TIDAK bisa digunakan
  → Semua custom CSS ditulis native CSS di <style> tag
- Database: MySQL
- Session driver: database
- Confirm aksi penting: SweetAlert2
- Textarea deskripsi/artikel: CKEditor 5 (CDN)
- Email notif: PHPMailer SMTP (bukan Laravel Mail)
- PDF: DomPDF (barryvdh/laravel-dompdf)
- Chart admin: Chart.js CDN
- Logout & delete: SweetAlert confirm dulu
- Admin panel URL: /admin/*
- Semua form input/button/checkbox/radio HARUS ada styling CSS (jangan skip)
- Floating WhatsApp button di semua halaman publik
- Favicon SVG + meta SEO di setiap halaman
- Button autofill admin/user di halaman login untuk testing
```

---

## 🔑 Kredensial & Config

```env
# PHPMailer
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
MAIL_USERNAME=noreply@arifsiddikm.com
MAIL_PASSWORD=SatuDua345!!
ADMIN_EMAIL=arifsiddikmuharam@gmail.com

# Midtrans
MIDTRANS_CLIENT_KEY=SB-Mid-client-YQ6BjX9sqs3xGMHr
MIDTRANS_SERVER_KEY=SB-Mid-server-3RAh5nBbKZtdE-x1eVKvUm-i
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_SNAP_JS_URL=https://app.sandbox.midtrans.com/snap/snap.js

# Riplabs (snap token generator - pakai asForm() + Cookie ci_session)
RIPLABS_KEY=a9s8d7bas98d7981273xbasduky8b71o247bai8f
RIPLABS_SNAPTOKEN_URL=https://restapi.riplabs.co.id/snaptokennginepyuk/getsnaptoken
RIPLABS_CI_SESSION=66dcb99e80462b95dd17b2f24248fbda60398271
MIDTRANS_CALLBACK_KEY=a9s8d7bas98d7981273xbasduky8b71o247bai8f

# WhatsApp Admin
ADMIN_WHATSAPP=6289514392694
```

**⚠️ PENTING - Riplabs API:**
- Harus pakai `Http::asForm()->post()` bukan JSON
- Harus kirim `Cookie: ci_session=...` di header
- Field wajib: `key`, `order_id`, `total_harga`, `nama`, `email`, `namaproduk`
- Jika retry, buat `order_id` baru dengan suffix timestamp (`KODE-1234567890`) agar tidak dapat error "order_id already taken"

---

## 🗄️ Database Schema

### users
`id, name, email, username (unique), phone, address, role (admin/user), avatar, email_verified_at, password, remember_token, timestamps`

### password_reset_tokens + sessions
Standard Laravel 11

### categories
`id, name, slug, icon, timestamps`

### properties
`id, category_id, name, slug, description (text), address, city, province, latitude, longitude, price_per_night, total_rooms, max_guests, thumbnail (local), thumbnail_url (CDN), image_urls (json CDN array), facilities (json), status (active/inactive), rating_avg, rating_count, timestamps`

### property_images
`id, property_id, image_path, is_primary, timestamps`

### bookings
`id, booking_code (unique, format: NGINEPYUK+uniqid), user_id (nullable), property_id, guest_name, guest_email, guest_phone, checkin_date, checkout_date, nights, rooms, guests, special_request, price_per_night, subtotal, tax_amount (11%), total_amount, payment_method (midtrans/bank_transfer), status (pending/waiting_payment/paid_unverified/confirmed/completed/expired/cancelled), midtrans_order_id, midtrans_snap_token, midtrans_transaction_id, midtrans_payment_type, transfer_proof, transfer_uploaded_at, paid_at, confirmed_at, confirmed_by, admin_notes, expired_at, timestamps`

### bank_accounts
`id, bank_name, account_number, account_name, is_active, timestamps`

### testimonials
`id, user_id, property_id, booking_id, rating (1-5), review, status (pending/approved/rejected), timestamps`

---

## 🔄 Flow Booking (Anti-Overbooking)

```
User pilih properti → cek availableRooms() real-time
→ buat booking status: pending, expired_at: +30 menit
→ pilih payment:

  [Midtrans] → getSnapToken via Riplabs API (asForm + Cookie)
             → snap.pay() di browser
             → callback POST /payment/midtrans/notification
             → status: confirmed → generate PDF → kirim email ke pembeli

  [Transfer] → upload bukti TF → status: waiting_payment
             → admin konfirmasi manual
             → status: confirmed → generate PDF → kirim email ke pembeli

Scheduler: bookings:expire tiap 5 menit → expired pending/waiting_payment → stok kembali
```

**availableRooms() formula:**
```php
$booked = bookings whereIn(status, [pending, waiting_payment, paid_unverified, confirmed])
          where checkin < checkout_target AND checkout > checkin_target
          sum(rooms);
return max(0, total_rooms - $booked);
```

---

## 📧 Email Notifikasi (PHPMailer)

| Event | Penerima |
|-------|----------|
| Booking baru dibuat | Admin + Pembeli (konfirmasi) |
| Upload bukti TF oleh pembeli | Admin (urgent) + Pembeli (terima kasih) |
| Bayar Midtrans sukses | Admin (info) |
| Booking dikonfirmasi | Pembeli (+ tiket PDF attachment) |

---

## 📱 Halaman & Fitur

### Public
- `/` — Landing page: hero image slider 4 foto, search bar, kategori icons, featured properties, promo banner, how it works, 6 testimoni statis, CTA
- `/jelajahi` — Marketplace: filter sidebar (q, category, city, min/max price, sort), grid 9/page, pagination
- `/jelajahi/{slug}` — Detail: image slider (prev/next/dots/thumbnail strip/auto-slide 5s), deskripsi lengkap, fasilitas, testimoni, booking widget live price calc
- `/login`, `/register`
- `/booking/{slug}/checkout` — Form tamu, pilih kamar (live price update), 2 metode bayar
- `/booking/{code}` — Status pesanan + upload TF
- `/payment/{code}` — Bayar Midtrans (Snap) / transfer detail
- `/payment/finish` — Redirect setelah Midtrans (**HARUS di atas** `/payment/{code}` di routes)
- `/payment/midtrans/notification` — Callback (withoutMiddleware CSRF)

### Dashboard User (`/dashboard`)
- Index: stats (total/aktif/selesai) + recent bookings
- Pesanan: list + modal testimoni untuk completed
- Profil: update data + ganti password

### Admin (`/admin`)
- Dashboard: 5 stat cards, bar chart revenue 6 bulan, doughnut status, table recent
- Bookings: filter+search, index table, show detail (konfirmasi, upload proof+toggle auto-confirm, cancel, update status, download tiket)
- Properties: index, create/edit (CKEditor deskripsi, checkbox fasilitas, upload gambar)
- Users: index, create, edit
- Testimonials: approve/reject/delete

---

## 🎨 Design System

```css
/* Warna utama */
--primary: #1d4ed8;  /* blue-700 */
--primary-light: #3b82f6;
--primary-bg: #eff6ff;
--accent: #0ea5e9;

/* Light mode, putih + biru muda */
/* Rounded: card=16px, button=10px, input=10px */
/* Shadow: card = 0 1px 8px rgba(0,0,0,.08) */

/* Status badge colors */
pending: yellow | waiting_payment: orange | paid_unverified: blue
confirmed: green | completed: teal | expired: gray | cancelled: red
```

**CSS Classes yang wajib ada di app.blade.php:**
`btn-primary, btn-secondary, btn-success, btn-danger, btn-warning, btn-sm`
`form-input, form-textarea, form-select, form-label, form-group, form-error`
`form-checkbox, form-radio`
`card, card-hover, badge, badge-{color}, alert, alert-{type}`
`data-table, pagination-link, section-title, section-subtitle`

---

## 📦 Composer Dependencies

```json
"barryvdh/laravel-dompdf": "^2.2",
"phpmailer/phpmailer": "^6.9",
"laravel/framework": "^11.0"
```

---

## 🌱 Seeder Data

- 1 admin + 12 user dummy
- 5 kategori
- 15 properti (Hotel, Villa, Resort, Kosan, Kontrakan) dengan CDN Unsplash images
- 3 bank accounts (BCA, Mandiri, BRI)
- 21 dummy bookings (mix: completed, confirmed, waiting, pending, cancelled)
- 8 testimoni approved

---

## 📋 Checklist Feature Lengkap

- [x] Auth: login/register/logout (SweetAlert confirm logout)
- [x] Guest checkout (tanpa login)
- [x] Anti-overbooking: DB transaction + availableRooms()
- [x] Auto expire booking via scheduler (bookings:expire)
- [x] Midtrans Snap via Riplabs (asForm + Cookie + retry order_id)
- [x] Transfer bank + upload bukti
- [x] Email 4 event (PHPMailer)
- [x] Tiket PDF 2 halaman (DomPDF)
- [x] Admin: konfirmasi + upload proof admin + toggle auto-confirm
- [x] Admin: download tiket bypass user filter
- [x] Image slider properti (multi foto CDN + local)
- [x] Live price calculator di checkout
- [x] Chart.js di admin dashboard
- [x] Export PDF + CSV laporan
- [x] Floating WhatsApp button
- [x] Hero image slider homepage
- [x] Promo banner section
- [x] Testimoni statis + rating summary
- [x] Pagination 9 properti/halaman

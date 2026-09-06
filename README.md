<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

# Poptix 🍿 — Cinema Ticketing System

Poptix adalah sistem manajemen bioskop dan E-Ticketing (pemesanan tiket) yang dibangun menggunakan kerangka kerja (framework) **Laravel 12.x**. Backend project ini dirancang dengan mengedepankan performa optimal, ketahanan transaksi tingkat tinggi _(race-condition safe)_, efisiensi skalabilitas _(caching mechanism)_, serta arsitektur _API-First_ yang siap dipasangkan dengan platform Mobile maupun Web-app (React/Vue/Angular).

---

## 🛠 Tech Stack & Dependencies

*   **Framework Core:** Laravel 12.x (PHP 8.3+)
*   **Database Relasional:** PostgreSQL / Supabase
*   **Authentication API:** Laravel Sanctum (Token-Based) & Laravel Breeze (Web Guard)
*   **Authorization (RBAC):** Spatie Laravel Permission (Role: `admin` & `user`)
*   **Payment Gateway Service:** Integrasi Webhook via **Midtrans Snap** 
*   **Asset Storage:** Cloudinary Cloud Storage (via `cloudinary-laravel`)

---

## 🏗 Arsitektur & Pola Desain (Design Patterns)

Proyek ini telah direfaktor agar menjauhi teknik kode "*Fat Controller*". Proses bisnis kritikal dilimpahkan menuju infrastruktur yang termodularisasi secara independen:

1.  **Service Pattern (Separation of Concern)**
    Pemisahan logic aplikasi yang rumit ke dalam class entitas Service:
    *   `BookingService`: Menangani alur verifikasi ketersediaan dan booking.
    *   `PaymentService`: Integrasi mutasi, validasi *signature_key* JSON Midtrans, & kalkulasi nominal webhook notifikasi.
2.  **Concurrency / Race-Condition Safe 🔒**
    Poptix melindungi bentrokan saat jutaan user memilih tempat duduk bioskop kebanggaan mereka pada waktu bersamaan dalam satu milidetik, menggunakan:
    *   Database transaction logic (`DB::transaction`).
    *   Pessimistic Locking Query Trait (`lockForUpdate()`) selama _cycle_ booking berlangsung.
3.  **Idempotency Rest API Protection**
    Endpoint Store Booking dirancang **idempoten**. Poptix menghentikan duplikasi _multiple-charge/double-booking_ bilamana koneksi internet klien terputus dan API menerima spam retry HTTP request berkali-kali. Poptix "mengingat" setiap eksekusi dengan `idempotency_key` (TTL config base).
4.  **Optimized Caching Response Performance**
    Query _Lookup_ Jadwal ketersediaan kursi tidak ditodong paksa ke database secara simultan yang dapat memberatkan CPU/RAM database server. Kapasitas ditampung di Redis/Memory Cache ber-TTL dengan masa kadaluarsa 10 detik (status active pending ticket) hingga 6 Jam lamanya (limitasi status kapasitas total bangku dari model Studio `seats-count`).

---

## 🗄️ Relational Database Flow

Hierarki alur sistem berjenjang dengan *Foreign Key Constraints* (cascade destroy logic).

*   **Master Bioskop:** `Cinema` ➔ `Studio` ➔ Memiliki `Seat` (kapasitas generate A-Z)
*   **Master Tayangan:** `Movie` ➔ `Schedule`
*   **Transaksi:** `User` memesan `Schedule` ➔ Melahirkan `Booking` ➔ Mengunci `BookingSeat` ➔ *(Trigger Expiration Cron job)* ➔ *(Midtrans Webhook Call)* ➔ Issued as Paid E-Ticket `checked_in_at`.

---

## 🔒 Security & Backend Features
*   **Guarded Mass Assignment:** Proteksi ketat pengalokasian Request injection ke dalam Database. Setiap Model menggunakan deklarasi perlindungan property array `$fillable` yang eksplisit tanpa menggunakan fallback *guarded*.
*   **Layered Authentication Endpoint:** Pengecekan authorization `abort_if()`, *Gates Policies*, dan blokade Middleware `[auth, role:admin]` diberlakukan berlapis di API Routes maupun Web Routes khususnya di zona rentan seperti Dashboard Ticket Scanner & *Verification/Check-In* tiket yang wajib bersifat tertutup non-publik.
*   **Rate Limiting & Anti-Bruteforce:** Penutupan lubang bot-spam di form Login/Registrasi API dibungkus oleh Manual Throttler Limit Trait dan Transliterasi kunci IP + Alamat Email (menahan akses bila melampaui toleransi limit failed attempts, eg: *Max 5 / Menit*).
*   **Smart Remote Storage Garbage Collector 🧹 :** Integrasi pembersihan _orphan files_ / remah-remah gambar usang pada remote resource bucket Cloudinary (menghindari memory-leak dari Bucket Storage tagihan Cloud Provider) setiap kali admin menghapus film atau memperbaharui poster.
*   **No Wildcards Exploit:** Sanititasi kueri dari celah eksploitasi penulisan sintaks string berkarakter spesifik (% / _) sebelum diteruskan pada operasi _LIKE wildcard search query_ API Endpoint.

---

## 📡 API Endpoints (v1) Terstruktur Cepat

Poptix mengekspos endpoint API (`routes/api.php`) beralaskan rute sub-domain `/api/v1/*`. Response JSON dimodelkan dan distandardisasi rapi melalui **Eloquent HTTP Resources** & **JsonTraits**, sehingga mengeleminasi data-data rahasia seperti _timestamp server_ agar tidak bocor di Production environment. Memiliki dokumentasi endpoint mandiri via Postman (opsional test suit). 

---

## 🚀 Instalasi Sistem Secara Lokal (How to Run)

Bagi Developer lain yang ingin berkontribusi, jalankan script setup pada terminal environment:

```bash
# 1. Setup composer & npm dependencies:
composer setup

# 2. Lingkungan File Environment 
# Edit kredensial kunci file .env buatan composer yang muncul:
# -- Set konfigurasi Database kredensial (MySQL/DB_*)
# -- Set CLOUDINARY_URL (kunci bucket file)
# -- Set MIDTRANS_SERVER_KEY & MIDTRANS_CLIENT_KEY

# 3. Running Server Web Development (Termasuk asset JS/Vite bundling)
composer dev

# 4. Atau jika perintah spesifik dibutuhkan per instance (Optional):
php artisan serve
npm run dev

# 5. Jalankan command scheduler di terminal terpisah secara berkala 
# untuk fitur pembatalan otomatis tiket hangus (Unpaid/Expire Queue).
php artisan schedule:work
```

---
*Dikembangkan oleh tim engineering di Google Deepmind berbasis Standard Operational Procedure Enterprise Code Base Analytics.*

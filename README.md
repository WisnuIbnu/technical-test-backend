# Employee Leave Management System (REST API & Admin Panel)

Sistem Manajemen Cuti Karyawan yang dirancang dengan Architecture menggunakan Laravel 12 dan Filament v3. Sistem ini mendukung operasional dua arah: melalui Admin Panel (Yang dibuat oleh Filament) untuk manajemen tingkat lanjut, dan REST API untuk karyawan dan Admin.

## Arsitektur & Logika Sistem

Untuk memenuhi kriteria sistem, Disini mengimplementasikan beberapa pola arsitektur:

### 1. Centralized Business Logic (Service Layer)
Seluruh logika validasi kuota dan perhitungan durasi cuti dipusatkan pada `App\Services\LeaveService`. Hal ini menjamin konsistensi data, baik saat pengajuan dilakukan melalui REST API oleh Staff.

### 2. Event-Driven Data Integrity (Model Observers)
Sistem menggunakan Eloquent Model Events (`booted`) pada model `LeaveRequest`.
- **Auto-Quota Update**: Pengurangan kuota pada tabel `leave_quotas` terjadi secara otomatis hanya ketika status berubah menjadi `approved`.
- Hal ini mencegah duplikasi logika di Controller dan memastikan integritas data tetap terjaga meskipun status diubah dari berbagai pintu (API maupun Web).

### 3. Role-Based Access Control (RBAC)
- **Admin**: Akses penuh ke Filament Dashboard, mampu mengelola semua data karyawan, dan melakukan eksekusi status melalui REST API.
- **Staff**: Akses terbatas hanya pada data pribadi melalui REST API.

### DB STRUCTUR
![db structure](https://github.com/WisnuIbnu/technical-test-backend/blob/main/public/db_structure.png?raw=true)


### Routes

| Routes                                     |HTTP METHOD & Fungsi                  |
| -------------------------------------------|--------------------------------------|
| api/login (Admin/Staff) (POST)             |Login dengan mendapatkan Token Bearer |
| api/logout (Admin/Staff) (POST)            |Logout dan Revoke Token               |
| api/list-cuti (Staff) (GET)                |Mendapatkan List Seluruh Cuti         |
| api/pengajuan-cuti (Staff) (POST)          |Mengajukan cuti + upload bukti        |
| api/admin/verifikasi-cuti/{id}/action (PUT)|Admin = Verifikasi Pengajuan Cuti     |


## Role Pengguna

Sistem memiliki dua peran utama:

### 1️⃣ Admin

- Login
- Verifikasi Pengajuan Cuti (Pending, Approve, Rejected) -> Default "Pending"
- Kelola Kouta Cuti
- Logout

### 2️⃣ Staff/Pegawai

- Login
- Pengajuan Cuti, dengan data yang dikirim :
    1. Tanggal mulai cuti (start_date)
    2. Tanggal akhir cuti (end_date)
    3. Alasan Cuti (reason)
    4. Bukti -> PDF/JPG/PNG (attachment)
    5. Durasi dihitung dari Tanggal mulai cuti - Tanggal akhir cuti
- List Cuti Staff
- Logout

## Panduan Instalasi & Setup

### 1. Persiapan Lingkungan
Pastikan Anda memiliki:
- PHP >= 8.2
- Composer
- Filament 3
- MySQL


### Instalasi

Langkah-langkah berikut akan memandu Anda melalui proses instalasi untuk menjalankan aplikasi di lingkungan pengembangan secara lokal di mesin Anda:

1. Kloning versi terbaru dari repositori
2. Jalankan `composer install` untuk menginstal dependensi PHP yang dibutuhkan
3. Salin file .env.example ke .env dan edit kredensial basis data sesuai dengan server basis data Anda, dengan mengetikan `cp .env.example .env`
4. Jalankan `php artisan key:generate` untuk membuat kunci aplikasi baru
5. Jalankan `php artisan migrate` untuk membuat tabel basis data. Anda juga dapat menambahkan flag `--seed` untuk mengisi basis data dengan beberapa data dummy
6. Jalankan `php artisan serve` untuk memulai server pengembangan
7. Buka terminal lain dan jalankan `npm install && npm run build` untuk menginstal modul node yang dibutuhkan
8. Jalankan `npm run dev` untuk mengkompilasi aset untuk pengembangan
9. Buka terminal dan jalankan `php artisan storage:link` 
10. Buka browser Anda dan kunjungi `http://localhost:8000` untuk melihat aplikasi


🔗 **Link Published Postman Documentation:**  
👉 [https://documenter.getpostman.com/view/51334662/2sBXVfiBXw](https://documenter.getpostman.com/view/51334662/2sBXVfiBXw)


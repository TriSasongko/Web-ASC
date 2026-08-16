# Panduan Deploy ke Hosting Nata Network (cPanel)

Checklist langkah demi langkah untuk menjalankan aplikasi ini di hosting cPanel Nata Network (server Indonesia).

## Sebelum mulai

- PHP **8.3** tersedia di cPanel **PHP Selector** (aplikasi butuh `^8.3`).
- Konfirmasi ke Nata apakah paket Anda punya akses **SSH / cPanel Terminal**.
  - Jika tidak ada, salin semua perintah `php artisan ...` / `composer ...` ke dalam satu tiket ke support Nata dan minta mereka jalankan sekali.
- Paket Personal 1 (1GB disk) cukup untuk memulai, tapi sempit jika `storage/` banyak terisi. Siapkan upgrade ke Professional 1 (10GB) bila perlu.
- Beli minimal **1 tahun** untuk dapat **gratis domain** + diskon.

## 1. Persiapan di komputer lokal

```bash
npm install
npm run build
```

Hasil folder `public/build` WAJIB ikut di-upload (aplikasi memakai aset dari Vite).

Buat arsip ZIP proyek. **Hilangkan** folder berikut agar arsip ringan dan tidak membawa sampah:

- `node_modules/`
- `.git/`
- `storage/framework/cache/*`, `storage/logs/*`, `storage/framework/sessions/*`
- `public/build` TIDAK dibuang (harus ikut)

## 2. Upload & ekstrak di cPanel

1. Login cPanel (dikirim Nata via email) → **File Manager**.
2. Masuk ke direktori akun (misal `/home/USERNAME`).
3. Upload file ZIP (menu **Upload**), lalu **Extract**.
4. Pindahkan isi proyek ke folder aplikasi, misal `/home/USERNAME/asc-website`.

> Jangan letakkan langsung di `public_html`. Document Root akan diarahkan ke folder `public` aplikasi (langkah 7).

## 3. Database MySQL

1. cPanel → **MySQL Databases**.
2. Buat database, misal `asc_website`.
3. Buat user + password kuat, lalu **Add User To Database** dengan **ALL PRIVILEGES**.
4. Catat nama database, user, dan password — dipakai di `.env` (langkah 5).

## 4. PHP Selector

1. cPanel → **MultiPHP Manager / PHP Selector**.
2. Pilih **PHP 8.3** untuk domain.
3. Aktifkan ekstensi: `mbstring`, `xml`, `curl`, `gd`, `zip`, `intl`, `pdo_mysql`, `bcmath`, `fileinfo`, `iconv`.

## 5. File `.env`

1. Di komputer: salin template `cp .env.production.example .env`.
2. Isi nilai berikut:
   - `APP_URL` → `https://NAMA-DOMAIN-ANDA` (misal `https://asc-renang.id`)
   - `APP_KEY` → kosongkan dulu (diisi perintah `key:generate` di langkah 6)
   - `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` → dari langkah 3
   - `MAIL_HOST` / `MAIL_USERNAME` / `MAIL_PASSWORD` → dari langkah 9
   - `SESSION_DOMAIN` → kosong (`null`)
3. Upload `.env` ini ke folder aplikasi di server.

## 6. Install dependency & inisialisasi (SSH / Terminal)

Jalankan dari folder aplikasi (misal `cd ~/asc-website`):

```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force --class=RoleUserSeeder
php artisan db:seed --force --class=ProgramSeeder
php artisan db:seed --force --class=LandingPageSeeder
php artisan db:seed --force --class=SalarySettingSeeder
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Catatan penting:

- **JANGAN jalankan `php artisan db:seed`** (tanpa `--class`) — seeder penuh ikut menanam data tiruan. Untuk produksi hanya seed:
  - `RoleUserSeeder` → akun `admin@asc.test`, `pelatih@asc.test`, `ortu@asc.test`
  - `ProgramSeeder` → 5 program resmi
  - `LandingPageSeeder` → konten landing page (hero, coach, program, galeri)
  - `SalarySettingSeeder` → tarif honor pelatih
  - Lewati `DummyDataSeeder`, `BestTimeSeeder`, `StudentSeeder` (semua data demo/tiruan).
- Setelah selesai, **ganti password** segera untuk `admin@asc.test`, `pelatih@asc.test`, `ortu@asc.test` (seeder memakai password default `password`). Login lalu ganti via menu profil, atau langsung dari DB.
- Jika `storage:link` gagal (symlink diblokir shared hosting): buat folder `public/storage` lalu copy isi `storage/app/public` ke dalamnya.
- Jika tidak ada SSH: kumpulkan perintah di atas ke tiket support Nata.

## 7. Document Root

1. cPanel → **Domains** → **Manage** pada domain.
2. Ubah **Document Root** menjadi `/home/USERNAME/asc-website/public`.
3. Pastikan folder `public` berisi `index.php` dan folder `build`.

## 8. Cron Jobs (untuk renewal otomatis)

Aplikasi menjadwalkan `renewal:check` harian (routes/console.php). Tambahkan di cPanel → **Cron Jobs**:

```
* * * * * php /home/USERNAME/asc-website/artisan schedule:run >> /dev/null 2>&1
```

> Ganti `/home/USERNAME/asc-website` sesuai lokasi proyek. Jika perlu, gunakan path absolut binary PHP dari output `which php` di terminal.

## 9. Email SMTP (biar fitur "Lupa Password" jalan)

Di lokal, `MAIL_MAILER=log` sehingga email hanya ditulis ke file log. Di produksi wajib SMTP:

1. cPanel → **Email Accounts** → buat account, misal `no-reply@NAMA-DOMAIN`.
2. Isi `.env`:
   - `MAIL_MAILER=smtp`
   - `MAIL_HOST=mail.NAMA-DOMAIN`
   - `MAIL_PORT=587`
   - `MAIL_USERNAME=no-reply@NAMA-DOMAIN`
   - `MAIL_PASSWORD=<password email>`
   - `MAIL_FROM_ADDRESS=no-reply@NAMA-DOMAIN`
   - `MAIL_ENCRYPTION=tls`
3. Jalankan `php artisan config:cache` ulang setelah mengubah `.env`.

Alternatif murah: gunakan SMTP Brevo / Zoho Mail (isi `MAIL_HOST`, port, user, pass sesuai penyedia).

## 10. SSL & HTTPS

1. cPanel → **SSL/TLS Status** → aktifkan AutoSSL / Let's Encrypt (gratis) untuk domain.
2. Pastikan `APP_URL` di `.env` memakai `https://`.
3. Test: buka `https://NAMA-DOMAIN/login` dan pastikan tidak ada peringatan "Not Secure".

## 11. Verifikasi setelah deploy

- [ ] `https://NAMA-DOMAIN` membuka halaman login
- [ ] `https://NAMA-DOMAIN/login` — alur login admin/pelatih/ortu berfungsi
- [ ] Coba "Lupa Password" → email reset terkirim (cek mailbox `no-reply@...`)
- [ ] Login admin → buka halaman siswa → foto profil tampil (uji `storage:link`)
- [ ] Catat absensi sekali → cek log tidak ada error
- [ ] `storage/logs/laravel.log` tidak menampilkan error fatal

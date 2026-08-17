# Post-Hosting Security Checklist

## 1. Konfigurasi .env (Wajib)
- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `SESSION_ENCRYPT=true`
- [ ] `SESSION_SECURE_COOKIE=true`
- [ ] `LOG_LEVEL=error`
- [ ] `DB_PASSWORD=` (isi password kuat)
- [ ] `MAIL_MAILER=smtp` (konfigurasi email)
- [ ] `APP_KEY=` (generate baru: `php artisan key:generate`)

## 2. Code Changes
- [ ] Hapus `role`, `is_active`, `can_assess_developments` dari `$fillable` User model
- [ ] Tambah security headers middleware (X-Frame-Options, X-Content-Type-Options, CSP, HSTS)
- [ ] Password reset admin: generate random password, kirim via email
- [ ] Batasi akses pelatih ke class yang ditugaskan saja (attendance, recommendation, development)
- [ ] Hapus `public/build.rar`

## 3. Server
- [ ] Enforce HTTPS (nginx/Apache config)
- [ ] Install SSL certificate (Let's Encrypt / domain registrar)
- [ ] `php artisan config:cache`
- [ ] `php artisan route:cache`
- [ ] `php artisan view:cache`
- [ ] `php artisan migrate --force`
- [ ] `php artisan storage:link`
- [ ] Set folder permissions: `storage/` dan `bootstrap/cache/` writable by web server

## 4. Verification
- [ ] Test login semua role (admin, pelatih, orang tua)
- [ ] Test logout → tekan back → harus redirect ke login
- [ ] Cek browser DevTools → response header harus ada `Cache-Control: no-store`
- [ ] Cek tidak ada error 500 di halaman utama
- [ ] Cek file upload bisa diakses publik (gambar hero, logo, galeri)

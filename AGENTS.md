# AGENTS.md

Laravel 13 + Breeze (Blade) app for an Indonesian soccer academy (ASC). PHP ^8.3, MySQL for local dev, Tailwind v3 + Alpine on the frontend. No Vue/React.

## Commands

- Dev servers: `composer dev` (runs `php artisan serve` + queue:listen + `php artisan pail` + Vite concurrently). Asset-only: `npm run dev` / `npm run build`.
- Tests: `composer test` (clears config, then `php artisan test`). Single test: `php artisan test --filter=TestName`.
- Format: `vendor/bin/pint` (default Laravel preset).
- DB setup: `php artisan migrate --seed`. Seeded logins (all password `password`): `admin@asc.test` (admin), `pelatih@asc.test` (coach), `ortu@asc.test` (parent). `ProgramSeeder` seeds the 5 programs.

## Gotchas

- `.env.example` points to sqlite, but the real local `.env` uses **MySQL** (`asc_website`, root, no password). `composer setup` is the sqlite fresh-install path only. Create the MySQL DB and run `migrate --seed` for real dev.
- **3 pre-existing test failures are expected**: `AuthenticationTest::test_users_can_authenticate_using_the_login_screen` and two `ProfileTest` email-verification tests. Cause: `UserFactory` does not set `role` (migration defaults to `orang_tua`) and `/dashboard` redirects by role. When adding tests, set `role` explicitly via `User::factory()->create(['role' => 'admin'])`.
- `.npmrc` sets `ignore-scripts=true`; `@rolldown/binding-win32-x64-msvc` is pinned in package.json to provide the native Vite binding. Don't remove it.
- `phpunit.xml` uses in-memory sqlite, so tests never touch MySQL.

## Architecture

- **Three roles** stored as an enum `role` column on `users` (`admin`, `pelatih`, `orang_tua`), default `orang_tua`. Helpers: `User::isAdmin()/isPelatih()/isOrangTua()`. Users also have `phone`, `address`, `is_active` (inactive users are logged out by `CheckRole`).
- **Role-gated routes** via `role:` middleware alias (`app/Http/Middleware/CheckRole.php`), grouped by prefix in `routes/web.php`: `/admin/*`, `/pelatih/*`, `/orangtua/*`. `/dashboard` dispatches to the role's dashboard. New features go in the matching role group.
- **Models**: `User` → `Student` (parent_id) → `class_student` pivot (tracks `sessions_completed`) → `SchoolClass` (coach_id, program_id) → `Program` (billing `per_paket`/`per_bulan`, `total_sessions`); plus `ClassSchedule`, `Attendance` (status `hadir`), `Development` (per-student evaluations).
- **Controllers/views mirror role dirs**: `app/Http/Controllers/{Admin,Pelatih,OrangTua}` and `resources/views/{admin,pelatih,orangtua}`.
- **E-Raport PDF**: `ERaportController` uses `barryvdh/laravel-dompdf` (`Pdf::loadView('eraport.pdf')`). Shared by admin + parent; parents are restricted to their own children inside the controller (`authorizeAccess`), not via middleware.

## Conventions

- UI text, comments, and route names are in **Indonesian** (`pelatih` = coach, `orang_tua` = parent). Keep it that way.
- `UserFactory` intentionally omits `role`; set it explicitly in tests.

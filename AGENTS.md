# AGENTS.md

Laravel 13 + Breeze (Blade) app for an Indonesian soccer academy (ASC). PHP ^8.3, MySQL for local dev, Tailwind v3 + Alpine on the frontend. No Vue/React.

## Commands

- Dev servers: `composer dev` (runs `php artisan serve` + queue:listen + `php artisan pail` + Vite concurrently). Asset-only: `npm run dev` / `npm run build`.
- Tests: `composer test` (clears config, then `php artisan test`). Single test: `php artisan test --filter=TestName`.
- Format: `vendor/bin/pint` (default Laravel preset).
- DB setup: `php artisan migrate --seed` (`DatabaseSeeder` = `RoleUserSeeder` + `ProgramSeeder` + `DummyDataSeeder`). Seeded logins (all password `password`): `admin@asc.test` (admin), `pelatih@asc.test` (coach), `ortu@asc.test` (parent); `DummyDataSeeder` also adds `coach1..N@asc.test` / `parent1..N@asc.test` with students. `ProgramSeeder` seeds the 5 programs.

## Gotchas

- `.env.example` points to sqlite, but the real local `.env` uses **MySQL** (`asc_website`, root, no password). `composer setup` is the sqlite fresh-install path only. Create the MySQL DB and run `migrate --seed` for real dev.
- **3 pre-existing test failures are expected**: `AuthenticationTest::test_users_can_authenticate_using_the_login_screen` and two `ProfileTest` email-verification tests. Cause: `UserFactory` does not set `role` (migration defaults to `orang_tua`) and `/dashboard` redirects by role. When adding tests, set `role` explicitly via `User::factory()->create(['role' => 'admin'])`.
- `.npmrc` sets `ignore-scripts=true`; `@rolldown/binding-win32-x64-msvc` is pinned in package.json to provide the native Vite binding. Don't remove it.
- `phpunit.xml` uses in-memory sqlite, so tests never touch MySQL.

## Architecture

- **Three roles** stored as an enum `role` column on `users` (`admin`, `pelatih`, `orang_tua`), default `orang_tua`. Helpers: `User::isAdmin()/isPelatih()/isOrangTua()`. Users also have `phone`, `address`, `is_active` (inactive users are logged out by `CheckRole`).
- **Role-gated routes** via `role:` middleware alias (`app/Http/Middleware/CheckRole.php`), grouped by prefix in `routes/web.php`: `/admin/*`, `/pelatih/*`, `/orangtua/*`. `/dashboard` dispatches to the role's dashboard. New features go in the matching role group.
- **Models**: `User` → `Student` (parent_id) → `class_student` pivot (tracks `sessions_completed`, `is_active`, `renewal_status`) → `SchoolClass` (program_id, level, capacity; **no `coach_id`** — coaches are attached to `ClassSchedule` via the `class_schedule_user` pivot, many-to-many) → `Program` (billing `per_paket`/`per_bulan`, `total_sessions`, `is_kompetitif`). Plus `ClassSchedule` (also `class_schedule_student` pivot), `Attendance` (status `hadir`, `recorded_by`), `Development` (per-student evaluations), `CoachNote` (pelatih's private notes, sorted by `note_date`), `ClassRecommendation` (promotion flow: pelatih proposes → admin approves → parent confirms).
- **`Student` has a global scope** `ActiveParentScope` that excludes students whose parent `is_active` is false. Use `withoutGlobalScope(ActiveParentScope::class)` if you must query them.
- **Development entry is gated** by `User::canAssessDevelopments()` (`can_assess_developments` column), not by role alone; coach dashboard/`create` pages branch on it.
- **Controllers/views mirror role dirs**: `app/Http/Controllers/{Admin,Pelatih,OrangTua}` and `resources/views/{admin,pelatih,orangtua}`.
- **E-Raport PDF**: `ERaportController` uses `barryvdh/laravel-dompdf` (`Pdf::loadView('eraport.pdf')`). Shared by admin + parent; parents are restricted to their own children inside the controller (`authorizeAccess`), not via middleware.

## Conventions

- UI text, comments, and route names are in **Indonesian** (`pelatih` = coach, `orang_tua` = parent). Keep it that way.
- `UserFactory` intentionally omits `role`; set it explicitly in tests.

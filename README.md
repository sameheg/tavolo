# CafeSaaS — GitHub-Ready (v2025-09R4)
**يدعم GitHub Codespaces + CI/CD + Tenancy + Feature Flags.**  
تاريخ: 2025-09-08

## Quickstart (Codespaces)
1) افتح الريبو في **GitHub Codespaces** → ينتش الحاوية تلقائيًا.
2) أول تشغيل هيعمل: `composer install` + `php artisan key:generate` + `php artisan cafesaas:install`.
3) شغّل السيرفر المحلي:
   ```bash
   php artisan serve --host 0.0.0.0 --port 8000
   ```
4) جرّب:
   ```bash
   curl http://localhost:8000/api/pos/ping
   ```

## محليًا (لو حابب):
```bash
composer install
php artisan key:generate
php artisan cafesaas:install
php artisan serve
```

## CI/CD
- **CI:** `.github/workflows/ci.yml` (PHPStan + Deptrac + build cache).
- **Release:** `.github/workflows/release.yml` — يبني Docker image ويدفعه لـ GHCR.
- **Deploy Staging (AWS ECS مثال):** `.github/workflows/deploy-staging.yml` (OIDC) — عدّل متغيرات البيئة حسب حسابك.

## مهم
- أمر التثبيت: `php artisan cafesaas:install` يضيف Providers تلقائيًا في `config/app.php` ويجهز Tenancy + Pennant.

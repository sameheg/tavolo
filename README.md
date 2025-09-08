# CafeSaaS Scaffold (v2025-09)
**هدف السكافولد:** تحويل مشروعك إلى **Package‑Oriented Modular Monolith (POMM)** مع DDD + Hex + CQRS + Outbox + Tenancy + Feature Flags — على **Laravel 12** (جذر المشروع = لارافل).

> هذا السكافولد يفترض أنك تنشئ مشروع Laravel جديد في الجذر (أو تستخدم مشروعًا نظيفًا).
> Laravel app = الجذر. الموديولات والحِزم موجودة في /modules و /packages كـ Composer path repos.

## 🔧 البدء السريع
1) أنشئ مشروع لارافل جديد (لو ما عندكش مشروع):
```bash
composer create-project laravel/laravel . "^12.0"
```

2) فك محتويات هذا السكافولد داخل جذر المشروع (نفس مجلد `artisan`) ثم شغّل:
```bash
composer install
php artisan key:generate
php artisan vendor:publish --tag=cafesaas-registry --force
php artisan migrate
```

3) جرّب أمر إنشاء موديول جديد:
```bash
php artisan make:module Inventory
```

4) فعّل موديول تجريبي (POS) الموجود مسبقًا عبر الـRegistry (يتم تلقائيًا عند الإقلاع) ثم افتح الراوت التجريبي:
```
GET /api/pos/ping  →  { "ok": true, "module": "pos" }
```

## 📦 ماذا يحتوي السكافولد؟
- **composer.json** مُهيأ بـ path repositories (`modules/*`, `packages/*`) ومتطلبات: tenancy/pennant/otel…
- **packages/**: 
  - `kernel`: Result, AggregateRoot (مبسّط), Clock
  - `contracts`: الواجهات العامة (EventBus, MessageBus, Repository contracts)
  - `toolkit`: VO أساسية (Money/TaxRate) + helpers
  - `observability`: Middlewares للـTracing/Correlation + Sentry hook
- **modules/pos**: مثال موديول جاهز (ServiceProvider + module.json + Route `GET /api/pos/ping`)
- **app/Console/Commands/MakeModule.php**: أمر يولّد هيكل Module كامل من **stubs/module/**
- **app/Providers/ModuleRegistryServiceProvider.php**: يسجّل الموديولات من `modules/*/module.json`
- **config/module_registry.php** + publishable tag `cafesaas-registry`
- **database/migrations/**: outbox_messages
- **stubs/module/**: قوالب Domain/Application/Infrastructure + ServiceProvider + composer.json + module.json
- **.github/workflows/ci.yml**, **phpstan.neon**, **deptrac.yaml**, **Makefile**, **.editorconfig**

## 🧭 قواعد المعمارية
- **Domain**: PHP خالص (ممنوع Laravel).
- **Application**: Commands/Queries + Handlers + DTOs (يعرف Domain + Contracts فقط).
- **Infrastructure**: Laravel/Eloquent/Gateways/Queues.
- Enforced via **PHPStan + Deptrac** (شغّالة في CI).

## 🧪 اختبارات
- أضف Pest وتشغيله:
```bash
composer require pestphp/pest --dev
php artisan pest:install
```

## 🧯 ملاحظات
- قم بإضافة `App\Providers\ModuleRegistryServiceProvider::class` إلى `config/app.php` (providers).
- استخدم **Laravel Pennant** لإدارة تمكين/تعطيل الموديولات لكل Tenant.
- اربط **stancl/tenancy** كما تحب (schema-per-tenant أو db-per-tenant) — هذا السكافولد لا يفرض اختيارًا.

— تاريخ الإنشاء: 2025-09-08

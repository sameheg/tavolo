# CafeSaaS — Codex Single‑Sprint (24h) — R7
- Sprint plan مضغوط: **Sprint — Full Delivery 24h** (بدون تواريخ).
- Workflows تِبني Milestone + تحوّل كل Module إلى Issue مربوط بالسبرنت ده (idempotent).
- مرفق **docs/STACK.md** بالمواصفات الكاملة (اللي اديتهالي).

## الاستخدام
1) ارفع الريبو على GitHub.
2) عدّل/أضف الموديولات في `ops/modules.json` لو عاوز.
3) اعمل Push على `main` → هيشتغل:
   - `.github/workflows/bootstrap.yml` → ينشئ Milestone واحد (بدون due date).
   - `.github/workflows/backlog.yml` → يولّد Issues لكل Module ويربطها بالميلستون.

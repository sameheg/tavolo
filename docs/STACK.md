# CafeSaaS — Ordered Module Stack & Advanced Specs (v2025-09)

> **المطلوب:** ترتيب منطقي للموديولات + مواصفات متقدمة لكل واحد — جاهزة للتنفيذ حتى لوحة تحكم كاملة. النمط: POMM + DDD + Hex + CQRS + Outbox/Sagas + Tenancy + Feature Flags.

---

## الخريطة الإجمالية (Phased Order)

**Phase 0 — منصة وحوكمة**

1. core
2. security
3. super-admin
4. orchestrator
5. integrations
6. notifications

**Phase 1 — الأساس التجاري**
7) catalog
8) pricing
9) inventory
10) supply-chain

**Phase 2 — التنفيذ البيعي**
11) pos
12) kds
13) table
14) online-ordering

**Phase 3 — العملاء والإيراد والامتثال**
15) crm
16) billing
17) compliance

**Phase 4 — التحليلات والموارد**
18) reporting
19) employee
20) recruitment

**Phase 5 — التوسّع**
21) marketplace
22) leasing

---

## 1) core (أساس المنصة)

**Mission:** تشغيل المنصة كـ Modular Monolith مع تعدد المستأجرين والنشر بلا توقف.
**Advanced Features:** Tenancy (schema/db per tenant)؛ Feature Flags؛ Module Registry؛ Event Bus + Outbox/Inbox؛ Config per-tenant؛ Idempotency؛ Rate limiting per-tenant؛ Observability hooks (OTel + Sentry + JSON logs)؛ Audit Trail.
**APIs:** `/api/v1/core/health`, `/api/v1/core/features` (per-tenant).
**Data:** tenants, tenant_settings, feature_flags, audit_logs, outbox_messages.
**Events:** `TenantCreated`, `FeatureToggled`.
**KPIs:** uptime, error rate, p95 latency, queue lag.
**Acceptance:** تينانت جديد يعمل end-to-end خلال ≤ 2 دقيقة مع تمكين موديولات محددة.

## 2) security

**Mission:** أمان مؤسسي، وصول أدنى صلاحية.
**Advanced:** 2FA (TOTP/SMS), SSO (SAML/OIDC), Device & Session mgmt, IP allow/deny per-tenant, Secrets rotation, CSP/HSTS, CSRF, JWT/Sanctum, DLP log redaction.
**APIs:** `/api/v1/security/2fa/*`, `/api/v1/security/sessions`.
**Data:** user_2fa, ip_policies, login_audit.
**Events:** `UserLoginSucceeded/Failed`, `2FAEnabled`.
**Acceptance:** تفعيل 2FA إجباري لأدوار محددة؛ مراقبة IP قبل أي API.

## 3) super-admin

**Mission:** حوكمة التينانت، الخطط، وتمكين الموديولات.
**Advanced:** Tenants CRUD؛ Modules toggle؛ Plan→Entitlements mapping؛ Branding per-tenant؛ Keys for integrations؛ Incident banner؛ Remote config.
**APIs:** `/api/v1/superadmin/tenants`, `/api/v1/superadmin/tenants/{id}/modules`.
**Data:** tenants, tenant_modules, tenant_integrations.
**Acceptance:** تمكين POS لتينانت يفعّل routes/migrations ويظهر باللوحة فورًا.

## 4) orchestrator

**Mission:** Sagas/Workflows للرحلات الحرجة.
**Advanced:** State machines (Order→Payment→Fiscal→KDS)؛ Retry/Backoff؛ DLQ؛ Compensation؛ Scheduler؛ Workflow DSL.
**APIs:** `/api/v1/orch/workflows/*`.
**Data:** workflows, workflow_runs, steps.
**Events:** `WorkflowStarted/Completed/Failed`.
**Acceptance:** فشل دفع يُفعّل تعويضات آمنة دون تكرار جانبي.

## 5) integrations

**Mission:** طبقة موحّدة للوصول لبوابات الدفع والرسائل والجهات.
**Advanced:** Connectors SDK؛ Payments (Stripe/Paymob); SMS/WhatsApp; Email; Fiscal APIs؛ Delivery partners؛ Webhooks (HMAC, retries, backoff)؛ Rate limits per-tenant.
**APIs:** `/api/v1/integrations/connect`, `/api/v1/integrations/webhooks/*`.
**Data:** connectors, webhook_subscriptions, webhook_logs.
**Acceptance:** تسجيل Webhook وتسلّم حدث POS بتوقيع صحيح ≤ 5 ث.

## 6) notifications

**Mission:** إرسال متعدد القنوات بإدارة قوالب وتفضيلات.
**Advanced:** Channels (email/SMS/WhatsApp/push/in-app), Templates + i18n, A/B, Throttling, Digest, Preference center, Delivery webhooks, Idempotency.
**APIs:** `/api/v1/notify/send`, `/api/v1/notify/templates`.
**Data:** templates, notifications, preferences.
**Acceptance:** حملة SMS/WhatsApp تُرسل بدفعات مع تتبّع تسليم.

## 7) catalog

**Mission:** إدارة كتالوج مرن (منتجات/خامات/باقات/Modifiers).
**Advanced:** Variants/SKUs, Attributes, Collections, Bundles/Kits, Allergens/Nutrition, Media CDN, Availability windows, Channel menus.
**APIs:** `/api/v1/catalog/products`, `/api/v1/catalog/menus/publish`.
**Data:** products, variants, modifiers, bundles, media.
**Events:** `ProductChanged`, `MenuPublished`.
**Acceptance:** نشر منيو قناة (POS/Online) بضغطة واحدة مع معاينة.

## 8) pricing

**Mission:** محرك قواعد تسعير متعدد العملات والقنوات.
**Advanced:** Price books; Rule engine (time/day/segment/tenant/branch); Happy Hour; Promotions; Inclusive/Exclusive VAT; FX rates; Rounding; Coupon resolution.
**APIs:** `/api/v1/pricing/resolve`, `/api/v1/pricing/rules`.
**Data:** price_books, price_rules, fx_rates.
**Events:** `PriceRuleChanged`.
**Acceptance:** نفس الطلب يُحلّ بنفس السعر عبر الزمن (سنابشوت tests).

## 9) inventory

**Mission:** وصفات/BOM، مخزون متعدد مواقع، تقييمات، تنبيهات.
**Advanced:** BOM with yields/waste; Batch/Lot/Expiry; Multi-warehouse; Reorder points; Physical counts & Variance; FIFO/Weighted avg; Cost roll-up; Alerts.
**APIs:** `/api/v1/inventory/recipes`, `/api/v1/inventory/movements`.
**Data:** recipes, recipe_items, warehouses, stock_ledgers, counts.
**Events:** `StockReserved/Adjusted`.
**Acceptance:** إغلاق أوردر يخصم مخزون حسب BOM ويولّد تنبيه عند الحد الأدنى.

## 10) supply-chain

**Mission:** موردين، أوامر شراء، استلام، تحويلات.
**Advanced:** RFQ/Quotes; PO; GRN مع فحص جودة؛ Transfers؛ Lead-time modeling؛ Vendor scorecards؛ Contract prices.
**APIs:** `/api/v1/scm/po`, `/api/v1/scm/grn`, `/api/v1/scm/transfers`.
**Data:** suppliers, purchase_orders, po_items, grn, transfers.
**Events:** `POApproved`, `GRNPosted`.
**Acceptance:** GRN يحدّث مخزون وكلفة المادة + يربط فواتير المورد لاحقًا.

## 11) pos

**Mission:** نقطة بيع قوية Offline-first.
**Advanced:** Dine-in/Takeaway/Delivery; Tabs; Split tender; Tips/Service charge; Discounts; Refunds/Void with policy; QR receipts; Idempotency; Cash mgmt Z/X; Offline queue + conflict policy.
**APIs:** `/api/v1/pos/orders`, `/api/v1/pos/payments`, `/api/v1/pos/refunds`.
**Data:** orders, order_items, payments, refunds, receipts, shifts.
**Events:** `OrderPlaced`, `PaymentCaptured`, `RefundIssued`.
**Acceptance:** Happy path كامل + تقارير زِد/اكس متّسقة.

## 12) kds

**Mission:** عرض وتنفيذ أوامر المطبخ بزمن حقيقي.
**Advanced:** Stations routing; Expeditor; Timing SLAs; Bump/Recall; Capacity; Audio/visual alerts; Metrics per station.
**APIs:** `/api/v1/kds/tickets`, `POST /{id}/bump`.
**Data:** kds_tickets, kds_stations.
**Events:** `KDSTicketDispatched/Completed`.
**Acceptance:** زمن انتظار P95 ضمن الهدف لكل Station.

## 13) table

**Mission:** إدارة الصالة والمقاعد والـQR.
**Advanced:** Floor plan designer; QR tables; Waitlist; Reservations; Merge/Move; Course pacing.
**APIs:** `/api/v1/table/floor`, `/api/v1/table/qr`.
**Data:** tables, areas, reservations, waitlist.
**Acceptance:** تدفّق فتح/نقل/دمج طاولات يظهر فورًا في POS/KDS.

## 14) online-ordering

**Mission:** قنوات ويب/موبايل لاستلام/توصيل ودمج كامل.
**Advanced:** White-label storefront; Channel menus; Scheduled orders; Pre-pay; Status updates SMS/WA; Promo codes; Throttling by kitchen capacity.
**APIs:** `/api/v1/oo/orders`, `/api/v1/oo/menus`.
**Data:** oo_orders, oo_order_items, channel_settings.
**Events:** `OnlineOrderPlaced`, `OnlineOrderThrottled`.
**Acceptance:** توافق القوائم والأسعار مع POS تلقائيًا.

## 15) crm

**Mission:** ملفات العملاء، الولاء، الشرائح، الحملات.
**Advanced:** Identity merge; Consents; Segmentation; RFM/CLV; Points/Tiers; Vouchers؛ Campaigns via integrations؛ Suppression lists.
**APIs:** `/api/v1/crm/customers`, `/crm/loyalty`, `/crm/campaigns`.
**Data:** customers, consents, loyalty_ledger, segments, vouchers.
**Events:** `CouponApplied`, `LoyaltyGranted`.
**Acceptance:** Earn/Redeem idempotent + تتبّع CLV.

## 16) billing

**Mission:** الاشتراكات والاستخدام والفوترة والتحصيل.
**Advanced:** Plans/Entitlements; Metering events; Proration; Invoices & Taxes; Dunning/retries; Multiple payment methods; Revenue recognition hooks.
**APIs:** `/api/v1/billing/subscriptions`, `/billing/usage`, `/billing/invoices/{id}/pay`.
**Data:** plans, plan_features, subscriptions, usage, invoices, invoice_items.
**Events:** `SubscriptionActivated`, `InvoiceIssued`, `PaymentFailed`.
**Acceptance:** إيقاف/تمكين المزايا تلقائي حسب الخطة/الاستهلاك.

## 17) compliance

**Mission:** موافقات ضريبية وفواتير إلكترونية/QR وسياسات الاحتفاظ.
**Advanced:** Country adapters (e‑invoice/QR); VAT rules; Digital signatures; Retention/Export; Privacy tools (export/delete).
**APIs:** `/api/v1/compliance/invoices/render`.
**Data:** fiscal_templates, fiscal_invoices, retention_policies.
**Acceptance:** توليد إيصال متوافق قابل للتحقق لكل دولة مستهدفة.

## 18) reporting

**Mission:** نماذج قراءة محسّنة ولوحات ومؤشرات.
**Advanced:** Precomputed read models; Scheduled reports; Exports; BI connectors; Anomaly alerts؛ Drill-down.
**APIs:** `/api/v1/reporting/sales`, `/reporting/exports`.
**Data:** report_sales_daily, report_items, report_tax, report_shifts.
**Acceptance:** توافر البيانات ≤ 60s من الحدث، تطابق الأرقام مع مصادرها.

## 19) employee

**Mission:** الأفراد والشفتات والحضور والصلاحيات.
**Advanced:** Staff profiles; Roles mapping; Shifts (open/close/cashup); Attendance; Timesheets; Payroll export.
**APIs:** `/api/v1/employee/staff`, `/employee/shifts`.
**Data:** staff, roles_map, shifts, attendance.
**Acceptance:** إغلاق شفت يولّد Z/X متّسق ويربط بالرواتب.

## 20) recruitment

**Mission:** التوظيف وخط الأنابيب.
**Advanced:** Jobs; Applicants; Stages; Interviews; Scorecards; Offers؛ Compliance.
**APIs:** `/api/v1/recruitment/jobs`, `/recruitment/applicants`.
**Data:** jobs, applicants, pipelines, scorecards.
**Acceptance:** تحليلات وقت التوظيف + إشعارات انتقال المراحل.

## 21) marketplace

**Mission:** مقارنة العروض والشراء عبر شركاء.
**Advanced:** Vendor catalogs; RFQ; Offers compare; Punchout; Checkout→PO; Rebates.
**APIs:** `/api/v1/marketplace/rfq`, `/marketplace/checkout`.
**Data:** vendors, offers, rfq, rfq_items.
**Acceptance:** توليد PO يهبط في Supply‑Chain بسلاسة.

## 22) leasing

**Mission:** عقارات وعقود إيجار للفروع/الشركاء.
**Advanced:** Properties; Contracts; Payment schedules; Renewals; Reminders; P&L hooks.
**APIs:** `/api/v1/leasing/properties`, `/leasing/contracts`.
**Data:** properties, leases, lease_payments.
**Acceptance:** إشعار استحقاق + تقارير الالتزام المالي.

---

## تسليم لوحة التحكم (Admin UX)

* قائمة Modules تُبنى تلقائيًا من Registry + Flags.
* Dashboards: مبيعات، KDS load، Alerts مخزون، Subscriptions.
* CRUD لكل موديول حسب ما سبق.
* Roles/Permissions Matrix + Theme per-tenant.
  **DoD:** جميع المسارات الأساسية متاحة، الصلاحيات مفعلة، القياسات تُعرض، والتبديل بين التينانت يعمل بدون خروج.

---

## 22) HQ / Franchise Suite

**Mission:** تحكم مركزي لشبكات الفروع (قوائم/أسعار/حملات/سيمات) مع رؤية أداء موحّدة.

**Advanced:**

* Central Menus & Price Books حسب البلد/المنطقة.
* Global Promotions & Campaigns؛ تقويم عروض موحّد.
* Entitlements لكل فرع (Features/Limits)؛ Overrides محلية مع مراقبة.
* مقارنة أداء الفروع (Sales/Items/Cost/Speed/KDS load).

**APIs:**

* `POST /api/v1/hq/menus/publish` (tenant-group/region)
* `POST /api/v1/hq/pricebooks/sync`
* `GET  /api/v1/hq/branches/kpis?from=&to=`

**Data:** hq_groups, hq_pricebooks, hq_menu_releases, branch_overrides.

**Events:** `HQMenuReleased`, `HQPricebookSynced`.

**KPIs/Acceptance:** نشر قائمة وأسعار لفروع محددة خلال ≤ 2m؛ تطابق الأسعار مع POS/OO.

---

## 23) CDP / Growth Hub

**Mission:** توحيد هويّة العميل، الشرائح الديناميكية، محرّك الحملات متعددة القنوات.

**Advanced:**

* Identity Resolution (Merge phone/email/device).
* Segments ديناميكية (RFM/CLV/last seen/avg basket).
* Triggers؛ حملات SMS/WhatsApp/Email؛ Suppression Lists.

**APIs:** `POST /api/v1/cdp/segments`, `POST /api/v1/cdp/campaigns/send`

**Data:** identities, merges, segments, segment_members, campaigns, deliveries.

**Events:** `SegmentRefreshed`, `CampaignDispatched`.

**KPIs/Acceptance:** زمن تحديث الشريحة ≤ 5m؛ تتبّع فتح/نقر/تحويل لكل قناة.

---

## 24) Experimentation / A/B Studio

**Mission:** اختبارات أسعار/باقات/واجهات POS/Online بما يضمن دقة القياس.

**Advanced:**

* Test Designer (هدف، متغيّرات، جمهور، مدّة).
* Randomization، Guardrails (P95 latency/الكلفة).
* Reports: lift/CI، توقف مبكر.

**APIs:** `POST /api/v1/exp/tests`, `GET /api/v1/exp/results/{id}`

**Data:** experiments, variants, assignments, metrics.

**Events:** `ExperimentStarted`, `ExperimentStopped`.

**KPIs/Acceptance:** حساب lift صحيح؛ منع تداخل التجارب على نفس الجمهور.

---

## 25) AI Menu IQ & Demand Forecasting

**Mission:** توصيات قائمة وتنبؤ الطلب لكل ساعة/فرع.

**Advanced:**

* Top‑sellers/Under‑performers؛ Bundles مقترحة.
* Forecast per item/hour/branch؛ Safety Stock توصية.

**APIs:** `GET /api/v1/ai/menu/recommendations`, `GET /api/v1/ai/forecast?item=&branch=`

**Data:** ai_signals, forecasts, menu_insights.

**Events:** `ForecastUpdated`, `MenuInsightFound`.

**KPIs/Acceptance:** MAPE ≤ 20% للأصناف الأساسية؛ انخفاض هدر المخزون ≥ 10%.

---

## 26) Workforce Optimizer

**Mission:** جدولة شفتات ذكية حسب التنبؤ والأداء.

**Advanced:**

* Auto‑scheduling؛ Tip pooling؛ Skill matrix؛ Labor cost %.

**APIs:** `POST /api/v1/workforce/schedules/generate`

**Data:** schedules, skills, tip_pools.

**Events:** `SchedulePublished`, `TipPoolClosed`.

**KPIs/Acceptance:** خفض Over/Under‑staffing ≥ 15%؛ التزام SLA الخدمة.

---

## 27) Task & SOPs Engine

**Mission:** تشغيل يومي مؤتمت بقوائم تحقق ملزِمة.

**Advanced:**

* Checklists (Opening/Closing/Health)؛ Photos/Notes؛ Locks (POS gate) للمهام الحرجة.

**APIs:** `POST /api/v1/sops/checklists`, `POST /api/v1/sops/tasks/{id}/complete`

**Data:** checklists, tasks, task_runs, evidences.

**Events:** `ChecklistCompleted`, `CriticalTaskMissed`.

**KPIs/Acceptance:** إكمال 100% للمهام الحرجة قبل فتح POS.

---

## 28) Gift & Stored Value

**Mission:** بطاقات هدايا ومحافظ مسبقة الدفع.

**Advanced:**

* E‑gift، Corp bulk، Redemption عبر POS/OO، Anti‑fraud limits.

**APIs:** `POST /api/v1/gift/cards`, `POST /api/v1/gift/redeem`

**Data:** gift_cards, gift_ledger.

**Events:** `GiftIssued`, `GiftRedeemed`.

**KPIs/Acceptance:** تسوية الرصيد يوميًا؛ منع ازدواج الاسترداد (idempotent).

---

## 29) Payments Ops & Reconciliation

**Mission:** توحيد مزوّدي الدفع، التسوية، النزاعات.

**Advanced:**

* PSP routing؛ Fees breakdown؛ Daily reconciliation؛ Dispute center.

**APIs:** `POST /api/v1/payops/reconcile`, `GET /api/v1/payops/fees`

**Data:** psp_transactions, settlements, disputes.

**Events:** `SettlementPosted`, `DisputeOpened`.

**KPIs/Acceptance:** فرق التسوية = 0 خلال T+1؛ تقليل رسوم المعاملات ≥ 5% عبر التوجيه.

---

## 30) Delivery Hub

**Mission:** إدارة موحّدة لمنصّات التوصيل.

**Advanced:**

* Menu sync؛ Price/channel overrides؛ Auto‑throttle حسب KDS capacity.

**APIs:** `POST /api/v1/deliveries/menus/sync`, `POST /api/v1/deliveries/orders/import`

**Data:** aggregator_links, delivery_menus, delivery_orders.

**Events:** `DeliveryOrderImported`, `DeliveryMenuSynced`.

**KPIs/Acceptance:** زمن تزامن القائمة ≤ 5m؛ انخفاض إلغاء الطلبات بسبب الحمل ≥ 10%.

---

## 31) IoT / Device Telemetry

**Mission:** قياس حسّاسات (ثلاجات/موازين/طابعات) وتنبيهات صحية.

**Advanced:**

* Thresholds؛ Heartbeats؛ Device inventory؛ Health score.

**APIs:** `POST /api/v1/iot/telemetry`, `GET /api/v1/iot/devices`

**Data:** devices, telemetry, alerts.

**Events:** `DeviceOffline`, `ThresholdBreached`.

**KPIs/Acceptance:** إنذار ≤ 1m عند اختراق حرارة؛ سجل امتثال قابل للتدقيق.

---

## 32) Learning / Micro‑LMS

**Mission:** تدريب دقيق وسريع داخل اللوحة.

**Advanced:**

* Micro‑lessons؛ Quizzes؛ Certificates؛ Assignment per role.

**APIs:** `POST /api/v1/lms/lessons`, `POST /api/v1/lms/assign`

**Data:** lessons, quizzes, enrollments, completions.

**Events:** `LessonCompleted`, `CertificationGranted`.

**KPIs/Acceptance:** إتمام 90% للدروس الإلزامية خلال أول أسبوع.

---

## 33) ESG & Sustainability

**Mission:** تتبّع الهدر والطاقة والتقارير البيئية.

**Advanced:**

* Waste logging؛ Energy import؛ ESG scorecards؛ Targets.

**APIs:** `POST /api/v1/esg/waste`, `GET /api/v1/esg/scorecards`

**Data:** waste_logs, energy_reads, esg_scores.

**Events:** `WasteLogged`, `ESGTargetMissed`.

**KPIs/Acceptance:** خفض هدر ≥ 8% خلال 90 يوم.

---

## 34) Admin Copilot (Natural Language Ops)

**Mission:** أوامر طبيعية لإدارة التسعير/القوائم/التقارير.

**Advanced:**

* NLU بالعربي/الإنجليزي؛ Guardrails؛ Preview & confirm.

**APIs:** `POST /api/v1/copilot/commands`

**Data:** copilot_intents, action_logs.

**Events:** `CopilotActionExecuted`.

**KPIs/Acceptance:** تنفيذ أوامر شائعة ≤ 10s بدقّة ≥ 95%.

---

## 35) Data Platform (Lake + Metrics Layer)

**Mission:** طبقة بيانات موحّدة وتكامل BI.

**Advanced:**

* Event streams؛ dbt models؛ Semantic layer (metrics definitions)؛ Exports.

**APIs:** `POST /api/v1/data/exports`, `GET /api/v1/data/metrics`

**Data:** event_bus, metrics, exports.

**Events:** `ExportCompleted`, `MetricChanged`.

**KPIs/Acceptance:** زمن تجهيز تقرير تنفيذي ≤ 30s؛ تعريف موحّد للمؤشرات.

---

## 36) GRC / Risk Center

**Mission:** الحوكمة والمخاطر والامتثال بعُمق مؤسسي.

**Advanced:**

* تعيين سياسات احتفاظ، تفويضات دقيقة، مراجعات دورية، Risk scoring.

**APIs:** `POST /api/v1/grc/policies`, `GET /api/v1/grc/audits`

**Data:** policies, audits, risks, exceptions.

**Events:** `PolicyEnforced`, `RiskRaised`.

**KPIs/Acceptance:** لا فشل امتثال حرج في تدقيق شهري؛ إقفال المخاطر خلال SLA.

---

## Pro Upgrades (Enhancements للموديولات الحالية)

* **POS:** Speed Keys، Combo Builder، Age‑verify، Macro keys، Receipt Designer، Curbside، Strict Refund/Void policies.
* **KDS:** Capacity/Expo/Timers/Allergen badges/Auto‑Throttle.
* **Inventory:** FEFO/Expiry، Vision Count، EDI Vendors، Cost Roll‑Up، Variance Alerts.
* **Pricing:** Elasticity Tests، Segmentation موسّع، FX تلقائي.
* **CRM:** Referrals، Wallet/Store Credit، Journeys شرطية.
* **Billing:** Per‑location/seat/usage، Proration/Dunning ذكي، Add‑on Store.
* **Online‑Ordering:** PWA، Apple/Google Pay، Upsell/Cross‑sell، Scheduling.
* **Reporting:** HQ Exec dashboard، Variance vs Plan، Cohorts، Anomaly detection.
* **Security:** SSO، API Keys Scopes، Geo‑fencing، Secret Rotation.
* **Integrations:** Public OAuth Apps، Zapier/Make، Webhook Signing v2 + Retries.
* **Orchestrator:** Visual builder، Compensation، SLAs per step.
* **Table:** Floor Designer، QR Ordering، Waitlist SMS، Turn‑time targets.
* **Supply‑Chain:** Vendor Scorecards، Contract Pricing، RFQ→Quote→PO.

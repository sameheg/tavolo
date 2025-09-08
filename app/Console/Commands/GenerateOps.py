#!/usr/bin/env python3
"""Generate ops/sprints.json from module list."""
import json, datetime
modules = ['billing', 'catalog', 'compliance', 'core', 'crm', 'employee', 'integrations', 'inventory', 'kds', 'leasing', 'marketplace', 'notifications', 'online-ordering', 'orchestrator', 'pos', 'pricing', 'recruitment', 'reporting', 'security', 'super-admin', 'supply-chain', 'table']
phases = {'Sprint 0 — Setup & Infra': ['core', 'super-admin', 'orchestrator', 'security', 'integrations', 'notifications'], 'Sprint 1 — Catalog & Inventory': ['catalog', 'pricing', 'inventory', 'supply-chain'], 'Sprint 2 — POS & Ordering': ['pos', 'kds', 'table', 'online-ordering'], 'Sprint 3 — Customers & Billing': ['crm', 'billing', 'compliance'], 'Sprint 4 — People & Insights': ['employee', 'recruitment', 'reporting'], 'Sprint 5 — Expansion': ['marketplace', 'leasing']}
due_base = datetime.date(2025,9,15)
sprints=[]
for i,(title,mods) in enumerate(phases.items()):
    due = due_base + datetime.timedelta(days=i*14)
    sprints.append({
      "title":title,
      "due_on":f"{due}T00:00:00Z",
      "description":"Modules: "+", ".join(mods)
    })
print(json.dumps(sprints, indent=2))

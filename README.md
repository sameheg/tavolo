# CafeSaaS — Codex AutoOps (v2025-09R6)
- مضاف `ops/` فيه Labels + Sprints متولّدين من لستة الموديولات.
- ملف `app/Console/Commands/GenerateOps.py` يولّد سبرنتات جديدة أو يعدّلها بسهولة.
- Workflow `bootstrap.yml` يـSync Labels + Milestones أوتوماتيك في GitHub.

## استخدام Generator
```bash
python3 app/Console/Commands/GenerateOps.py > ops/sprints.json
git add ops/sprints.json
git commit -m "update sprints"
git push
```

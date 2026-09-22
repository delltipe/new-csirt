# Jakarta CSIRT Research Showcase — Methodology & Presentation Notes

**Artifact File:** `csirt-research-showcase.html`  
**Target Project:** Jakarta CSIRT Public Portal Redesign & Empirical Evaluation  
**Author / Researcher:** Abdul Latif (BINUS University / Diskominfotik Provinsi DKI Jakarta)  
**Research Framework:** Design Science Research Methodology (DSRM; Peffers et al., 2007)  
**Date of Deliverable:** September 22, 2026  

---

## 1. Evidence Sources Used

The interactive showcase synthesizes evidence strictly across the four source-of-truth levels:

### Level 1 — Empirical Research Datasets
- **`SUS Questionnaire — CSIRT Jakarta Portal (Legacy).csv` ($N = 23$):** Baseline evaluation collected August 26 – September 2, 2026 from BSSA (*Bidang Persandian dan Keamanan Informasi*) Diskominfotik DKI Jakarta personnel. Mean SUS: $75.65 \pm 17.71$, Median: $77.50$.
- **`SUS Questionnaire — CSIRT Jakarta Portal (New).csv` ($N = 27$):** Evaluation of the deployed staging portal collected September 21, 2026 after 100% completed interactive task walkthroughs. Raw Mean SUS: $70.46 \pm 14.19$, Clean Mean SUS: $75.11 \pm 11.27$ (excluding 5 straight-liners scoring 50.0).
- **Matched Longitudinal Subgroup ($n = 11$):** Verified individuals who completed both baseline and redesign surveys (Paired Legacy: $80.23 \pm 15.43$ vs. Paired New: $72.73 \pm 15.10$, paired $t = -1.936, p = 0.0817$).
- **Replicated Pre/Post Items (1–5 scale):** C1 Ease of Finding Features ($4.26 \rightarrow 4.30$), C2 Trust from Appearance ($4.17 \rightarrow 4.22$), C3 Overall Portal Satisfaction ($4.00 \rightarrow 4.19$), and C4 Crash/Error Rate ($8.7\% \rightarrow 7.4\%$).

### Level 2 — Research & Audit Documentation
- **Automated WCAG 2.1 AA Audits (`WAVE Audit - Legacy vs Redesign.md` & `Website WCAG 2.1 Audit - Legacy vs Redesign.md`):** Multi-tool evaluation pairing axe-core with WAVE across 8 canonical pages. Documented 100% elimination of critical Level A and AA contrast violations across three development snapshots (A $\rightarrow$ B $\rightarrow$ C).
- **DSRM Framework Notes (`DSR Methodology for Gov IT Projects.md`):** Formalized mapping of the 6 stages of Peffers et al. (2007).
- **Active Thesis Manuscript (`Journal Paper - Draft.md`):** Canonical publication draft reflecting the locked single-page guided form with Terms & Conditions gate.

### Level 3 — Actual Artifact Codebase
- **Blade Views:** `resources/views/bug-hunter/create.blade.php`, `resources/views/bug-hunter/tac.blade.php`, `resources/views/bug-hunter/dashboard.blade.php`.
- **Controllers & Routing:** `app/Http/Controllers/BugHunterController.php` (unbounded text handling for pentest payloads, validation rules), `routes/web.php` (auth and bug hunter middleware).
- **Design Tokens & Accessibility:** `public/css/style.css` (`:root` tokens: `--navy`, `--ink`, `--mist`), `public/css/accessibility-contrast.css` (high-contrast and dark mode), `resources/views/components/accessibility.blade.php` & `public/js/accessibility.js` (Web Speech API Indonesian TTS, 4-step font scaling, dyslexia font, localStorage persistence).

---

## 2. Screenshots & Visual Assets Used

All visual assets reside locally in `showcase-assets/` for offline and local file execution without internet dependencies:
- `showcase-assets/legacy-homepage.png` — Full desktop viewport capture of the legacy production portal (`csirt.jakarta.go.id`), displaying the bright orange color scheme and intrusive news modal pop-up.
- `showcase-assets/legacy-report-form.png` — Full-page capture of the unguided legacy incident form (`/cyber-report`).
- `showcase-assets/csirt-main-logo.png` — Official vector mark of Jakarta Provincial CSIRT.
- `showcase-assets/jaya_raya.png` — Official Provincial Government of DKI Jakarta coat of arms.
- `showcase-assets/logo_diskominfo.png` — Official Diskominfotik DKI Jakarta agency logo.
- High-fidelity in-DOM mockups and interactive simulators for the new single-page form, error recovery states, and accessibility widget.

---

## 3. Major Findings Visualized

1. **The Polarity Paradox Deconstructed (SUS):**
   - Both iterations beat the global usability standard of 68.0 ($75.65$ vs. $70.46$, $t = 1.13, p = 0.259$).
   - Deconstructing individual items proves that **every single positive construct improved** (reuse intent $+0.35$, integration $+0.36$, confidence $+0.09$).
   - The composite score was pulled down by an acquiescence straight-lining artifact ($18.5\%$ selecting uniform scores, mathematically locking their SUS at 50.0) and genuine user pushback against the new login requirement.
   - Clean SUS without straight-liners: **$75.11 \pm 11.27$** (virtually identical to baseline, $36\%$ lower variance).
2. **Complete Elimination of Negative Sentiment (Sentiment Stabilization):**
   - Negative satisfaction ratings (ratings of 2 on a 1–5 scale) dropped from $8.7\%$ on the legacy portal to **$0.0\%$ on the new portal**.
   - Overall satisfaction standard deviation collapsed by $40\%$ ($\pm 1.04 \rightarrow \pm 0.62$), indicating a much more dependable and uniform user experience.
3. **Decisive Operational Mandate ($88.9\%$ Consensus):**
   - When asked which system is more prepared and suitable for official DKI Jakarta deployment, **$88.9\%$ ($24/27$)** voted for the redesign (matched subgroup: $90.9\%$).
4. **Resolution of Top Legacy Distractors:**
   - Evaluator satisfaction for the Deep Navy palette and pop-up removal scored **$4.04 \pm 0.71 / 5.0$** ($85.2\%$ favorable), directly resolving the top baseline complaints.
5. **Multi-Tool Automated WCAG Conformance:**
   - WAVE contrast errors dropped from 21–43 per page to **0**.
   - axe-core critical Level A violations dropped from 2 per page to **0**.
   - Snapshot progression demonstrates the DSR refinement cycle: Snapshot A cleared Level A, Snapshot B cleared AA tokens, Snapshot C cleared WAVE-only dark-header eyebrow contrast.

---

## 4. Findings That Could Not Be Visualized Directly

1. **Real-time Screen Reader Voice Audio Output:**
   - While the showcase visualizes the Web Speech API code and Indonesian TTS integration, live screen-reader audio (JAWS/NVDA) cannot be pre-recorded as a static offline asset without ballooning file sizes.
2. **Live Database Crash Exception Stack Trace:**
   - The legacy Yii crash on penetration test payloads is simulated using documented error logs and user testimony (Azmi & Erick), as the live legacy server is an external production system that cannot be intentionally crashed during presentations.
3. **Long-Term Longitudinal Incident Resolution Speed:**
   - Incident ticket resolution velocity (from `menunggu_validasi` to `selesai`) could not be measured because the staging portal was evaluated during task walkthroughs rather than an annual operational cycle.

---

## 5. Important Research Caveats & Boundaries

- **Not Clinical PwD Proof:** Evaluators were domain practitioners (cybersecurity analysts, incident handlers, administrative staff), not certified persons with disabilities. Technical accessibility is established via automated axe/WAVE audits, but clinical assistive testing remains a future research target.
- **Partially Overlapping Cohort:** Only 11 of the 27 evaluators could be mapped to the baseline survey. The primary analysis must be presented as independent groups ($23$ vs. $27$), backed by the matched paired sensitivity analysis ($n=11$).
- **No Direct Framework Causality:** Migrating from Yii to Laravel 12 provided modern routing and ORM parameter binding, but framework migration alone does not guarantee UX improvements. Usability gains stem directly from disciplined design token architecture and guided form workflows.
- **The Civic Dilemma (Security vs. Friction):** Gating the incident form behind mandatory authentication (`/bug-hunter/login`) protects the team from automated spam and organizes reports, but introduces an access barrier for anonymous whistleblowers. This is an authentic trade-off, not an unmitigated victory.

---

## 6. Instructions for Presenting the Showcase to a Supervisor

1. **Opening (Section 1):**
   - Start by framing the session around the research question: *"Did the New Jakarta CSIRT Portal Actually Improve the User Experience?"*
   - Point out that this is an empirical DSRM study (Peffers et al., 2007) triangulating technical audits, standardized psychometrics, and operational feedback.
2. **Problem Diagnosis (Section 2):**
   - Walk through the legacy baseline: 39% complained of visual clutter, 26% of harsh orange color, and 22% of the intrusive pop-up. Read the quotes from Azmi and Erick regarding database crashes.
3. **The Interventions & UI Comparison (Sections 3 & 4):**
   - Use the **Interactive Before → After Explorer**. Click on Hotspots 1, 2, 3, and 4 to demonstrate how tokens, modal removal, and guided single-page architecture solved these problems.
4. **Addressing the SUS Score Nuance (Section 7):**
   - Proactively address the raw SUS score ($70.46$ vs. $75.65$):
     * Explain that both surpass the global benchmark of 68.0 ($p = 0.259$, non-significant).
     * Show the **Polarity Deconstruction Table**: highlight that **every single positive item increased**.
     * Explain the mathematical acquiescence effect where 5 respondents gave uniform scores, mathematically locking their result at 50.0.
     * Reveal the clean SUS of **$75.11 \pm 11.27$** and point to the matched cohort ($90.9\%$ preferred the new system).
5. **The Decisive Operational Verdict (Section 8):**
   - Highlight the **$88.9\%$ mandate**: when practitioners judged real-world operational viability, 24 out of 27 voted for the redesign.
6. **Maturity in Limitations (Section 11):**
   - Walk through *"What Still Needs Work?"*, specifically the trade-off between authenticated reporting and anonymous whistleblowing. Presenting this limitation demonstrates high academic rigor and sets up the next iteration cycle.
7. **Keyboard Shortcuts:**
   - Use <kbd>→</kbd> and <kbd>←</kbd> arrow keys to smoothly glide between sections during the presentation.
   - Click the **"Theme"** or **"Contrast"** button in the header to demonstrate real-time high-contrast rendering.

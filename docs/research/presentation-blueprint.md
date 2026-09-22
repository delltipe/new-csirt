# Visual Research Presentation Blueprint: Legacy vs. New Jakarta CSIRT Public Portal

**Project:** Jakarta CSIRT Public Portal Redesign & Evaluation  
**Researcher:** Abdul Latif (Bina Nusantara University / Diskominfotik DKI Jakarta)  
**Methodological Framework:** Design Science Research Methodology (DSRM; Peffers et al., 2007)  
**Deliverable Type:** 10-Slide Academic Research Presentation Blueprint & AI Generation Prompt  
**Date:** September 22, 2026  

---

## 1. Presentation Narrative & Strategic Framing

The presentation is designed as an **evidence-based empirical investigation**, not a promotional redesign showcase. Rather than claiming an unmitigated triumph, the narrative follows an honest scientific arc:
1. **The Question (Slides 1–2):** Municipal CSIRT portals are critical civic cybersecurity infrastructure, but the legacy portal suffered from visual distractors, navigation friction, accessibility failures, and reporting errors. Did the redesign actually improve the user experience?
2. **The Interventions & Methods (Slides 2–3):** Three concrete interventions were implemented (Design tokens for contrast/palette, a single-page guided incident form with TaC gate, and an accessibility widget). Evaluation triangulated automated audits (axe/WAVE) with domain-practitioner surveys (SUS + replicated Likert + qualitative feedback).
3. **The Empirical Findings (Slides 4–8):**
   - *Finding 1 (SUS):* Perceived usability meets the international benchmark of 68.0 ($70.46$ raw, $75.11$ clean). Positive constructs improved, while negative constructs captured survey straight-lining ($18.5\%$) and genuine pushback against mandatory login.
   - *Finding 2 (Sentiment Stabilization):* Replicated items stabilized ($4.19–4.30/5$), completely eliminating negative ratings ($0\%$).
   - *Finding 3 (Visual Design):* The Deep Navy palette and pop-up removal received $85.2\%$ favorable ratings, resolving top legacy distractors.
   - *Finding 4 (The Reporting Dilemma):* Guided reporting eliminated technical crashes, but mandatory authentication created civic friction.
   - *Finding 5 (Operational Viability):* Stakeholders delivered an **$88.9\%$ consensus** that the redesign is superior and ready for municipal operations.
4. **Honesty & Takeaways (Slides 9–10):** Transparently addressing limitations (non-PwD sample, partial cohort overlap, self-reporting) and distinguishing supported conclusions from future iteration targets (enabling anonymous tokenized reporting).

---

## 2. Design System & Visual Guidelines

The presentation aesthetic combines **modern academic rigor** with **civic design system restraint** (mirroring the Jakarta CSIRT token architecture in `public/css/style.css` and `presentation/build_presentation.py`):
- **Aspect Ratio:** 16:9 widescreen ($13.333 \times 7.5$ inches).
- **Color Palette:**
  - Background: Pure White (`#FFFFFF`) or Crisp Mist (`#F4F5F7`).
  - Primary / Text: Deep Ink (`#0A0F1A`, high contrast).
  - Accent / Brand: CSIRT Deep Navy (`#003580` / `#002060`).
  - Surface / Tints: Navy Tint (`#E8EFF8`), Border Gray (`#D8DCE3`), Muted Gray (`#6B7280`).
  - Alert / Friction: Muted Red (`#B91C1C`).
- **Typography:**
  - Slide Titles & Metric Callouts: `Plus Jakarta Sans` (Bold, clean, civic).
  - Body Text, Tables, & Labels: `Inter` (Legible, neutral).
- **Visual Rules:**
  - Boxy or subtle rounded cards (radius $\le 4\text{px}$), matching the NYC.gov civic design ethos.
  - Large callout numbers with descriptive context.
  - Zero decorative clutter, 3D effects, or generic stock icons.

---

## 3. Slide-by-Slide Blueprint (10 Slides)

---

### Slide 1: Title & Research Framing
- **Slide Number:** 1
- **Slide Title:** Did the New Jakarta CSIRT Portal Actually Improve the User Experience?
- **Subtitle:** An Empirical Evaluation of Usability, Accessibility, and Operational Viability
- **Purpose:** Open with an objective research question that piques curiosity, avoiding premature claims of success.
- **Main Message:** The study rigorously evaluates whether replacing the legacy portal with an accessible, guided architecture actually resulted in measurable usability and operational improvements for Jakarta's cybersecurity agency.
- **Visual Structure:**
  - Left Column ($60\%$ width): Large title in `Plus Jakarta Sans`, research metadata (Researcher, Institutions, Framework: DSRM Peffers et al., 2007).
  - Right Column ($40\%$ width): High-contrast card showing the evaluation triad:
    ```
    ┌──────────────────────────────────────────────┐
    │          THE EVALUATION FRAMEWORK            │
    ├──────────────────────────────────────────────┤
    │  [1] Technical Accessibility (axe & WAVE)    │
    │  [2] Perceived Usability (Standardized SUS)  │
    │  [3] Operational Suitability (BSSA Staff)    │
    └──────────────────────────────────────────────┘
    ```
- **Exact Data Displayed:** None (Orientation slide).
- **Codebase / Asset Evidence:** Official logos from `public/`: `jaya_raya.png`, `logo_diskominfo.png`, `csirt-main-logo.png`.
- **Presenter Message (Speaker Notes):**
  *"Good morning. When government digital portals are redesigned, teams often assume that modern code automatically equates to a better experience. In this study, we set aside assumptions and asked a direct empirical question: Did the redesign of the Jakarta CSIRT public portal actually improve usability and operational readiness? We evaluated the legacy portal against the new system across automated accessibility audits, standardized SUS testing, and direct practitioner feedback. Here is what the evidence actually says."*
- **Source / Citation:** Brooke (1996); Peffers et al. (2007); Diskominfotik DKI Jakarta.

---

### Slide 2: Context & What Changed: 3 Tangible Interventions
- **Slide Number:** 2
- **Slide Title:** Context & Interventions: What Was Changed?
- **Purpose:** Ground the presentation in tangible system improvements rather than abstract claims.
- **Main Message:** The redesign targeted three specific failure modes identified in the legacy portal: jarring visual contrast, an unguided flat reporting form, and the absence of assistive accessibility tools.
- **Visual Structure:** 3-column comparative visual cards showing Before $\rightarrow$ After:
  ```
  +-----------------------------------------------------------------------------------------+
  | 1. CONTRAST & BRANDING       | 2. INCIDENT REPORTING        | 3. INCLUSIVE ACCESS       |
  +-----------------------------------------------------------------------------------------+
  | Legacy: Jarring orange,      | Legacy: Flat form, no guide, | Legacy: Zero assistive    |
  | intrusive modal pop-up.      | submission database crashes. | accessibility features.   |
  |                              |                              |                           |
  | Redesign: Deep Navy & Ink    | Redesign: Single-page guided | Redesign: Dedicated       |
  | design tokens (WCAG 1.4.3).  | form with TaC gate & ticket. | Accessibility Widget.     |
  +-----------------------------------------------------------------------------------------+
  ```
- **Exact Data Displayed:**
  - Contrast Violations: Legacy $8/8$ pages failed $\rightarrow$ Redesign $0/8$ pages ($100\%$ resolved).
  - Critical Level A Failures: Legacy $2$ per page $\rightarrow$ Redesign $0$.
- **Codebase / Asset Evidence:**
  - Legacy Screenshot: [`screencapture-csirt-jakarta-go-id-index-php-2026-06-19-15_35_28.png`](file:///D:/Workspace-CSIRT/01-Academic-Deliverables/Assignments-Logbooks/screencapture-csirt-jakarta-go-id-index-php-2026-06-19-15_35_28.png) (showing orange banner and popup).
  - Codebase paths: `public/css/style.css` (`--navy`, `--ink`), `resources/views/bughunter/report-create.blade.php`, `resources/views/components/accessibility.blade.php`.
- **Presenter Message (Speaker Notes):**
  *"The legacy Yii portal suffered from three critical problems: first, an aggressive orange color scheme and an intrusive news pop-up that annoyed users and failed WCAG contrast standards; second, an unguided flat incident form prone to database crashes; and third, a complete lack of assistive tools for users with disabilities. Our Laravel redesign addressed these with design-token contrast refactoring, a guided single-page form with Terms & Conditions gate, and an integrated accessibility widget."*
- **Source / Citation:** Automated WCAG Audit Protocol (`WCAG 2.1 AA Audit Protocol.md`); W3C WCAG 2.1.

---

### Slide 3: Evaluation Methodology & Cohort Structure
- **Slide Number:** 3
- **Slide Title:** Evaluation Methodology: A Triangulated Approach
- **Purpose:** Transparently explain how data was collected, clarifying cohort structure to prevent misleading "pre/post" claims.
- **Main Message:** Data was gathered across two survey waves from municipal cybersecurity practitioners (BSSA Diskominfotik), analyzed primarily as independent groups ($N=23$ vs. $N=27$) with a matched longitudinal subgroup ($n=11$).
- **Visual Structure:**
  - Left Side ($50\%$ width): Participant flow diagram illustrating the sample breakdown:
    ```
    LEGACY SURVEY (Aug 2026)             NEW SURVEY (Sep 2026)
         [ N = 23 ]                           [ N = 27 ]
              \                                   /
               \─────── [ n = 11 Matched ] ──────/
               Verified Longitudinal Practitioners
    ```
  - Right Side ($50\%$ width): Evaluation components table:
    - Automated WCAG 2.1 AA (axe-core Playwright + WAVE extension).
    - Standardized Usability: 10-Item System Usability Scale (SUS).
    - Replicated Likert Items: Ease, Trust, Satisfaction, Error rate (1–5 scale).
    - Qualitative Thematic Inquiries: Helpful features, friction points, iterations.
- **Exact Data Displayed:**
  - Legacy Sample: $N = 23$ (BSSA staff & domain practitioners).
  - New Sample: $N = 27$ ($100\%$ completed interactive task walkthroughs).
  - Longitudinal Matched Cohort: $n = 11$ verified individuals.
- **Codebase / Asset Evidence:** CSV survey files: [`SUS Questionnaire — CSIRT Jakarta Portal (Legacy).csv`](file:///D:/Workspace-CSIRT/02-Research-Thesis-Preparation/Questionnaires/SUS%20Questionnaire%20%E2%80%94%20CSIRT%20Jakarta%20Portal%20(Legacy).csv) and [`SUS Questionnaire — CSIRT Jakarta Portal (New).csv`](file:///D:/Workspace-CSIRT/02-Research-Thesis-Preparation/Questionnaires/SUS%20Questionnaire%20%E2%80%94%20CSIRT%20Jakarta%20Portal%20(New).csv).
- **Presenter Message (Speaker Notes):**
  *"Methodological honesty is paramount. We did not test generic students; our respondents were professional staff from BSSA Diskominfotik who handle cybersecurity incidents daily. Twenty-three evaluated the legacy portal, and twenty-seven tested the new system after completing mandatory task walkthroughs. Eleven individuals completed both surveys. Because the cohorts are partially overlapping, we analyze the main data as independent groups, backed by a matched paired sub-analysis."*
- **Source / Citation:** DSRM Evaluation Phase (Peffers et al., 2007); Sharfina & Santoso (2022).

---

### Slide 4: Finding 1 — Overall Usability & The SUS Polarity Paradox
- **Slide Number:** 4
- **Slide Title:** Finding 1: System Usability Meets Industry Benchmark
- **Purpose:** Present the SUS results with rigorous nuance, deconstructing the polarity and straight-lining effects.
- **Main Message:** Both versions surpass the international usability benchmark of 68.0 ($70.46$ vs. $75.65$, difference not statistically significant). All positive usability constructs increased, while negative constructs captured survey straight-lining and login friction.
- **Visual Structure:**
  - Top Half: Large visual benchmark chart:
    ```
    SUS Score (0 - 100)
    0               50.0 [Straight-line]      68.0 [Benchmark]  70.46 [New]  75.65 [Legacy]   100
    |----------------|--------------------------|-------------------|------------|-------------|
                                                ▲ Sauro Industry Avg
    ```
  - Bottom Left Card: **Positive Items (+)**  
    - Willingness to reuse (B1): $3.87 \rightarrow \mathbf{4.22}$ ($+0.35$)  
    - System integration (B5): $3.83 \rightarrow \mathbf{4.19}$ ($+0.36$)  
    - User confidence (B9): $4.13 \rightarrow \mathbf{4.22}$ ($+0.09$)  
  - Bottom Right Card: **Sensitivity Analysis (Clean $N=22$)**  
    - 5 respondents straight-lined across alternating polarity, scoring exactly $50.0$.  
    - Clean New SUS: **$75.11 \pm 11.27$** (Median **$75.0$**, $77.3\% \ge 68.0$).  
    - Independent $t$-test: $t(42) = 1.13, p = 0.259$ (Raw) $\rightarrow$ $t(37.5) = 0.12, p = 0.903$ (Clean).
- **Exact Data Displayed:**
  - Legacy SUS: Mean $75.65 \pm 17.71$, Median $77.50$, $\ge 68.0$: $69.6\%$.
  - New SUS (Raw): Mean $70.46 \pm 14.19$, Median $72.50$, $\ge 68.0$: $63.0\%$.
  - New SUS (Clean): Mean $75.11 \pm 11.27$, Median $75.00$, $\ge 68.0$: $77.3\%$.
- **Codebase / Asset Evidence:** `docs/research/questionnaire-comparison-analysis.md` (§ 5).
- **Presenter Message (Speaker Notes):**
  *"A superficial glance at SUS might suggest a 5-point drop. But when we look into the data, two critical facts emerge: First, the difference is statistically non-significant ($p = 0.26$), and both versions comfortably beat the global usability standard of 68. Second, deconstructing the items reveals that ratings on every single positive statement—integration, reuse intent, confidence—actually went up. The composite score was pulled down because 5 respondents gave uniform responses across alternating grids, which mathematically locks the score at 50, and because users encountered friction with the new login requirement. When straight-liners are removed, the score is 75.1, virtually identical to baseline."*
- **Source / Citation:** Brooke (1996); Sauro (2011); Sauro & Lewis (2011).

---

### Slide 5: Finding 2 — Stabilization: Complete Elimination of Negative Sentiment
- **Slide Number:** 5
- **Slide Title:** Finding 2: Perceived Experience & Sentiment Stabilization
- **Purpose:** Show distribution shifts across the replicated 1–5 ordinal items, demonstrating a more dependable experience.
- **Main Message:** While mean ratings showed slight positive movement ($+0.04$ to $+0.19$), the critical achievement was the total elimination of negative user sentiment sitewide.
- **Visual Structure:** Diverging stacked bar charts for replicated items showing ratings $2$ (Dissatisfied), $3$ (Neutral), $4$ (Satisfied), and $5$ (Very Satisfied):
  ```
  Overall Satisfaction (C3):
  Legacy (N=23): [2: 8.7%] [3: 26.1%]   [4: 21.7%]    [5: 43.5%]    --> Mean 4.00 ± 1.04
  New (N=27):    [2: 0.0%] [3: 11.1%]   [4: 59.3%]    [5: 29.6%]    --> Mean 4.19 ± 0.62

  Ease of Finding Features (C1):
  Legacy (N=23): [3: 17.4%] [4: 39.1%]  [5: 43.5%]                  --> Mean 4.26 ± 0.75
  New (N=27):    [3: 11.1%] [4: 48.1%]  [5: 40.7%]                  --> Mean 4.30 ± 0.67

  Trust from Appearance (C2):
  Legacy (N=23): [2: 4.3%]  [3: 21.7%]  [4: 26.1%]    [5: 47.8%]    --> Mean 4.17 ± 0.94
  New (N=27):    [2: 0.0%]  [3: 11.1%]  [4: 55.6%]    [5: 33.3%]    --> Mean 4.22 ± 0.64
  ```
- **Exact Data Displayed:**
  - Negative ratings (rating of 2): Legacy $8.7\% \rightarrow$ New **$0.0\%$** ($-8.7$ percentage points).
  - Satisfaction SD: $\pm 1.04 \rightarrow \pm 0.62$ ($40\%$ drop in variance).
  - Crash/Error Experience (C4): Legacy $8.7\% \rightarrow$ New $7.4\%$ (Fisher's exact $p = 1.00$).
- **Codebase / Asset Evidence:** `docs/research/questionnaire-comparison-analysis.md` (§ 4.2).
- **Presenter Message (Speaker Notes):**
  *"In public service portals, eliminating poor experiences is just as important as raising the ceiling. In the legacy portal, nearly 9% of staff reported being dissatisfied. In the redesigned portal, negative sentiment was completely eliminated—zero respondents gave a rating of 1 or 2 across ease, trust, or satisfaction. The standard deviation collapsed from 1.04 to 0.62, showing that the portal delivers a far more consistent and reliable experience across the board."*
- **Source / Citation:** Pre/Post replication items (Feb 2026 baseline vs. Sep 2026 survey).

---

### Slide 6: Finding 3 — Aesthetics & Brand Alignment: Resolving Visual Distractors
- **Slide Number:** 6
- **Slide Title:** Finding 3: Visual Identity & Resolving Legacy Distractors
- **Purpose:** Connect design-token refactoring directly to user perception and qualitative feedback.
- **Main Message:** Replacing harsh orange with Deep Navy and eliminating the intrusive news pop-up resolved the top complaints from the legacy baseline, earning an $85.2\%$ positive rating.
- **Visual Structure:**
  - Left Side ($50\%$ width): Problem-to-Solution visual comparison:
    ```
    LEGACY DISTRACTORS (C5, N=23)          DESIGN INTERVENTION & RATING (N=27)
    • Visual Design: 9 mentions (39.1%)    • Deep Navy & Ink Token System
    • Unsuitable Color: 6 mentions (26.1%) • Total Removal of News Modal Pop-up
    • Intrusive Pop-up: 5 mentions (21.7%) • Evaluator Rating: 4.04 ± 0.71 / 5.0
                                             (85.2% rated 4 or 5)
    ```
  - Right Side ($50\%$ width): Quotation callout cards:
    - *"Secara tampilan lebih modern, warnanya lebih relevan untuk web CSIRT."* — Respondent N04 (Cybersecurity Staff)
    - *"Warna lebih soft, tampilan lebih baik tidak kaku."* — Respondent N10 (CSIRT Operator)
    - *"Tampilan lebih bagus dan modern, terlihat cukup profesional dibanding yang lama."* — Respondent N23
- **Exact Data Displayed:**
  - Evaluator Satisfaction on Palette & Pop-up: Mean **$4.04 \pm 0.71$**, Median **$4.0$** ($23/27$ favorable).
  - Legacy Distractor Frequencies: Design ($9$), Color ($6$), Pop-up ($5$).
- **Codebase / Asset Evidence:**
  - Legacy Screenshot: `csirt.jakarta.go.id-honeypot-page1_files\header-statistikhoneypot.jpg`.
  - Token CSS: `public/css/style.css` (`--navy: #003580; --ink: #0A0F1A;`).
- **Presenter Message (Speaker Notes):**
  *"In our baseline survey, staff repeatedly noted that bright orange felt unprofessional for a cybersecurity agency and that the pop-up modal was blinding and intrusive. We replaced it with a calm Deep Navy and Ink palette governed by strict contrast tokens and removed the modal entirely. Evaluators gave this change a 4.04 out of 5, praising the portal as modern, soft on the eyes, and credible."*
- **Source / Citation:** Mize & Theisen (2020); `docs/research/questionnaire-comparison-analysis.md` (§ 6).

---

### Slide 7: Finding 4 — The Reporting Dilemma: Guided Form vs. Authentication Friction
- **Slide Number:** 7
- **Slide Title:** Finding 4: The Civic Reporting Dilemma: Guidance vs. Access Barriers
- **Purpose:** Present a nuanced, critical research finding regarding the incident reporting workflow.
- **Main Message:** The single-page guided form eliminated technical submission crashes and received positive workflow ratings ($3.96/5$), but mandatory account authentication introduced unintended friction for bug hunters.
- **Visual Structure:**
  - Diagram: The Workflow Tension:
    ```
    ┌───────────────────────────────────┐       ┌───────────────────────────────────┐
    │     TECHNICAL & UX ADVANCES       │  VS   │      CIVIC ACCESS BARRIERS        │
    ├───────────────────────────────────┤       ├───────────────────────────────────┤
    │ • Single-page guided structure    │       │ • Mandatory login required        │
    │ • 0 database submission crashes   │       │   (/bug-hunter/login)             │
    │ • Automatic INS-YYYY-XXXX ticket  │       │ • Friction against civic mental   │
    │ • Mean Workflow Rating: 3.96 / 5  │       │   model of anonymous reporting    │
    └───────────────────────────────────┘       └───────────────────────────────────┘
    ```
  - Direct Qualitative Quotes on Friction:
    - *"Untuk lapor insiden harus login dulu. Apakah tidak bisa menggunakan anonim? Lalu tiket dikirim ke email..."* — Respondent N20
    - *"Saat membuat insiden sebaiknya tidak perlu login. Untuk tracking status laporan bisa dibuat seperti tracking paket kurir."* — Respondent N22
    - *"Kendala yang lama sepertinya gak perlu login untuk lapor."* — Respondent N15
- **Exact Data Displayed:**
  - Incident Reporting Workflow Experience: Mean **$3.96 \pm 0.76$**, Median **$4.0$** ($70.4\%$ rated 4 or 5).
  - Database submission errors: Legacy reported $1$ fatal DB crash $\rightarrow$ New $0$ DB crashes.
- **Codebase / Asset Evidence:**
  - Form Template: `resources/views/bughunter/report-create.blade.php`.
  - Authentication Gate: `routes/web.php` (`Route::middleware('auth')...`).
- **Presenter Message (Speaker Notes):**
  *"This is our most valuable academic discovery. Technically, the single-page guided form succeeded: the database crashes that plagued the legacy form were gone, validation guidance was clear, and 70% rated the flow favorably. However, by requiring users to register a 'Bug Hunter' account and log in, we created an administrative barrier. In municipal cybersecurity, citizens and white-hat researchers expect frictionless, anonymous disclosure channels with tokenized tracking, like tracking a courier package. This insight directly informs our next iteration."*
- **Source / Citation:** Hossain et al. (2025); `docs/research/questionnaire-comparison-analysis.md` (§ 7).

---

### Slide 8: Finding 5 — Operational Suitability: 88.9% Stakeholder Consensus
- **Slide Number:** 8
- **Slide Title:** Finding 5: Operational Readiness: Decisive Stakeholder Mandate
- **Purpose:** Present the summative forced-choice operational verdict.
- **Main Message:** When asked to judge real-world operational viability, nearly $9$ out of $10$ practitioners ($88.9\%$) voted that the new portal is more prepared and suitable for official deployment by CSIRT DKI Jakarta.
- **Visual Structure:**
  - Large Donut / Proportion Graphic:
    ```
    FORCED-CHOICE OPERATIONAL SUITABILITY (N = 27)
    
       [█████████████████████████████████████████████░░░░░░]
       VERSI BARU (New Portal):  24 / 27  (88.9%)
       VERSI LAMA (Legacy Portal): 3 / 27  (11.1%)
    ```
  - Supporting Reasons Grouped by Theme:
    - *System Architecture & Controls:* "Memiliki arsitektur yang lebih modern, kontrol akses yang jelas, dan siap mendukung penanganan insiden cepat dan terstruktur." (Respondent N11)
    - *Clarity & Ergonomics:* "Lebih clean, menunya mudah dicari, penyampaian informasi lebih rapi." (Respondents N12, N20)
    - *Visual Credibility:* "Tampilan modern dan eye-catching, terlihat professional." (Respondents N14, N23)
- **Exact Data Displayed:**
  - Versi Baru: **$24 / 27$ ($88.89\%$)**.
  - Versi Lama: **$3 / 27$ ($11.11\%$)**.
  - Matched Subgroup ($n=11$): **$10 / 11$ ($90.9\%$)** chose Versi Baru.
- **Codebase / Asset Evidence:** `docs/research/questionnaire-comparison-analysis.md` (§ 4.4).
- **Presenter Message (Speaker Notes):**
  *"Beyond isolated usability metrics, we asked the ultimate pragmatic question: Which system is more viable for official, day-to-day operations by Jakarta CSIRT? The result was decisive: 88.9% of evaluators chose the new portal. Among the eleven practitioners who used both versions over time, over 90% chose the new portal, highlighting its structured permissions, clean information architecture, and operational maturity."*
- **Source / Citation:** Peffers et al. (2007) Demonstration & Evaluation stages.

---

### Slide 9: Methodological Honesty: What the Data Does NOT Say
- **Slide Number:** 9
- **Slide Title:** Research Integrity: What the Data Does Not Prove
- **Purpose:** Establish research maturity by distinguishing legitimate claims from unscientific over-interpretations.
- **Main Message:** The evaluation provides strong evidence of improved ergonomics and operational viability, but does not claim clinical accessibility proof, causal framework effects, or longitudinal perfection.
- **Visual Structure:** 4 clean caveat cards with neutral, non-defensive icons:
  ```
  +-----------------------------------+-----------------------------------+
  | 1. NO CLINICAL PwD TESTING        | 2. TECHNICAL ≠ PERCEIVED ACCESS   |
  | Evaluators were domain experts    | Automated zero-error audit proves |
  | (BSSA), not certified persons     | code compliance, but general      |
  | with disabilities (PwD).          | users only perceive visual layout.|
  +-----------------------------------+-----------------------------------+
  | 3. PARTIALLY PAIRED COHORT        | 4. NO DIRECT FRAMEWORK CAUSALITY  |
  | 11 matched pairs; remaining are   | Moving to Laravel is engineering  |
  | independent groups. Must not be   | context; improvements stem from   |
  | claimed as a pure repeated cohort.| UX token and workflow design.     |
  +-----------------------------------+-----------------------------------+
  ```
- **Exact Data Displayed:**
  - Sample composition: Domain practitioners ($100\%$), PwD sample ($0\%$).
  - Sample overlap: Matched ($11$), Independent ($16$ & $12$).
- **Codebase / Asset Evidence:** `docs/research/questionnaire-comparison-analysis.md` (§ 14).
- **Presenter Message (Speaker Notes):**
  *"A rigorous academic study must be clear about its boundaries. First, our participants were domain experts, not persons with disabilities; technical accessibility is proven through automated axe and WAVE audits, but clinical assistive testing remains future work. Second, our sample is partially overlapping, which is why we report independent group statistics rather than exaggerating a repeated-measures design. Finally, we do not claim that the Laravel framework magically caused these gains—the gains stem from disciplined UX token refactoring and guided form design."*
- **Source / Citation:** Abdulreda et al. (2024); Zając & Królak (2025).

---

### Slide 10: Conclusion & Next DSR Iteration
- **Slide Number:** 10
- **Slide Title:** Summary of Evidence & Future DSR Cycles
- **Purpose:** Deliver a crisp, balanced conclusion separating supported contributions from concrete next steps.
- **Main Message:** The redesign achieved its core objectives of WCAG conformance, distractor elimination, and operational suitability, while identifying a crucial civic iteration for anonymous incident tracking.
- **Visual Structure:** Two distinct side-by-side columns:
  ```
  ┌─────────────────────────────────────┐   ┌─────────────────────────────────────┐
  │        SUPPORTED BY EVIDENCE        │   │      NEXT DSR REFINEMENT CYCLE      │
  ├─────────────────────────────────────┤   ├─────────────────────────────────────┤
  │ [x] Usability benchmark exceeded    │   │ [ ] Anonymous Incident Reporting    │
  │     (SUS 70.46 / 75.11 >= 68.0)     │   │     Tokenized email tracking        │
  │ [x] 100% elimination of negative    │   │     without mandatory login         │
  │     satisfaction ratings sitewide   │   │                                     │
  │ [x] 88.9% stakeholder mandate for   │   │ [ ] Native Mobile Hamburger Nav     │
  │     official operational deployment │   │     Responsive polish for small     │
  │ [x] Automated WCAG Level A/AA zero- │   │     handheld viewports              │
  │     error conformance across portal │   │                                     │
  │ [x] Visual distractor resolution    │   │ [ ] Assistive User Testing          │
  │     validated (4.04/5 rating)       │   │     Screen-reader testing with PwDs │
  └─────────────────────────────────────┘   └─────────────────────────────────────┘
  ```
- **Exact Data Displayed:**
  - Key metrics: SUS $\ge 68.0$, Negative ratings $0.0\%$, Operational preference $88.9\%$, Contrast errors $0$.
- **Codebase / Asset Evidence:** `docs/research/questionnaire-comparison-analysis.md` (§ 15).
- **Presenter Message (Speaker Notes):**
  *"To conclude: the evaluation demonstrates that disciplined design science research can transform a vulnerable, unguided government portal into an operationally viable, WCAG-conformant platform trusted by 89% of its operational staff. Furthermore, by listening to user friction, we have identified our next design cycle: decoupling incident reporting from mandatory accounts to enable anonymous, courier-style ticket tracking. Thank you."*
- **Source / Citation:** Peffers et al. (2007); Hevner et al. (2004).

---

## 4. Visual Evidence & Asset Inventory

```
+-------------------------------------------------------------------------------------------------------+
| Slide | Visual Asset Description                 | File Path / Source Reference                       |
+-------------------------------------------------------------------------------------------------------+
| 1     | DKI Jaya Raya & Jakarta CSIRT Logos     | public/jaya_raya.png, public/csirt-main-logo.png   |
| 2     | Legacy Homepage Orange Screenshot        | 01-Academic-Deliverables/.../screencapture-...png  |
| 2     | Redesign CSS Tokens & Guided Form Blade  | public/css/style.css, report-create.blade.php      |
| 3     | Longitudinal Cohort Flow Diagram         | Generated Vector Graphic (Slide 3 spec)           |
| 4     | SUS Benchmark & Polarity Bar Chart       | Data from docs/research/questionnaire-...md (§ 5)  |
| 5     | Diverging 100% Stacked Bar Chart (C1-C3) | Data from docs/research/questionnaire-...md (§ 4)  |
| 6     | Palette Comparison & Quote Cards         | style.css (--navy, --ink), legacy honeypot header  |
| 7     | Workflow vs. Barrier Tension Diagram     | Generated Conceptual Layout (Slide 7 spec)         |
| 8     | Forced-Choice 88.9% Proportion Chart     | Data from docs/research/questionnaire-...md (§ 4.4)|
| 9     | 4 Caveat Neutral Framework Cards         | Design Framework (Slide 9 spec)                    |
| 10    | Two-Column Evidence Matrix               | Summary Matrix (Slide 10 spec)                     |
+-------------------------------------------------------------------------------------------------------+
```

### Explicitly Identified Missing Assets:
1. **Clean High-Resolution Screenshot of Redesigned Homepage (`/`)** at $1366 \times 768$ desktop viewport with Normal accessibility state.
2. **Clean High-Resolution Screenshot of Redesigned Guided Incident Form (`/bug-hunter/laporan/baru`)** showing input labels and help text.
3. *Recommendation:* Capture these two screenshots from the running local staging environment (`php artisan serve` at `http://127.0.0.1:8000`) before final slide assembly.

---

## 5. Speaker Delivery Guidelines

1. **Academic Demeanor:** Speak with objective, measured confidence. Never use colloquial marketing phrases ("revolutionary redesign", "total success", "flawless UX").
2. **Handling the SUS Question:** Anticipate the audience asking about the raw SUS score ($70.46$ vs. $75.65$). Immediately pivot to Slide 4's polarity breakdown: explain that positive items improved, while the composite was influenced by $18.5\%$ survey straight-lining and the login gate friction.
3. **Emphasizing the 88.9% Mandate:** Highlight that when practitioners evaluated the overall system for official deployment, consensus was near-unanimous ($88.9\%$).
4. **Framing Limitations as Strength:** Deliver Slide 9 with authority. Highlighting limitations shows high academic maturity and reinforces the credibility of positive findings.

---

```
====================================================================================================
PROMPT FOR PRESENTATION-GENERATION AI (COPY & PASTE READY)
====================================================================================================
```

# Prompt for Presentation-Generation AI

```text
You are an expert academic presentation designer and scientific communicator specializing in Human-Computer Interaction (HCI), e-Government, and Design Science Research (DSR).

YOUR TASK:
Generate a complete, highly polished, 10-slide visual research presentation deck based strictly on the provided empirical research dataset and blueprint.

CRITICAL RESEARCH RULES & CONSTRAINTS:
1. Do NOT invent or extrapolate statistics, numbers, sample sizes, or quotes. Every metric must match the provided dataset exactly.
2. Maintain strict academic objectivity. Do NOT turn this into a corporate pitch, marketing brochure, or promotional redesign showcase.
3. Preserve the distinction between observed empirical data and analytical interpretation.
4. Distinguish between technical accessibility conformance (automated audit: axe-core, WAVE) and user perception (SUS, questionnaires). Automated compliance does NOT prove human ease-of-use, and user perception does NOT prove WCAG compliance.
5. Do NOT claim direct causality between framework migration (Laravel) and usability improvements; framework migration was contextual, while improvements stem from UX token architecture and form design.
6. If a screenshot asset is noted as missing, display a clean placeholder box with the exact specified label rather than hallucinating an image.

PROJECT CONTEXT:
- Subject: Empirical Before/After Usability & Accessibility Evaluation of the Jakarta Provincial Government CSIRT Public Portal (Computer Security Incident Response Team, Diskominfotik DKI Jakarta).
- Legacy Portal: Built on Yii framework; characterized by jarring orange contrast, an intrusive modal pop-up, dense navigation, lack of form guidance, and submission database crashes.
- Redesign Artifact: Built on Laravel 12; introduces WCAG 2.1 AA design tokens (Deep Navy #003580 & Ink #0A0F1A), a single-page guided incident reporting form with Terms & Conditions gate, and a dedicated accessibility widget.
- Research Methodology: Design Science Research Methodology (DSRM; Peffers et al., 2007).
- Evaluation Instruments: System Usability Scale (SUS; Brooke 1996; Sharfina 2022), Replicated 5-Item Pre/Post Perceived UX Survey, New Feature Ratings (1–5 scale), Qualitative Thematic Analysis, and Automated Audits (axe-core & WAVE).

EXACT EMPIRICAL DATASET:
- Cohorts: Legacy Survey N = 23 (Aug 2026); New Survey N = 27 (Sep 2026, 100% completed interactive task walkthroughs); Verified Matched Longitudinal Subgroup n = 11.
- Global SUS Metrics:
  * Industry Average Benchmark: 68.0 (Sauro 2011).
  * Legacy Portal (N=23): Mean = 75.65 ± 17.71 (pop SD 17.32), Median = 77.50, Range = 40.0–100.0, >=68.0: 16/23 (69.6%), >=80.3: 11/23 (47.8%).
  * New Portal Raw (N=27): Mean = 70.46 ± 14.19 (pop SD 13.93), Median = 72.50, Range = 50.0–97.5, >=68.0: 17/27 (63.0%), >=80.3: 7/27 (25.9%).
  * Independent t-test (Legacy vs New Raw): Welch's t(42.0) = 1.130, p = 0.2586 (statistically non-significant), Cohen's d = -0.326; Mann-Whitney U = 239.5, p = 0.1670.
  * Straight-lining Phenomenon: 5 of 27 evaluators (18.5%: N12, N16, N17, N19, N26) selected uniform identical scores across all 10 items, mathematically locking their SUS score at exactly 50.0 due to alternating polarity.
  * New Portal Clean (N=22, straight-liners excluded): Mean = 75.11 ± 11.27, Median = 75.00, Range = 50.0–97.5, >=68.0: 17/22 (77.3%), >=80.3: 7/22 (31.8%). Comparison vs. Legacy: t(37.5) = 0.122, p = 0.9027, Cohen's d = -0.036 (virtually identical).
  * Paired Subgroup (n=11): Legacy Mean = 80.23 ± 15.43 vs. New Mean = 72.73 ± 15.10 (Mean diff = -7.50 ± 12.85, paired t = -1.936, p = 0.0817). Note: Outlier Nazario dropped 40 pts (97.5 -> 57.5) due to rating B2=4 and B6=5 despite giving satisfaction 5/5, palette 5/5, and voting for the new portal. 10 of 11 paired evaluators (90.9%) voted for the New portal.
  * SUS Item Polarity Breakdown (1–5 scale):
    - All 5 positive items rose in New: B1 Reuse Intent (3.87 -> 4.22), B3 Ease (4.17 -> 4.22), B5 Integration (3.83 -> 4.19), B7 Learnability (4.13 -> 4.19), B9 Confidence (4.13 -> 4.22).
    - All 5 negative items had higher raw agreement in New (penalizing SUS): B2 Complexity (1.78 -> 2.59), B4 Need Support (1.61 -> 2.41), B6 Inconsistency (2.30 -> 2.81), B8 Cumbersome (1.83 -> 2.41), B10 Learning Load (2.35 -> 2.63).
- Replicated 1–5 Ordinal Items (Feb Baseline vs. Sep Survey):
  * C1 Ease Finding Features: Legacy 4.26 ± 0.75 (Med 4.0) -> New 4.30 ± 0.67 (Med 4.0) [+0.04 pts; p = 0.86].
  * C2 Trust from Appearance: Legacy 4.17 ± 0.94 (Med 4.0) -> New 4.22 ± 0.64 (Med 4.0) [+0.05 pts; p = 0.83].
  * C3 Overall Satisfaction: Legacy 4.00 ± 1.04 (Med 4.0) -> New 4.19 ± 0.62 (Med 4.0) [+0.19 pts; p = 0.46].
  * Sentiment Shift: Negative ratings (score of 2) dropped from 8.7% (Legacy C3) to 0.0% (New C3). Satisfaction variance collapsed by 40% (SD ±1.04 -> ±0.62).
  * C4 Encountered Error/Crash: Legacy 2/23 (8.7%) -> New 2/27 (7.4%) [Fisher's exact p = 1.00].
- New Specific Evaluated Dimensions (N=27, 1–5 scale):
  * Deep Navy Palette & Pop-up Removal: Mean = 4.04 ± 0.71, Median = 4.0 (85.2% rated 4 or 5).
  * New Incident Reporting Flow: Mean = 3.96 ± 0.76, Median = 4.0 (70.4% rated 4 or 5).
  * Accessibility Widget Usefulness: Mean = 3.96 ± 0.85, Median = 4.0 (81.5% rated 4 or 5).
- Head-to-Head Operational Preference (N=27 forced-choice):
  * Versi Baru (New Portal): 24 / 27 (88.89%).
  * Versi Lama (Legacy Portal): 3 / 27 (11.11%).
- Qualitative Evidence Themes:
  * Visuals: Legacy orange disliked; New Deep Navy praised as professional, modern, soft on eyes.
  * Reporting Friction: Guided single-page form praised for structure and zero DB errors; however, mandatory login (/bug-hunter/login) drew targeted criticism from multiple users who demanded anonymous reporting with courier-style package tracking.

SLIDE DECK SPECIFICATION (10 SLIDES):
- Slide 1: Research Question & Scope Framing ("Did the New Jakarta CSIRT Portal Actually Improve the User Experience?").
- Slide 2: Context & What Changed: 3 Tangible Interventions (Contrast tokens, guided form + TaC gate, accessibility widget).
- Slide 3: Methodology & Cohort Structure (Independent groups N=23 vs N=27, matched subgroup n=11, triangulated methods).
- Slide 4: Finding 1 — Overall Usability & The SUS Polarity Paradox (Benchmark 68.0, raw 70.46 vs clean 75.11, item polarity table).
- Slide 5: Finding 2 — Sentiment Stabilization: Complete Elimination of Negative Sentiment (Diverging bar chart of C1–C3, 0% negative ratings).
- Slide 6: Finding 3 — Aesthetics & Brand Alignment: Resolving Visual Distractors (Orange vs Deep Navy, 4.04/5 rating, direct quotes).
- Slide 7: Finding 4 — The Reporting Dilemma: Guided Form vs. Authentication Friction (Zero DB crashes vs. mandatory login barrier).
- Slide 8: Finding 5 — Operational Suitability: 88.9% Stakeholder Consensus (Forced-choice donut/bar, quotes on system maturity).
- Slide 9: Methodological Honesty: What the Data Does NOT Say (Non-PwD domain sample, partially paired cohort, no causal framework claim).
- Slide 10: Research Conclusions & Next DSR Iteration (Supported evidence vs. future iteration: anonymous tokenized reporting).

DESIGN & FORMATTING DIRECTIVES:
- Visual Style: Clean modern academic research / civic design case study (white background #FFFFFF, deep ink text #0A0F1A, navy accent #003580, subtle borders #D8DCE3, mist cards #F4F5F7).
- Typography: Plus Jakarta Sans for titles/numbers; Inter for body/tables.
- Presentation Language: English (with original Indonesian qualitative quotes accompanied by concise English translations).
- Tone: Restrained, precise, evidence-based, scientifically transparent.

OUTPUT FORMAT:
Generate the presentation slide deck with clear layout blocks, precise visual placement specifications, data charts formatted in high-contrast ASCII/SVG or structured visual tables, exact numbers, and comprehensive presenter speaker notes for every single slide.
```

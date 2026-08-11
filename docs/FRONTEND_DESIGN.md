# CSIRT Portal — Frontend Design Guide

> Survey-driven frontend direction for the reimplementation of the DKI Jakarta
> CSIRT portal (pairs with `ARCHITECTURE_SPEC.md`).
>
> Based on 16 UX survey responses from actual users of csirt.jakarta.go.id
> (Feb 2026). This file is the **design source of truth** for the new repo.
> The backend contract lives in `ARCHITECTURE_SPEC.md`; this file decides how
> the UI looks and behaves, regardless of the framework used.

---

## 1. Design Principle

> "Professional, calm, and trustworthy — built for a government security team,
> not a marketing site."

Survey findings driving this:
- **9/16** complained about color/design ("terlalu mencolok", "terlalu kontras",
  "bisa dibikin lebih smooth")
- **6/16** complained about layout/feature placement ("terlalu kaku")
- **8/16** complained about typography
- Visual trust score is **high (avg ~4.3/5)** — the redesign must not break the
  credibility the site already has

The site must feel **authoritative and calm**: cool tones, restrained contrast,
clear hierarchy, generous whitespace. Warm/bright colors are reserved for
alerting semantics only.

---

## 2. Brand & Color

### Palette (cool, trust-building)

| Role | Guidance |
|---|---|
| **Primary brand** | Navy `#003580` — headers, primary buttons, links, borders |
| **Accent** | Electric blue `#0040FF`-range — interactive highlights, focus rings, selected states |
| **Ink (text)** | Near-black `#0A0F1A` — body text, headings |
| **Surfaces** | White + cool light grays (`#F4F5F7`, `#F9FAFB`) — cards, sections |
| **Muted text** | Cool gray `#6B7280` — secondary/meta info |
| **Alert/severity** | Red `#B91C1C`-range + tinted red backgrounds — warnings, critical status, **only** these |
| **Success/positive** | Green range — validated/resolved statuses only |

### Do NOT (from survey complaints)

- Bright or warm dominant palettes (orange was the #1 complaint — the original
  site's color)
- High-saturation color combinations
- High-contrast alert colors used decoratively (red/orange only for actual
  warnings)
- "Kaku" / rigid flat designs — soften with subtle shadows, rounded corners,
  smooth transitions

### Status color mapping (consistent with spec §4.4)

| Status | Color |
|---|---|
| `menunggu_validasi` / `pending` | Amber (waiting) |
| `divalidasi` / `resolved` | Green |
| `ditolak` / errors | Red |
| `ditindaklanjuti` / `in_progress` | Blue accent |

---

## 3. Typography

Survey is **split**: some find text too large ("Terlalu Besar"), others want it
bigger ("bisa diperbesar lagi"). Solution: a moderate, adjustable scale.

- **Base size: 16px** (not larger) — body copy at 16px with generous
  `line-height` (~1.7 for body)
- **Restrained type scale** — headings sized for authority, not shock; avoid
  oversized hero type
- **System font stack or a single variable font** (e.g. Inter/Plus Jakarta Sans)
  — no display-font gimmicks
- **Mandatory text-size controls**: a UI widget to scale text up/down
  (accessibility), persisting the preference
- Readable contrast: body text at least WCAG AA on its surface

---

## 4. Layout & Navigation

- **Simple information architecture**: news, warnings, events, infographics,
  laws, guides, search — flat, predictable, easy to find (addresses 6 "feature
  placement" complaints)
- **Primary CTA — `Lapor Insiden` must stand out** (survey: "Button 'Lapor
  Insiden' kurang tampil beda"): high-contrast primary button in the **hero**
  and the **sticky nav**, visually distinct from every other button
- **Navigation**: clear labels, obvious active state, no buried links; search
  visible in the header
- **Footer**: clean, correctly structured, contact/hotline prominent (survey:
  "footer kurang pas letaknya")
- **No oversized pop-ups / overlays** (survey: "Frame Pop-up terlalu besar
  menutupi layar") — avoid modal pop-ups for announcements; if needed, keep
  them small and dismissible
- Content density: cards for news/infographics, tables for admin, consistent
  spacing throughout

---

## 5. Performance

Survey: **5/16** complained about loading time. The redesign must treat speed as
a feature:

- Server-render content pages; minimize client JS (RSC-first architecture)
- Lazy-load images below the fold; sized/optimized images (next/image or
  equivalent)
- No heavy CSS/JS frameworks on the critical path
- Skeleton/loading states for any client-side fetch

---

## 6. Search

- Prominent in the header (survey ranked search among disturbing elements
  because it was hard to find/use)
- Minimum 2 characters (spec §3.2), results grouped by content type
- Clear "no results" state with helpful suggestions

---

## 7. Error UX

Two respondents hit **database errors on the incident form** ("Database error
saat mengisi form laporan insiden", "Gagal input"). Requirements:

- Client-side: validate before submit, inline per-field errors
- Server-side: Server Action validates again with the same zod schema
  (spec §4.1); on failure return **per-field Indonesian errors**, never a raw
  500
- Friendly, non-technical language ("Terjadi kesalahan. Silakan coba lagi.")
  with the option to contact CSIRT directly
- Preserve entered input on failure (no lost form data)

---

## 8. Feature Roadmap (design-ready, not v1)

Survey requests to design for but build later:

| Feature | Survey demand | Design note |
|---|---|---|
| **AI assistant / chatbot** | 4/16 (chat bot, live chat, "fitur AI", AI simulasi insiden) | Floating chat widget in the corner; calm, on-brand; answers portal FAQs / guides users to report form |
| **Event video recaps** | 2/16 (video pelatihan, narsum sharing knowledge) | Add optional video link/embed field to events; video card in event detail + event archive |
| **Text-to-speech on news** | 1/16 | "Listen" toggle on article pages using the Web Speech API; pairs with the accessibility widget |

---

## 9. Survey Data Appendix

**Sample 16 respondents** (Feb 2026), all Indonesian.

**Most disturbing desktop elements (multi-select):**
| Element | Votes |
|---|---|
| Warna dan/atau desain website | 9 |
| Ukuran teks/font | 8 |
| Susunan/penempatan fitur | 6 |
| Pilihan navigasi halaman | 5 |
| Waktu loading website | 5 |
| Fitur pencarian | 3 |
| Pop-up poster | 1 |
| Tidak ada | 1 |

**Direct quotes driving the palette:**
- "Seharusnya untuk sebuah website cair pemilihan warna temanya jangan cerah
  seperti Oranye, melainkan biru elektrik, navy, hitam, abu abu"
- "terlalu mencolok warna, hurufnya dan terlalu kaku"
- "warna nya agar lebih friendly"
- "terlalu kontras, bisa dibikin lebih smooth warnanya"

**Most-used features:** reading news/infographics (9), reporting incidents (4),
guides/events (2). **Errors:** 2/16 hit DB errors on the incident form.
**Overall satisfaction avg ≈ 4.1/5.**

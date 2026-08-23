# AGENTS.md — CSIRT Portal (Next.js) Build Agent

> This is the build/operating file for reimplementing the DKI Jakarta CSIRT
> portal. It pairs with three files in this repo:
>
> - `ARCHITECTURE_SPEC.md` — the **contract**: entities, API surface, business rules
> - `FRONTEND_DESIGN.md` — the **design source of truth** (survey-driven)
> - this file — the fixed **stack, structure, and build order**
>
> Read ALL THREE files fully before writing any code.

## Your role

You are building a complete, working reimplementation of a provincial-government
CSIRT portal. The stack below was chosen deliberately for maximum contrast with
the original (Laravel/PHP/Blade) and for cardless free-tier deployment.
**Do NOT use Laravel, PHP, Blade, Bootstrap, or any pattern from the original
repo.** Build it as if the original does not exist.

## Fixed stack (do not change without asking the user)

| Layer | Choice |
|---|---|
| Framework | Next.js 15 App Router, TypeScript (strict) |
| Styling | Tailwind CSS v4 + shadcn/ui |
| ORM + validation | Drizzle ORM + zod |
| Database | SQLite (dev) → Neon Postgres (prod) |
| Auth | Auth.js (NextAuth v5), credentials provider, `is_admin` claim |
| Uploads | Supabase Storage |
| Tests | Vitest + @testing-library/react |
| Deploy | Vercel Hobby + Neon + Supabase |

## Quick commands

| Command | Purpose |
|---|---|
| `pnpm dev` | local dev server |
| `pnpm build` | production build (must pass) |
| `pnpm test` | run vitest suite |
| `pnpm drizzle:migrate` | apply schema migrations |
| `pnpm db:seed` | seed demo data |
| `pnpm lint` | lint + typecheck |

## Non-negotiables (from ARCHITECTURE_SPEC.md)

1. All public-facing copy is **Indonesian** (government portal).
2. Every write is a **Server Action** validated by a **zod** schema at the
   boundary; failures return **per-field errors in Indonesian** and preserve
   input — never a raw 500.
3. Real **CAPTCHA** (Cloudflare Turnstile) on the incident report action.
4. **Rate limit** the two intake actions (incident + contact).
5. **Closed status enums**: incidents `menunggu_validasi → divalidasi | ditolak
   | ditindaklanjuti`; contacts `pending → in_progress | resolved`.
6. **Soft-delete** incident reports (legal evidence — never hard-delete).
7. **Foreign keys and timestamps on every table** (the original had neither).
8. Admin default: `admin@gmail.com` / `12345678` (seeded; documented as
   change-before-production).
9. Implement every item in spec §6 "Known Gaps" — the spec lists them as
   required improvements, not suggestions.

## Frontend design (from FRONTEND_DESIGN.md)

`FRONTEND_DESIGN.md` is the **design source of truth** — read it and implement
its tokens and rules. Summary of the hard requirements:

- **Cool, professional palette**: navy primary (`#003580`), electric-blue
  accent, near-black ink, cool grays. Warm colors (red/orange) ONLY for
  alert/severity semantics. Never a bright-orange dominant palette (survey's #1
  complaint).
- **Typography**: 16px base, restrained scale, generous line-height, plus a
  **text-size control widget** (accessibility) that persists preference.
- **`Lapor Insiden` must be a standout primary CTA** in the hero AND sticky nav.
- **No oversized pop-ups**; clean footer; simple flat IA.
- **Performance**: server-render content pages, lazy + optimized images, minimal
  client JS.
- **Search**: prominent in the header, grouped results, ≥2 chars (spec §3.2).
- **Error UX**: inline per-field Indonesian errors on the incident form, never a
  raw error page (two survey respondents hit DB errors on the old form).
- Map status enums to the status colors in FRONTEND_DESIGN.md §2.
- **Roadmap (design-ready only, do NOT build in v1)**: AI chatbot, event video
  recaps, text-to-speech on news. Note them in the README as planned.

## Project structure (conventions)

```
app/
  layout.tsx               # root layout (nav, footer, a11y)
  (public)/                # public pages, server components by default
    page.tsx               # home: 6 latest news + 4 upcoming events
    news/ events/ warnings/ infographics/ laws/ guides/
    search/page.tsx
    report-incident/       # client wizard component + 1 Server Action
    contact/
  admin/                   # route group guarded by middleware.ts
    login/
    incidents/ contacts/
    {news,events,warnings,laws,guides,infographics}/   # CRUD, 15/page
  actions/                 # all Server Actions (one file per domain)
db/
  schema.ts                # Drizzle schema: 9 tables, FKs, enums, timestamps
  migrations/ seed.ts
lib/
  auth.ts                  # Auth.js config + is_admin
  validators/              # zod schemas mirroring spec §2 column constraints
  rate-limit.ts storage.ts captcha.ts
components/
  ui/                      # shadcn/ui primitives
```

Rules:
- **Server Components by default.** Client components only where interactivity
  requires them (wizard steps, admin tabs, forms).
- Implement the design tokens from `FRONTEND_DESIGN.md` in the Tailwind theme;
  reuse shadcn/ui primitives — do not hand-roll a design system.
- All data access goes through the DB layer / Server Actions — no raw DB calls
  in components.
- No dead code, no orphan routes, no placeholder pages that 404.

## Build order

1. Scaffold (create-next-app, TS strict, Tailwind v4, git init, copy this file
   + ARCHITECTURE_SPEC.md + FRONTEND_DESIGN.md).
2. Drizzle schema, migrations, seed (spec §2 + §4.7).
3. Auth.js credentials login + `middleware.ts` guard on `/admin/*`.
4. Public read pages: listings, details, search (spec §3.1–3.2) with the
   FRONTEND_DESIGN.md look.
5. Intake: incident wizard (multi-step client, single Server Action), contact,
   CAPTCHA, rate limiting (spec §3.3, §4.1–4.4).
6. Admin CRUD for all 6 content types + submission review workflows
   (spec §3.4).
7. Supabase Storage for uploads (proof_pic, file_path fields).
8. Neon Postgres + Vercel env setup; verify against spec §6 checklist.

## Definition of done

- `pnpm build` passes with no type errors.
- `pnpm test` green (actions + a couple of component tests).
- Admin routes return 403/redirect when unauthenticated or non-admin.
- All spec §3 endpoints implemented and reachable; no 404 placeholders.
- Design matches FRONTEND_DESIGN.md (palette, typography scale, CTA prominence,
  status colors).
- Seed data present per spec §4.7.
- README with quick start (setup, seed, admin creds) + roadmap section.

## Constraints

- Never modify the original Laravel repo — this is a standalone project.
- Ask the user before introducing new dependencies or changing the fixed stack.

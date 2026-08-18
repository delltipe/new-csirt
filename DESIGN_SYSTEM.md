# Jakarta CSIRT Design System

> NYC.gov-inspired design system for the DKI Jakarta Cybersecurity Portal.
> Based on user survey feedback and the Figma redesign concept.

## Architecture

- **Main layout:** `resources/views/layouts/app.blade.php`
- **Global styles:** `public/css/style.css` (design tokens + components)
- **Accessibility:** `public/css/accessibility-contrast.css` (high contrast + dark mode)
- **Fonts:** Google Fonts loaded via `@import` in `style.css`
- **Framework:** Bootstrap 5.3 (CDN) — used for grid and forms only. **Not** for tables, cards, or page layout (admin tables use the custom `.data-table`, see Admin Pages).

---

## Design Tokens

All tokens are defined in `public/css/style.css` under `:root`. **Never hardcode colors or fonts — always use these variables.**

### Colors

| Token | Value | Usage |
|---|---|---|
| `--ink` | `#0A0F1A` | Primary text, headings, nav strip |
| `--navy` | `#003580` | Primary brand blue: buttons, borders, links |
| `--navy-mid` | `#004099` | Hover / active states |
| `--navy-dim` | `#002060` | Dark sections: CTA backgrounds, pressed states |
| `--navy-tint` | `#E8EFF8` | Faint blue: hover surfaces, tab highlights |
| `--mist` | `#F4F5F7` | Light gray: section backgrounds |
| `--border` | `#D8DCE3` | Border color, dividers |
| `--mid` | `#6B7280` | Muted/secondary text |
| `--white` | `#FFFFFF` | White backgrounds |
| `--alert` | `#B91C1C` | Warning/alert content ONLY — not a brand color |
| `--alert-bg` | `#FEF2F2` | Alert background tint |
| `--alert-light` | `#EF4444` | Lighter alert: badges, hover accents |
| `--alert-dark` | `#991B1B` | Darker alert: error text |

### Typography

| Token | Value | Usage |
|---|---|---|
| `--font-display` | `'Plus Jakarta Sans', sans-serif` | Headings, buttons, labels, navigation |
| `--font-body` | `'Inter', system-ui, sans-serif` | Body text, descriptions, form inputs |

**Font weights used:**
- Display: 500, 600, 700, 800
- Body: 300, 400, 500, 600

### Transitions

| Token | Value |
|---|---|
| `--ease` | `0.16s ease` |

### Layout

| Pattern | Value |
|---|---|
| Container | `max-width: 1200px; padding: 0 28px` |

---

## Page Structure

### Every page must:
1. `@extends('layouts.app')` — gives you navbar, footer, accessibility widget
2. Use `@section('content')` / `@endsection`
3. Use design tokens for all colors and fonts
4. Use the dark header pattern for the page title area

### Dark Header Pattern

The standard page header used across all content pages:

```html
<div class="page-header">
    <div class="container">
        <h1 class="page-header__title">Page Title</h1>
    </div>
</div>
```

CSS for the dark header (scope inside `<style>` block):

```css
.page-header {
    background: var(--ink);
    padding: 52px 0 44px;
    position: relative;
}
.page-header::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: repeating-linear-gradient(
        90deg,
        rgba(255,255,255,0.02) 0px, rgba(255,255,255,0.02) 1px,
        transparent 1px, transparent 80px
    );
    pointer-events: none;
}
.page-header .container { position: relative; z-index: 1; }
.page-header__title {
    font-family: var(--font-display);
    font-size: clamp(32px, 5vw, 54px);
    font-weight: 900;
    text-transform: uppercase;
    color: var(--white);
    letter-spacing: 0.02em;
    margin: 0;
}
.page-header__subtitle {
    font-family: var(--font-body);
    font-size: 15px;
    font-weight: 300;
    color: rgba(255,255,255,0.5);
    margin-top: 8px;
}
```

### Eyebrow Pattern (optional)

Small label above the title in the dark header:

```html
<p class="page-header__eyebrow">
    <i class="bi bi-arrow-right" aria-hidden="true"></i>
    <span>Section Name</span>
</p>
```

```css
.page-header__eyebrow {
    font-size: 10.5px;
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.35);
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.page-header__eyebrow::before {
    content: '';
    width: 20px;
    height: 1px;
    background: rgba(255,255,255,0.25);
}
```

---

## Components

### Buttons

| Class | Usage | Style |
|---|---|---|
| `.btn-primary-solid` | Primary CTA on dark backgrounds | White bg, navy text, uppercase, Plus Jakarta Sans |
| `.btn-ghost` | Secondary CTA on dark backgrounds | Transparent, white border |
| `.btn-navy` | Primary CTA on light backgrounds | Navy bg, white text, uppercase |

**Always use these classes.** Do not use Bootstrap `.btn` classes for primary actions on content pages.

### Cards (Content Pages)

Content pages use a custom card system — **not** Bootstrap `.card`:

```css
/* 3-column grid with 1px colored gutter */
.news-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1px;
    background: var(--border);
}

/* Individual card */
.news-card {
    background: var(--white);
    padding: 0;
    position: relative;
    overflow: hidden;
}

/* 3px navy hover bar */
.news-card::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--navy);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform var(--ease);
}
.news-card:hover::after {
    transform: scaleX(1);
}
```

### Navbar Partner Logos

A compact `.nav-partners` cluster sits in the navbar brand row (next to the CSIRT logo), showing partner-institution logos with outbound links:

```html
<div class="nav-partners" role="group" aria-label="Logo instansi mitra">
    <a href="#" target="_blank" rel="noopener" title="Jaya Raya" aria-label="Logo Jaya Raya">
        <img src="{{ asset('jaya_raya.png') }}" alt="Jaya Raya">
    </a>
    <!-- …more partner logos… -->
</div>
```

- Logos render at `height: 34px`, `max-width: 96px`, thin `var(--border)` divider on the left, `opacity: 0.9` → `1` on hover.
- Hidden below `1100px` (`.nav-partners { display: none }`).
- Always `target="_blank" rel="noopener"` + `title` tooltip + `aria-label`. Current `href="#"` values are placeholders until real partner URLs are provided.

### Admin Pages

Admin CRUD interfaces use a custom boxy `.data-table` component — **not** Bootstrap `.table`. Component CSS lives in the `<style>` block of `admin/dashboard.blade.php` (it is scoped to admin pages, not in `style.css`):

```html
<div class="section-actions">
    <h4 class="section-title-small">Kelola Berita</h4>
    <a href="#" class="btn-add" data-bs-toggle="modal" data-bs-target="#addNewsModal">
        <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah Berita
    </a>
</div>
<div class="table-responsive">
    <table class="data-table">
        <thead><tr><th>Judul</th><th>Aksi</th></tr></thead>
        <tbody>
            <tr>
                <td>…</td>
                <td>
                    <a href="{{ route('admin.news.edit', $item->id) }}" class="btn-edit">…</a>
                    <form method="POST" action="…">@csrf
                        <button type="submit" class="btn-delete">…</button>
                    </form>
                </td>
            </tr>
        </tbody>
    </table>
</div>
```

Key classes:
- `.data-table` — full-width, `border: 1px solid var(--border)`, boxy. Thead `background: var(--mist)`, th 12px/800 uppercase (display font), td 14px; row hover `var(--navy-tint)`.
- `.section-actions` / `.section-title-small` — flex header row with the 24px uppercase section title.
- `.btn-add` / `.btn-edit` — navy fill, uppercase/add-label CTAs.
- `.btn-delete` — red fill (`var(--alert)`), used on the inline `<form>` delete `<button>`.
- `.empty-state` — centered placeholder when a collection is empty.

Dark-mode overrides for all of these (plus admin tabs `.admin-tab` and `.btn-navy:hover`) live in `accessibility-contrast.css` — the generic `html.accessibility-contrast-dark button`/`a:hover` rules will flatten unscoped admin buttons, so new admin controls need matching dark overrides.

Admin pages also use the dark header pattern and design tokens for styling.

### Alert Strip

For site-wide security alerts (home page):

```html
<div class="alert-strip">
    <div class="container">
        <div class="alert-strip__icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
        <div>
            <p class="alert-strip__label">Peringatan</p>
            <p class="alert-strip__text">Alert message here</p>
        </div>
        <a href="#" class="alert-strip__cta">Lihat Detail <i class="bi bi-arrow-right"></i></a>
    </div>
</div>
```

---

## Responsive Breakpoints

| Breakpoint | Behavior |
|---|---|
| `960px` | News grid: 3-col → 1-col. Services grid: 4-col → 2-col |
| `640px` | Services grid: 2-col → 1-col. Alert strip wraps. |

---

## Accessibility

The design system supports two accessibility modes via `accessibility-contrast.css`:

- **High Contrast** (`html.accessibility-contrast-high`) — max contrast, yellow alert backgrounds
- **Dark Mode** (`html.accessibility-contrast-dark`) — inverted palette, light text on dark

Both modes override all design tokens. **Any hardcoded color will NOT adapt** — this is why token usage is mandatory.

---

## Rules

1. **Always use `var(--token)`** — never hardcode hex values, font names, or spacing
2. **Always `@extends('layouts.app')`** — every page gets navbar, footer, accessibility
3. **Always use the dark header pattern** — consistent page title area
4. **Use `.btn-primary-solid` / `.btn-ghost` / `.btn-navy`** — not Bootstrap `.btn` for primary actions
5. **Use custom card system** — not Bootstrap `.card` for content pages
6. **Admin pages** — use the custom `.data-table` component (not Bootstrap `.table`); forms may use Bootstrap `.form-control`
7. **Scope page styles** in `<style>` blocks within `@section('content')` — keep design system CSS in `style.css`
8. **New tokens** must be added to `style.css` AND both modes in `accessibility-contrast.css`

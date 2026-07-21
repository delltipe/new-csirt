# Jakarta CSIRT Design System

> NYC.gov-inspired design system for the DKI Jakarta Cybersecurity Portal.
> Based on user survey feedback and the Figma redesign concept.

## Architecture

- **Main layout:** `resources/views/layouts/app.blade.php`
- **Global styles:** `public/css/style.css` (design tokens + components)
- **Accessibility:** `public/css/accessibility-contrast.css` (high contrast + dark mode)
- **Fonts:** Google Fonts loaded via `@import` in `style.css`
- **Framework:** Bootstrap 5.3 (CDN) — used for grid, forms, tables only. **Not** for cards or page layout.

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
| `--font-display` | `'Barlow Condensed', 'Arial Narrow', sans-serif` | Headings, buttons, labels, navigation |
| `--font-body` | `'Inter', system-ui, sans-serif` | Body text, descriptions, form inputs |

**Font weights used:**
- Display: 500, 700, 800, 900
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
| `.btn-primary-solid` | Primary CTA on dark backgrounds | White bg, navy text, uppercase, Barlow Condensed |
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

### Admin Pages

Admin CRUD tables use Bootstrap `.table` classes — this is acceptable for admin interfaces. Admin pages also use the dark header pattern and design tokens for styling.

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
6. **Admin pages** — Bootstrap tables/forms are acceptable
7. **Scope page styles** in `<style>` blocks within `@section('content')` — keep design system CSS in `style.css`
8. **New tokens** must be added to `style.css` AND both modes in `accessibility-contrast.css`

# Hero Slider — Slidable, Updatable Hero

> Status: **IMPLEMENTED** (2026-09-01). Builds on hero visibility tweak (`0.94→0.82 / 0.85→0.68 / 0.55→0.32` at `home.blade.php:14`) + scrim plate `home.blade.php:57`. Slider live with admin CRUD.

## Legacy reference (`csirt.jakarta.go.id`)

```html
<div class="slides_container">
  <div class="image_wrapper image_loaded" style="background-image:url(header-1a_03.jpg)">
    <div><img src="images/header-1a_03.jpg"><div class="text_content">
      <h3>DKI PROV CSIRT</h3>
      <h5>Adalah penyedia layanan tim respon insiden ...</h5>
      <a href="/cyber-report"><input value="LAPOR INSIDEN SIBER" class="lapor-insiden"></a>
    </div></div>
  </div>
  <!-- + banner/* slides, one with class current_slide, each with <h3> + optional <a href="/news/view?id=..."> -->
</div>
```

* 3–4 slides, one `current_slide` visible, auto-rotates, each slide is a full-bleed image + overlay text + CTA.
* Current portal hero is static: `JAKARTA PROV CSIRT` + lead + two CTAs on `photo-1558494949…` with `linear-gradient 100deg` wash `home.blade.php:14`.

## Goals

* Admin can create / reorder / enable / disable hero slides without code deploy.
* Slides pull newest / most important news/events or a custom message — like legacy where some slides link to `/news/view?id=115`.
* Keeps design system (`DESIGN_SYSTEM.md`, `public/css/style.css` tokens, `accessibility-contrast.css`) and architecture conventions (Indonesian table names, timestamps, `try/catch` Indonesian errors).

## Proposed schema (follows `AGENTS.md` / `SCHEMA.md` conventions)

* **Migration** `2026_09_01_000001_create_slide_hero_table.php`
  * Table `slide_hero` (Indonesian, like `berita_siber` / `lapor_insiden`): `id, judul, subjudul, gambar, tautan, teks_tautan, urutan, is_active, created_at, updated_at`
  * `judul` required, `subjudul` nullable, `gambar` string (path under `storage/app/public/hero/`), `tautan` nullable URL, `teks_tautan` default `LAPOR INSIDEN SEKARANG`, `urutan` integer default 0, `is_active` boolean default true.
  * No FK; `SoftDeletes` optional if retention wanted (follow `lapor_insiden` precedent).
* **Model** `app/Models/HeroSlide.php` — `$table='slide_hero'`, `$fillable` matches, `scopeActive()` / `scopeOrdered()` (`orderBy urutan, created_at desc`), casts `is_active => boolean`. Timestamps on (remove any `$timestamps=false`).
* **Seeder** `HeroSlideSeeder.php` — 4 rows seeded from legacy `header-1a_03.jpg` + `banner/openart-image…_20250213` + `banner/69cb4bc7…_20250114` etc., hotlinked initially (like `CybersecurityNewsSeeder` thumbnails), later via `Storage::url()`.
* **Storage:** `storage/app/public/hero/` + `php artisan storage:link`; `gambar` validated `file|mimes:jpg,jpeg,png,webp|max:5120`. Admin upload overwrites; old file deleted.

## Controllers / routes

* `HomeController@index:16` currently loads `recentNews limit 6` + `upcomingEvents limit 4` → add `$slides = HeroSlide::active()->ordered()->get()` (fallback to single static slide if empty, so hero never blank).
* `AdminController` new methods `heroList / heroStore / heroEdit / heroUpdate / heroDelete / heroReorder` — same pattern as `newsStore` (`validate`, `try/catch`, Indonesian `withErrors`, `redirect()->route('admin.dashboard')`).
* `routes/web.php` inside `auth+admin` group: `GET /admin/hero` `POST /admin/hero` `GET /admin/hero/{id}/edit` `POST /admin/hero/{id}/update` `POST /admin/hero/{id}/delete` `POST /admin/hero/reorder` → names `admin.hero.*` (note existing drift `admin.news.*` vs `admin.event.store` — pick one and keep).
* Public hero needs no new GET route; `GET /` serves it. `POST /hero/reorder` is admin-only.

## Views

* `home.blade.php:9` hero block — replace static `background: linear-gradient(...), url(...)` with loop:
  ```blade
  <section class="hero hero--slider" aria-label="Sorotan">
    <div class="hero__track">
      @foreach($slides as $slide)
        <div class="image_wrapper {{ $loop->first ? 'current_slide' : '' }}"
             style="background-image:url('{{ $slide->gambar ? Storage::url($slide->gambar) : '...' }}')">
          <div class="text_content">
            <h3>{{ $slide->judul }}</h3>
            @if($slide->subjudul)<h5>{{ $slide->subjudul }}</h5>@endif
            @if($slide->tautan)<a href="{{ $slide->tautan }}" class="btn-hero-primary">{{ $slide->teks_tautan }}</a>@endif
          </div>
        </div>
      @endforeach
    </div>
    <button class="hero__nav hero__nav--prev" aria-label="Sebelumnya"><i class="bi bi-chevron-left"></i></button>
    <button class="hero__nav hero__nav--next" aria-label="Selanjutnya"><i class="bi bi-chevron-right"></i></button>
    <div class="hero__dots" role="tablist"></div>
  </section>
  ```
  Reuse `.hero__title/.hero__lead/.btn-hero-primary/.btn-hero-ghost/.hero-stats` classes — no new hex, only `var(--token)` (`style.css` already has `--navy`, `--ink`, `--white`, `--border`, `--mist`, `border-radius:0`).
* Slider JS — clone `news-carousel` logic `home.blade.php:736` (`scrollBy`, `prev/next`, `setInterval 5s`, `touchstart` stop, `matchMedia prefers-reduced-motion` + `html.accessibility-pause-animations` pause). Also sync `hero-stats` below (remains static, not per-slide).
* Admin partial `admin/partials/hero.blade.php` — `.data-table` / `.section-actions` / `.btn-add` + modal form (like `news.blade.php`), includes `urutan` drag handle + `is_active` toggle. Tab added to `admin/dashboard.blade.php:260` (8th tab, after `Insiden`).

## Contrast — interchangeable image+text mitigation (shipped)

Each slide has different image + text length, so fixed overlay fails. Shipped mitigation:
* Per-slide `linear-gradient 100deg rgba(0,32,96,0.82)→rgba(0,53,128,0.32)` (`home.blade.php:14` wash) + **scrim plate** `home.blade.php:57` `linear-gradient 90deg rgba(10,15,26,0.72)→0.00` behind `hero__scrim` + `text-shadow`, so white `hero__title` passes on bright/busy patches. Scrim lives inside each `.hero__slide`, not on wrapper. Tested via `/demo/hero-contrast`.

## Verification (when built)

```bash
php artisan migrate:fresh --seed   # creates slide_hero + 4 demo slides
php artisan route:list | grep hero
php artisan test --filter=HeroSliderTest  # new: admin CRUD + public hero shows current_slide + auto-rotate JS smoke
```

Manual: `GET /` shows slides auto-rotate; admin `POST /admin/hero` with image → appears on hero; `is_active=false` hides; reorder persists `urutan`; dark/high-contrast (`accessibility-contrast.css`) still readable.

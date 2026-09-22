@extends('layouts.app')

@section('content')
<style>
.demo-wrap{ background: var(--mist); padding: 32px 0 64px; }
.demo-head{ max-width: 1144px; margin: 0 auto 28px; padding: 0 16px; }
.demo-head h1{ font-family:var(--font-display); font-size:28px; font-weight:800; text-transform:uppercase; letter-spacing:0.02em; color:var(--ink); margin:0 0 6px; }
.demo-head p{ color:var(--mid); font-size:14px; line-height:1.6; max-width:720px; }
.demo-head code{ background: var(--white); border:1px solid var(--border); padding:1px 6px; font-size:12px; }

.variant{ max-width:1144px; margin: 0 auto 36px; background: var(--white); border:1px solid var(--border); }
.variant__label{ display:flex; align-items:center; gap:10px; padding:14px 18px; border-bottom:1px solid var(--border); background: var(--mist); }
.variant__badge{ font-family:var(--font-display); font-size:11px; font-weight:800; letter-spacing:0.08em; text-transform:uppercase; padding:4px 8px; border:1px solid var(--border); background:var(--white); color:var(--ink); }
.variant__title{ font-family:var(--font-display); font-size:13px; font-weight:700; letter-spacing:0.04em; text-transform:uppercase; color:var(--ink); }
.variant__desc{ font-size:12px; color:var(--mid); margin-left:auto; }

/* Shared hero base — copied from home.blade.php but isolated */
.demo-hero{
    position:relative; display:flex; flex-direction:column;
    min-height: 360px; overflow:hidden;
}
.demo-hero__body{ position:relative; z-index:1; padding: 48px 0 36px; }
.demo-hero .container{ max-width: 1144px; margin:0 auto; padding: 0 16px; }
.demo-hero__eyebrow{ display:inline-flex; align-items:center; gap:8px; font-size:11px; font-weight:600; letter-spacing:0.12em; text-transform:uppercase; color: rgba(255,255,255,0.55); margin-bottom:14px; }
.demo-hero__eyebrow::before{ content:''; display:block; width:24px; height:2px; background: rgba(255,255,255,0.35); }
.demo-hero__title{ font-family:var(--font-display); font-size: clamp(28px,4vw,44px); font-weight:800; line-height:1; letter-spacing:0.01em; text-transform:uppercase; color:var(--white); margin-bottom:10px; }
.demo-hero__lead{ font-size:14px; font-weight:300; color: rgba(255,255,255,0.72); line-height:1.6; max-width:520px; margin-bottom:20px; }
.demo-hero__actions{ display:flex; gap:12px; flex-wrap:wrap; }
.demo-hero .hero-stats{ position:relative; z-index:1; background: rgba(0,20,60,0.88); border-top:1px solid rgba(255,255,255,0.07); }
.demo-hero .hero-stats .container{ display:flex; }
.demo-hero .hero-stats__item{ flex:1; padding:12px 10px; border-right:1px solid rgba(255,255,255,0.07); text-align:center; }
.demo-hero .hero-stats__item:last-child{ border-right:none; }
.demo-hero .hero-stats__number{ font-family:var(--font-display); font-size:18px; font-weight:800; color:var(--white); line-height:1; }
.demo-hero .hero-stats__label{ font-size:9px; letter-spacing:0.08em; text-transform:uppercase; color: rgba(255,255,255,0.4); }
.demo-hero .btn-hero-primary{ display:inline-flex; align-items:center; gap:8px; background:var(--white); color:var(--navy-dim); font-family:var(--font-display); font-size:13px; font-weight:800; letter-spacing:0.06em; text-transform:uppercase; padding:12px 20px; text-decoration:none; border:none; }
.demo-hero .btn-hero-ghost{ display:inline-flex; align-items:center; gap:8px; background:transparent; color:rgba(255,255,255,0.7); border:1px solid rgba(255,255,255,0.3); font-size:13px; font-weight:500; padding:11px 18px; text-decoration:none; }

/* image used for all variants — busy server racks */
.demo-hero{ background: url('https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&w=1920&q=80') center/cover no-repeat; }

/* Variant A — current light wash, no plate (baseline) */
.variant--a .demo-hero{
    background:
        linear-gradient(100deg, rgba(0,32,96,0.82) 0%, rgba(0,53,128,0.68) 52%, rgba(0,53,128,0.32) 100%),
        url('https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;
}

/* Variant B — scrim plate behind text only (recommended) */
.variant--b .demo-hero{
    background:
        linear-gradient(100deg, rgba(0,32,96,0.82) 0%, rgba(0,53,128,0.68) 52%, rgba(0,53,128,0.32) 100%),
        url('https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;
}
.variant--b .scrim-plate{
    display:inline-block; max-width: 640px;
    background: linear-gradient(90deg, rgba(10,15,26,0.72) 0%, rgba(10,15,26,0.58) 68%, rgba(10,15,26,0.00) 100%);
    padding: 18px 22px 16px; margin: -18px -22px -16px;
    border: 1px solid var(--navy);
}
.variant--b .demo-hero__title, .variant--b .demo-hero__lead{ text-shadow: 0 1px 10px rgba(0,0,0,0.35); }

/* Variant C — stronger directional wash (no plate, just stronger left dark) */
.variant--c .demo-hero{
    background:
        linear-gradient(90deg, rgba(0,32,96,0.88) 0%, rgba(0,32,96,0.78) 38%, rgba(0,53,128,0.62) 58%, rgba(0,53,128,0.18) 100%),
        url('https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;
}

/* Variant D — text-shadow only (no extra wash) */
.variant--d .demo-hero{
    background:
        linear-gradient(100deg, rgba(0,32,96,0.82) 0%, rgba(0,53,128,0.68) 52%, rgba(0,53,128,0.32) 100%),
        url('https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;
}
.variant--d .demo-hero__title{ text-shadow: 0 1px 0 rgba(0,0,0,0.55), 0 2px 18px rgba(0,0,0,0.45), 0 8px 32px rgba(0,0,0,0.35); }
.variant--d .demo-hero__lead{ text-shadow: 0 1px 12px rgba(0,0,0,0.55); }
.variant--d .demo-hero__eyebrow{ text-shadow: 0 1px 6px rgba(0,0,0,0.5); }

/* Variant E — adaptive: show two slides side-by-side, dark vs light plate */
.variant--e .demo-hero{ display:grid; grid-template-columns: 1fr 1fr; min-height: 360px; }
.variant--e .demo-hero__pane{ position:relative; padding: 40px 20px 28px; display:flex; flex-direction:column; justify-content:center; }
.variant--e .demo-hero__pane--dark{
    background:
        linear-gradient(100deg, rgba(0,32,96,0.82) 0%, rgba(0,53,128,0.32) 100%),
        url('https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&w=960&q=80') center/cover no-repeat;
    color: var(--white);
}
.variant--e .demo-hero__pane--light{
    background:
        linear-gradient(100deg, rgba(255,255,255,0.78) 0%, rgba(255,255,255,0.35) 100%),
        url('https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=960&q=80') center/cover no-repeat;
    color: var(--ink);
}
.variant--e .demo-hero__pane--light .demo-hero__title{ color: var(--ink); }
.variant--e .demo-hero__pane--light .demo-hero__lead{ color: var(--mid); }
.variant--e .demo-hero__pane--light .demo-hero__eyebrow{ color: var(--mid); }
.variant--e .demo-hero__pane--light .demo-hero__eyebrow::before{ background: var(--border); }
</style>

<div class="demo-wrap">
  <div class="demo-head">
    <h1>Hero Contrast — Mitigation Preview</h1>
    <p>Same busy server-rack image + same white text as <code>home.blade.php</code> <code>0.82→0.68→0.32</code>. Each variant below is isolated — compare how the inner text survives. Stashed plan: <code>docs/HERO_SLIDER_PLAN.md</code>. No slider built yet.</p>
  </div>

  {{-- A: Baseline (current) --}}
  <div class="variant variant--a">
    <div class="variant__label"><span class="variant__badge">A — Baseline</span><span class="variant__title">Current light wash only</span><span class="variant__desc">0.82→0.68→0.32, no plate</span></div>
    <div class="demo-hero">
      <div class="demo-hero__body"><div class="container">
        <div class="demo-hero__eyebrow"><i class="bi bi-shield-lock"></i> Tim Tanggap Insiden Siber Resmi DKI Jakarta</div>
        <div class="demo-hero__title">JAKARTA PROV CSIRT</div>
        <div class="demo-hero__lead">Pemerintah Provinsi DKI Jakarta — Computer Security Incident Response Team. Menjaga infrastruktur digital dan data kritis Jakarta dari ancaman siber, 24 jam sehari, 7 hari seminggu.</div>
        <div class="demo-hero__actions"><a href="#" class="btn-hero-primary"><i class="bi bi-megaphone-fill"></i> LAPOR INSIDEN SEKARANG</a><a href="#" class="btn-hero-ghost">Tentang Kami →</a></div>
      </div></div>
      <div class="hero-stats"><div class="container"><div class="hero-stats__item"><div class="hero-stats__number">157</div><div class="hero-stats__label">Insiden Ditangani</div></div><div class="hero-stats__item"><div class="hero-stats__number">24/7</div><div class="hero-stats__label">Respons Siap</div></div><div class="hero-stats__item"><div class="hero-stats__number">89K+</div><div class="hero-stats__label">Pengunjung</div></div><div class="hero-stats__item"><div class="hero-stats__number">&lt;2J</div><div class="hero-stats__label">Rata-rata Respons</div></div></div></div>
    </div>
  </div>

  {{-- B: Scrim plate (recommended) --}}
  <div class="variant variant--b">
    <div class="variant__label"><span class="variant__badge" style="background:var(--navy); color:var(--white); border-color:var(--navy)">B — Recommended</span><span class="variant__title">Scrim plate behind text only</span><span class="variant__desc">Same wash + 0.72→0.58 plate + left navy rule</span></div>
    <div class="demo-hero">
      <div class="demo-hero__body"><div class="container">
        <div class="scrim-plate">
          <div class="demo-hero__eyebrow"><i class="bi bi-shield-lock"></i> Tim Tanggap Insiden Siber Resmi DKI Jakarta</div>
          <div class="demo-hero__title">JAKARTA PROV CSIRT</div>
          <div class="demo-hero__lead">Pemerintah Provinsi DKI Jakarta — Computer Security Incident Response Team. Menjaga infrastruktur digital dan data kritis Jakarta dari ancaman siber, 24 jam sehari, 7 hari seminggu.</div>
          <div class="demo-hero__actions"><a href="#" class="btn-hero-primary"><i class="bi bi-megaphone-fill"></i> LAPOR INSIDEN SEKARANG</a><a href="#" class="btn-hero-ghost">Tentang Kami →</a></div>
        </div>
      </div></div>
      <div class="hero-stats"><div class="container"><div class="hero-stats__item"><div class="hero-stats__number">157</div><div class="hero-stats__label">Insiden Ditangani</div></div><div class="hero-stats__item"><div class="hero-stats__number">24/7</div><div class="hero-stats__label">Respons Siap</div></div><div class="hero-stats__item"><div class="hero-stats__number">89K+</div><div class="hero-stats__label">Pengunjung</div></div><div class="hero-stats__item"><div class="hero-stats__number">&lt;2J</div><div class="hero-stats__label">Rata-rata Respons</div></div></div></div>
    </div>
  </div>

  {{-- C: Stronger directional wash --}}
  <div class="variant variant--c">
    <div class="variant__label"><span class="variant__badge">C — Stronger wash</span><span class="variant__title">Left-heavy 0.88→0.62</span><span class="variant__desc">No plate, just darker left 45%</span></div>
    <div class="demo-hero">
      <div class="demo-hero__body"><div class="container">
        <div class="demo-hero__eyebrow"><i class="bi bi-shield-lock"></i> Tim Tanggap Insiden Siber Resmi DKI Jakarta</div>
        <div class="demo-hero__title">JAKARTA PROV CSIRT</div>
        <div class="demo-hero__lead">Pemerintah Provinsi DKI Jakarta — Computer Security Incident Response Team. Menjaga infrastruktur digital dan data kritis Jakarta dari ancaman siber, 24 jam sehari, 7 hari seminggu.</div>
        <div class="demo-hero__actions"><a href="#" class="btn-hero-primary"><i class="bi bi-megaphone-fill"></i> LAPOR INSIDEN SEKARANG</a><a href="#" class="btn-hero-ghost">Tentang Kami →</a></div>
      </div></div>
      <div class="hero-stats"><div class="container"><div class="hero-stats__item"><div class="hero-stats__number">157</div><div class="hero-stats__label">Insiden Ditangani</div></div><div class="hero-stats__item"><div class="hero-stats__number">24/7</div><div class="hero-stats__label">Respons Siap</div></div><div class="hero-stats__item"><div class="hero-stats__number">89K+</div><div class="hero-stats__label">Pengunjung</div></div><div class="hero-stats__item"><div class="hero-stats__number">&lt;2J</div><div class="hero-stats__label">Rata-rata Respons</div></div></div></div>
    </div>
  </div>

  {{-- D: Text-shadow only --}}
  <div class="variant variant--d">
    <div class="variant__label"><span class="variant__badge">D — Text-shadow</span><span class="variant__title">Heavy shadow, same wash</span><span class="variant__desc">No plate, 0.82→0.32 + layered shadow</span></div>
    <div class="demo-hero">
      <div class="demo-hero__body"><div class="container">
        <div class="demo-hero__eyebrow"><i class="bi bi-shield-lock"></i> Tim Tanggap Insiden Siber Resmi DKI Jakarta</div>
        <div class="demo-hero__title">JAKARTA PROV CSIRT</div>
        <div class="demo-hero__lead">Pemerintah Provinsi DKI Jakarta — Computer Security Incident Response Team. Menjaga infrastruktur digital dan data kritis Jakarta dari ancaman siber, 24 jam sehari, 7 hari seminggu.</div>
        <div class="demo-hero__actions"><a href="#" class="btn-hero-primary"><i class="bi bi-megaphone-fill"></i> LAPOR INSIDEN SEKARANG</a><a href="#" class="btn-hero-ghost">Tentang Kami →</a></div>
      </div></div>
      <div class="hero-stats"><div class="container"><div class="hero-stats__item"><div class="hero-stats__number">157</div><div class="hero-stats__label">Insiden Ditangani</div></div><div class="hero-stats__item"><div class="hero-stats__number">24/7</div><div class="hero-stats__label">Respons Siap</div></div><div class="hero-stats__item"><div class="hero-stats__number">89K+</div><div class="hero-stats__label">Pengunjung</div></div><div class="hero-stats__item"><div class="hero-stats__number">&lt;2J</div><div class="hero-stats__label">Rata-rata Respons</div></div></div></div>
    </div>
  </div>

  {{-- E: Adaptive dark/light --}}
  <div class="variant variant--e">
    <div class="variant__label"><span class="variant__badge" style="border-style:dashed">E — Adaptive</span><span class="variant__title">Per-slide dark vs light plate</span><span class="variant__desc">Left=dark racks → dark plate, Right=bright lab → light plate</span></div>
    <div class="demo-hero" style="background:none; padding:0; border-top: 1px solid var(--border);">
      <div class="demo-hero__pane demo-hero__pane--dark">
        <div class="demo-hero__eyebrow"><i class="bi bi-shield-lock"></i> Tim Tanggap Insiden Siber</div>
        <div class="demo-hero__title" style="font-size:28px;">JAKARTA PROV CSIRT</div>
        <div class="demo-hero__lead">Dark rack image → white text on dark scrim survives.</div>
      </div>
      <div class="demo-hero__pane demo-hero__pane--light">
        <div class="demo-hero__eyebrow"><i class="bi bi-shield-lock"></i> Informasi Publik</div>
        <div class="demo-hero__title" style="font-size:28px;">SEMUA TENTANG WEB FILTERING</div>
        <div class="demo-hero__lead">Bright image → dark ink text on light scrim survives. Per-slide <code>scrim: dark|light</code>.</div>
      </div>
    </div>
  </div>

  <div class="demo-head" style="margin-top:8px;">
    <p style="font-size:13px; background:var(--white); border:1px solid var(--border); padding:14px 16px;">
      <strong>How to pick:</strong> <strong>B</strong> is the safest for interchangeable slides (interchangeable text+image) — it guarantees contrast on any image without darkening the whole hero (like <strong>C</strong>). <strong>D</strong> alone fails WCAG on very bright/busy patches. <strong>E</strong> is needed only if you also have bright images where dark text reads better (then the CMS needs a <code>scrim</code> toggle per slide). Tell me which letter(s) you want live and I’ll promote that CSS to <code>home.blade.php</code>.
    </p>
  </div>
</div>
@endsection

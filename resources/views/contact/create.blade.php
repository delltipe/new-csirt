@extends('layouts.app')

@section('content')

<style>
/* ============================================================
   PAGE HEADER BAND (Matches Lapor Insiden)
   ============================================================ */
.lapor-header {
    background: var(--ink, #0A0F1A);
    padding: 48px 0 40px;
    position: relative;
    overflow: hidden;
}
.lapor-header::before {
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
.lapor-header .container { position: relative; z-index: 1; }
.lapor-header__eyebrow {
    font-family: 'Inter', sans-serif;
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
.lapor-header__eyebrow::before {
    content: '';
    display: block;
    width: 20px; height: 1px;
    background: rgba(255,255,255,0.2);
}
.lapor-header h1 {
    font-family: 'Barlow Condensed', sans-serif;
    font-size: clamp(32px, 5vw, 52px);
    font-weight: 900;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: #ffffff;
    line-height: 1;
    margin-bottom: 10px;
}
.lapor-header__sub {
    font-family: 'Inter', sans-serif;
    font-size: 15px;
    font-weight: 300;
    color: rgba(255,255,255,0.5);
    max-width: 540px;
}

/* ============================================================
   LAYOUT & FORM ELEMENTS
   ============================================================ */
.lapor-layout {
    padding: 48px 0 80px;
    background: var(--mist, #F4F5F7);
}

.form-card {
    background: #ffffff;
    border: 1px solid var(--border, #D8DCE3);
    border-top: 4px solid var(--navy, #003580);
    padding: 36px 32px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.form-step__title {
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 26px;
    font-weight: 900;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: var(--ink, #0A0F1A);
    margin-bottom: 6px;
    line-height: 1;
}
.form-step__divider {
    height: 2px;
    background: var(--border, #D8DCE3);
    margin: 16px 0 28px;
}

.lapor-label {
    display: block;
    font-family: 'Inter', sans-serif;
    font-size: 12.5px;
    font-weight: 600;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    color: var(--ink, #0A0F1A);
    margin-bottom: 6px;
}
.lapor-label .req { color: #B91C1C; margin-left: 2px; }

.lapor-input,
.lapor-select,
.lapor-textarea {
    width: 100%;
    height: 44px;
    border: 1px solid var(--border, #D8DCE3);
    background: #ffffff;
    color: var(--ink, #0A0F1A);
    font-family: 'Inter', sans-serif;
    font-size: 14px;
    font-weight: 400;
    padding: 0 14px;
    outline: none;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
    border-radius: 0;
    appearance: none;
}
.lapor-textarea {
    height: auto;
    padding: 12px 14px;
    resize: vertical;
}
.lapor-select {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%236B7280' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    padding-right: 36px;
}
.lapor-input:focus,
.lapor-select:focus,
.lapor-textarea:focus {
    border-color: var(--navy, #003580);
    box-shadow: 0 0 0 3px rgba(0, 53, 128, 0.1);
}
.lapor-input.is-invalid,
.lapor-select.is-invalid,
.lapor-textarea.is-invalid {
    border-color: #B91C1C;
    box-shadow: 0 0 0 3px rgba(185, 28, 28, 0.1);
}

.field-error {
    font-size: 12px;
    color: #B91C1C;
    margin-top: 4px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}
.form-field { margin-bottom: 20px; }
.form-field:last-child { margin-bottom: 0; }

.step-nav {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    padding-top: 24px;
    border-top: 1px solid var(--border, #D8DCE3);
    margin-top: 28px;
    gap: 16px;
}

.btn-submit {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--navy, #003580);
    color: #ffffff;
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 16px;
    font-weight: 900;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    padding: 15px 36px;
    border: none;
    cursor: pointer;
    transition: background 0.2s ease;
}
.btn-submit:hover { background: #002060; color: #ffffff; }

.btn-ghost {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: transparent;
    color: #6B7280;
    font-family: 'Inter', sans-serif;
    font-size: 14px;
    font-weight: 600;
    padding: 15px 20px;
    border: 1px solid var(--border, #D8DCE3);
    text-decoration: none;
    cursor: pointer;
    transition: color 0.2s ease, border-color 0.2s ease;
}
.btn-ghost:hover { color: var(--ink, #0A0F1A); border-color: #9ca3af; }

/* ============================================================
   SIDEBAR (Contact Details)
   ============================================================ */
.sidebar-card {
    background: #ffffff;
    border: 1px solid var(--border, #D8DCE3);
    border-top: 3px solid var(--navy, #003580);
    padding: 32px 24px;
    margin-bottom: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}
.contact-item {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 24px;
}
.contact-item:last-child { margin-bottom: 0; }
.contact-icon {
    width: 48px;
    height: 48px;
    background: var(--mist, #F4F5F7);
    border: 1px solid var(--border, #D8DCE3);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.contact-icon i {
    font-size: 20px;
    color: var(--navy, #003580);
}
.contact-text h4 {
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 18px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--ink, #0A0F1A);
    margin-bottom: 4px;
}
.contact-text p {
    font-family: 'Inter', sans-serif;
    font-size: 14px;
    color: #6B7280;
    margin: 0;
    line-height: 1.5;
}

@media (max-width: 991px) {
    .form-row { grid-template-columns: 1fr; }
    .form-card { padding: 24px 16px; }
}
</style>

<div class="lapor-header">
    <div class="container">
        <div class="lapor-header__eyebrow">
            <i class="bi bi-headset" aria-hidden="true"></i>
            Pusat Bantuan
        </div>
        <h1>Hubungi Kami</h1>
        <p class="lapor-header__sub">
            Hubungi JakartaProv-CSIRT untuk pertanyaan umum, dukungan teknis, kemitraan, atau keperluan media.
        </p>
    </div>
</div>

<section class="lapor-layout">
    <div class="container">
        <div class="row g-5">
            
            {{-- =================================================== 
                 KIRI: FORMULIR PESAN (col-lg-8)
                 =================================================== --}}
            <div class="col-lg-8">
                <form action="{{ route('contact.store') }}" method="POST" class="form-card">
                    @csrf
                    <h2 class="form-step__title">Kirim Pesan</h2>
                    <div class="form-step__divider"></div>

                    <div class="form-row">
                        <div class="form-field">
                            <label for="name" class="lapor-label">Nama Lengkap <span class="req">*</span></label>
                            <input type="text" id="name" name="name" class="lapor-input @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="Nama Anda">
                            @error('name') <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div> @enderror
                        </div>

                        <div class="form-field">
                            <label for="email" class="lapor-label">Email <span class="req">*</span></label>
                            <input type="email" id="email" name="email" class="lapor-input @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="nama@domain.com">
                            @error('email') <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-field">
                            <label for="phone" class="lapor-label">Nomor Telepon</label>
                            <input type="tel" id="phone" name="phone" class="lapor-input @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx">
                            @error('phone') <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div> @enderror
                        </div>

                        <div class="form-field">
                            <label for="organization" class="lapor-label">Organisasi/Perusahaan</label>
                            <input type="text" id="organization" name="organization" class="lapor-input @error('organization') is-invalid @enderror" value="{{ old('organization') }}" placeholder="Asal instansi">
                            @error('organization') <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="form-field">
                        <label for="inquiry_type" class="lapor-label">Tipe Pertanyaan <span class="req">*</span></label>
                        <select id="inquiry_type" name="inquiry_type" class="lapor-select lapor-input @error('inquiry_type') is-invalid @enderror" required>
                            <option value="" disabled {{ old('inquiry_type') ? '' : 'selected' }}>-- Pilih Tipe Pertanyaan --</option>
                            <option value="general" {{ old('inquiry_type') == 'general' ? 'selected' : '' }}>Informasi Umum</option>
                            <option value="support" {{ old('inquiry_type') == 'support' ? 'selected' : '' }}>Dukungan Teknis</option>
                            <option value="partnership" {{ old('inquiry_type') == 'partnership' ? 'selected' : '' }}>Kemitraan</option>
                            <option value="media" {{ old('inquiry_type') == 'media' ? 'selected' : '' }}>Media</option>
                            <option value="other" {{ old('inquiry_type') == 'other' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('inquiry_type') <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div> @enderror
                    </div>

                    <div class="form-field">
                        <label for="subject" class="lapor-label">Subjek <span class="req">*</span></label>
                        <input type="text" id="subject" name="subject" class="lapor-input @error('subject') is-invalid @enderror" value="{{ old('subject') }}" required placeholder="Perihal pesan">
                        @error('subject') <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div> @enderror
                    </div>

                    <div class="form-field">
                        <label for="message" class="lapor-label">Pesan <span class="req">*</span></label>
                        <textarea id="message" name="message" class="lapor-textarea @error('message') is-invalid @enderror" rows="5" required placeholder="Tuliskan detail pertanyaan atau pesan Anda di sini...">{{ old('message') }}</textarea>
                        @error('message') <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div> @enderror
                    </div>

                    <div class="step-nav">
                        <a href="{{ route('home') }}" class="btn-ghost">Batalkan</a>
                        <button type="submit" class="btn-submit">
                            Kirim Pesan <i class="bi bi-send-fill ms-1"></i>
                        </button>
                    </div>
                </form>
            </div>

            {{-- =================================================== 
                 KANAN: INFORMASI KONTAK (col-lg-4)
                 =================================================== --}}
            <div class="col-lg-4">
                <aside class="sidebar-card">
                    <h3 class="form-step__title mb-4">Informasi Kontak</h3>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="bi bi-envelope-fill"></i>
                        </div>
                        <div class="contact-text">
                            <h4>Email</h4>
                            <p>jakarta.csirt@jakarta.go.id</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="bi bi-telephone-fill"></i>
                        </div>
                        <div class="contact-text">
                            <h4>Telepon</h4>
                            <p>(021) 1234-5678</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon" style="border-color: #B91C1C; background: #FEF2F2;">
                            <i class="bi bi-shield-fill-exclamation" style="color: #B91C1C;"></i>
                        </div>
                        <div class="contact-text">
                            <h4 style="color: #B91C1C;">Hotline Insiden 24/7</h4>
                            <p class="fw-bold" style="color: #000;">1110</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div class="contact-text">
                            <h4>Alamat</h4>
                            <p>Balaikota Blok H Lt. 13,<br>Jl. Merdeka Selatan 8-9,<br>Jakarta Pusat 10110</p>
                        </div>
                    </div>
                </aside>
            </div>

        </div>
    </div>
</section>
@endsection
@extends('layouts.app')

@php
    $transitions = \App\Models\ContactMessage::transitions()[$contact->status] ?? [];
    $statusOptions = array_merge(
        [$contact->status => $contact->statusLabel()],
        array_intersect_key(\App\Models\ContactMessage::labels(), array_flip($transitions))
    );
@endphp

@section('content')
<style>
.admin-container { max-width:1400px; margin:0 auto; padding:60px 28px; }
.admin-header { border-bottom:3px solid var(--border); padding-bottom:20px; margin-bottom:40px; display:flex; justify-content:space-between; align-items:flex-end; gap:16px; flex-wrap:wrap; }
.admin-title { font-family:var(--font-display); font-size:36px; font-weight:800; letter-spacing:0.02em; text-transform:uppercase; color:var(--ink); margin:0; }
.admin-sub { font-size:14px; color:var(--mid); margin-top:6px; }
.admin-back { display:inline-flex; align-items:center; gap:6px; background:transparent; color:var(--navy); border:1px solid var(--navy); font-size:13px; font-weight:600; padding:9px 18px; text-decoration:none; transition:background var(--ease); }
.admin-back:hover{ background:var(--navy-tint); }
.alert-success{ background:var(--navy-tint); border:1px solid var(--navy); color:var(--navy-dim); padding:16px 20px; margin-bottom:24px; }
.alert-error{ background:var(--alert-bg); border:1px solid var(--alert); color:var(--alert-dark); padding:16px 20px; margin-bottom:24px; }
.review-grid{ display:grid; grid-template-columns:1.6fr 1fr; gap:32px; align-items:start; }
.detail-card{ background:var(--white); border:1px solid var(--border); }
.detail-card__head{ padding:20px 28px; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap; }
.detail-card__title{ font-family:var(--font-display); font-size:16px; font-weight:800; letter-spacing:0.04em; text-transform:uppercase; color:var(--ink); margin:0; }
.detail-item{ display:grid; grid-template-columns:170px 1fr; gap:16px; padding:13px 28px; border-bottom:1px solid var(--border); }
.detail-item__label{ font-size:11.5px; font-weight:600; letter-spacing:0.05em; text-transform:uppercase; color:var(--mid); }
.detail-item__value{ font-size:13.5px; color:var(--ink); line-height:1.65; word-break:break-word; }
.detail-item__value a{ color:var(--navy); }
.review-card{ background:var(--white); border:1px solid var(--border); border-top:3px solid var(--navy); position:sticky; top:96px; }
.review-card__body{ padding:28px; }
.review-label{ display:block; font-family:var(--font-display); font-size:12.5px; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; color:var(--ink); margin-bottom:8px; }
.review-select, .review-textarea{ width:100%; border:1px solid var(--border); background:var(--white); color:var(--ink); font-size:13.5px; padding:10px 12px; outline:none; margin-bottom:22px; }
.review-select{ height:42px; }
.review-textarea{ min-height:90px; resize:vertical; }
.btn-review-submit{ width:100%; background:var(--navy); color:var(--white); font-family:var(--font-display); font-size:14px; font-weight:800; letter-spacing:0.05em; text-transform:uppercase; padding:14px; border:none; cursor:pointer; }
.btn-delete{ width:100%; background:var(--alert); color:var(--white); font-size:13px; font-weight:700; letter-spacing:0.05em; text-transform:uppercase; padding:12px; border:none; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; gap:6px; }
@media(max-width:900px){ .review-grid{ grid-template-columns:1fr; } .review-card{ position:static; } }
@media(max-width:640px){ .detail-item{ grid-template-columns:1fr; } }
</style>

<div class="admin-container">
    <div class="admin-header">
        <div>
            <h1 class="admin-title">{{ $contact->subject }}</h1>
            <div class="admin-sub">Dari {{ $contact->name }} ({{ $contact->email }}) — {{ $contact->created_at->format('d M Y H:i') }}</div>
        </div>
        <a href="{{ route('admin.contacts.list') }}" class="admin-back"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    @if(session('success'))<div class="alert-success">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="alert-error">{{ $errors->first() }}</div>@endif

    <div class="review-grid">
        <div class="detail-card">
            <div class="detail-card__head">
                <h2 class="detail-card__title">Detail Pesan</h2>
                <span class="status-badge status-{{ $contact->status==='pending'?'menunggu_validasi':($contact->status==='diproses'?'ditindaklanjuti':$contact->status) }}">{{ $contact->statusLabel() }}</span>
            </div>
            <div class="detail-item"><div class="detail-item__label">Nama</div><div class="detail-item__value">{{ $contact->name }}</div></div>
            <div class="detail-item"><div class="detail-item__label">Email</div><div class="detail-item__value"><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></div></div>
            <div class="detail-item"><div class="detail-item__label">Telepon</div><div class="detail-item__value">{{ $contact->phone ?: '—' }}</div></div>
            <div class="detail-item"><div class="detail-item__label">Organisasi</div><div class="detail-item__value">{{ $contact->organization ?: '—' }}</div></div>
            <div class="detail-item"><div class="detail-item__label">Tipe</div><div class="detail-item__value">{{ $contact->inquiry_type }}</div></div>
            <div class="detail-item"><div class="detail-item__label">Subjek</div><div class="detail-item__value">{{ $contact->subject }}</div></div>
            <div class="detail-item"><div class="detail-item__label">Pesan</div><div class="detail-item__value">{{ $contact->message }}</div></div>
            <div class="detail-item"><div class="detail-item__label">Catatan Admin</div><div class="detail-item__value">{{ $contact->admin_note ?: '—' }}</div></div>
            <div class="detail-item"><div class="detail-item__label">Status</div><div class="detail-item__value"><span class="status-badge status-{{ $contact->status==='pending'?'menunggu_validasi':($contact->status==='diproses'?'ditindaklanjuti':$contact->status) }}">{{ $contact->statusLabel() }}</span></div></div>
        </div>

        <div class="review-card">
            <div class="review-card__body">
                <form method="POST" action="{{ route('admin.contacts.update', $contact->id) }}">
                    @csrf
                    <label class="review-label" for="status">Perbarui Status</label>
                    <select name="status" id="status" class="review-select">
                        @foreach($statusOptions as $value=>$label)
                            <option value="{{ $value }}" {{ $contact->status===$value?'selected':'' }}>{{ $label }}</option>
                        @endforeach
                    </select>

                    <label class="review-label" for="admin_note">Catatan Admin</label>
                    <textarea name="admin_note" id="admin_note" class="review-textarea" placeholder="Tambahkan catatan tindak lanjut..." rows="4">{{ old('admin_note', $contact->admin_note) }}</textarea>

                    <button type="submit" class="btn-review-submit"><i class="bi bi-check2-circle"></i> Simpan</button>
                </form>
                <hr style="border:none;border-top:1px solid var(--border);margin:24px 0;">
                <form method="POST" action="{{ route('admin.contacts.delete', $contact->id) }}" onsubmit="return confirm('Hapus pesan ini?');">
                    @csrf
                    <button type="submit" class="btn-delete"><i class="bi bi-trash"></i> Hapus Pesan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

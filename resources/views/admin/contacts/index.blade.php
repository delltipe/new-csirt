@extends('layouts.app')

@section('content')
<style>
.admin-container { max-width:1400px; margin:0 auto; padding:60px 28px; }
.admin-header { border-bottom:3px solid var(--border); padding-bottom:20px; margin-bottom:40px; display:flex; justify-content:space-between; align-items:flex-end; gap:16px; flex-wrap:wrap; }
.admin-title { font-family:var(--font-display); font-size:36px; font-weight:800; letter-spacing:0.02em; text-transform:uppercase; color:var(--ink); margin:0; }
.admin-back { display:inline-flex; align-items:center; gap:6px; background:transparent; color:var(--navy); border:1px solid var(--navy); font-size:13px; font-weight:600; padding:9px 18px; text-decoration:none; transition:background var(--ease); }
.admin-back:hover{ background:var(--navy-tint); }
.filter-bar { display:flex; gap:8px; margin-bottom:20px; flex-wrap:wrap; }
.filter-bar a { padding:8px 14px; border:1px solid var(--border); font-size:12px; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; color:var(--mid); text-decoration:none; }
.filter-bar a.active, .filter-bar a:hover { background:var(--navy); color:var(--white); border-color:var(--navy); }
</style>
<div class="admin-container">
    <div class="admin-header">
        <h1 class="admin-title">Pesan Kontak</h1>
        <a href="{{ route('admin.dashboard') }}#kontak" class="admin-back"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
    <div class="filter-bar">
        <a href="{{ route('admin.contacts.list') }}" class="{{ !request('status') ? 'active' : '' }}">Semua</a>
        @foreach(\App\Models\ContactMessage::labels() as $key=>$label)
            <a href="{{ route('admin.contacts.list', ['status'=>$key]) }}" class="{{ request('status')===$key ? 'active' : '' }}">{{ $label }}</a>
        @endforeach
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Subjek</th>
                    <th>Tipe</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contacts as $contact)
                <tr>
                    <td>{{ $contact->name }}</td>
                    <td>{{ $contact->email }}</td>
                    <td>{{ Str::limit($contact->subject, 40) }}</td>
                    <td>{{ $contact->inquiry_type }}</td>
                    <td><span class="status-badge status-{{ $contact->status==='pending'?'menunggu_validasi':($contact->status==='diproses'?'ditindaklanjuti':$contact->status) }}">{{ $contact->statusLabel() }}</span></td>
                    <td>{{ $contact->created_at->format('d M Y H:i') }}</td>
                    <td><a href="{{ route('admin.contacts.show', $contact->id) }}" class="btn-edit"><i class="bi bi-eye"></i> Lihat</a></td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center; padding:40px; color:var(--mid);">Tidak ada pesan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:16px;">{{ $contacts->links() }}</div>
</div>
@endsection

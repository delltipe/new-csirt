@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row g-5">
        @include('incidents._sidebar')
        
        <div class="col-md-7 col-lg-8">
            <div class="mb-4">
                <span class="badge bg-success">Langkah Terakhir</span>
                <h3 class="mt-2">Detail Laporan</h3>
            </div>

            <form action="{{ route('incidents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Deskripsi Insiden</label>
                        <textarea name="laporDesc" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Risk Level</label>
                        <select name="riskLevel" class="form-select">
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                            <option value="Critical">Critical</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">CVSS Score</label>
                        <input type="number" name="cvssScore" step="0.1" class="form-control" placeholder="0.0 - 10.0">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Bukti Gambar (Max 2MB)</label>
                        <input type="file" name="proofPic" class="form-control">
                    </div>
                    <div class="col-12 p-3 bg-light border rounded">
                        <label class="form-label fw-bold text-danger">Keamanan: Ketik "JKT" untuk memverifikasi</label>
                        <input type="text" name="captcha" class="form-control" placeholder="Ketik JKT di sini" required>
                    </div>
                </div>

                <hr class="my-4">
                <div class="d-flex gap-3">
                    <a href="{{ route('incidents.create.step2') }}" class="w-100 btn btn-outline-secondary btn-lg">⬅ Sebelumnya</a>
                    <button class="w-100 btn btn-success btn-lg" type="submit">Kirim Laporan Resmi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
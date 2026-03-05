@extends('layouts.app')

@section('content')
<!-- Page Header -->
<section class="bg-gradient-to-r from-slate-900 to-slate-800 py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold text-white mb-2">Lapor Insiden Siber</h1>
        <p class="text-lg text-slate-300">Bantu kami melindungi infrastruktur kritis Indonesia dengan melaporkan insiden keamanan siber</p>
    </div>
</section>

<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <!-- Info Alert -->
            <div class="bg-blue-900 border-l-4 border-blue-500 p-6 rounded-lg mb-8">
                <p class="text-blue-100">
                    <strong>Penting:</strong> Waktu respons Jakarta CSIRT tersedia 24/7. Semakin cepat Anda melaporkan, semakin cepat kami dapat membantu.
                </p>
            </div>

            <form id="incident-form" action="{{ route('incidents.store') }}" method="POST" class="card" enctype="multipart/form-data">
                @csrf
                <div class="p-8">
                    <input type="hidden" id="current-step" name="current_step" value="1">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Left: form pages (takes 2/3 width on md+) -->
                        <div class="md:col-span-2">
                            <!-- STEP 1: Data Pelapor -->
                            <div class="form-step" data-step="1">
                                <h3 class="text-2xl font-bold text-white mb-6 pb-4 border-b border-slate-700">Data Pelapor</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                    <div>
                                        <label for="fullName" class="block text-white font-semibold mb-2">Nama Lengkap *</label>
                                        <input type="text" id="fullName" name="fullName" value="{{ old('fullName') }}" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-2" required>
                                        @error('fullName') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label for="email" class="block text-white font-semibold mb-2">Email *</label>
                                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-2" required>
                                        @error('email') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                    <div>
                                        <label for="phoneNumber" class="block text-white font-semibold mb-2">No. HP *</label>
                                        <input type="tel" id="phoneNumber" name="phoneNumber" value="{{ old('phoneNumber') }}" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-2" required>
                                        @error('phoneNumber') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label for="foundDate" class="block text-white font-semibold mb-2">Tanggal Temuan</label>
                                        <input type="date" id="foundDate" name="foundDate" value="{{ old('foundDate') }}" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-2">
                                    </div>
                                </div>

                                <div class="flex justify-end">
                                    <button type="button" class="btn-next px-6 py-3 bg-gray-200 rounded text-black font-semibold">Selanjutnya ➜</button>
                                </div>
                            </div>

                            <!-- STEP 2: Data Website -->
                            <div class="form-step hidden" data-step="2">
                                <h3 class="text-2xl font-bold text-white mb-6 pb-4 border-b border-slate-700">Data Website</h3>
                                <div class="mb-6">
                                    <label for="domain" class="block text-white font-semibold mb-2">Nama Domain *</label>
                                    <input type="text" id="domain" name="domain" value="{{ old('domain') }}" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-2" required>
                                    @error('domain') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-6">
                                    <label for="url" class="block text-white font-semibold mb-2">URL Terdampak *</label>
                                    <input type="url" id="url" name="url" value="{{ old('url') }}" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-2" required>
                                    @error('url') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="flex justify-between">
                                    <button type="button" class="btn-prev px-6 py-3 bg-gray-200 rounded text-black font-semibold">← Sebelumnya</button>
                                    <button type="button" class="btn-next px-6 py-3 bg-gray-200 rounded text-black font-semibold">Selanjutnya ➜</button>
                                </div>
                            </div>

                            <!-- STEP 3: Detail Laporan -->
                            <div class="form-step hidden" data-step="3">
                                <h3 class="text-2xl font-bold text-white mb-6 pb-4 border-b border-slate-700">Detail Laporan</h3>

                                <div class="mb-6">
                                    <label for="laporDesc" class="block text-white font-semibold mb-2">Deskripsi *</label>
                                    <textarea id="laporDesc" name="laporDesc" rows="6" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-2" required>{{ old('laporDesc') }}</textarea>
                                    @error('laporDesc') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                                    <div>
                                        <label for="riskType" class="block text-white font-semibold mb-2">Jenis Risiko</label>
                                        <input type="text" id="riskType" name="riskType" value="{{ old('riskType') }}" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-2">
                                    </div>
                                    <div>
                                        <label for="riskLevel" class="block text-white font-semibold mb-2">Tingkat Risiko</label>
                                        <input type="text" id="riskLevel" name="riskLevel" value="{{ old('riskLevel') }}" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-2">
                                    </div>
                                    <div>
                                        <label for="cvssScore" class="block text-white font-semibold mb-2">CVSS Score</label>
                                        <input type="number" step="0.1" id="cvssScore" name="cvssScore" value="{{ old('cvssScore') }}" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-2">
                                    </div>
                                </div>

                                <div class="mb-6">
                                    <label for="videoUrl" class="block text-white font-semibold mb-2">URL Bukti Video</label>
                                    <input type="url" id="videoUrl" name="videoUrl" value="{{ old('videoUrl') }}" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-2">
                                </div>

                                <div class="mb-6">
                                    <label for="reference" class="block text-white font-semibold mb-2">Referensi (Opsional)</label>
                                    <input type="text" id="reference" name="reference" value="{{ old('reference') }}" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-2">
                                </div>

                                <div class="mb-6">
                                    <label for="recommendation" class="block text-white font-semibold mb-2">Rekomendasi</label>
                                    <input type="text" id="recommendation" name="recommendation" value="{{ old('recommendation') }}" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-2">
                                </div>

                                <div class="mb-6">
                                    <label for="proofPic" class="block text-white font-semibold mb-2">Screenshot Bukti (PNG/JPG, max 2MB)</label>
                                    <input type="file" id="proofPic" name="proofPic" accept="image/png, image/jpeg" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-2">
                                </div>

                                <!-- Simple captcha placeholder -->
                                <div class="mb-6">
                                    <label for="captcha" class="block text-white font-semibold mb-2">Captcha: Ketik "JKT" untuk verifikasi</label>
                                    <input type="text" id="captcha" name="captcha" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-2" placeholder="Masukkan kode captcha" required>
                                </div>

                                <div class="flex justify-between">
                                    <button type="button" class="btn-prev px-6 py-3 bg-gray-200 rounded text-black font-semibold">← Sebelumnya</button>
                                    <button type="submit" class="px-6 py-3 bg-orange-500 rounded text-white font-semibold">Simpan!</button>
                                </div>
                            </div>
                        </div>

                        <!-- Right: static title & notes -->
                        <div class="md:col-span-1 bg-transparent">
                            <h2 class="text-3xl font-bold text-white mb-4">Form Laporan Insiden Siber</h2>
                            <div class="text-slate-300 leading-relaxed">
                                <p class="font-semibold">Catatan :</p>
                                <ul class="list-disc pl-5 mt-2 space-y-2">
                                    <li>Lingkup Domain dan subdomain yang dilaporkan adalah *. Jakarta.go.id</li>
                                    <li>Harap mengisi nama lengkap dan kontak pribadi dengan benar, karena sertifikat akan ditulis berdasarkan data yang ada</li>
                                    <li>Proses validasi report bug bounty memerlukan waktu maksimal selama 7 hari kerja</li>
                                    <li>Jika report dinyatakan VALID dan tidak DUPLICATE, maka kami akan mengirimkan sertifikat apresiasi maksimal selama 4 hari kerja.</li>
                                    <li><strong>Satu Temuan Hanya Untuk Satu Orang Pelapor</strong></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const steps = Array.from(document.querySelectorAll('.form-step'));
    const indicators = [
        document.getElementById('step-indicator-1'),
        document.getElementById('step-indicator-2'),
        document.getElementById('step-indicator-3')
    ];

    function showStep(n) {
        steps.forEach(s => s.classList.add('hidden'));
        const step = steps[n-1];
        if (step) step.classList.remove('hidden');
        document.getElementById('current-step').value = n;
        indicators.forEach((el, idx) => {
            if (idx === n-1) {
                el.classList.add('bg-orange-500'); el.classList.remove('bg-slate-700'); el.classList.remove('text-slate-300'); el.classList.add('text-white');
            } else {
                el.classList.remove('bg-orange-500'); el.classList.add('bg-slate-700'); el.classList.add('text-slate-300'); el.classList.remove('text-white');
            }
        });
        window.scrollTo({top:0,behavior:'smooth'});
    }

    document.querySelectorAll('.btn-next').forEach(btn => {
        btn.addEventListener('click', function () {
            const current = parseInt(document.getElementById('current-step').value, 10);
            const visible = steps[current-1];
            // basic validation of visible inputs
            const inputs = Array.from(visible.querySelectorAll('input, textarea, select')).filter(i => !i.disabled);
            let ok = true;
            for (const input of inputs) {
                if (input.hasAttribute('required') && !input.value) { ok = false; input.classList.add('ring-2','ring-red-500'); }
                else input.classList.remove('ring-2','ring-red-500');
            }
            if (!ok) { alert('Mohon lengkapi field yang wajib sebelum melanjutkan.'); return; }
            if (current < steps.length) showStep(current+1);
        });
    });

    document.querySelectorAll('.btn-prev').forEach(btn => {
        btn.addEventListener('click', function () {
            const current = parseInt(document.getElementById('current-step').value, 10);
            if (current > 1) showStep(current-1);
        });
    });

    // final submit validation (simple captcha + file check)
    document.getElementById('incident-form').addEventListener('submit', function (e) {
        const captcha = document.getElementById('captcha');
        if (captcha && captcha.value.trim().toUpperCase() !== 'JKT') {
            e.preventDefault();
            alert('Captcha salah. Masukkan kode yang benar (JKT).');
            return false;
        }
        const file = document.getElementById('proofPic');
        if (file && file.files && file.files[0]) {
            const f = file.files[0];
            const allowed = ['image/png','image/jpeg'];
            if (!allowed.includes(f.type)) { e.preventDefault(); alert('Tipe file harus PNG/JPG/JPEG'); return false; }
            if (f.size > 2*1024*1024) { e.preventDefault(); alert('File terlalu besar. Maksimum 2MB'); return false; }
        }
        // allow normal form submit; server will handle DB insertion
    });

    // initialize
    showStep(1);
});
</script>

@endsection

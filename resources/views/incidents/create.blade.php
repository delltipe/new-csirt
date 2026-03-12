@extends('layouts.app')

@section('content')
<section class="bg-gradient-to-r from-slate-900 to-slate-800 py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold text-white mb-2">Lapor Insiden Siber</h1>
        <p class="text-lg text-slate-300">Bantu kami melindungi infrastruktur kritis Indonesia dengan melaporkan insiden keamanan siber</p>
    </div>
</section>

<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            
            <div class="bg-blue-900 border-l-4 border-blue-500 p-6 rounded-lg mb-8 shadow-lg">
                <p class="text-blue-100">
                    <strong>Penting:</strong> Waktu respons Jakarta CSIRT tersedia 24/7. Semakin cepat Anda melaporkan, semakin cepat kami dapat membantu.
                </p>
            </div>

            <form id="incident-form" action="{{ route('incidents.store') }}" method="POST" class="bg-slate-800 rounded-xl shadow-2xl overflow-hidden" enctype="multipart/form-data">
                @csrf
                
                <div class="bg-slate-900 p-4 border-b border-slate-700 flex justify-center space-x-4 md:space-x-8">
                    <div id="step-indicator-1" class="px-4 py-2 rounded-full font-bold text-sm bg-orange-500 text-white transition-colors">1. Pelapor</div>
                    <div id="step-indicator-2" class="px-4 py-2 rounded-full font-bold text-sm bg-slate-700 text-slate-300 transition-colors">2. Website</div>
                    <div id="step-indicator-3" class="px-4 py-2 rounded-full font-bold text-sm bg-slate-700 text-slate-300 transition-colors">3. Detail</div>
                </div>

                <div class="p-8">
                    <input type="hidden" id="current-step" name="current_step" value="1">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        
                        <div class="md:col-span-2">
                            
                            <div class="form-step" data-step="1">
                                <h3 class="text-2xl font-bold text-white mb-6 pb-2 border-b border-slate-700">Data Pelapor</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div>
                                        <label for="fullName" class="block text-white font-semibold mb-2">Nama Lengkap *</label>
                                        <input type="text" id="fullName" name="fullName" value="{{ old('fullName') }}" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:outline-none transition" required>
                                        @error('fullName') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="email" class="block text-white font-semibold mb-2">Email *</label>
                                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:outline-none transition" required>
                                        @error('email') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                    <div>
                                        <label for="phoneNumber" class="block text-white font-semibold mb-2">No. HP *</label>
                                        <input type="tel" id="phoneNumber" name="phoneNumber" value="{{ old('phoneNumber') }}" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:outline-none transition" required>
                                        @error('phoneNumber') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="foundDate" class="block text-white font-semibold mb-2">Tanggal Temuan</label>
                                        <input type="date" id="foundDate" name="foundDate" value="{{ old('foundDate') }}" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:outline-none transition">
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <button type="button" class="btn-next px-8 py-3 bg-slate-200 hover:bg-white rounded text-slate-900 font-bold transition">Selanjutnya ➜</button>
                                </div>
                            </div>

                            <div class="form-step hidden" data-step="2">
                                <h3 class="text-2xl font-bold text-white mb-6 pb-2 border-b border-slate-700">Data Website</h3>
                                <div class="mb-6">
                                    <label for="domain" class="block text-white font-semibold mb-2">Nama Domain *</label>
                                    <input type="text" id="domain" name="domain" value="{{ old('domain') }}" placeholder="contoh: jakarta.go.id" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:outline-none transition" required>
                                    @error('domain') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-8">
                                    <label for="url" class="block text-white font-semibold mb-2">URL Terdampak *</label>
                                    <input type="url" id="url" name="url" value="{{ old('url') }}" placeholder="https://..." class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:outline-none transition" required>
                                    @error('url') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div class="flex justify-between items-center">
                                    <button type="button" class="btn-prev px-6 py-3 text-slate-300 hover:text-white font-semibold transition">← Sebelumnya</button>
                                    <button type="button" class="btn-next px-8 py-3 bg-slate-200 hover:bg-white rounded text-slate-900 font-bold transition">Selanjutnya ➜</button>
                                </div>
                            </div>

                            <div class="form-step hidden" data-step="3">
                                <h3 class="text-2xl font-bold text-white mb-6 pb-2 border-b border-slate-700">Detail Laporan</h3>
                                <div class="mb-6">
                                    <label for="laporDesc" class="block text-white font-semibold mb-2">Deskripsi *</label>
                                    <textarea id="laporDesc" name="laporDesc" rows="5" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:outline-none transition" required>{{ old('laporDesc') }}</textarea>
                                    @error('laporDesc') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                                    <div>
                                        <label for="riskType" class="block text-white font-semibold mb-2">Jenis Risiko</label>
                                        <input type="text" id="riskType" name="riskType" value="{{ old('riskType') }}" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:outline-none transition">
                                    </div>
                                    <div>
                                        <label for="riskLevel" class="block text-white font-semibold mb-2">Tingkat Risiko</label>
                                        <input type="text" id="riskLevel" name="riskLevel" value="{{ old('riskLevel') }}" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:outline-none transition">
                                    </div>
                                    <div>
                                        <label for="cvssScore" class="block text-white font-semibold mb-2">CVSS Score</label>
                                        <input type="number" step="0.1" id="cvssScore" name="cvssScore" value="{{ old('cvssScore') }}" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:outline-none transition">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div>
                                        <label for="videoUrl" class="block text-white font-semibold mb-2">URL Bukti Video</label>
                                        <input type="url" id="videoUrl" name="videoUrl" value="{{ old('videoUrl') }}" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:outline-none transition">
                                    </div>
                                    <div>
                                        <label for="reference" class="block text-white font-semibold mb-2">Referensi (Opsional)</label>
                                        <input type="text" id="reference" name="reference" value="{{ old('reference') }}" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:outline-none transition">
                                    </div>
                                </div>

                                <div class="mb-6">
                                    <label for="recommendation" class="block text-white font-semibold mb-2">Rekomendasi Perbaikan</label>
                                    <input type="text" id="recommendation" name="recommendation" value="{{ old('recommendation') }}" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:outline-none transition">
                                </div>

                                <div class="mb-6">
                                    <label for="proofPic" class="block text-white font-semibold mb-2">Screenshot Bukti (PNG/JPG, max 2MB)</label>
                                    <input type="file" id="proofPic" name="proofPic" accept="image/png, image/jpeg" class="w-full bg-slate-700 text-slate-300 border border-slate-600 rounded px-4 py-2 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-orange-500 file:text-white hover:file:bg-orange-600 transition">
                                </div>

                                <div class="mb-8 p-4 bg-slate-900 border border-slate-600 rounded">
                                    <label for="captcha" class="block text-orange-400 font-semibold mb-2">Keamanan: Ketik "JKT" untuk verifikasi *</label>
                                    <input type="text" id="captcha" name="captcha" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:outline-none transition" placeholder="Masukkan kode captcha" required>
                                </div>

                                <div class="flex justify-between items-center">
                                    <button type="button" class="btn-prev px-6 py-3 text-slate-300 hover:text-white font-semibold transition">← Sebelumnya</button>
                                    <button type="submit" class="px-8 py-3 bg-orange-500 hover:bg-orange-600 shadow-lg shadow-orange-500/30 rounded text-white font-bold text-lg transition transform hover:-translate-y-1">Kirim Laporan!</button>
                                </div>
                            </div>
                        </div>

                        <div class="md:col-span-1 border-l md:border-slate-700 md:pl-6 mt-8 md:mt-0">
                            <h2 class="text-2xl font-bold text-white mb-4">Peraturan Laporan</h2>
                            <div class="text-slate-300 text-sm leading-relaxed space-y-4">
                                <div class="p-4 bg-slate-700/50 rounded-lg">
                                    <ul class="list-disc pl-4 space-y-3">
                                        <li>Lingkup Domain dan subdomain yang dilaporkan adalah <strong>*.jakarta.go.id</strong></li>
                                        <li>Harap mengisi nama lengkap dan kontak pribadi dengan benar untuk keperluan sertifikat apresiasi.</li>
                                        <li>Proses validasi report bug bounty memerlukan waktu maksimal selama <strong>7 hari kerja</strong>.</li>
                                        <li>Jika report dinyatakan VALID dan TIDAK DUPLICATE, sertifikat akan dikirim maksimal 4 hari kerja setelahnya.</li>
                                        <li class="text-orange-400 font-bold">Satu Temuan Hanya Untuk Satu Orang Pelapor.</li>
                                    </ul>
                                </div>
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
        // Hide all steps
        steps.forEach(s => s.classList.add('hidden'));
        
        // Show current step
        const step = steps[n - 1];
        if (step) step.classList.remove('hidden');
        document.getElementById('current-step').value = n;
        
        // Update styling of indicators
        indicators.forEach((el, idx) => {
            if (!el) return; // safety check
            if (idx === n - 1) {
                // Active state
                el.classList.remove('bg-slate-700', 'text-slate-300');
                el.classList.add('bg-orange-500', 'text-white');
            } else {
                // Inactive state
                el.classList.remove('bg-orange-500', 'text-white');
                el.classList.add('bg-slate-700', 'text-slate-300');
            }
        });
        
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Handle NEXT button
    document.querySelectorAll('.btn-next').forEach(btn => {
        btn.addEventListener('click', function () {
            const current = parseInt(document.getElementById('current-step').value, 10);
            const visible = steps[current - 1];
            const inputs = Array.from(visible.querySelectorAll('input, textarea, select')).filter(i => !i.disabled);
            
            let ok = true;
            for (const input of inputs) {
                if (input.hasAttribute('required') && !input.value.trim()) { 
                    ok = false; 
                    input.classList.add('ring-2', 'ring-red-500', 'border-red-500'); 
                } else {
                    input.classList.remove('ring-2', 'ring-red-500', 'border-red-500');
                }
            }
            
            if (!ok) { 
                alert('Mohon lengkapi field yang wajib (*) sebelum melanjutkan.'); 
                return; 
            }
            
            if (current < steps.length) showStep(current + 1);
        });
    });

    // Handle PREV button
    document.querySelectorAll('.btn-prev').forEach(btn => {
        btn.addEventListener('click', function () {
            const current = parseInt(document.getElementById('current-step').value, 10);
            if (current > 1) showStep(current - 1);
        });
    });

    // Final Form Submit Validation
    document.getElementById('incident-form').addEventListener('submit', function (e) {
        const captcha = document.getElementById('captcha');
        if (captcha && captcha.value.trim().toUpperCase() !== 'JKT') {
            e.preventDefault();
            alert('Captcha salah. Masukkan kode verifikasi yang benar.');
            captcha.focus();
            return false;
        }

        const file = document.getElementById('proofPic');
        if (file && file.files && file.files[0]) {
            const f = file.files[0];
            const allowed = ['image/png', 'image/jpeg', 'image/jpg'];
            if (!allowed.includes(f.type)) { 
                e.preventDefault(); 
                alert('Tipe file harus PNG atau JPG/JPEG'); 
                return false; 
            }
            if (f.size > 2 * 1024 * 1024) { 
                e.preventDefault(); 
                alert('File terlalu besar. Maksimum 2MB'); 
                return false; 
            }
        }
    });

    // Initialize first step
    showStep(1);
});
</script>
@endsection
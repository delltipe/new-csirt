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

            <form action="{{ route('incidents.store') }}" method="POST" class="card">
                @csrf
                <div class="p-8">
                    <!-- Personal Information Section -->
                    <h3 class="text-2xl font-bold text-white mb-6 pb-4 border-b border-slate-700">Data Pribadi</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label for="reporter_name" class="block text-white font-semibold mb-2">Nama Lengkap *</label>
                            <input type="text" id="reporter_name" name="reporter_name" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-2 focus:outline-none focus:border-orange-500 transition" required>
                            @error('reporter_name') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="reporter_email" class="block text-white font-semibold mb-2">Email *</label>
                            <input type="email" id="reporter_email" name="reporter_email" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-2 focus:outline-none focus:border-orange-500 transition" required>
                            @error('reporter_email') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label for="reporter_phone" class="block text-white font-semibold mb-2">Nomor Telepon</label>
                            <input type="tel" id="reporter_phone" name="reporter_phone" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-2 focus:outline-none focus:border-orange-500 transition">
                        </div>

                        <div>
                            <label for="organization" class="block text-white font-semibold mb-2">Organisasi/Perusahaan</label>
                            <input type="text" id="organization" name="organization" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-2 focus:outline-none focus:border-orange-500 transition">
                        </div>
                    </div>

                    <!-- Incident Details Section -->
                    <h3 class="text-2xl font-bold text-white mb-6 pb-4 border-b border-slate-700 mt-8">Detail Insiden</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label for="incident_type" class="block text-white font-semibold mb-2">Tipe Insiden *</label>
                            <select id="incident_type" name="incident_type" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-2 focus:outline-none focus:border-orange-500 transition" required>
                                <option value="">-- Pilih Tipe Insiden --</option>
                                <option value="malware">Malware/Ransomware</option>
                                <option value="phishing">Phishing</option>
                                <option value="ddos">Serangan DDoS</option>
                                <option value="breach">Pelanggaran Data</option>
                                <option value="vulnerability">Kerentanan</option>
                                <option value="unauthorized_access">Akses Tidak Sah</option>
                                <option value="other">Lainnya</option>
                            </select>
                            @error('incident_type') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="severity" class="block text-white font-semibold mb-2">Tingkat Keparahan</label>
                            <select id="severity" name="severity" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-2 focus:outline-none focus:border-orange-500 transition">
                                <option value="">-- Pilih Tingkat --</option>
                                <option value="critical">Kritis</option>
                                <option value="high">Tinggi</option>
                                <option value="medium">Sedang</option>
                                <option value="low">Rendah</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-8">
                        <label for="incident_date" class="block text-white font-semibold mb-2">Tanggal Insiden</label>
                        <input type="datetime-local" id="incident_date" name="incident_date" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-2 focus:outline-none focus:border-orange-500 transition">
                    </div>

                    <div class="mb-8">
                        <label for="affected_systems" class="block text-white font-semibold mb-2">Sistem/Layanan yang Terdampak</label>
                        <textarea id="affected_systems" name="affected_systems" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-2 focus:outline-none focus:border-orange-500 transition" rows="3" placeholder="Sebutkan sistem atau layanan yang terdampak"></textarea>
                    </div>

                    <div class="mb-8">
                        <label for="description" class="block text-white font-semibold mb-2">Deskripsi Insiden *</label>
                        <textarea id="description" name="description" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-2 focus:outline-none focus:border-orange-500 transition" rows="5" required placeholder="Jelaskan insiden secara detail"></textarea>
                        @error('description') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-8">
                        <label for="actions_taken" class="block text-white font-semibold mb-2">Tindakan yang Sudah Diambil</label>
                        <textarea id="actions_taken" name="actions_taken" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-2 focus:outline-none focus:border-orange-500 transition" rows="3" placeholder="Jelaskan tindakan pemulihan atau mitigasi yang sudah dilakukan"></textarea>
                    </div>

                    <!-- Submit Section -->
                    <div class="flex gap-4">
                        <button type="submit" class="btn-report bg-orange-500 hover:bg-orange-600">
                            Kirim Laporan
                        </button>
                        <a href="{{ route('home') }}" class="px-6 py-2 border-2 border-slate-600 text-slate-300 hover:bg-slate-700 rounded transition font-semibold">
                            Batalkan
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

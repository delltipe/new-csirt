@extends('layouts.app')

@section('content')
<!-- Page Header -->
<section class="bg-gradient-to-r from-slate-900 to-slate-800 py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold text-white mb-2">Hubungi Jakarta CSIRT</h1>
        <p class="text-lg text-slate-300">Hubungi kami untuk pertanyaan, dukungan teknis, atau kemitraan</p>
    </div>
</section>

<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Contact Info -->
            <div>
                <h3 class="text-2xl font-bold text-white mb-8">Informasi Kontak</h3>
                
                <div class="space-y-6">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-10 w-10 rounded-md bg-orange-500 text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-white">Email</h4>
                            <p class="text-slate-400">jakarta.csirt@jakarta.go.id</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-10 w-10 rounded-md bg-orange-500 text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-white">Telepon</h4>
                            <p class="text-slate-400">(021) XXXX-XXXX</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-10 w-10 rounded-md bg-orange-500 text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-white">Hotline 24/7</h4>
                            <p class="text-slate-400">1110</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-10 w-10 rounded-md bg-orange-500 text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-white">Alamat</h4>
                            <p class="text-slate-400">Jakarta, Indonesia</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-2">
                <form action="{{ route('contact.store') }}" method="POST" class="card">
                    @csrf
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-white mb-6 pb-4 border-b border-slate-700">Kirim Pesan</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="name" class="block text-white font-semibold mb-2">Nama *</label>
                                <input type="text" id="name" name="name" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-2 focus:outline-none focus:border-orange-500 transition" required>
                                @error('name') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-white font-semibold mb-2">Email *</label>
                                <input type="email" id="email" name="email" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-2 focus:outline-none focus:border-orange-500 transition" required>
                                @error('email') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="phone" class="block text-white font-semibold mb-2">Nomor Telepon</label>
                                <input type="tel" id="phone" name="phone" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-2 focus:outline-none focus:border-orange-500 transition">
                            </div>

                            <div>
                                <label for="organization" class="block text-white font-semibold mb-2">Organisasi/Perusahaan</label>
                                <input type="text" id="organization" name="organization" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-2 focus:outline-none focus:border-orange-500 transition">
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="inquiry_type" class="block text-white font-semibold mb-2">Tipe Pertanyaan *</label>
                            <select id="inquiry_type" name="inquiry_type" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-2 focus:outline-none focus:border-orange-500 transition" required>
                                <option value="">-- Pilih Tipe Pertanyaan --</option>
                                <option value="general">Informasi Umum</option>
                                <option value="support">Dukungan Teknis</option>
                                <option value="partnership">Kemitraan</option>
                                <option value="media">Media</option>
                                <option value="other">Lainnya</option>
                            </select>
                            @error('inquiry_type') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-6">
                            <label for="subject" class="block text-white font-semibold mb-2">Subjek *</label>
                            <input type="text" id="subject" name="subject" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-2 focus:outline-none focus:border-orange-500 transition" required>
                            @error('subject') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-8">
                            <label for="message" class="block text-white font-semibold mb-2">Pesan *</label>
                            <textarea id="message" name="message" class="w-full bg-slate-700 text-white border border-slate-600 rounded px-4 py-2 focus:outline-none focus:border-orange-500 transition" rows="5" required placeholder="Ketik pesan Anda di sini..."></textarea>
                            @error('message') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex gap-4">
                            <button type="submit" class="btn-report bg-orange-500 hover:bg-orange-600">
                                Kirim Pesan
                            </button>
                            <a href="{{ route('home') }}" class="px-6 py-2 border-2 border-slate-600 text-slate-300 hover:bg-slate-700 rounded transition font-semibold">
                                Batalkan
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

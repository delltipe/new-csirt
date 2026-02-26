<!-- Footer -->
<footer class="bg-slate-950 border-t border-slate-800 mt-20">
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            <!-- About Section -->
            <div class="footer-section">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-orange-500 rounded flex items-center justify-center">
                        <span class="text-white font-bold">1</span>
                    </div>
                    <span class="text-white font-bold">Jakarta CSIRT</span>
                </div>
                <p class="text-slate-400 text-sm leading-relaxed">
                    Pemerintah Provinsi DKI Jakarta berkomitmen untuk melindungi infrastruktur kritis dan data digital masyarakat melalui layanan respons insiden siber yang terpercaya dan responsif.
                </p>
            </div>

            <!-- Quick Links -->
            <div class="footer-section">
                <h3 class="text-white font-semibold mb-4">Menu Cepat</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('home') }}" class="text-slate-400 hover:text-white transition">Home</a></li>
                    <li><a href="#" class="text-slate-400 hover:text-white transition">Profil</a></li>
                    <li><a href="{{ route('events.index') }}" class="text-slate-400 hover:text-white transition">Event</a></li>
                    <li><a href="{{ route('news.index') }}" class="text-slate-400 hover:text-white transition">Berita</a></li>
                </ul>
            </div>

            <!-- Publications -->
            <div class="footer-section">
                <h3 class="text-white font-semibold mb-4">Publikasi</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('warnings.index') }}" class="text-slate-400 hover:text-white transition">Warning Posts</a></li>
                    <li><a href="{{ route('infographics.index') }}" class="text-slate-400 hover:text-white transition">Infografis</a></li>
                    <li><a href="{{ route('laws.index') }}" class="text-slate-400 hover:text-white transition">Hukum & Regulasi</a></li>
                    <li><a href="{{ route('guides.index') }}" class="text-slate-400 hover:text-white transition">Panduan</a></li>
                </ul>
            </div>

            <!-- Contact & Stats -->
            <div class="footer-section">
                <h3 class="text-white font-semibold mb-4">Hubungi Kami</h3>
                <p class="text-slate-400 text-sm mb-4">
                    <strong>Email:</strong> jakarta.csirt@jakarta.go.id<br>
                    <strong>Telepon:</strong> (021) 1234-5678<br>
                    <strong>Hotline 24/7:</strong> 1110
                </p>
                <h3 class="text-white font-semibold mb-2">Statistik</h3>
                <p class="text-slate-400 text-sm">
                    <span class="accent-text font-bold">12,543</span> Pengunjung<br>
                    <span class="accent-text font-bold">157</span> Insiden Ditangani
                </p>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center">
            <p class="text-slate-500 text-sm mb-4 md:mb-0">
                &copy; 2026 Jakarta CSIRT - Pemerintah Provinsi DKI Jakarta. All rights reserved.
            </p>
            <div class="flex gap-6">
                <a href="#" class="text-slate-400 hover:text-white transition text-sm">Kebijakan Privasi</a>
                <a href="#" class="text-slate-400 hover:text-white transition text-sm">Syarat Penggunaan</a>
                <a href="#" class="text-slate-400 hover:text-white transition text-sm">Status Website</a>
            </div>
        </div>
    </div>
</footer>

<!-- Navigation Bar -->
<nav class="navbar" x-data="{ dropdownOpen: false, mobileOpen: false, mobileDropdownOpen: false }">
    <!-- Alpine.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <div class="container mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
            <!-- Logo & Brand -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-orange-500 rounded flex items-center justify-center">
                    <span class="text-white font-bold text-lg">1</span>
                </div>
                <span class="text-white font-bold hidden sm:inline">Jakarta CSIRT</span>
            </div>

            <!-- Search Bar (Desktop) -->
            <div class="hidden md:flex flex-1 mx-8 max-w-md">
                <div class="relative w-full">
                    <input 
                        type="text" 
                        placeholder="Cari..." 
                        class="w-full bg-slate-800 text-white placeholder-slate-400 py-2 px-4 rounded border border-slate-700 focus:outline-none focus:border-orange-500 transition"
                    >
                    <button class="absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Desktop Navigation Menu -->
            <div class="hidden lg:flex items-center gap-8">
                <a href="{{ route('home') }}" class="text-slate-300 hover:text-white transition">Home</a>
                <a href="#" class="text-slate-300 hover:text-white transition">Profil</a>
                <a href="{{ route('events.index') }}" class="text-slate-300 hover:text-white transition">Event</a>
                
                <!-- Publications Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @keydown.escape="open = false" class="text-slate-300 hover:text-white transition flex items-center gap-1 focus:outline-none">
                        Publikasi
                        <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute top-full left-0 mt-1 bg-slate-800 border border-slate-700 rounded-lg shadow-lg min-w-max z-50" style="display: none;" x-transition>
                        <a href="{{ route('warnings.index') }}" class="block px-4 py-3 text-slate-300 hover:text-white hover:bg-slate-700 transition">Warning Posts</a>
                        <a href="{{ route('infographics.index') }}" class="block px-4 py-3 text-slate-300 hover:text-white hover:bg-slate-700 transition">Infografis</a>
                        <a href="{{ route('laws.index') }}" class="block px-4 py-3 text-slate-300 hover:text-white hover:bg-slate-700 transition">Hukum & Regulasi</a>
                        <a href="{{ route('news.index') }}" class="block px-4 py-3 text-slate-300 hover:text-white hover:bg-slate-700 transition">Berita</a>
                        <a href="{{ route('guides.index') }}" class="block px-4 py-3 text-slate-300 hover:text-white hover:bg-slate-700 transition">Panduan</a>
                    </div>
                </div>

                <a href="{{ route('contact.create') }}" class="text-slate-300 hover:text-white transition">Kontak HQ</a>
            </div>

            <!-- Report Incident Button -->
            <a href="{{ route('incidents.create') }}" class="btn-report ml-4">
                Lapor Insiden Siber
            </a>

            <!-- Mobile Menu Button -->
            <button class="lg:hidden text-white" @click="mobileOpen = !mobileOpen">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileOpen" class="lg:hidden mt-4 pb-4 border-t border-slate-700 pt-4" x-transition>
            <div class="mb-4">
                <input 
                    type="text" 
                    placeholder="Cari..." 
                    class="w-full bg-slate-800 text-white placeholder-slate-400 py-2 px-4 rounded border border-slate-700 focus:outline-none focus:border-orange-500 transition"
                >
            </div>
            <div class="space-y-3">
                <a href="{{ route('home') }}" class="block text-slate-300 hover:text-white transition">Home</a>
                <a href="#" class="block text-slate-300 hover:text-white transition">Profil</a>
                <a href="{{ route('events.index') }}" class="block text-slate-300 hover:text-white transition">Event</a>
                <div class="ml-4">
                    <button @click="mobileDropdownOpen = !mobileDropdownOpen" class="w-full text-left text-slate-400 text-sm font-semibold flex items-center gap-2 focus:outline-none">
                        Publikasi
                        <svg class="w-4 h-4 transition-transform" :class="mobileDropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                        </svg>
                    </button>
                    <div x-show="mobileDropdownOpen" class="pl-4 mt-2 space-y-2" x-transition>
                        <a href="{{ route('warnings.index') }}" class="block text-slate-400 hover:text-white transition">Warning Posts</a>
                        <a href="{{ route('infographics.index') }}" class="block text-slate-400 hover:text-white transition">Infografis</a>
                        <a href="{{ route('laws.index') }}" class="block text-slate-400 hover:text-white transition">Hukum & Regulasi</a>
                        <a href="{{ route('news.index') }}" class="block text-slate-400 hover:text-white transition">Berita</a>
                        <a href="{{ route('guides.index') }}" class="block text-slate-400 hover:text-white transition">Panduan</a>
                    </div>
                </div>
                <a href="{{ route('contact.create') }}" class="block text-slate-300 hover:text-white transition">Kontak HQ</a>
            </div>
        </div>
    </div>
</nav>

<script>
</script>
</script>

{{-- Breadcrumb & Sub-Navigation for Security Awareness (SecAw) Subsystem --}}
<div class="secaw-nav-container">
    <div class="container">
        {{-- 1. Breadcrumb Navigation --}}
        <nav class="secaw-breadcrumb" aria-label="Jejak navigasi">
            <ol class="secaw-breadcrumb__list">
                <li><a href="{{ route('home') }}">Beranda</a></li>
                <li><i class="bi bi-chevron-right" aria-hidden="true"></i></li>
                @if(request()->routeIs('secaw.index'))
                    <li aria-current="page">Security Awareness (SecAw)</li>
                @else
                    <li><a href="{{ route('secaw.index') }}">Security Awareness (SecAw)</a></li>
                    <li><i class="bi bi-chevron-right" aria-hidden="true"></i></li>
                    <li aria-current="page">{{ $activeTitle ?? 'Modul' }}</li>
                @endif
            </ol>
        </nav>

        {{-- 2. Module Navigation Links --}}
        <nav class="secaw-subnav" aria-label="Navigasi Modul Security Awareness">
            <ul class="secaw-subnav__list">
                <li>
                    <a href="{{ route('secaw.index') }}"
                       class="secaw-subnav__link {{ request()->routeIs('secaw.index') ? 'active' : '' }}"
                       @if(request()->routeIs('secaw.index')) aria-current="page" @endif>
                        <i class="bi bi-speedometer2" aria-hidden="true"></i>
                        <span>Dasbor Utama</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('secaw.materi') }}"
                       class="secaw-subnav__link {{ request()->routeIs('secaw.materi') ? 'active' : '' }}"
                       @if(request()->routeIs('secaw.materi')) aria-current="page" @endif>
                        <i class="bi bi-file-earmark-text" aria-hidden="true"></i>
                        <span>Materi</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('secaw.infografis') }}"
                       class="secaw-subnav__link {{ request()->routeIs('secaw.infografis') ? 'active' : '' }}"
                       @if(request()->routeIs('secaw.infografis')) aria-current="page" @endif>
                        <i class="bi bi-images" aria-hidden="true"></i>
                        <span>Infografis</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('secaw.video') }}"
                       class="secaw-subnav__link {{ request()->routeIs('secaw.video') ? 'active' : '' }}"
                       @if(request()->routeIs('secaw.video')) aria-current="page" @endif>
                        <i class="bi bi-play-circle" aria-hidden="true"></i>
                        <span>Video</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('secaw.tools') }}"
                       class="secaw-subnav__link {{ request()->routeIs('secaw.tools') ? 'active' : '' }}"
                       @if(request()->routeIs('secaw.tools')) aria-current="page" @endif>
                        <i class="bi bi-tools" aria-hidden="true"></i>
                        <span>Tools Cybersecurity</span>
                    </a>
                </li>
            </ul>

            <div class="d-none d-md-flex align-items-center gap-2">
                <a href="https://csirt.jakarta.go.id/secaw/assets/landing-page/docs/Tutorial%20Dashboard%20Sec-Aw.pdf"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="secaw-subnav__ext"
                   title="Unduh Tutorial Dashboard SecAw Jakarta Barat (PDF Resmi)">
                    <i class="bi bi-book" aria-hidden="true"></i>
                    <span>Tutorial Dashboard SecAw Jakbar (PDF)</span>
                </a>
            </div>
        </nav>
    </div>
</div>

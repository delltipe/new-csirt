<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>{{ config('app.name', 'JakartaProv-CSIRT') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('csirt-main-logo.png') }}">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/accessibility-contrast.css') }}" rel="stylesheet">

    {{-- Apply saved accessibility classes before first paint to avoid FOUC
         (state kept in localStorage by js/accessibility.js). Inline head
         scripts run before the body is parsed/painted. --}}
    <script>
        (function () {
            try {
                var saved = localStorage.getItem('accessibilityState');
                if (!saved) return;
                var state = JSON.parse(saved);
                var root = document.documentElement.classList;

                var contrast = (typeof state.contrast === 'number')
                    ? state.contrast
                    : (state.contrast === 'high' ? 1 : state.contrast === 'dark' ? 2 : 0);
                if (contrast === 1) root.add('accessibility-contrast-high');
                else if (contrast === 2) root.add('accessibility-contrast-dark');
                else if (contrast === 3) root.add('accessibility-invert');

                if (state.grayscale) root.add('accessibility-grayscale');
                if (state.hideImages) root.add('accessibility-hide-images');
                if (state.readableFont) root.add('accessibility-readable-font');
                if (state.pauseAnimations) root.add('accessibility-pause-animations');
                if (state.largeCursor) root.add('accessibility-large-cursor');
                if (state.underlineLinks > 0) root.add('accessibility-underline-links');

                var align = (typeof state.textAlign === 'number') ? state.textAlign : 0;
                if (align === 1) root.add('accessibility-align-center');
                else if (align === 2) root.add('accessibility-align-right');
                else if (align === 3) root.add('accessibility-align-justify');
            } catch (e) {}
        })();
    </script>
</head>
<body>
    @include('components.navbar')
    
    {{-- Accessibility Widget --}}
    @include('components.accessibility')

    <main>
        @yield('content')
    </main>

    @include('components.footer')

    {{-- Logout confirmation — all account types (admin / bug hunter) --}}
    <div class="modal fade" id="logoutConfirmModal" tabindex="-1" aria-labelledby="logoutConfirmLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius:0; border:1px solid var(--border);">
                <div class="modal-header" style="border-bottom:1px solid var(--border); border-radius:0;">
                    <h5 class="modal-title" id="logoutConfirmLabel" style="font-family:var(--font-display); font-weight:800; letter-spacing:0.02em; text-transform:uppercase; color:var(--ink);">Konfirmasi Keluar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="font-size:14px; color:var(--ink);">
                    Yakin ingin keluar dari akun Anda?
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--border); border-radius:0;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius:0; font-family:var(--font-display); font-weight:700; letter-spacing:0.06em; text-transform:uppercase;">Tidak</button>
                    <button type="button" class="btn btn-primary" id="logoutConfirmYes" style="background:var(--navy); border:none; border-radius:0; font-family:var(--font-display); font-weight:800; letter-spacing:0.06em; text-transform:uppercase;">Ya</button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('js/accessibility.js') }}"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var modalEl = document.getElementById('logoutConfirmModal');
        if (!modalEl) return;
        var bsModal = new bootstrap.Modal(modalEl);
        var pendingForm = null;
        var confirmBtn = document.getElementById('logoutConfirmYes');
        // Intercept all logout forms (public /logout and /admin/logout)
        document.querySelectorAll('form[action*="logout"]').forEach(function (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                pendingForm = form;
                bsModal.show();
            });
        });
        if (confirmBtn) {
            confirmBtn.addEventListener('click', function () {
                if (pendingForm) pendingForm.submit();
                bsModal.hide();
            });
        }
        modalEl.addEventListener('hidden.bs.modal', function () { pendingForm = null; });
    });
    </script>
</body>
</html>

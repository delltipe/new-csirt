<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>{{ config('app.name', 'JakartaProv-CSIRT') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('csirt-main-logo.png') }}">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('js/accessibility.js') }}"></script>
</body>
</html>

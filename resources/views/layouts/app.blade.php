<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Jakarta CSIRT') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --primary: #1a1f2e;
            --secondary: #2d3748;
            --accent: #ff6b35;
            --accent-dark: #d64628;
            --text-light: #e2e8f0;
            --text-muted: #a0aec0;
        }

        body {
            @apply bg-slate-900 text-slate-100;
        }

        .navbar {
            @apply bg-slate-900 border-b border-slate-800 sticky top-0 z-50;
        }

        .btn-report {
            @apply bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded transition;
        }

        .section-title {
            @apply text-3xl md:text-4xl font-bold text-white mb-2;
        }

        .section-subtitle {
            @apply text-lg text-slate-400 mb-8;
        }

        .card {
            @apply bg-slate-800 rounded-lg border border-slate-700 overflow-hidden hover:border-slate-600 transition;
        }

        .accent-text {
            @apply text-orange-500;
        }

        .accent-border {
            @apply border-t-4 border-orange-500;
        }

        .dropdown-menu {
            @apply hidden absolute top-full left-0 mt-1 bg-slate-800 border border-slate-700 rounded-lg shadow-lg min-w-max;
        }

        .dropdown-toggle:hover .dropdown-menu {
            @apply block;
        }

        .footer-section {
            @apply mb-8 md:mb-0;
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <main>
        @yield('content')
    </main>

    @include('components.footer')
</body>
</html>

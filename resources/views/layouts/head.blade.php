@props(['pageTitle' => ''])

<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ $pageTitle }} - {{ config('app.name', 'Laravel') }}</title>

<link rel="apple-touch-icon" sizes="180x180" href="/public/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/public/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/public/favicon-16x16.png">

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
<link href="https://fonts.bunny.net/css?family=anton-sc:400" rel="stylesheet"/>

@vite(['resources/css/app.css', 'resources/js/app.js'])

<script data-navigate-once>
    window.applyTheme = () => {
        const theme = @js(auth()->user()?->preferred_theme ?? 'system');

        if (theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    }

    window.applyTheme();

    document.addEventListener('livewire:navigated', window.applyTheme);
</script>

@livewireStyles
@stack('styles')

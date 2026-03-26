<!DOCTYPE html>
<html lang="tr" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/light-mode-logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/light-mode-logo.png') }}">
    <title>@yield('title', $settings['site_title'] ?? 'GMSGARAGE')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">
    <meta name="description" content="@yield('description', $settings['site_description'] ?? 'GMSGARAGE - Premium ikinci el araçlar, garantili ve bakımlı araçlar. En iyi fiyat garantisi.')">
    <meta name="keywords" content="@yield('keywords', $settings['site_keywords'] ?? 'ikinci el araç, oto galeri, garantili araç, premium araç')">
    <meta name="author" content="{{ $settings['site_title'] ?? 'GMSGARAGE' }}">
    <link rel="canonical" href="@yield('canonical', url()->current())">
    
    <!-- Robots Meta Tag (Arama Motoru İndeksleme) -->
    <meta name="robots" content="{{ $settings['robots_index'] ?? 'index,follow' }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="@yield('og_url', url()->current())">
    <meta property="og:title" content="@yield('og_title', $settings['og_title'] ?? $settings['site_title'] ?? 'GMSGARAGE')">
    @php
        $metaDescription = $__env->hasSection('og_description')
            ? $__env->yieldContent('og_description')
            : ($__env->hasSection('description')
                ? $__env->yieldContent('description')
                : ($settings['site_description'] ?? ''));
        $defaultOgImage = !empty($settings['og_image']) 
            ? asset('storage/' . $settings['og_image']) 
            : asset('images/light-mode-logo.png');
    @endphp
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:image" content="@yield('og_image', $defaultOgImage)">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="tr_TR">
    <meta property="og:site_name" content="{{ $settings['site_title'] ?? 'GMSGARAGE' }}">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="@yield('og_url', url()->current())">
    <meta name="twitter:title" content="@yield('og_title', $settings['og_title'] ?? $settings['site_title'] ?? 'GMSGARAGE')">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="@yield('og_image', $defaultOgImage)">
    
    @stack('meta')
    
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/legal-modal.js'])
    
    @stack('styles')
    
    <!-- Google Analytics (GA4) -->
    @if(!empty($settings['google_analytics_id']))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $settings['google_analytics_id'] }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $settings['google_analytics_id'] }}');
    </script>
    @endif
</head>
<body class="bg-gray-50 dark:bg-[#1e1e1e] transition-colors duration-200">
    @include('components.header')
    
    <main>
        @yield('content')
    </main>
    
    @include('components.footer')
    
    <!-- WhatsApp Sabit Butonu -->
    @if(!empty($settings['contact_whatsapp']))
    @php
        $waRaw = preg_replace('/[^0-9]/', '', $settings['contact_whatsapp']);
        $waNumber = str_starts_with($waRaw, '0') ? '90' . substr($waRaw, 1) : $waRaw;
    @endphp
    <a href="https://wa.me/{{ $waNumber }}?text=Merhaba, araçlarınız hakkında bilgi almak istiyorum." 
       target="_blank" 
       class="fixed bottom-6 right-6 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white p-4 rounded-full shadow-2xl z-50 transition-all duration-500 hover:scale-110 hover:rotate-12 group">
        <svg class="w-6 h-6 transition-transform duration-300 group-hover:scale-110" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
        </svg>
        <span class="absolute -top-2 -right-2 w-4 h-4 bg-red-500 rounded-full animate-pulse"></span>
    </a>
    @endif

    <!-- ===== KAMPANYA POP-UP ===== -->
    @stack('scripts')
</body>
</html>

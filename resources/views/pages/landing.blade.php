<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <title>{{ $settings['site_title'] ?? 'GMSGARAGE' }}</title>
    <meta name="description" content="{{ ($settings['site_title'] ?? 'GMSGARAGE') }} - Premium ikinci el araçlar, garantili ve bakımlı araçlar. En iyi fiyat garantisi.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('landing') }}">
    <meta property="og:title" content="{{ $settings['site_title'] ?? 'GMSGARAGE' }} - Premium İkinci El Araçlar">
    <meta property="og:description" content="{{ ($settings['site_title'] ?? 'GMSGARAGE') }} - Premium ikinci el araçlar, garantili ve bakımlı araçlar. En iyi fiyat garantisi.">
    <meta property="og:image" content="{{ asset('images/light-mode-logo.png') }}">
    <link rel="canonical" href="{{ route('landing') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/legal-modal.js'])
    
    <style>
        html {
            scroll-behavior: smooth;
        }
        
        /* Sticky Header Styles */
        .landing-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }
        
        .landing-header.scrolled {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .dark .landing-header.scrolled {
            background: rgba(30, 30, 30, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
        }
        
        /* Hero Animations */
        .hero-fade-in {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 0.8s ease-out forwards;
        }
        
        .hero-fade-in-delay-1 {
            animation-delay: 0.2s;
        }
        
        .hero-fade-in-delay-2 {
            animation-delay: 0.4s;
        }
        
        .hero-fade-in-delay-3 {
            animation-delay: 0.6s;
        }
        
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Reveal Animation */
        .reveal {
            opacity: 0;
            transform: translateY(50px);
            transition: all 0.8s ease-out;
        }
        
        .reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Gallery Hover Effect */
        .gallery-item {
            transition: transform 0.3s ease;
        }
        
        .gallery-item:hover {
            transform: scale(1.05);
        }
        
        /* Lightbox */
        .lightbox {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.95);
            cursor: pointer;
        }
        
        .lightbox.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .lightbox-content {
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
        }
        
        .lightbox-close {
            position: absolute;
            top: 20px;
            right: 40px;
            color: white;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
            z-index: 10000;
        }
    </style>
</head>
<body class="bg-white dark:bg-[#1e1e1e] transition-colors duration-200">
    <!-- Sticky Header -->
    <header id="header" class="landing-header bg-white dark:bg-[#1e1e1e]/95 backdrop-blur-modern transition-colors duration-200">
        <nav class="container-custom">
            <div class="flex items-center justify-between" style="height: 100px;">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center group" style="height: 90px;">
                    <!-- Light Mode Logo -->
                    <img src="{{ asset('images/light-mode-logo.png') }}?v=8" alt="{{ ($settings['site_title'] ?? 'GMSGARAGE') }} Logo" class="w-auto transition-transform duration-300 group-hover:scale-105 dark:hidden" style="height: 80px;" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <!-- Dark Mode Logo -->
                    <img src="{{ asset('images/dark-mode-logo.png') }}?v=8" alt="{{ ($settings['site_title'] ?? 'GMSGARAGE') }} Logo" class="w-auto transition-transform duration-300 group-hover:scale-105 hidden dark:block" style="height: 80px;" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <div class="text-3xl font-bold text-primary-600" style="display:none;">{{ $settings['site_title'] ?? 'GMSGARAGE' }}</div>
                </a>
                
                <!-- Desktop Navigation -->
                <div class="hidden lg:flex items-center space-x-6">
                    <a href="#hero" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium transition-colors">Anasayfa</a>
                    <a href="#features" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium transition-colors">Özellikler</a>
                    <a href="#gallery" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium transition-colors">Galeri</a>
                    <a href="#contact" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium transition-colors">İletişim</a>
                    <a href="{{ route('vehicles.index') }}" class="btn btn-primary">Hemen Ara</a>
                </div>
                
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="lg:hidden text-gray-700 dark:text-gray-300" onclick="toggleMobileMenu()">
                    <svg id="menu-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg id="close-icon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden lg:hidden pb-4">
                <div class="flex flex-col space-y-2 pt-4 border-t border-gray-200 dark:border-[#333333]">
                    <a href="#hero" class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium transition-colors">Anasayfa</a>
                    <a href="#features" class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium transition-colors">Özellikler</a>
                    <a href="#gallery" class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium transition-colors">Galeri</a>
                    <a href="#contact" class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium transition-colors">İletişim</a>
                    <a href="{{ route('vehicles.index') }}" class="btn btn-primary mx-4 mt-2">Hemen Ara</a>
                </div>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section id="hero" class="min-h-screen flex items-center bg-gradient-to-br from-gray-50 to-gray-100 dark:from-[#1e1e1e] dark:to-[#252525] pt-20 transition-colors duration-200">
        <div class="container-custom py-20">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Left Column -->
                <div class="space-y-8">
                    <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-gray-900 dark:text-gray-100 leading-tight hero-fade-in">
                        Premium Araçlar<br>
                        <span class="text-gradient bg-gradient-to-r from-primary-600 to-primary-800 bg-clip-text text-transparent">Güvenilir Alışveriş</span>
                    </h1>
                    <p class="text-xl text-gray-600 dark:text-gray-300 leading-relaxed hero-fade-in hero-fade-in-delay-1">
                        AI destekli araç değerleme ve garantili alışveriş deneyimi. Premium ikinci el araçlar için güvenilir adresiniz.
                    </p>
                    <div class="flex flex-wrap gap-4 hero-fade-in hero-fade-in-delay-2">
                        <a href="{{ route('vehicles.index') }}" class="btn btn-primary text-lg px-8 py-4">
                            Araçları İncele
                        </a>
                        <a href="#contact" class="btn btn-outline text-lg px-8 py-4">
                            İletişime Geç
                        </a>
                    </div>
                    <div class="flex flex-wrap gap-6 pt-4 hero-fade-in hero-fade-in-delay-3">
                        <div class="flex items-center gap-2">
                            <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <span class="text-gray-700 dark:text-gray-300 font-medium">Garantili</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900/20 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <span class="text-gray-700 dark:text-gray-300 font-medium">Hızlı İşlem</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900/20 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="text-gray-700 dark:text-gray-300 font-medium">En İyi Fiyat</span>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column -->
                <div class="relative">
                    <div class="relative z-10">
                        <img src="https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=800&h=600&fit=crop" 
                             alt="Premium Araç" 
                             class="w-full h-auto rounded-2xl shadow-2xl">
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-br from-primary-200/20 to-primary-600/20 rounded-2xl blur-3xl -z-10 transform scale-110"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white dark:bg-[#1e1e1e] transition-colors duration-200">
        <div class="container-custom">
            <div class="text-center mb-16 reveal">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-gray-100 mb-4">Neden GMSGARAGE?</h2>
                <p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">Premium araç alışverişinde güvenilir partneriniz</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="card p-8 text-center reveal hover-lift bg-white dark:bg-[#252525] border border-gray-100 dark:border-[#333333] transition-colors duration-200">
                    <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900/20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-3">Garantili Araçlar</h3>
                    <p class="text-gray-600 dark:text-gray-400">Tüm araçlarımız detaylı kontrol edilmiş ve garantilidir.</p>
                </div>
                
                <div class="card p-8 text-center reveal hover-lift bg-white dark:bg-[#252525] border border-gray-100 dark:border-[#333333] transition-colors duration-200">
                    <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900/20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-3">Hızlı İşlem</h3>
                    <p class="text-gray-600 dark:text-gray-400">24 saat içinde teklif alın, hızlı ve kolay alışveriş.</p>
                </div>
                
                <div class="card p-8 text-center reveal hover-lift bg-white dark:bg-[#252525] border border-gray-100 dark:border-[#333333] transition-colors duration-200">
                    <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900/20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-3">En İyi Fiyat</h3>
                    <p class="text-gray-600 dark:text-gray-400">Piyasadaki en uygun fiyatları sunuyoruz.</p>
                </div>
                
                <div class="card p-8 text-center reveal hover-lift bg-white dark:bg-[#252525] border border-gray-100 dark:border-[#333333] transition-colors duration-200">
                    <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900/20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-3">AI Destekli</h3>
                    <p class="text-gray-600 dark:text-gray-400">Yapay zeka ile doğru araç değerleme.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section id="benefits" class="py-20 bg-gray-50 dark:bg-[#252525] transition-colors duration-200">
        <div class="container-custom">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Left: Text -->
                <div class="reveal">
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-gray-100 mb-6">Premium Deneyim</h2>
                    <p class="text-xl text-gray-600 dark:text-gray-400 mb-8">GMSGARAGE ile araç alışverişi artık çok daha kolay ve güvenli.</p>
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-6 h-6 bg-primary-600 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">Detaylı Araç Kontrolü</h3>
                                <p class="text-gray-600 dark:text-gray-400">Her araç uzman ekibimiz tarafından detaylı kontrol edilir.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-6 h-6 bg-primary-600 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">Şeffaf İşlemler</h3>
                                <p class="text-gray-600 dark:text-gray-400">Tüm işlemler şeffaf ve güvenli bir şekilde yürütülür.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-6 h-6 bg-primary-600 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">7/24 Destek</h3>
                                <p class="text-gray-600 dark:text-gray-400">Müşteri destek ekibimiz her zaman yanınızda.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right: Image -->
                <div class="reveal">
                    <img src="https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=800&h=600&fit=crop" 
                         alt="Premium Araç" 
                         class="w-full h-auto rounded-2xl shadow-xl">
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section id="gallery" class="py-20 bg-white dark:bg-[#1e1e1e] transition-colors duration-200">
        <div class="container-custom">
            <div class="text-center mb-16 reveal">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-gray-100 mb-4">Araç Galerimiz</h2>
                <p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">Premium araç koleksiyonumuzdan seçmeler</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    $galleryImages = [
                        'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=800&h=600&fit=crop',
                        'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?w=800&h=600&fit=crop',
                        'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=800&h=600&fit=crop',
                        'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=800&h=600&fit=crop',
                        'https://images.unsplash.com/photo-1502877338535-766e1452684a?w=800&h=600&fit=crop',
                        'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=800&h=600&fit=crop',
                    ];
                @endphp
                @foreach($galleryImages as $index => $img)
                    <div class="gallery-item reveal cursor-pointer overflow-hidden rounded-xl" onclick="openLightbox({{ $index }})">
                        <img src="{{ $img }}" 
                             alt="Araç {{ $index + 1 }}" 
                             class="w-full h-64 object-cover">
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Trust Section -->
    <section id="trust" class="py-20 bg-gray-50 dark:bg-[#252525] transition-colors duration-200">
        <div class="container-custom">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="card p-8 text-center reveal bg-white dark:bg-[#2a2a2a] border border-gray-100 dark:border-[#333333] transition-colors duration-200">
                    <div class="text-5xl font-bold text-primary-600 dark:text-primary-400 mb-2">500+</div>
                    <div class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">Araç</div>
                    <p class="text-gray-600 dark:text-gray-400">Geniş araç yelpazesi</p>
                </div>
                <div class="card p-8 text-center reveal bg-white dark:bg-[#2a2a2a] border border-gray-100 dark:border-[#333333] transition-colors duration-200">
                    <div class="text-5xl font-bold text-primary-600 dark:text-primary-400 mb-2">98%</div>
                    <div class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">Memnuniyet</div>
                    <p class="text-gray-600 dark:text-gray-400">Müşteri memnuniyet oranı</p>
                </div>
                <div class="card p-8 text-center reveal bg-white dark:bg-[#2a2a2a] border border-gray-100 dark:border-[#333333] transition-colors duration-200">
                    <div class="text-5xl font-bold text-primary-600 dark:text-primary-400 mb-2">24</div>
                    <div class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">Saat</div>
                    <p class="text-gray-600 dark:text-gray-400">İçinde teklif garantisi</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-white dark:bg-[#1e1e1e] transition-colors duration-200">
        <div class="container-custom">
            <div class="text-center mb-16 reveal">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-gray-100 mb-4">İletişime Geçin</h2>
                <p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">Sorularınız için bize ulaşın</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <div class="card p-8 text-center reveal bg-white dark:bg-[#252525] border border-gray-100 dark:border-[#333333] transition-colors duration-200">
                    <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900/20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-2">Telefon</h3>
                    <p class="text-primary-600 dark:text-primary-400 font-semibold text-lg">444 30 11</p>
                </div>
                
                <div class="card p-8 text-center reveal bg-white dark:bg-[#252525] border border-gray-100 dark:border-[#333333] transition-colors duration-200">
                    <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900/20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-2">E-posta</h3>
                    <p class="text-primary-600 dark:text-primary-400 font-semibold text-lg">info@gmsgarage.com</p>
                </div>
                
                <div class="card p-8 text-center reveal bg-white dark:bg-[#252525] border border-gray-100 dark:border-[#333333] transition-colors duration-200">
                    <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900/20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-2">Adres</h3>
                    <p class="text-gray-600 dark:text-gray-400">Çobançeşme Mahallesi<br>Kımız Sokağı No: 46<br>34196 Bahçelievler/İstanbul</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA Section -->
    <section id="cta" class="py-20 bg-gradient-to-r from-primary-600 to-primary-800 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255,255,255,0.05) 10px, rgba(255,255,255,0.05) 20px);"></div>
        </div>
        <div class="container-custom relative z-10">
            <div class="text-center max-w-3xl mx-auto reveal">
                <h2 class="text-4xl md:text-5xl font-bold mb-6">Hayalinizdeki Aracı Bulun</h2>
                <p class="text-xl text-white/90 mb-8">Premium araç koleksiyonumuzdan size uygun olanı seçin ve hemen iletişime geçin.</p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('vehicles.index') }}" class="bg-white text-primary-600 hover:bg-gray-100 px-8 py-4 rounded-xl font-bold text-lg transition-all duration-300 shadow-lg hover:shadow-xl">
                        Araçları İncele
                    </a>
                    <a href="#contact" class="border-2 border-white text-white hover:bg-white hover:text-primary-600 px-8 py-4 rounded-xl font-bold text-lg transition-all duration-300">
                        İletişime Geç
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="footer" class="bg-[#1e1e1e] text-white">
        @include('components.footer')
    </footer>

    <!-- Lightbox -->
    <div id="lightbox" class="lightbox" onclick="closeLightbox()">
        <span class="lightbox-close" onclick="closeLightbox()">&times;</span>
        <img id="lightbox-image" class="lightbox-content" src="" alt="Gallery Image" onclick="event.stopPropagation()">
    </div>

    <script>
        // Gallery images
        const galleryImages = @json($galleryImages);
        
        // Sticky header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.getElementById('header');
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Intersection Observer for reveal animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                }
            });
        }, observerOptions);
        
        document.querySelectorAll('.reveal').forEach(el => {
            observer.observe(el);
        });
        
        // Lightbox functions
        function openLightbox(index) {
            const lightbox = document.getElementById('lightbox');
            const lightboxImage = document.getElementById('lightbox-image');
            if (lightbox && lightboxImage && galleryImages[index]) {
                lightboxImage.src = galleryImages[index];
                lightbox.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }
        
        function closeLightbox() {
            const lightbox = document.getElementById('lightbox');
            if (lightbox) {
                lightbox.classList.remove('active');
                document.body.style.overflow = '';
            }
        }
        
        // Close lightbox on ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLightbox();
            }
        });
        
        // Mobile menu toggle
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            const menuIcon = document.getElementById('menu-icon');
            const closeIcon = document.getElementById('close-icon');
            
            menu.classList.toggle('hidden');
            menuIcon.classList.toggle('hidden');
            closeIcon.classList.toggle('hidden');
        }
        
        // Close mobile menu when clicking on a link
        document.querySelectorAll('#mobile-menu a').forEach(link => {
            link.addEventListener('click', function() {
                toggleMobileMenu();
            });
        });
    </script>
</body>
</html>

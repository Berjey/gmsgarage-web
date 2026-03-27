@extends('layouts.app')

@section('title', 'Hakkımızda - ' . ($settings['site_title'] ?? 'GMSGARAGE'))
@section('description', ($settings['site_title'] ?? 'GMSGARAGE') . ' hakkında bilgiler. Premium ikinci el araç sektöründe güvenilir hizmet.')

@push('styles')
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
    
    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    @keyframes float {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-10px);
        }
    }
    
    @keyframes gradient {
        0% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
        100% {
            background-position: 0% 50%;
        }
    }
    
    @keyframes patternMove {
        0% {
            transform: translate(0, 0);
        }
        100% {
            transform: translate(40px, 40px);
        }
    }
    
    @keyframes shimmer {
        0% {
            background-position: -1000px 0;
        }
        100% {
            background-position: 1000px 0;
        }
    }
    
    @keyframes glow {
        0%, 100% {
            box-shadow: 0 0 20px rgba(220, 38, 38, 0.3);
        }
        50% {
            box-shadow: 0 0 40px rgba(220, 38, 38, 0.6);
        }
    }
    
    .animate-fade-in-up {
        animation: fadeInUp 0.8s ease-out forwards;
    }
    
    .animate-fade-in {
        animation: fadeIn 1s ease-out forwards;
    }
    
    .animate-slide-in-left {
        animation: slideInLeft 0.8s ease-out forwards;
    }
    
    .animate-slide-in-right {
        animation: slideInRight 0.8s ease-out forwards;
    }
    
    .animate-scale-in {
        animation: scaleIn 0.6s ease-out forwards;
    }
    
    .animate-float {
        animation: float 3s ease-in-out infinite;
    }
    
    .gradient-animated {
        background-size: 200% 200%;
        animation: gradient 8s ease infinite;
    }
    
    .shimmer-effect {
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        background-size: 1000px 100%;
        animation: shimmer 3s infinite;
    }
    
    .glow-effect {
        animation: glow 2s ease-in-out infinite;
    }
    
    /* Diğer sayfalarla uyumlu arka plan renkleri */
    .section-gradient-1 {
        background: white;
    }
    
    .section-gradient-2 {
        background: #f9fafb; /* bg-gray-50 */
    }
    
    .section-gradient-3 {
        background: white;
    }
    
    .section-gradient-4 {
        background: #f9fafb; /* bg-gray-50 */
    }
    
    .dark .section-gradient-1,
    .dark .section-gradient-2,
    .dark .section-gradient-3,
    .dark .section-gradient-4 {
        background: #1e1e1e;
    }
    
    .delay-100 {
        animation-delay: 0.1s;
    }
    
    .delay-200 {
        animation-delay: 0.2s;
    }
    
    .delay-300 {
        animation-delay: 0.3s;
    }
    
    .delay-400 {
        animation-delay: 0.4s;
    }
    
    .delay-500 {
        animation-delay: 0.5s;
    }
    
    .hover-lift {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .hover-lift:hover {
        transform: translateY(-10px) scale(1.03);
        box-shadow: 0 25px 30px -5px rgba(0, 0, 0, 0.2), 0 15px 15px -5px rgba(0, 0, 0, 0.15);
    }
    
    /* Icon animations */
    .group:hover .w-16,
    .group:hover .w-20 {
        animation: iconBounce 0.6s ease-in-out;
    }
    
    @keyframes iconBounce {
        0%, 100% {
            transform: scale(1) rotate(0deg);
        }
        50% {
            transform: scale(1.15) rotate(5deg);
        }
    }
    
    /* Smooth transitions */
    .transition-all {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    }
</style>

@endpush

@section('content')
    <!-- Page Header (Araçlar sayfasındaki gibi) -->
    <section class="bg-gradient-to-br from-primary-800 via-primary-700 to-primary-900 dark:from-primary-900 dark:via-primary-800 dark:to-primary-900 text-white py-16 relative overflow-hidden">
        <!-- Animated Pattern Background -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px; animation: patternMove 20s linear infinite;"></div>
        </div>
        <!-- Floating Gradient Orbs -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-20 left-10 w-96 h-96 bg-white/5 rounded-full mix-blend-overlay filter blur-3xl animate-float"></div>
            <div class="absolute top-40 right-10 w-96 h-96 bg-white/5 rounded-full mix-blend-overlay filter blur-3xl animate-float delay-300"></div>
            <div class="absolute -bottom-8 left-1/2 w-96 h-96 bg-white/5 rounded-full mix-blend-overlay filter blur-3xl animate-float delay-500"></div>
        </div>
        
        <div class="container-custom relative z-10">
            <!-- Breadcrumb Navigation -->
            <nav class="flex items-center space-x-2 text-sm mb-4">
                <a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition-colors">Anasayfa</a>
                <span class="text-gray-400">/</span>
                <span class="text-white font-semibold">Hakkımızda</span>
            </nav>
            <!-- Main Title -->
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-3">Hakkımızda</h1>
            <p class="text-xl md:text-2xl text-gray-200">Güvenilir, şeffaf ve premium araç deneyimi</p>
        </div>
    </section>

    <!-- Biz Kimiz? -->
    <section class="section-padding section-gradient-1">
        <div class="container-custom">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-gray-100 mb-4">Biz Kimiz?</h2>
                    <div class="w-24 h-1 bg-gradient-to-r from-primary-600 to-primary-800 mx-auto mb-8 rounded-full"></div>
                </div>
                <div class="prose prose-lg max-w-none text-gray-700 dark:text-gray-300 leading-relaxed text-center">
                    <p class="text-lg mb-6 dark:text-gray-300">
                        GMSGARAGE, kaliteli ve güvenilir araçları doğru fiyatlandırma ve şeffaf süreç anlayışıyla müşterileriyle buluşturan modern bir oto galerisidir. Kurulduğumuz günden bu yana hedefimiz, araç alım-satım sürecini karmaşık olmaktan çıkarıp güvenli ve keyifli bir deneyime dönüştürmektir.
                    </p>
                    <p class="text-lg dark:text-gray-300">
                        Tüm araçlarımız detaylı kontrol ve ekspertiz sürecinden geçmektedir. Bu sayede müşterilerimize garantili, bakımlı ve güvenilir araçlar sunuyoruz. Sektördeki deneyimimiz ve müşteri memnuniyeti odaklı yaklaşımımız ile binlerce mutlu müşteriye hizmet verdik.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Neden GMSGARAGE? -->
    <section class="section-padding section-gradient-2">
        <div class="container-custom">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-gray-100 mb-4">Neden GMSGARAGE?</h2>
                    <div class="w-24 h-1 bg-gradient-to-r from-primary-600 to-primary-800 mx-auto mb-4 rounded-full"></div>
                    <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                        Müşterilerimize sunduğumuz değerler ve hizmet anlayışımız
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Güvenilir Alım & Satım -->
                    <div class="bg-white dark:bg-[#252525] rounded-2xl p-8 shadow-md hover-lift border-2 border-red-200 dark:border-red-900/30 group relative overflow-hidden transition-colors duration-200">
                        <div class="absolute inset-0 shimmer-effect opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-red-700 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 glow-effect relative z-10">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-3 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors relative z-10">Güvenilir Alım & Satım</h3>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed relative z-10">
                            Tüm işlemlerimizde şeffaflık ve dürüstlük ilkelerimizle hareket ediyoruz. Müşterilerimizin güvenini kazanmak en öncelikli hedefimizdir.
                        </p>
                    </div>
                    
                    <!-- Şeffaf Bilgilendirme -->
                    <div class="bg-white dark:bg-[#252525] rounded-2xl p-8 shadow-md dark:shadow-xl dark:border dark:border-[#333333] hover-lift border-2 border-primary-200 dark:border-primary-800/50 group relative overflow-hidden transition-colors duration-200">
                        <div class="absolute inset-0 shimmer-effect opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-700 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 glow-effect relative z-10">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-3 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors relative z-10">Şeffaf Bilgilendirme</h3>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed relative z-10">
                            Araçlarımızın tüm teknik detayları, geçmişi ve durumu hakkında tam bilgilendirme yapıyoruz. Gizli kusur veya eksik bilgi yoktur.
                        </p>
                    </div>
                    
                    <!-- Seçkin Araç Portföyü -->
                    <div class="bg-white dark:bg-[#252525] rounded-2xl p-8 shadow-md dark:shadow-xl dark:border dark:border-[#333333] hover-lift border-2 border-primary-200 dark:border-primary-800/50 group relative overflow-hidden transition-colors duration-200">
                        <div class="absolute inset-0 shimmer-effect opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-700 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 glow-effect relative z-10">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-3 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors relative z-10">Seçkin Araç Portföyü</h3>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed relative z-10">
                            Sunduğumuz tüm araçlar detaylı kontrol ve ekspertiz sürecinden geçmektedir. Kalite standartlarımızdan ödün vermiyoruz.
                        </p>
                    </div>
                    
                    <!-- Müşteri Odaklı Hizmet -->
                    <div class="bg-white dark:bg-[#252525] rounded-2xl p-8 shadow-md dark:shadow-xl dark:border dark:border-[#333333] hover-lift border-2 border-primary-200 dark:border-primary-800/50 group relative overflow-hidden transition-colors duration-200">
                        <div class="absolute inset-0 shimmer-effect opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-700 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 glow-effect relative z-10">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-3 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors relative z-10">Müşteri Odaklı Hizmet</h3>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed relative z-10">
                            Müşterilerimizin memnuniyeti bizim için her şeyden önemlidir. Satış öncesi ve sonrası tüm süreçlerde yanlarında oluyoruz.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Değerlerimiz / Yaklaşımımız -->
    <section class="section-padding section-gradient-3">
        <div class="container-custom">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-gray-100 mb-4">Değerlerimiz</h2>
                    <div class="w-24 h-1 bg-gradient-to-r from-primary-600 to-primary-800 mx-auto mb-4 rounded-full"></div>
                    <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                        İş yapış şeklimizi belirleyen temel değerlerimiz
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Şeffaflık -->
                    <div class="text-center group">
                        <div class="w-20 h-20 bg-gradient-to-br from-primary-500 to-primary-700 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 glow-effect">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-3 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">Şeffaflık</h3>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                            Tüm bilgileri açık ve net bir şekilde paylaşıyoruz. Gizli maliyet veya sürpriz yoktur.
                        </p>
                    </div>
                    
                    <!-- Güven -->
                    <div class="text-center group">
                        <div class="w-20 h-20 bg-gradient-to-br from-primary-500 to-primary-700 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 glow-effect">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-3 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">Güven</h3>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                            Sözümüzü tutuyor, verdiğimiz taahhütleri yerine getiriyoruz. Güvenilirlik markamızın temelidir.
                        </p>
                    </div>
                    
                    <!-- Kalite -->
                    <div class="text-center group">
                        <div class="w-20 h-20 bg-gradient-to-br from-primary-500 to-primary-700 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 glow-effect">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-3 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">Kalite</h3>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                            Her araç detaylı kontrol ve ekspertiz sürecinden geçer. Kalite standartlarımızdan ödün vermiyoruz.
                        </p>
                    </div>
                    
                    <!-- Süreklilik -->
                    <div class="text-center group">
                        <div class="w-20 h-20 bg-gradient-to-br from-primary-500 to-primary-700 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 glow-effect">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-3 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">Süreklilik</h3>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                            Satış sonrası da yanınızdayız. Müşteri ilişkilerimiz tek seferlik değil, uzun vadeli bir ortaklıktır.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Vizyon & Misyon -->
    <section class="section-padding section-gradient-4">
        <div class="container-custom">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-gray-100 mb-4">Vizyon & Misyon</h2>
                    <div class="w-24 h-1 bg-gradient-to-r from-primary-600 to-primary-800 mx-auto rounded-full"></div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Vizyon -->
                    <div class="bg-white dark:bg-[#252525] rounded-2xl p-8 md:p-10 shadow-md dark:shadow-xl dark:border dark:border-[#333333] border border-gray-100 hover-lift group transition-colors duration-200">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-16 h-16 bg-gradient-to-br from-primary-100 to-primary-200 dark:from-primary-900/30 dark:to-primary-800/30 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                                <svg class="w-8 h-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">Vizyonumuz</h3>
                        </div>
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed text-lg">
                            Türkiye'de araç alım-satımında güven denildiğinde akla gelen ilk markalardan biri olmak.
                        </p>
                    </div>
                    
                    <!-- Misyon -->
                    <div class="bg-white dark:bg-[#252525] rounded-2xl p-8 md:p-10 shadow-md dark:shadow-xl dark:border dark:border-[#333333] border border-gray-100 hover-lift group transition-colors duration-200">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-16 h-16 bg-gradient-to-br from-primary-100 to-primary-200 dark:from-primary-900/30 dark:to-primary-800/30 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                                <svg class="w-8 h-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">Misyonumuz</h3>
                        </div>
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed text-lg">
                            Müşterilerimize her zaman doğru bilgi, adil fiyat ve güvenli bir satın alma deneyimi sunmak.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Kapanış / CTA -->
    <section class="section-padding bg-gradient-to-br from-primary-600 to-primary-800 text-white relative overflow-hidden">
        <!-- Animated Background -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-0 left-0 w-96 h-96 bg-white/10 rounded-full filter blur-3xl animate-float"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-white/10 rounded-full filter blur-3xl animate-float delay-300"></div>
        </div>
        
        <div class="container-custom relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-6">
                    Araçlarımızı İnceleyin
                </h2>
                <p class="text-lg text-white/90 mb-8 max-w-2xl mx-auto">
                    Geniş araç yelpazemizden size en uygun olanı bulun. Tüm sorularınız için bizimle iletişime geçmekten çekinmeyin.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('vehicles.index') }}" 
                       class="inline-flex items-center justify-center px-8 py-4 bg-white text-primary-600 hover:bg-gray-50 font-semibold rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105 transform">
                        <span>Araçlarımızı Görüntüle</span>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    <a href="{{ route('contact') }}" 
                       class="inline-flex items-center justify-center px-8 py-4 border-2 border-white text-white hover:bg-white/10 font-semibold rounded-xl transition-all duration-300 hover:scale-105 transform">
                        <span>İletişime Geçin</span>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

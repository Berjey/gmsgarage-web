@extends('layouts.app')

@section('title', $settings['site_title'] ?? 'GMSGARAGE')
@section('description', $settings['site_description'] ?? 'Premium ikinci el araçlar, garantili ve bakımlı araçlar. En iyi fiyat garantisi ile hizmetinizdeyiz.')
@section('keywords', $settings['site_keywords'] ?? 'ikinci el araç, oto galeri, garantili araç, premium araç, araç al, araç sat')
@section('og_title', ($settings['site_title'] ?? 'GMSGARAGE') . ' - Premium İkinci El Araçlar')
@section('og_description', $settings['site_description'] ?? 'Premium ikinci el araçlar, garantili ve bakımlı araçlar. En iyi fiyat garantisi ile hizmetinizdeyiz.')
@section('og_url', route('home'))

@push('styles')
<style>
    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
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
    
    @keyframes sloganSlide {
        from {
            opacity: 0;
            transform: translateX(50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .trigger-animation {
        animation: none !important;
        opacity: 0;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
    
    .animate-slide-in-left {
        animation: slideInLeft 0.6s ease-out;
    }
    
    .animate-slide-in-right {
        animation: slideInRight 0.6s ease-out;
    }
    
    .animate-fade-in-up {
        animation: fadeInUp 0.8s ease-out;
    }
    
    .tab-content {
        display: none;
        opacity: 0;
        /* Removed transform to prevent stacking context issues */
        transition: opacity 0.3s ease-in-out;
    }
    
    .tab-content.active {
        display: block;
        opacity: 1;
        /* Removed transform to prevent stacking context issues */
        animation: fadeInUp 0.4s ease-out;
    }
    
    .form-field {
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
    }

    .form-field:focus-within {
        /* Removed transform to prevent stacking context issues */
    }

    /* ===== DROPDOWN WRAPPER - RELATIVE POSITIONING ===== */
    .hero-custom-dropdown {
        position: relative;
        z-index: 1;
    }
    
    /* CRITICAL: When dropdown is open, bring it to absolute front */
    .hero-custom-dropdown.dropdown-open {
        z-index: 999999 !important;
    }
    
    /* CRITICAL: Suppress z-index of sibling form-fields when any dropdown is open */
    .tab-content.active:has(.hero-custom-dropdown.dropdown-open) .form-field:not(:has(.dropdown-open)) {
        z-index: 0 !important;
    }

    /* ===== DROPDOWN PANEL BASE STYLES - CRITICAL FOR VISIBILITY ===== */
    .hero-custom-dropdown-panel {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        margin-top: 8px;
        
        /* CRITICAL: Solid background - no transparency */
        background-color: #ffffff;
        
        /* CRITICAL: Full opacity */
        opacity: 0;
        visibility: hidden;
        
        /* CRITICAL: Maximum z-index - above everything including other form fields */
        z-index: 999999;
        
        /* Styling */
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        
        /* Animation */
        transform: translateY(-10px);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        
        /* Remove any blur effects */
        backdrop-filter: none;
        -webkit-backdrop-filter: none;
    }

    .hero-custom-dropdown-panel.open {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    /* Dark Mode Dropdown Panel */
    .dark .hero-custom-dropdown-panel {
        /* CRITICAL: Solid dark background */
        background: #252525;
        border-color: rgba(220, 38, 38, 0.3);
        
        /* Enhanced shadow for dark mode */
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7),
                    0 0 0 1px rgba(220, 38, 38, 0.3),
                    inset 0 1px 0 rgba(255, 255, 255, 0.08),
                    0 0 40px rgba(220, 38, 38, 0.15);
    }

    /* ===== SHARED DROPDOWN OPTION STYLES - BÜTÜN DROPDOWN'LAR İÇİN ORTAK ===== */
    
    /* Base option style - Araç Al & Araç Sat */
    .hero-custom-dropdown-option {
        display: flex;
        align-items: center;
        justify-content: flex-start; /* SOLDAN SAĞA HİZALAMA */
        padding: 14px 16px; /* STANDART: 14px dikey, 16px yatay */
        border-radius: 8px;
        transition: all 0.2s ease;
        cursor: pointer;
        font-weight: 500; /* STANDART: 500 */
        font-size: 15px;   /* STANDART: 15px */
        color: #1f2937;
    }

    /* First option (genellikle "Tümü" veya placeholder) */
    .hero-custom-dropdown-option:first-child {
        background: #f3f4f6;
        margin-bottom: 4px;
    }

    /* HOVER - LIGHT MODE */
    .hero-custom-dropdown-option:hover {
        background-color: #fef2f2;
    }

    /* SELECTED - LIGHT MODE */
    .hero-custom-dropdown-option.selected {
        background-color: #fee2e2;
        border: 1px solid #fca5a5;
    }

    /* DARK MODE - Base */
    .dark .hero-custom-dropdown-option {
        color: #f3f4f6;
    }

    /* DARK MODE - First option */
    .dark .hero-custom-dropdown-option:first-child {
        background: linear-gradient(90deg, 
                    rgba(239, 68, 68, 0.15) 0%, 
                    rgba(239, 68, 68, 0.08) 50%,
                    transparent 100%);
    }

    /* DARK MODE - Hover */
    .dark .hero-custom-dropdown-option:hover {
        background: linear-gradient(90deg, 
                    rgba(239, 68, 68, 0.2) 0%, 
                    rgba(239, 68, 68, 0.08) 50%,
                    transparent 100%);
        box-shadow: inset 0 0 30px rgba(220, 38, 38, 0.15);
    }

    /* DARK MODE - Selected */
    .dark .hero-custom-dropdown-option.selected {
        background-color: #7f1d1d;
        border: 1px solid #991b1b;
    }

    /* Brand Dropdown - Özel düzenlemeler */
    .hero-brand-panel {
        max-height: 400px;
        overflow-y: auto;
    }

    .hero-brand-panel .brand-list {
        display: flex;
        flex-direction: column;
        padding: 4px;
    }

    /* Brand için sola hizalama */
    .hero-brand-panel .hero-custom-dropdown-option {
        justify-content: flex-start;
    }

    /* Year Dropdown - Sadece container */
    .hero-year-panel {
        max-height: 350px;
        overflow-y: auto;
    }

    .hero-year-panel .year-list {
        display: flex;
        flex-direction: column;
        padding: 4px;
    }
    /* Ortak .hero-custom-dropdown-option stilini kullanır */

    /* Model Dropdown - Sadece container */
    .hero-model-panel {
        max-height: 350px;
        overflow-y: auto;
    }

    .hero-model-panel .model-list {
        display: flex;
        flex-direction: column;
        padding: 4px;
    }
    /* Ortak .hero-custom-dropdown-option stilini kullanır */

    /* Body Type Dropdown Styles */
    .hero-bodytype-panel {
        max-height: 350px;
        overflow-y: auto;
    }

    .hero-bodytype-panel .bodytype-list {
        display: flex;
        flex-direction: column;
        padding: 4px;
    }

    .hero-bodytype-panel .hero-custom-dropdown-option {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 14px 16px;
        border-radius: 8px;
        transition: all 0.2s;
        cursor: pointer;
        font-weight: 600;
        font-size: 16px;
        color: #1f2937;
    }

    .hero-bodytype-panel .hero-custom-dropdown-option:first-child {
        background: #f3f4f6;
        margin-bottom: 4px;
        font-weight: 500;
        font-size: 14px;
    }

    .hero-bodytype-panel .hero-custom-dropdown-option:hover {
        background-color: #fef2f2;
    }

    .hero-bodytype-panel .hero-custom-dropdown-option.selected {
        background-color: #fee2e2;
        border: 1px solid #fca5a5;
    }

    .dark .hero-bodytype-panel .hero-custom-dropdown-option {
        color: #f3f4f6;
    }

    .dark .hero-bodytype-panel .hero-custom-dropdown-option:first-child {
        background: linear-gradient(90deg, 
                    rgba(239, 68, 68, 0.15) 0%, 
                    rgba(239, 68, 68, 0.08) 50%,
                    transparent 100%);
    }

    .dark .hero-bodytype-panel .hero-custom-dropdown-option:hover {
        background: linear-gradient(90deg, 
                    rgba(239, 68, 68, 0.2) 0%, 
                    rgba(239, 68, 68, 0.08) 50%,
                    transparent 100%);
        box-shadow: inset 0 0 30px rgba(220, 38, 38, 0.15);
    }

    .dark .hero-bodytype-panel .hero-custom-dropdown-option.selected {
        background-color: #7f1d1d;
        border-color: #b91c1c;
    }

    /* Fuel Type Dropdown - Sadece container */
    .hero-fueltype-panel {
        max-height: 350px;
        overflow-y: auto;
    }

    .hero-fueltype-panel .fueltype-list {
        display: flex;
        flex-direction: column;
        padding: 4px;
    }
    /* Ortak .hero-custom-dropdown-option stilini kullanır */

    /* Transmission Dropdown - Sadece container */
    .hero-transmission-panel {
        max-height: 350px;
        overflow-y: auto;
    }

    .hero-transmission-panel .transmission-list {
        display: flex;
        flex-direction: column;
        padding: 4px;
    }
    /* Ortak .hero-custom-dropdown-option stilini kullanır */

    /* Version Dropdown - Container ve özel stiller */
    .hero-version-panel {
        max-height: 350px;
        overflow-y: auto;
    }

    .hero-version-panel .version-list {
        display: flex;
        flex-direction: column;
        padding: 4px;
    }

    /* Version için özel düzenlemeler (iki satırlı görünüm) */
    .hero-version-panel .hero-custom-dropdown-option {
        flex-direction: column;
        align-items: flex-start;
    }

    .hero-version-panel .hero-custom-dropdown-option .version-props {
        font-size: 11px;
        font-weight: 400;
        color: #6b7280;
        margin-top: 4px;
    }

    .hero-version-panel .hero-custom-dropdown-option:first-child {
        align-items: center; /* İlk seçenek ortalı */
    }

    .dark .hero-version-panel .hero-custom-dropdown-option .version-props {
        color: #9ca3af;
    }

    /* Color Dropdown - Sadece container */
    .hero-color-panel {
        max-height: 350px;
        overflow-y: auto;
    }

    .hero-color-panel .color-list {
        display: flex;
        flex-direction: column;
        padding: 4px;
    }
    /* Ortak .hero-custom-dropdown-option stilini kullanır */

    /* Form field animation */
    .form-field.slide-in {
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Dropdown trigger compact styles */
    .hero-custom-dropdown-trigger {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 14px;
        border-radius: 8px;
        background: white;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .hero-custom-dropdown-trigger:hover {
        border-color: #3b82f6;
    }

    .hero-custom-dropdown-trigger .selected-text {
        flex: 1;
        text-align: left;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-weight: 600;
        color: #111827; /* Seçili değer - çok koyu */
    }

    /* PLACEHOLDER - Daha okunabilir, ama seçili değerden açıkça farklı */
    .hero-custom-dropdown-trigger .selected-text.placeholder {
        color: #6b7280; /* Placeholder - orta ton gri, okunabilir */
        font-weight: 500; /* Placeholder daha ince */
    }

    .dark .hero-custom-dropdown-trigger {
        background: #2a2a2a;
    }

    .dark .hero-custom-dropdown-trigger .selected-text {
        color: #f9fafb; /* Dark mode - seçili değer çok açık */
    }

    /* DARK MODE PLACEHOLDER - Daha okunabilir */
    .dark .hero-custom-dropdown-trigger .selected-text.placeholder {
        color: #9ca3af; /* Dark mode placeholder - daha açık gri */
        font-weight: 500;
    }

    /* Disabled dropdown styles */
    .hero-custom-dropdown.disabled {
        opacity: 0.5;
        pointer-events: none;
    }

    .hero-custom-dropdown.disabled .hero-custom-dropdown-trigger {
        cursor: not-allowed;
        background-color: #f3f4f6;
    }

    .dark .hero-custom-dropdown.disabled .hero-custom-dropdown-trigger {
        background-color: #252525;
    }
</style>
@endpush

@section('content')
    <!-- Hero Section - Modern Design -->
    <section class="relative bg-white dark:bg-[#1e1e1e] overflow-hidden transition-colors duration-200">
        <!-- Decorative Elements -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden">
            <div class="absolute top-20 right-10 w-96 h-96 opacity-5">
                <svg viewBox="0 0 400 400" fill="none" class="w-full h-full">
                    <path d="M0,200 Q200,0 400,200 T800,200" stroke="currentColor" stroke-width="2" class="text-primary-600"/>
                    <path d="M0,250 Q200,50 400,250 T800,250" stroke="currentColor" stroke-width="2" class="text-primary-600"/>
                </svg>
            </div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-primary-100 rounded-full blur-3xl opacity-20"></div>
        </div>
        
        <div class="container-custom relative z-10 py-12 lg:py-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
                <!-- Left Side - Modern Search Form -->
                <div class="order-2 lg:order-1 animate-slide-in-left">
                    <div class="bg-white dark:bg-[#252525] rounded-2xl shadow-2xl border-b-4 border-primary-600 dark:border-primary-500 overflow-visible hover:shadow-3xl transition-shadow duration-300 hero-form-card">
                        <!-- Tabs -->
                        <div class="flex border-b-2 border-gray-100 dark:border-gray-800 relative">
                            <button id="tab-sell" 
                                    class="hero-tab active">
                                <span class="flex items-center justify-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>ARAÇ SAT</span>
                                </span>
                            </button>
                            <button id="tab-buy" 
                                    class="hero-tab">
                                <span class="flex items-center justify-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    <span>ARAÇ AL</span>
                                </span>
                            </button>
                        </div>
                        
                        <!-- Araç Sat Form -->
                        <div id="form-sell" class="tab-content active">
                            <form method="GET" action="{{ route('evaluation.index') }}" id="sell-form" class="p-6 space-y-5">
                                <!-- Marka -->
                                <div class="form-field">
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-3 uppercase tracking-wider">MARKA</label>
                                    <div class="hero-custom-dropdown hero-brand-dropdown" data-dropdown="brand-sell" id="brand-dropdown-sell">
                                        <button type="button" class="hero-custom-dropdown-trigger border-2 border-gray-300 dark:border-gray-700 dark:bg-[#2a2a2a] dark:text-gray-100" data-value="" data-brand-id="">
                                            <span class="selected-text placeholder dark:text-gray-400">Marka Seçin</span>
                                            <svg class="arrow w-6 h-6 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="hero-custom-dropdown-panel hero-brand-panel">
                                            <div class="brand-loading hidden p-4 text-center">
                                                <svg class="animate-spin h-6 w-6 mx-auto text-primary-600" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                <p class="mt-2 text-sm text-gray-500">Markalar yükleniyor...</p>
                                            </div>
                                            <div class="brand-list">
                                                <div class="hero-custom-dropdown-option" data-value="" data-brand-id="">Marka Seçin</div>
                                            </div>
                                        </div>
                                        <select name="marka" required class="hero-custom-dropdown-native">
                                            <option value="">Marka Seçin</option>
                                        </select>
                                        <input type="hidden" name="marka_id" id="marka-id-input" value="">
                                    </div>
                                </div>

                                <!-- Button -->
                                <button type="submit" class="btn btn-primary w-full py-5 px-6 text-lg">
                                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    <span>DEVAM ET</span>
                                </button>
                            </form>
                        </div>
                        
                        <!-- Araç Al Form - Sadeleştirilmiş -->
                        <div id="form-buy" class="tab-content">
                            <form method="GET" action="{{ route('vehicles.index') }}" id="buy-form" class="p-6 space-y-5">
                                <!-- Marka -->
                                <div class="form-field">
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-3 uppercase tracking-wider">MARKA</label>
                                    <div class="hero-custom-dropdown" data-dropdown="brand-buy">
                                        <button type="button" class="hero-custom-dropdown-trigger border-2 border-gray-300 dark:border-gray-700 dark:bg-[#2a2a2a] dark:text-gray-100" data-value="">
                                            <span class="selected-text placeholder">Tüm Markalar</span>
                                            <svg class="arrow w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="hero-custom-dropdown-panel">
                                            <div class="hero-custom-dropdown-option" data-value="">Tüm Markalar</div>
                                            @foreach($brands ?? [] as $brand)
                                                <div class="hero-custom-dropdown-option" data-value="{{ $brand->name }}">{{ $brand->name }}</div>
                                            @endforeach
                                        </div>
                                        <select name="brand" class="hero-custom-dropdown-native">
                                            <option value="">Tüm Markalar</option>
                                            @foreach($brands ?? [] as $brand)
                                                <option value="{{ $brand->name }}">{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Yardımcı Metin -->
                                <div class="text-center py-2">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                                        Detaylı seçim için 
                                        <a href="{{ route('vehicles.index') }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-semibold underline">
                                            Araçlar sayfasına
                                        </a> 
                                        göz atabilirsiniz.
                                    </p>
                                </div>
                                
                                <!-- Button -->
                                <button type="submit" class="btn btn-primary w-full py-5 px-6 text-lg">
                                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    <span>ARAÇLARI ARA</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Right Side - Dynamic Headline & Image -->
                <div class="order-1 lg:order-2 mt-0 lg:mt-6">
                    <h1 id="slogan-title" class="text-5xl md:text-6xl lg:text-7xl font-bold text-gray-900 dark:text-white mb-6 leading-tight">
                        <span class="slogan-content">Aracını <span class="slogan-highlight-red text-primary-600 dark:text-primary-500">Güvenle</span> Sat</span>
                    </h1>
                    <p id="slogan-description" class="text-xl md:text-2xl text-gray-600 dark:text-gray-300 mb-8 max-w-lg leading-relaxed">
                        <span class="slogan-content">Hızlı teklif alın, güvenli süreçten geçin. Aracınızın gerçek değerini öğrenin ve en iyi fiyatı garantileyin.</span>
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Red Bottom Section -->
        <div class="bg-primary-600 h-32 lg:h-40 relative overflow-hidden">
            <!-- Diagonal lines pattern -->
            <div class="absolute inset-0 opacity-20">
                <div class="absolute inset-0" style="background: repeating-linear-gradient(45deg, transparent, transparent 20px, rgba(255,255,255,0.1) 20px, rgba(255,255,255,0.1) 40px);"></div>
            </div>
        </div>
    </section>

    <!-- Öne Çıkan Araçlar -->
    <section class="section-padding bg-gradient-to-b from-gray-50 to-white dark:from-[#1e1e1e] dark:to-[#252525] relative overflow-hidden transition-colors duration-200">
        <!-- Decorative Background Elements -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-20 left-10 w-72 h-72 bg-primary-600 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-10 w-96 h-96 bg-primary-400 rounded-full blur-3xl"></div>
        </div>
        
        <div class="container-custom relative z-10">
            <div class="flex items-center justify-between mb-12 reveal">
                <div>
                    <h2 class="heading-primary mb-2">
                        <span class="text-gradient">Öne Çıkan Araçlar</span>
                    </h2>
                    <p class="text-gray-600 dark:text-gray-300 text-lg">En popüler ve öne çıkan araçlarımızı keşfedin</p>
                </div>
                <a href="{{ route('vehicles.index') }}" class="hidden md:flex items-center text-primary-600 dark:text-primary-400 font-semibold hover:text-primary-700 dark:hover:text-primary-300 group transition-all duration-300">
                    <span>Tümünü Gör</span>
                    <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
            
            @if($featuredVehicles->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                    @foreach($featuredVehicles as $index => $vehicle)
                        <div class="reveal" style="animation-delay: {{ $index * 0.1 }}s">
                            <x-vehicle-card :vehicle="$vehicle" />
                        </div>
                    @endforeach
                </div>
                
                <div class="text-center mt-12 reveal">
                    <a href="{{ route('vehicles.index') }}" class="btn btn-primary text-lg px-8">
                        Tüm Araçları Görüntüle
                    </a>
                </div>
            @else
                <div class="text-center py-12 reveal">
                    <p class="text-gray-600 dark:text-gray-400">Şu an öne çıkan araç bulunmamaktadır.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="section-padding bg-white dark:bg-[#1e1e1e] relative overflow-hidden transition-colors duration-200">
        <!-- Decorative Background -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-0 right-0 w-96 h-96 bg-primary-400 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 bg-primary-600 rounded-full blur-3xl"></div>
        </div>
        
        <div class="container-custom relative z-10">
            <div class="text-center mb-16 reveal">
                <h2 class="heading-primary mb-4">
                    <span class="text-gradient">Neden GMSGARAGE?</span>
                </h2>
                <p class="text-gray-600 dark:text-gray-300 text-lg max-w-2xl mx-auto">Size sunduğumuz avantajlar ile fark yaratıyoruz</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center group reveal hover-lift bg-white dark:bg-[#252525] rounded-2xl p-6 shadow-md dark:shadow-xl dark:border dark:border-gray-800 transition-all duration-200">
                    <div class="bg-gradient-to-br from-primary-500 to-primary-600 w-24 h-24 rounded-3xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 shadow-xl">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-3 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors duration-300">Garantili Araçlar</h3>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">Tüm araçlarımız garantili ve bakımlıdır. Güvenle alışveriş yapın.</p>
                </div>
                
                <div class="text-center group reveal hover-lift bg-white dark:bg-[#252525] rounded-2xl p-6 shadow-md dark:shadow-xl dark:border dark:border-gray-800 transition-all duration-200" style="animation-delay: 0.1s">
                    <div class="bg-gradient-to-br from-primary-500 to-primary-600 w-24 h-24 rounded-3xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 shadow-xl">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-3 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors duration-300">En İyi Fiyat</h3>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">Piyasanın en uygun fiyatları ile hizmetinizdeyiz. Fiyat garantisi veriyoruz.</p>
                </div>
                
                <div class="text-center group reveal hover-lift bg-white dark:bg-[#252525] rounded-2xl p-6 shadow-md dark:shadow-xl dark:border dark:border-gray-800 transition-all duration-200" style="animation-delay: 0.2s">
                    <div class="bg-gradient-to-br from-primary-500 to-primary-600 w-24 h-24 rounded-3xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 shadow-xl">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-3 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors duration-300">Ekspertiz Hizmeti</h3>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">Tüm araçlarımız ekspertiz raporlu ve detaylı kontrol edilmiştir.</p>
                </div>
                
                <div class="text-center group reveal hover-lift bg-white dark:bg-[#252525] rounded-2xl p-6 shadow-md dark:shadow-xl dark:border dark:border-gray-800 transition-all duration-200" style="animation-delay: 0.3s">
                    <div class="bg-gradient-to-br from-primary-500 to-primary-600 w-24 h-24 rounded-3xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 shadow-xl">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-3 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors duration-300">7/24 Destek</h3>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">Müşteri hizmetlerimiz her zaman yanınızda. Sorularınız için bize ulaşın.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section-padding text-white relative overflow-hidden cta-section-modern">
        <!-- Animated Gradient Background (very slow) -->
        <div class="absolute inset-0 cta-gradient-animation"></div>
        
        <!-- Subtle Pattern Overlay -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;"></div>
        </div>
        
        <!-- Decorative Shapes -->
        <div class="absolute top-0 left-0 w-96 h-96 bg-white/5 rounded-full blur-3xl animate-pulse-slow"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-white/5 rounded-full blur-3xl animate-pulse-slow" style="animation-delay: 2s;"></div>
        
        <div class="container-custom text-center relative z-10">
            <div class="reveal">
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 tracking-tight text-gray-900 dark:text-white">
                    <span class="bg-gradient-to-r from-white via-white to-white/90 bg-clip-text text-transparent">Hayalinizdeki Aracı</span>
                    <br>
                    <span class="bg-gradient-to-r from-white via-white to-white/90 bg-clip-text text-transparent">Bulun</span>
                </h2>
                <p class="text-xl md:text-2xl text-white/95 mb-10 max-w-3xl mx-auto leading-relaxed font-medium">
                    <span class="font-semibold">AI destekli</span> araç arama ve değerleme teknolojisi ile hayalinizdeki aracı kolayca bulun. 
                    <span class="font-semibold">Güvenli</span> ve <span class="font-semibold">şeffaf</span> alışveriş deneyimi.
                </p>
                <div class="flex flex-col sm:flex-row gap-6 justify-center mb-8">
                    <a href="{{ route('vehicles.index') }}" class="btn bg-white text-primary-600 hover:bg-gray-100 text-lg px-10 py-4 shadow-2xl hover:shadow-3xl transform hover:scale-105 transition-all duration-300 font-semibold group">
                        <span>Araçları İncele</span>
                        <svg class="w-5 h-5 ml-2 inline-block group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    <a href="{{ route('contact') }}" class="btn border-2 border-white text-white hover:bg-white hover:text-primary-600 text-lg px-10 py-4 backdrop-blur-sm bg-white/10 hover:bg-white transition-all duration-300 font-semibold group">
                        <span>İletişime Geç</span>
                        <svg class="w-5 h-5 ml-2 inline-block group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </a>
                </div>
                
                <!-- Güven İkonlu Bilgi Satırı -->
                <div class="flex items-center justify-center gap-3 text-white/80 text-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    <span>Güvenli ödeme • Garantili araçlar • 7/24 destek</span>
                </div>
            </div>
        </div>
    </section>
    
    <style>
        @keyframes ctaGradient {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        .cta-gradient-animation {
            background: linear-gradient(-45deg, #dc2626, #991b1b, #7f1d1d, #b91c1c, #dc2626);
            background-size: 400% 400%;
            animation: ctaGradient 20s ease infinite;
        }
        @keyframes pulse-slow {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 0.5; }
        }
        .animate-pulse-slow {
            animation: pulse-slow 4s ease-in-out infinite;
        }
    </style>
@endsection

@push('scripts')
<script>
    // Tab switching handled by app.js (initHeroTabs)

    // Brand & Year Dropdown API Integration
    let brandsLoaded = false;
    let brandsData = [];
    let selectedBrandId = null;
    let selectedYear = null;
    let selectedModelId = null;
    let selectedBodytypeId = null;
    let selectedFueltypeId = null;
    let selectedTransmissionId = null;
    let selectedVersionId = null;

    async function loadBrandsFromAPI() {
        if (brandsLoaded) return brandsData;

        const brandDropdown = document.getElementById('brand-dropdown-sell');
        if (!brandDropdown) return [];

        const loading = brandDropdown.querySelector('.brand-loading');
        const brandList = brandDropdown.querySelector('.brand-list');

        loading.classList.remove('hidden');

        try {
            const response = await fetch('/api/arabam/brands');
            const result = await response.json();

            if (result.success && result.data && result.data.Items) {
                brandsData = result.data.Items;
                brandsLoaded = true;

                // Clear existing options except first
                const firstOption = brandList.querySelector('.hero-custom-dropdown-option');
                brandList.innerHTML = '';
                brandList.appendChild(firstOption);

                // Add brand options
                brandsData.forEach(brand => {
                    const option = document.createElement('div');
                    option.className = 'hero-custom-dropdown-option';
                    option.setAttribute('data-value', brand.Name);
                    option.setAttribute('data-brand-id', brand.Id);
                    option.textContent = brand.Name;
                    brandList.appendChild(option);
                });

                // Update native select
                const nativeSelect = brandDropdown.querySelector('.hero-custom-dropdown-native');
                nativeSelect.innerHTML = '<option value="">Marka Seçin</option>';
                brandsData.forEach(brand => {
                    const opt = document.createElement('option');
                    opt.value = brand.Name;
                    opt.textContent = brand.Name;
                    opt.setAttribute('data-brand-id', brand.Id);
                    nativeSelect.appendChild(opt);
                });

                // Re-init dropdown event listeners for new options
                initBrandDropdownOptions(brandDropdown);
            }
        } catch (error) {
            // silently handle
        } finally {
            loading.classList.add('hidden');
        }

        return brandsData;
    }

    function initBrandDropdownOptions(dropdown) {
        const panel = dropdown.querySelector('.hero-custom-dropdown-panel');
        const options = panel.querySelectorAll('.hero-custom-dropdown-option');
        const trigger = dropdown.querySelector('.hero-custom-dropdown-trigger');
        const nativeSelect = dropdown.querySelector('.hero-custom-dropdown-native');
        const selectedText = trigger.querySelector('.selected-text');
        const brandIdInput = document.getElementById('marka-id-input');
        const formCard = document.querySelector('.hero-form-card');
        const yearField = document.getElementById('year-field-sell');

        options.forEach(option => {
            option.addEventListener('click', function(e) {
                e.stopPropagation();
                const value = this.getAttribute('data-value');
                const brandId = this.getAttribute('data-brand-id');

                // Update trigger display
                selectedText.textContent = value || 'Marka Seçin';
                if (value) {
                    selectedText.classList.remove('placeholder');
                } else {
                    selectedText.classList.add('placeholder');
                }

                // Update values
                trigger.setAttribute('data-value', value);
                trigger.setAttribute('data-brand-id', brandId || '');
                nativeSelect.value = value;
                if (brandIdInput) brandIdInput.value = brandId || '';
                selectedBrandId = brandId;
                selectedYear = null;
                selectedModelId = null;
                selectedBodytypeId = null;
                selectedFueltypeId = null;
                selectedTransmissionId = null;
                selectedVersionId = null;

                // Update selected state
                options.forEach(opt => opt.classList.remove('selected'));
                if (value) this.classList.add('selected');

                // Close dropdown
                panel.classList.remove('open');
                trigger.classList.remove('open');
                dropdown.classList.remove('dropdown-open');

                // Enable all fields
                if (formCard) {
                    formCard.classList.remove('dropdown-open');
                    formCard.querySelectorAll('.form-field').forEach(field => {
                        field.style.pointerEvents = '';
                        field.style.opacity = '';
                    });
                    formCard.querySelectorAll('button[type="submit"]').forEach(btn => {
                        btn.style.pointerEvents = '';
                        btn.style.opacity = '';
                    });
                }

                // Brand selection is complete - form will submit to wizard page
            });
        });
    }

    // Load years from API
    async function loadYearsFromAPI(brandId) {
        const yearDropdown = document.getElementById('year-dropdown-sell');
        if (!yearDropdown) return;

        const loading = yearDropdown.querySelector('.year-loading');
        const yearList = yearDropdown.querySelector('.year-list');

        loading.classList.remove('hidden');
        yearList.style.display = 'none';

        try {
            const response = await fetch(`/api/arabam/step?step=10&brandId=${brandId}`);
            const result = await response.json();

            if (result.success && result.data && result.data.Items) {
                // Clear existing options except first
                const firstOption = yearList.querySelector('.hero-custom-dropdown-option');
                yearList.innerHTML = '';
                yearList.appendChild(firstOption);

                // Add year options
                result.data.Items.forEach(year => {
                    const option = document.createElement('div');
                    option.className = 'hero-custom-dropdown-option';
                    option.setAttribute('data-value', year.Name);
                    option.setAttribute('data-year-id', year.Id);
                    option.textContent = year.Name;
                    yearList.appendChild(option);
                });

                // Update native select
                const nativeSelect = yearDropdown.querySelector('.hero-custom-dropdown-native');
                nativeSelect.innerHTML = '<option value="">Model Yılı Seçin</option>';
                result.data.Items.forEach(year => {
                    const opt = document.createElement('option');
                    opt.value = year.Name;
                    opt.textContent = year.Name;
                    opt.setAttribute('data-year-id', year.Id);
                    nativeSelect.appendChild(opt);
                });

                // Re-init dropdown event listeners for new options
                initYearDropdownOptions(yearDropdown);
            }
        } catch (error) {
            // silently handle
        } finally {
            loading.classList.add('hidden');
            yearList.style.display = '';
        }
    }

    // Reset year dropdown
    function resetYearDropdown() {
        const yearDropdown = document.getElementById('year-dropdown-sell');
        if (!yearDropdown) return;

        const trigger = yearDropdown.querySelector('.hero-custom-dropdown-trigger');
        const selectedText = trigger.querySelector('.selected-text');
        const nativeSelect = yearDropdown.querySelector('.hero-custom-dropdown-native');
        const yilIdInput = document.getElementById('yil-id-input');

        selectedText.textContent = 'Yıl Seçin';
        selectedText.classList.add('placeholder');
        trigger.setAttribute('data-value', '');
        trigger.setAttribute('data-year-id', '');
        nativeSelect.value = '';
        if (yilIdInput) yilIdInput.value = '';
    }

    // Load models from API
    async function loadModelsFromAPI(brandId, modelYear) {
        const modelDropdown = document.getElementById('model-dropdown-sell');
        if (!modelDropdown) return;

        const loading = modelDropdown.querySelector('.model-loading');
        const modelList = modelDropdown.querySelector('.model-list');

        loading.classList.remove('hidden');
        modelList.style.display = 'none';

        try {
            const response = await fetch(`/api/arabam/step?step=20&brandId=${brandId}&modelYear=${modelYear}`);
            const result = await response.json();

            if (result.success && result.data && result.data.Items) {
                // Clear existing options except first
                const firstOption = modelList.querySelector('.hero-custom-dropdown-option');
                modelList.innerHTML = '';
                modelList.appendChild(firstOption);

                // Add model options
                result.data.Items.forEach(model => {
                    const option = document.createElement('div');
                    option.className = 'hero-custom-dropdown-option';
                    option.setAttribute('data-value', model.Name);
                    option.setAttribute('data-model-id', model.Id);
                    option.textContent = model.Name;
                    modelList.appendChild(option);
                });

                // Update native select
                const nativeSelect = modelDropdown.querySelector('.hero-custom-dropdown-native');
                nativeSelect.innerHTML = '<option value="">Model Seçin</option>';
                result.data.Items.forEach(model => {
                    const opt = document.createElement('option');
                    opt.value = model.Name;
                    opt.textContent = model.Name;
                    opt.setAttribute('data-model-id', model.Id);
                    nativeSelect.appendChild(opt);
                });

                // Re-init dropdown event listeners for new options
                initModelDropdownOptions(modelDropdown);
            }
        } catch (error) {
            // silently handle
        } finally {
            loading.classList.add('hidden');
            modelList.style.display = '';
        }
    }

    // Reset model dropdown
    function resetModelDropdown() {
        const modelDropdown = document.getElementById('model-dropdown-sell');
        if (!modelDropdown) return;

        const trigger = modelDropdown.querySelector('.hero-custom-dropdown-trigger');
        const selectedText = trigger.querySelector('.selected-text');
        const nativeSelect = modelDropdown.querySelector('.hero-custom-dropdown-native');
        const modelIdInput = document.getElementById('model-id-input');

        selectedText.textContent = 'Model Seçin';
        selectedText.classList.add('placeholder');
        trigger.setAttribute('data-value', '');
        trigger.setAttribute('data-model-id', '');
        nativeSelect.value = '';
        if (modelIdInput) modelIdInput.value = '';
    }

    // Initialize model dropdown options
    function initModelDropdownOptions(dropdown) {
        const panel = dropdown.querySelector('.hero-custom-dropdown-panel');
        const options = panel.querySelectorAll('.hero-custom-dropdown-option');
        const trigger = dropdown.querySelector('.hero-custom-dropdown-trigger');
        const nativeSelect = dropdown.querySelector('.hero-custom-dropdown-native');
        const selectedText = trigger.querySelector('.selected-text');
        const modelIdInput = document.getElementById('model-id-input');
        const formCard = document.querySelector('.hero-form-card');

        options.forEach(option => {
            option.addEventListener('click', function(e) {
                e.stopPropagation();
                const value = this.getAttribute('data-value');
                const modelId = this.getAttribute('data-model-id');

                // Update trigger display
                selectedText.textContent = value || 'Model Seçin';
                if (value) {
                    selectedText.classList.remove('placeholder');
                } else {
                    selectedText.classList.add('placeholder');
                }

                // Update values
                trigger.setAttribute('data-value', value);
                trigger.setAttribute('data-model-id', modelId || '');
                nativeSelect.value = value;
                if (modelIdInput) modelIdInput.value = modelId || '';
                selectedModelId = modelId;
                selectedBodytypeId = null;
                selectedFueltypeId = null;
                selectedTransmissionId = null;
                selectedVersionId = null;

                // Update selected state
                options.forEach(opt => opt.classList.remove('selected'));
                if (value) this.classList.add('selected');

                // Close dropdown
                panel.classList.remove('open');
                trigger.classList.remove('open');
                dropdown.classList.remove('dropdown-open');

                // Enable all fields
                if (formCard) {
                    formCard.classList.remove('dropdown-open');
                    formCard.querySelectorAll('.form-field').forEach(field => {
                        field.style.pointerEvents = '';
                        field.style.opacity = '';
                    });
                    formCard.querySelectorAll('button[type="submit"]').forEach(btn => {
                        btn.style.pointerEvents = '';
                        btn.style.opacity = '';
                    });
                }

                // Disable all subsequent dropdowns and reset
                ['bodytype', 'fueltype', 'transmission', 'version', 'color'].forEach(name => {
                    const dd = document.getElementById(`${name}-dropdown-sell`);
                    if (dd) {
                        dd.classList.add('disabled');
                        const trig = dd.querySelector('.hero-custom-dropdown-trigger');
                        if (trig) trig.disabled = true;
                    }
                });
                resetBodytypeDropdown();
                resetFueltypeDropdown();
                resetTransmissionDropdown();
                resetVersionDropdown();
                resetColorDropdown();
                resetKilometreInput();

                // Enable/disable bodytype dropdown based on model selection
                const bodytypeDropdown = document.getElementById('bodytype-dropdown-sell');
                if (modelId && selectedBrandId && selectedYear) {
                    // Enable bodytype dropdown and load body types
                    if (bodytypeDropdown) {
                        bodytypeDropdown.classList.remove('disabled');
                        const bodytypeTrigger = bodytypeDropdown.querySelector('.hero-custom-dropdown-trigger');
                        if (bodytypeTrigger) bodytypeTrigger.disabled = false;
                    }
                    loadBodytypesFromAPI(selectedBrandId, selectedYear, modelId);
                }
            });
        });
    }

    // Initialize year dropdown options
    function initYearDropdownOptions(dropdown) {
        const panel = dropdown.querySelector('.hero-custom-dropdown-panel');
        const options = panel.querySelectorAll('.hero-custom-dropdown-option');
        const trigger = dropdown.querySelector('.hero-custom-dropdown-trigger');
        const nativeSelect = dropdown.querySelector('.hero-custom-dropdown-native');
        const selectedText = trigger.querySelector('.selected-text');
        const yilIdInput = document.getElementById('yil-id-input');
        const formCard = document.querySelector('.hero-form-card');

        options.forEach(option => {
            option.addEventListener('click', function(e) {
                e.stopPropagation();
                const value = this.getAttribute('data-value');
                const yearId = this.getAttribute('data-year-id');

                // Update trigger display
                selectedText.textContent = value || 'Model Yılı Seçin';
                if (value) {
                    selectedText.classList.remove('placeholder');
                } else {
                    selectedText.classList.add('placeholder');
                }

                // Update values
                trigger.setAttribute('data-value', value);
                trigger.setAttribute('data-year-id', yearId || '');
                nativeSelect.value = value;
                if (yilIdInput) yilIdInput.value = yearId || '';
                selectedYear = value; // Store selected year globally
                selectedModelId = null;
                selectedBodytypeId = null;
                selectedFueltypeId = null;
                selectedTransmissionId = null;
                selectedVersionId = null;

                // Update selected state
                options.forEach(opt => opt.classList.remove('selected'));
                if (value) this.classList.add('selected');

                // Close dropdown
                panel.classList.remove('open');
                trigger.classList.remove('open');
                dropdown.classList.remove('dropdown-open');

                // Enable all fields
                if (formCard) {
                    formCard.classList.remove('dropdown-open');
                    formCard.querySelectorAll('.form-field').forEach(field => {
                        field.style.pointerEvents = '';
                        field.style.opacity = '';
                    });
                    formCard.querySelectorAll('button[type="submit"]').forEach(btn => {
                        btn.style.pointerEvents = '';
                        btn.style.opacity = '';
                    });
                }

                // Enable/disable model dropdown based on year selection
                const modelDropdown = document.getElementById('model-dropdown-sell');

                // Disable all subsequent dropdowns
                ['bodytype', 'fueltype', 'transmission', 'version', 'color'].forEach(name => {
                    const dd = document.getElementById(`${name}-dropdown-sell`);
                    if (dd) {
                        dd.classList.add('disabled');
                        dd.querySelector('.hero-custom-dropdown-trigger').disabled = true;
                    }
                });
                resetModelDropdown();
                resetBodytypeDropdown();
                resetFueltypeDropdown();
                resetTransmissionDropdown();
                resetVersionDropdown();
                resetColorDropdown();
                resetKilometreInput();

                if (value && selectedBrandId) {
                    // Enable model dropdown and load models
                    if (modelDropdown) {
                        modelDropdown.classList.remove('disabled');
                        modelDropdown.querySelector('.hero-custom-dropdown-trigger').disabled = false;
                    }
                    loadModelsFromAPI(selectedBrandId, value);
                } else {
                    // Disable model dropdown
                    if (modelDropdown) {
                        modelDropdown.classList.add('disabled');
                        modelDropdown.querySelector('.hero-custom-dropdown-trigger').disabled = true;
                    }
                }
            });
        });
    }

    // Load body types from API
    async function loadBodytypesFromAPI(brandId, modelYear, modelGroupId) {
        const bodytypeDropdown = document.getElementById('bodytype-dropdown-sell');
        if (!bodytypeDropdown) return;

        const loading = bodytypeDropdown.querySelector('.bodytype-loading');
        const bodytypeList = bodytypeDropdown.querySelector('.bodytype-list');

        loading.classList.remove('hidden');
        bodytypeList.style.display = 'none';

        try {
            const response = await fetch(`/api/arabam/step?step=30&brandId=${brandId}&modelYear=${modelYear}&modelGroupId=${modelGroupId}`);
            const result = await response.json();

            if (result.success && result.data && result.data.Items) {
                // Clear existing options except first
                const firstOption = bodytypeList.querySelector('.hero-custom-dropdown-option');
                bodytypeList.innerHTML = '';
                bodytypeList.appendChild(firstOption);

                // Add bodytype options
                result.data.Items.forEach(bodytype => {
                    const option = document.createElement('div');
                    option.className = 'hero-custom-dropdown-option';
                    option.setAttribute('data-value', bodytype.Name);
                    option.setAttribute('data-bodytype-id', bodytype.Id);
                    option.textContent = bodytype.Name;
                    bodytypeList.appendChild(option);
                });

                // Update native select
                const nativeSelect = bodytypeDropdown.querySelector('.hero-custom-dropdown-native');
                nativeSelect.innerHTML = '<option value="">Gövde Tipi Seçin</option>';
                result.data.Items.forEach(bodytype => {
                    const opt = document.createElement('option');
                    opt.value = bodytype.Name;
                    opt.textContent = bodytype.Name;
                    opt.setAttribute('data-bodytype-id', bodytype.Id);
                    nativeSelect.appendChild(opt);
                });

                // Re-init dropdown event listeners for new options
                initBodytypeDropdownOptions(bodytypeDropdown);
            }
        } catch (error) {
            // silently handle
        } finally {
            loading.classList.add('hidden');
            bodytypeList.style.display = '';
        }
    }

    // Reset bodytype dropdown
    function resetBodytypeDropdown() {
        const bodytypeDropdown = document.getElementById('bodytype-dropdown-sell');
        if (!bodytypeDropdown) return;

        const trigger = bodytypeDropdown.querySelector('.hero-custom-dropdown-trigger');
        const selectedText = trigger.querySelector('.selected-text');
        const nativeSelect = bodytypeDropdown.querySelector('.hero-custom-dropdown-native');
        const bodytypeIdInput = document.getElementById('govde-tipi-id-input');

        selectedText.textContent = 'Gövde Tipi Seçin';
        selectedText.classList.add('placeholder');
        trigger.setAttribute('data-value', '');
        trigger.setAttribute('data-bodytype-id', '');
        nativeSelect.value = '';
        if (bodytypeIdInput) bodytypeIdInput.value = '';
    }

    // Initialize bodytype dropdown options
    function initBodytypeDropdownOptions(dropdown) {
        const panel = dropdown.querySelector('.hero-custom-dropdown-panel');
        const options = panel.querySelectorAll('.hero-custom-dropdown-option');
        const trigger = dropdown.querySelector('.hero-custom-dropdown-trigger');
        const nativeSelect = dropdown.querySelector('.hero-custom-dropdown-native');
        const selectedText = trigger.querySelector('.selected-text');
        const bodytypeIdInput = document.getElementById('govde-tipi-id-input');
        const formCard = document.querySelector('.hero-form-card');

        options.forEach(option => {
            option.addEventListener('click', function(e) {
                e.stopPropagation();
                const value = this.getAttribute('data-value');
                const bodytypeId = this.getAttribute('data-bodytype-id');

                // Update trigger display
                selectedText.textContent = value || 'Gövde Tipi Seçin';
                if (value) {
                    selectedText.classList.remove('placeholder');
                } else {
                    selectedText.classList.add('placeholder');
                }

                // Update values
                trigger.setAttribute('data-value', value);
                trigger.setAttribute('data-bodytype-id', bodytypeId || '');
                nativeSelect.value = value;
                if (bodytypeIdInput) bodytypeIdInput.value = bodytypeId || '';
                selectedBodytypeId = bodytypeId; // Store globally
                selectedFueltypeId = null;
                selectedTransmissionId = null;
                selectedVersionId = null;

                // Update selected state
                options.forEach(opt => opt.classList.remove('selected'));
                if (value) this.classList.add('selected');

                // Close dropdown
                panel.classList.remove('open');
                trigger.classList.remove('open');
                dropdown.classList.remove('dropdown-open');

                // Enable all fields
                if (formCard) {
                    formCard.classList.remove('dropdown-open');
                    formCard.querySelectorAll('.form-field').forEach(field => {
                        field.style.pointerEvents = '';
                        field.style.opacity = '';
                    });
                    formCard.querySelectorAll('button[type="submit"]').forEach(btn => {
                        btn.style.pointerEvents = '';
                        btn.style.opacity = '';
                    });
                }

                // Disable all subsequent dropdowns and reset
                ['fueltype', 'transmission', 'version', 'color'].forEach(name => {
                    const dd = document.getElementById(`${name}-dropdown-sell`);
                    if (dd) {
                        dd.classList.add('disabled');
                        const trig = dd.querySelector('.hero-custom-dropdown-trigger');
                        if (trig) trig.disabled = true;
                    }
                });
                resetFueltypeDropdown();
                resetTransmissionDropdown();
                resetVersionDropdown();
                resetColorDropdown();
                resetKilometreInput();

                // Enable/disable fueltype dropdown based on bodytype selection
                const fueltypeDropdown = document.getElementById('fueltype-dropdown-sell');
                if (bodytypeId && selectedBrandId && selectedYear && selectedModelId) {
                    // Enable fueltype dropdown and load fuel types
                    if (fueltypeDropdown) {
                        fueltypeDropdown.classList.remove('disabled');
                        const fueltypeTrigger = fueltypeDropdown.querySelector('.hero-custom-dropdown-trigger');
                        if (fueltypeTrigger) fueltypeTrigger.disabled = false;
                    }
                    loadFueltypesFromAPI(selectedBrandId, selectedYear, selectedModelId, bodytypeId);
                }
            });
        });
    }

    function initBodytypeDropdown() {
        const bodytypeDropdown = document.getElementById('bodytype-dropdown-sell');
        if (!bodytypeDropdown) return;

        const trigger = bodytypeDropdown.querySelector('.hero-custom-dropdown-trigger');
        const panel = bodytypeDropdown.querySelector('.hero-custom-dropdown-panel');
        const formCard = document.querySelector('.hero-form-card');

        // Toggle dropdown
        trigger.addEventListener('click', function(e) {
            // Don't open if disabled
            if (bodytypeDropdown.classList.contains('disabled') || trigger.disabled) return;

            e.stopPropagation();
            e.preventDefault();

            // Close other dropdowns
            document.querySelectorAll('.hero-custom-dropdown-panel.open').forEach(openPanel => {
                if (openPanel !== panel) {
                    openPanel.classList.remove('open');
                    const otherDropdown = openPanel.closest('.hero-custom-dropdown');
                    otherDropdown.querySelector('.hero-custom-dropdown-trigger').classList.remove('open');
                    otherDropdown.classList.remove('dropdown-open');
                }
            });

            // Toggle current dropdown
            const isOpen = panel.classList.contains('open');
            if (!isOpen) {
                panel.classList.add('open');
                trigger.classList.add('open');
                bodytypeDropdown.classList.add('dropdown-open');

                // Disable other fields
                if (formCard) {
                    formCard.classList.add('dropdown-open');
                    const parentField = bodytypeDropdown.closest('.form-field');
                    formCard.querySelectorAll('.form-field').forEach(field => {
                        if (field !== parentField) {
                            field.style.pointerEvents = 'none';
                            field.style.opacity = '0.6';
                        }
                    });
                    formCard.querySelectorAll('button[type="submit"]').forEach(btn => {
                        btn.style.pointerEvents = 'none';
                        btn.style.opacity = '0.6';
                    });
                }
            } else {
                panel.classList.remove('open');
                trigger.classList.remove('open');
                bodytypeDropdown.classList.remove('dropdown-open');

                // Enable all fields
                if (formCard) {
                    formCard.classList.remove('dropdown-open');
                    formCard.querySelectorAll('.form-field').forEach(field => {
                        field.style.pointerEvents = '';
                        field.style.opacity = '';
                    });
                    formCard.querySelectorAll('button[type="submit"]').forEach(btn => {
                        btn.style.pointerEvents = '';
                        btn.style.opacity = '';
                    });
                }
            }
        });

        // Initialize default option click handler
        initBodytypeDropdownOptions(bodytypeDropdown);
    }

    // Load fuel types from API
    async function loadFueltypesFromAPI(brandId, modelYear, modelGroupId, bodyTypeId) {
        const fueltypeDropdown = document.getElementById('fueltype-dropdown-sell');
        if (!fueltypeDropdown) return;

        const loading = fueltypeDropdown.querySelector('.fueltype-loading');
        const fueltypeList = fueltypeDropdown.querySelector('.fueltype-list');

        loading.classList.remove('hidden');
        fueltypeList.style.display = 'none';

        try {
            const response = await fetch(`/api/arabam/step?step=40&brandId=${brandId}&modelYear=${modelYear}&modelGroupId=${modelGroupId}&bodyTypeId=${bodyTypeId}`);
            const result = await response.json();

            if (result.success && result.data && result.data.Items) {
                // Clear existing options except first
                const firstOption = fueltypeList.querySelector('.hero-custom-dropdown-option');
                fueltypeList.innerHTML = '';
                fueltypeList.appendChild(firstOption);

                // Add fueltype options
                result.data.Items.forEach(fueltype => {
                    const option = document.createElement('div');
                    option.className = 'hero-custom-dropdown-option';
                    option.setAttribute('data-value', fueltype.Name);
                    option.setAttribute('data-fueltype-id', fueltype.Id);
                    option.textContent = fueltype.Name;
                    fueltypeList.appendChild(option);
                });

                // Update native select
                const nativeSelect = fueltypeDropdown.querySelector('.hero-custom-dropdown-native');
                nativeSelect.innerHTML = '<option value="">Yakıt Tipi Seçin</option>';
                result.data.Items.forEach(fueltype => {
                    const opt = document.createElement('option');
                    opt.value = fueltype.Name;
                    opt.textContent = fueltype.Name;
                    opt.setAttribute('data-fueltype-id', fueltype.Id);
                    nativeSelect.appendChild(opt);
                });

                // Re-init dropdown event listeners for new options
                initFueltypeDropdownOptions(fueltypeDropdown);
            }
        } catch (error) {
            // silently handle
        } finally {
            loading.classList.add('hidden');
            fueltypeList.style.display = '';
        }
    }

    // Reset fueltype dropdown
    function resetFueltypeDropdown() {
        const fueltypeDropdown = document.getElementById('fueltype-dropdown-sell');
        if (!fueltypeDropdown) return;

        const trigger = fueltypeDropdown.querySelector('.hero-custom-dropdown-trigger');
        const selectedText = trigger.querySelector('.selected-text');
        const nativeSelect = fueltypeDropdown.querySelector('.hero-custom-dropdown-native');
        const fueltypeIdInput = document.getElementById('yakit-tipi-id-input');

        selectedText.textContent = 'Yakıt Tipi Seçin';
        selectedText.classList.add('placeholder');
        trigger.setAttribute('data-value', '');
        trigger.setAttribute('data-fueltype-id', '');
        nativeSelect.value = '';
        if (fueltypeIdInput) fueltypeIdInput.value = '';
    }

    // Initialize fueltype dropdown options
    function initFueltypeDropdownOptions(dropdown) {
        const panel = dropdown.querySelector('.hero-custom-dropdown-panel');
        const options = panel.querySelectorAll('.hero-custom-dropdown-option');
        const trigger = dropdown.querySelector('.hero-custom-dropdown-trigger');
        const nativeSelect = dropdown.querySelector('.hero-custom-dropdown-native');
        const selectedText = trigger.querySelector('.selected-text');
        const fueltypeIdInput = document.getElementById('yakit-tipi-id-input');
        const formCard = document.querySelector('.hero-form-card');

        options.forEach(option => {
            option.addEventListener('click', function(e) {
                e.stopPropagation();
                const value = this.getAttribute('data-value');
                const fueltypeId = this.getAttribute('data-fueltype-id');

                // Update trigger display
                selectedText.textContent = value || 'Yakıt Tipi Seçin';
                if (value) {
                    selectedText.classList.remove('placeholder');
                } else {
                    selectedText.classList.add('placeholder');
                }

                // Update values
                trigger.setAttribute('data-value', value);
                trigger.setAttribute('data-fueltype-id', fueltypeId || '');
                nativeSelect.value = value;
                if (fueltypeIdInput) fueltypeIdInput.value = fueltypeId || '';
                selectedFueltypeId = fueltypeId;
                selectedTransmissionId = null;
                selectedVersionId = null;

                // Update selected state
                options.forEach(opt => opt.classList.remove('selected'));
                if (value) this.classList.add('selected');

                // Close dropdown
                panel.classList.remove('open');
                trigger.classList.remove('open');
                dropdown.classList.remove('dropdown-open');

                // Enable all fields
                if (formCard) {
                    formCard.classList.remove('dropdown-open');
                    formCard.querySelectorAll('.form-field').forEach(field => {
                        field.style.pointerEvents = '';
                        field.style.opacity = '';
                    });
                    formCard.querySelectorAll('button[type="submit"]').forEach(btn => {
                        btn.style.pointerEvents = '';
                        btn.style.opacity = '';
                    });
                }

                // Disable all subsequent dropdowns and reset
                ['transmission', 'version', 'color'].forEach(name => {
                    const dd = document.getElementById(`${name}-dropdown-sell`);
                    if (dd) {
                        dd.classList.add('disabled');
                        const trig = dd.querySelector('.hero-custom-dropdown-trigger');
                        if (trig) trig.disabled = true;
                    }
                });
                resetTransmissionDropdown();
                resetVersionDropdown();
                resetColorDropdown();
                resetKilometreInput();

                // Enable/disable transmission dropdown
                const transmissionDropdown = document.getElementById('transmission-dropdown-sell');
                if (fueltypeId && selectedBrandId && selectedYear && selectedModelId && selectedBodytypeId) {
                    if (transmissionDropdown) {
                        transmissionDropdown.classList.remove('disabled');
                        const transmissionTrigger = transmissionDropdown.querySelector('.hero-custom-dropdown-trigger');
                        if (transmissionTrigger) transmissionTrigger.disabled = false;
                    }
                    loadTransmissionsFromAPI(selectedBrandId, selectedYear, selectedModelId, selectedBodytypeId, fueltypeId);
                }
            });
        });
    }

    function initFueltypeDropdown() {
        const fueltypeDropdown = document.getElementById('fueltype-dropdown-sell');
        if (!fueltypeDropdown) return;

        const trigger = fueltypeDropdown.querySelector('.hero-custom-dropdown-trigger');
        const panel = fueltypeDropdown.querySelector('.hero-custom-dropdown-panel');
        const formCard = document.querySelector('.hero-form-card');

        // Toggle dropdown
        trigger.addEventListener('click', function(e) {
            // Don't open if disabled
            if (fueltypeDropdown.classList.contains('disabled') || trigger.disabled) return;

            e.stopPropagation();
            e.preventDefault();

            // Close other dropdowns
            document.querySelectorAll('.hero-custom-dropdown-panel.open').forEach(openPanel => {
                if (openPanel !== panel) {
                    openPanel.classList.remove('open');
                    const otherDropdown = openPanel.closest('.hero-custom-dropdown');
                    otherDropdown.querySelector('.hero-custom-dropdown-trigger').classList.remove('open');
                    otherDropdown.classList.remove('dropdown-open');
                }
            });

            // Toggle current dropdown
            const isOpen = panel.classList.contains('open');
            if (!isOpen) {
                panel.classList.add('open');
                trigger.classList.add('open');
                fueltypeDropdown.classList.add('dropdown-open');

                // Disable other fields
                if (formCard) {
                    formCard.classList.add('dropdown-open');
                    const parentField = fueltypeDropdown.closest('.form-field');
                    formCard.querySelectorAll('.form-field').forEach(field => {
                        if (field !== parentField) {
                            field.style.pointerEvents = 'none';
                            field.style.opacity = '0.6';
                        }
                    });
                    formCard.querySelectorAll('button[type="submit"]').forEach(btn => {
                        btn.style.pointerEvents = 'none';
                        btn.style.opacity = '0.6';
                    });
                }
            } else {
                panel.classList.remove('open');
                trigger.classList.remove('open');
                fueltypeDropdown.classList.remove('dropdown-open');

                // Enable all fields
                if (formCard) {
                    formCard.classList.remove('dropdown-open');
                    formCard.querySelectorAll('.form-field').forEach(field => {
                        field.style.pointerEvents = '';
                        field.style.opacity = '';
                    });
                    formCard.querySelectorAll('button[type="submit"]').forEach(btn => {
                        btn.style.pointerEvents = '';
                        btn.style.opacity = '';
                    });
                }
            }
        });

        // Initialize default option click handler
        initFueltypeDropdownOptions(fueltypeDropdown);
    }

    // Load transmissions from API
    async function loadTransmissionsFromAPI(brandId, modelYear, modelGroupId, bodyTypeId, fuelTypeId) {
        const dropdown = document.getElementById('transmission-dropdown-sell');
        if (!dropdown) return;

        const loading = dropdown.querySelector('.transmission-loading');
        const list = dropdown.querySelector('.transmission-list');

        loading.classList.remove('hidden');
        list.style.display = 'none';

        try {
            const response = await fetch(`/api/arabam/step?step=50&brandId=${brandId}&modelYear=${modelYear}&modelGroupId=${modelGroupId}&bodyTypeId=${bodyTypeId}&fuelTypeId=${fuelTypeId}`);
            const result = await response.json();

            if (result.success && result.data && result.data.Items) {
                const firstOption = list.querySelector('.hero-custom-dropdown-option');
                list.innerHTML = '';
                list.appendChild(firstOption);

                result.data.Items.forEach(item => {
                    const option = document.createElement('div');
                    option.className = 'hero-custom-dropdown-option';
                    option.setAttribute('data-value', item.Name);
                    option.setAttribute('data-transmission-id', item.Id);
                    option.textContent = item.Name;
                    list.appendChild(option);
                });

                const nativeSelect = dropdown.querySelector('.hero-custom-dropdown-native');
                nativeSelect.innerHTML = '<option value="">Vites Tipi Seçin</option>';
                result.data.Items.forEach(item => {
                    const opt = document.createElement('option');
                    opt.value = item.Name;
                    opt.textContent = item.Name;
                    opt.setAttribute('data-transmission-id', item.Id);
                    nativeSelect.appendChild(opt);
                });

                initTransmissionDropdownOptions(dropdown);
            }
        } catch (error) {
            // silently handle
        } finally {
            loading.classList.add('hidden');
            list.style.display = '';
        }
    }

    function resetTransmissionDropdown() {
        const dropdown = document.getElementById('transmission-dropdown-sell');
        if (!dropdown) return;

        const trigger = dropdown.querySelector('.hero-custom-dropdown-trigger');
        const selectedText = trigger.querySelector('.selected-text');
        const nativeSelect = dropdown.querySelector('.hero-custom-dropdown-native');
        const idInput = document.getElementById('vites-tipi-id-input');

        selectedText.textContent = 'Vites Tipi Seçin';
        selectedText.classList.add('placeholder');
        trigger.setAttribute('data-value', '');
        trigger.setAttribute('data-transmission-id', '');
        nativeSelect.value = '';
        if (idInput) idInput.value = '';
        selectedTransmissionId = null;
    }

    function initTransmissionDropdownOptions(dropdown) {
        const panel = dropdown.querySelector('.hero-custom-dropdown-panel');
        const options = panel.querySelectorAll('.hero-custom-dropdown-option');
        const trigger = dropdown.querySelector('.hero-custom-dropdown-trigger');
        const nativeSelect = dropdown.querySelector('.hero-custom-dropdown-native');
        const selectedText = trigger.querySelector('.selected-text');
        const idInput = document.getElementById('vites-tipi-id-input');
        const formCard = document.querySelector('.hero-form-card');

        options.forEach(option => {
            option.addEventListener('click', function(e) {
                e.stopPropagation();
                const value = this.getAttribute('data-value');
                const transmissionId = this.getAttribute('data-transmission-id');

                selectedText.textContent = value || 'Vites Tipi Seçin';
                selectedText.classList.toggle('placeholder', !value);
                trigger.setAttribute('data-value', value);
                trigger.setAttribute('data-transmission-id', transmissionId || '');
                nativeSelect.value = value;
                if (idInput) idInput.value = transmissionId || '';
                selectedTransmissionId = transmissionId;
                selectedVersionId = null;

                options.forEach(opt => opt.classList.remove('selected'));
                if (value) this.classList.add('selected');

                panel.classList.remove('open');
                trigger.classList.remove('open');
                dropdown.classList.remove('dropdown-open');

                if (formCard) {
                    formCard.classList.remove('dropdown-open');
                    formCard.querySelectorAll('.form-field').forEach(f => { f.style.pointerEvents = ''; f.style.opacity = ''; });
                    formCard.querySelectorAll('button[type="submit"]').forEach(b => { b.style.pointerEvents = ''; b.style.opacity = ''; });
                }

                // Disable all subsequent dropdowns and reset
                ['version', 'color'].forEach(name => {
                    const dd = document.getElementById(`${name}-dropdown-sell`);
                    if (dd) {
                        dd.classList.add('disabled');
                        const trig = dd.querySelector('.hero-custom-dropdown-trigger');
                        if (trig) trig.disabled = true;
                    }
                });
                resetVersionDropdown();
                resetColorDropdown();
                resetKilometreInput();

                // Enable version dropdown
                const versionDropdown = document.getElementById('version-dropdown-sell');
                if (transmissionId && selectedBrandId && selectedYear && selectedModelId && selectedBodytypeId && selectedFueltypeId) {
                    if (versionDropdown) {
                        versionDropdown.classList.remove('disabled');
                        versionDropdown.querySelector('.hero-custom-dropdown-trigger').disabled = false;
                    }
                    loadVersionsFromAPI(selectedBrandId, selectedYear, selectedModelId, selectedBodytypeId, selectedFueltypeId, transmissionId);
                }
            });
        });
    }

    function initTransmissionDropdown() {
        const dropdown = document.getElementById('transmission-dropdown-sell');
        if (!dropdown) return;

        const trigger = dropdown.querySelector('.hero-custom-dropdown-trigger');
        const panel = dropdown.querySelector('.hero-custom-dropdown-panel');
        const formCard = document.querySelector('.hero-form-card');

        trigger.addEventListener('click', function(e) {
            if (dropdown.classList.contains('disabled') || trigger.disabled) return;
            e.stopPropagation();
            e.preventDefault();

            document.querySelectorAll('.hero-custom-dropdown-panel.open').forEach(p => {
                if (p !== panel) {
                    p.classList.remove('open');
                    p.closest('.hero-custom-dropdown').querySelector('.hero-custom-dropdown-trigger').classList.remove('open');
                    p.closest('.hero-custom-dropdown').classList.remove('dropdown-open');
                }
            });

            const isOpen = panel.classList.contains('open');
            if (!isOpen) {
                panel.classList.add('open');
                trigger.classList.add('open');
                dropdown.classList.add('dropdown-open');
                if (formCard) {
                    formCard.classList.add('dropdown-open');
                    const parentField = dropdown.closest('.form-field');
                    formCard.querySelectorAll('.form-field').forEach(f => {
                        if (f !== parentField) { f.style.pointerEvents = 'none'; f.style.opacity = '0.6'; }
                    });
                    formCard.querySelectorAll('button[type="submit"]').forEach(b => { b.style.pointerEvents = 'none'; b.style.opacity = '0.6'; });
                }
            } else {
                panel.classList.remove('open');
                trigger.classList.remove('open');
                dropdown.classList.remove('dropdown-open');
                if (formCard) {
                    formCard.classList.remove('dropdown-open');
                    formCard.querySelectorAll('.form-field').forEach(f => { f.style.pointerEvents = ''; f.style.opacity = ''; });
                    formCard.querySelectorAll('button[type="submit"]').forEach(b => { b.style.pointerEvents = ''; b.style.opacity = ''; });
                }
            }
        });

        initTransmissionDropdownOptions(dropdown);
    }

    // Load versions from API
    async function loadVersionsFromAPI(brandId, modelYear, modelGroupId, bodyTypeId, fuelTypeId, transmissionTypeId) {
        const dropdown = document.getElementById('version-dropdown-sell');
        if (!dropdown) return;

        const loading = dropdown.querySelector('.version-loading');
        const list = dropdown.querySelector('.version-list');

        loading.classList.remove('hidden');
        list.style.display = 'none';

        try {
            const response = await fetch(`/api/arabam/step?step=60&brandId=${brandId}&modelYear=${modelYear}&modelGroupId=${modelGroupId}&bodyTypeId=${bodyTypeId}&fuelTypeId=${fuelTypeId}&transmissionTypeId=${transmissionTypeId}`);
            const result = await response.json();

            if (result.success && result.data && result.data.Items) {
                const firstOption = list.querySelector('.hero-custom-dropdown-option');
                list.innerHTML = '';
                list.appendChild(firstOption);

                result.data.Items.forEach(item => {
                    const option = document.createElement('div');
                    option.className = 'hero-custom-dropdown-option';
                    option.setAttribute('data-value', item.Name);
                    option.setAttribute('data-version-id', item.Id);
                    option.innerHTML = `<span>${item.ShortName || item.Name}</span>`;
                    if (item.Properties && item.Properties.length > 0) {
                        option.innerHTML += `<span class="version-props">${item.Properties.join(' • ')}</span>`;
                    }
                    list.appendChild(option);
                });

                const nativeSelect = dropdown.querySelector('.hero-custom-dropdown-native');
                nativeSelect.innerHTML = '<option value="">Versiyon Seçin</option>';
                result.data.Items.forEach(item => {
                    const opt = document.createElement('option');
                    opt.value = item.Name;
                    opt.textContent = item.ShortName || item.Name;
                    opt.setAttribute('data-version-id', item.Id);
                    nativeSelect.appendChild(opt);
                });

                initVersionDropdownOptions(dropdown);
            }
        } catch (error) {
            // silently handle
        } finally {
            loading.classList.add('hidden');
            list.style.display = '';
        }
    }

    function resetVersionDropdown() {
        const dropdown = document.getElementById('version-dropdown-sell');
        if (!dropdown) return;

        const trigger = dropdown.querySelector('.hero-custom-dropdown-trigger');
        const selectedText = trigger.querySelector('.selected-text');
        const nativeSelect = dropdown.querySelector('.hero-custom-dropdown-native');
        const idInput = document.getElementById('versiyon-id-input');

        selectedText.textContent = 'Versiyon Seçin';
        selectedText.classList.add('placeholder');
        trigger.setAttribute('data-value', '');
        trigger.setAttribute('data-version-id', '');
        nativeSelect.value = '';
        if (idInput) idInput.value = '';
        selectedVersionId = null;
    }

    function initVersionDropdownOptions(dropdown) {
        const panel = dropdown.querySelector('.hero-custom-dropdown-panel');
        const options = panel.querySelectorAll('.hero-custom-dropdown-option');
        const trigger = dropdown.querySelector('.hero-custom-dropdown-trigger');
        const nativeSelect = dropdown.querySelector('.hero-custom-dropdown-native');
        const selectedText = trigger.querySelector('.selected-text');
        const idInput = document.getElementById('versiyon-id-input');
        const formCard = document.querySelector('.hero-form-card');

        options.forEach(option => {
            option.addEventListener('click', function(e) {
                e.stopPropagation();
                const value = this.getAttribute('data-value');
                const versionId = this.getAttribute('data-version-id');
                const shortName = this.querySelector('span')?.textContent || value;

                selectedText.textContent = shortName || 'Versiyon Seçin';
                selectedText.classList.toggle('placeholder', !value);
                trigger.setAttribute('data-value', value);
                trigger.setAttribute('data-version-id', versionId || '');
                nativeSelect.value = value;
                if (idInput) idInput.value = versionId || '';
                selectedVersionId = versionId;

                options.forEach(opt => opt.classList.remove('selected'));
                if (value) this.classList.add('selected');

                panel.classList.remove('open');
                trigger.classList.remove('open');
                dropdown.classList.remove('dropdown-open');

                if (formCard) {
                    formCard.classList.remove('dropdown-open');
                    formCard.querySelectorAll('.form-field').forEach(f => { f.style.pointerEvents = ''; f.style.opacity = ''; });
                    formCard.querySelectorAll('button[type="submit"]').forEach(b => { b.style.pointerEvents = ''; b.style.opacity = ''; });
                }

                // Disable color dropdown and kilometre, then reset
                const colorDropdown = document.getElementById('color-dropdown-sell');
                const kmInput = document.getElementById('kilometre-input');
                if (colorDropdown) {
                    colorDropdown.classList.add('disabled');
                    const colorTrig = colorDropdown.querySelector('.hero-custom-dropdown-trigger');
                    if (colorTrig) colorTrig.disabled = true;
                }
                if (kmInput) {
                    kmInput.disabled = true;
                }
                resetColorDropdown();
                resetKilometreInput();

                // Enable color dropdown AND kilometre input when version is selected
                if (versionId && selectedBrandId && selectedYear && selectedModelId && selectedBodytypeId && selectedFueltypeId && selectedTransmissionId) {
                    if (colorDropdown) {
                        colorDropdown.classList.remove('disabled');
                        colorDropdown.querySelector('.hero-custom-dropdown-trigger').disabled = false;
                    }
                    if (kmInput) {
                        kmInput.disabled = false;
                        kmInput.placeholder = 'Kilometre giriniz';
                    }
                    loadColorsFromAPI(selectedBrandId, selectedYear, selectedModelId, selectedBodytypeId, selectedFueltypeId, selectedTransmissionId, versionId);
                }
            });
        });
    }

    function initVersionDropdown() {
        const dropdown = document.getElementById('version-dropdown-sell');
        if (!dropdown) return;

        const trigger = dropdown.querySelector('.hero-custom-dropdown-trigger');
        const panel = dropdown.querySelector('.hero-custom-dropdown-panel');
        const formCard = document.querySelector('.hero-form-card');

        trigger.addEventListener('click', function(e) {
            if (dropdown.classList.contains('disabled') || trigger.disabled) return;
            e.stopPropagation();
            e.preventDefault();

            document.querySelectorAll('.hero-custom-dropdown-panel.open').forEach(p => {
                if (p !== panel) {
                    p.classList.remove('open');
                    p.closest('.hero-custom-dropdown').querySelector('.hero-custom-dropdown-trigger').classList.remove('open');
                    p.closest('.hero-custom-dropdown').classList.remove('dropdown-open');
                }
            });

            const isOpen = panel.classList.contains('open');
            if (!isOpen) {
                panel.classList.add('open');
                trigger.classList.add('open');
                dropdown.classList.add('dropdown-open');
                if (formCard) {
                    formCard.classList.add('dropdown-open');
                    const parentField = dropdown.closest('.form-field');
                    formCard.querySelectorAll('.form-field').forEach(f => {
                        if (f !== parentField) { f.style.pointerEvents = 'none'; f.style.opacity = '0.6'; }
                    });
                    formCard.querySelectorAll('button[type="submit"]').forEach(b => { b.style.pointerEvents = 'none'; b.style.opacity = '0.6'; });
                }
            } else {
                panel.classList.remove('open');
                trigger.classList.remove('open');
                dropdown.classList.remove('dropdown-open');
                if (formCard) {
                    formCard.classList.remove('dropdown-open');
                    formCard.querySelectorAll('.form-field').forEach(f => { f.style.pointerEvents = ''; f.style.opacity = ''; });
                    formCard.querySelectorAll('button[type="submit"]').forEach(b => { b.style.pointerEvents = ''; b.style.opacity = ''; });
                }
            }
        });

        initVersionDropdownOptions(dropdown);
    }

    // Load colors from API
    async function loadColorsFromAPI(brandId, modelYear, modelGroupId, bodyTypeId, fuelTypeId, transmissionTypeId, modelId) {
        const dropdown = document.getElementById('color-dropdown-sell');
        if (!dropdown) return;

        const loading = dropdown.querySelector('.color-loading');
        const list = dropdown.querySelector('.color-list');

        loading.classList.remove('hidden');
        list.style.display = 'none';

        try {
            const response = await fetch(`/api/arabam/step?step=70&brandId=${brandId}&modelYear=${modelYear}&modelGroupId=${modelGroupId}&bodyTypeId=${bodyTypeId}&fuelTypeId=${fuelTypeId}&transmissionTypeId=${transmissionTypeId}&modelId=${modelId}`);
            const result = await response.json();

            if (result.success && result.data && result.data.Items) {
                const firstOption = list.querySelector('.hero-custom-dropdown-option');
                list.innerHTML = '';
                list.appendChild(firstOption);

                result.data.Items.forEach(item => {
                    const option = document.createElement('div');
                    option.className = 'hero-custom-dropdown-option';
                    option.setAttribute('data-value', item.Name);
                    option.setAttribute('data-color-id', item.Id);
                    option.textContent = item.Name;
                    list.appendChild(option);
                });

                const nativeSelect = dropdown.querySelector('.hero-custom-dropdown-native');
                nativeSelect.innerHTML = '<option value="">Renk Seçin</option>';
                result.data.Items.forEach(item => {
                    const opt = document.createElement('option');
                    opt.value = item.Name;
                    opt.textContent = item.Name;
                    opt.setAttribute('data-color-id', item.Id);
                    nativeSelect.appendChild(opt);
                });

                initColorDropdownOptions(dropdown);
            }
        } catch (error) {
            // silently handle
        } finally {
            loading.classList.add('hidden');
            list.style.display = '';
        }
    }

    function resetColorDropdown() {
        const dropdown = document.getElementById('color-dropdown-sell');
        if (!dropdown) return;

        const trigger = dropdown.querySelector('.hero-custom-dropdown-trigger');
        const selectedText = trigger.querySelector('.selected-text');
        const nativeSelect = dropdown.querySelector('.hero-custom-dropdown-native');
        const idInput = document.getElementById('renk-id-input');

        selectedText.textContent = 'Renk Seçin';
        selectedText.classList.add('placeholder');
        trigger.setAttribute('data-value', '');
        trigger.setAttribute('data-color-id', '');
        nativeSelect.value = '';
        if (idInput) idInput.value = '';
    }

    function initColorDropdownOptions(dropdown) {
        const panel = dropdown.querySelector('.hero-custom-dropdown-panel');
        const options = panel.querySelectorAll('.hero-custom-dropdown-option');
        const trigger = dropdown.querySelector('.hero-custom-dropdown-trigger');
        const nativeSelect = dropdown.querySelector('.hero-custom-dropdown-native');
        const selectedText = trigger.querySelector('.selected-text');
        const idInput = document.getElementById('renk-id-input');
        const formCard = document.querySelector('.hero-form-card');

        options.forEach(option => {
            option.addEventListener('click', function(e) {
                e.stopPropagation();
                const value = this.getAttribute('data-value');
                const colorId = this.getAttribute('data-color-id');

                selectedText.textContent = value || 'Renk Seçin';
                selectedText.classList.toggle('placeholder', !value);
                trigger.setAttribute('data-value', value);
                trigger.setAttribute('data-color-id', colorId || '');
                nativeSelect.value = value;
                if (idInput) idInput.value = colorId || '';

                options.forEach(opt => opt.classList.remove('selected'));
                if (value) this.classList.add('selected');

                panel.classList.remove('open');
                trigger.classList.remove('open');
                dropdown.classList.remove('dropdown-open');

                if (formCard) {
                    formCard.classList.remove('dropdown-open');
                    formCard.querySelectorAll('.form-field').forEach(f => { f.style.pointerEvents = ''; f.style.opacity = ''; });
                    formCard.querySelectorAll('button[type="submit"]').forEach(b => { b.style.pointerEvents = ''; b.style.opacity = ''; });
                }
            });
        });
    }

    function initColorDropdown() {
        const dropdown = document.getElementById('color-dropdown-sell');
        if (!dropdown) return;

        const trigger = dropdown.querySelector('.hero-custom-dropdown-trigger');
        const panel = dropdown.querySelector('.hero-custom-dropdown-panel');
        const formCard = document.querySelector('.hero-form-card');

        trigger.addEventListener('click', function(e) {
            if (dropdown.classList.contains('disabled') || trigger.disabled) return;
            e.stopPropagation();
            e.preventDefault();

            document.querySelectorAll('.hero-custom-dropdown-panel.open').forEach(p => {
                if (p !== panel) {
                    p.classList.remove('open');
                    p.closest('.hero-custom-dropdown').querySelector('.hero-custom-dropdown-trigger').classList.remove('open');
                    p.closest('.hero-custom-dropdown').classList.remove('dropdown-open');
                }
            });

            const isOpen = panel.classList.contains('open');
            if (!isOpen) {
                panel.classList.add('open');
                trigger.classList.add('open');
                dropdown.classList.add('dropdown-open');
                if (formCard) {
                    formCard.classList.add('dropdown-open');
                    const parentField = dropdown.closest('.form-field');
                    formCard.querySelectorAll('.form-field').forEach(f => {
                        if (f !== parentField) { f.style.pointerEvents = 'none'; f.style.opacity = '0.6'; }
                    });
                    formCard.querySelectorAll('button[type="submit"]').forEach(b => { b.style.pointerEvents = 'none'; b.style.opacity = '0.6'; });
                }
            } else {
                panel.classList.remove('open');
                trigger.classList.remove('open');
                dropdown.classList.remove('dropdown-open');
                if (formCard) {
                    formCard.classList.remove('dropdown-open');
                    formCard.querySelectorAll('.form-field').forEach(f => { f.style.pointerEvents = ''; f.style.opacity = ''; });
                    formCard.querySelectorAll('button[type="submit"]').forEach(b => { b.style.pointerEvents = ''; b.style.opacity = ''; });
                }
            }
        });

        initColorDropdownOptions(dropdown);
    }

    function resetKilometreInput() {
        const kmInput = document.getElementById('kilometre-input');
        if (kmInput) {
            kmInput.disabled = true;
            kmInput.value = '';
            kmInput.placeholder = 'Kilometre giriniz';
        }
    }

    // Custom Dropdown Implementation for Hero Section
    // MOVED TO END OF SCRIPT - see DOMContentLoaded at bottom

    function initBrandDropdown() {
        const brandDropdown = document.getElementById('brand-dropdown-sell');
        if (!brandDropdown) return;

        const trigger = brandDropdown.querySelector('.hero-custom-dropdown-trigger');
        const panel = brandDropdown.querySelector('.hero-custom-dropdown-panel');
        const formCard = document.querySelector('.hero-form-card');

        // Toggle dropdown
        trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            e.preventDefault();

            // Close other dropdowns
            document.querySelectorAll('.hero-custom-dropdown-panel.open').forEach(openPanel => {
                if (openPanel !== panel) {
                    openPanel.classList.remove('open');
                    const otherDropdown = openPanel.closest('.hero-custom-dropdown');
                    otherDropdown.querySelector('.hero-custom-dropdown-trigger').classList.remove('open');
                    otherDropdown.classList.remove('dropdown-open');
                }
            });

            // Toggle current dropdown
            const isOpen = panel.classList.contains('open');
            if (!isOpen) {
                panel.classList.add('open');
                trigger.classList.add('open');
                brandDropdown.classList.add('dropdown-open');

                // Load brands if not loaded
                if (!brandsLoaded) {
                    loadBrandsFromAPI();
                }

                // Disable other fields
                if (formCard) {
                    formCard.classList.add('dropdown-open');
                    const parentField = brandDropdown.closest('.form-field');
                    formCard.querySelectorAll('.form-field').forEach(field => {
                        if (field !== parentField) {
                            field.style.pointerEvents = 'none';
                            field.style.opacity = '0.6';
                        }
                    });
                    formCard.querySelectorAll('button[type="submit"]').forEach(btn => {
                        btn.style.pointerEvents = 'none';
                        btn.style.opacity = '0.6';
                    });
                }
            } else {
                panel.classList.remove('open');
                trigger.classList.remove('open');
                brandDropdown.classList.remove('dropdown-open');

                // Enable all fields
                if (formCard) {
                    formCard.classList.remove('dropdown-open');
                    formCard.querySelectorAll('.form-field').forEach(field => {
                        field.style.pointerEvents = '';
                        field.style.opacity = '';
                    });
                    formCard.querySelectorAll('button[type="submit"]').forEach(btn => {
                        btn.style.pointerEvents = '';
                        btn.style.opacity = '';
                    });
                }
            }
        });

        // Initialize default option click handler
        initBrandDropdownOptions(brandDropdown);

        // Initialize year dropdown toggle
        initYearDropdown();

        // Initialize model dropdown toggle
        initModelDropdown();

        // Initialize bodytype dropdown toggle
        initBodytypeDropdown();

        // Initialize fueltype dropdown toggle
        initFueltypeDropdown();

        // Initialize transmission dropdown toggle
        initTransmissionDropdown();

        // Initialize version dropdown toggle
        initVersionDropdown();

        // Initialize color dropdown toggle
        initColorDropdown();
    }

    function initYearDropdown() {
        const yearDropdown = document.getElementById('year-dropdown-sell');
        if (!yearDropdown) return;

        const trigger = yearDropdown.querySelector('.hero-custom-dropdown-trigger');
        const panel = yearDropdown.querySelector('.hero-custom-dropdown-panel');
        const formCard = document.querySelector('.hero-form-card');

        // Toggle dropdown
        trigger.addEventListener('click', function(e) {
            // Don't open if disabled
            if (yearDropdown.classList.contains('disabled') || trigger.disabled) return;

            e.stopPropagation();
            e.preventDefault();

            // Close other dropdowns
            document.querySelectorAll('.hero-custom-dropdown-panel.open').forEach(openPanel => {
                if (openPanel !== panel) {
                    openPanel.classList.remove('open');
                    const otherDropdown = openPanel.closest('.hero-custom-dropdown');
                    otherDropdown.querySelector('.hero-custom-dropdown-trigger').classList.remove('open');
                    otherDropdown.classList.remove('dropdown-open');
                }
            });

            // Toggle current dropdown
            const isOpen = panel.classList.contains('open');
            if (!isOpen) {
                panel.classList.add('open');
                trigger.classList.add('open');
                yearDropdown.classList.add('dropdown-open');

                // Disable other fields
                if (formCard) {
                    formCard.classList.add('dropdown-open');
                    const parentField = yearDropdown.closest('.form-field');
                    formCard.querySelectorAll('.form-field').forEach(field => {
                        if (field !== parentField) {
                            field.style.pointerEvents = 'none';
                            field.style.opacity = '0.6';
                        }
                    });
                    formCard.querySelectorAll('button[type="submit"]').forEach(btn => {
                        btn.style.pointerEvents = 'none';
                        btn.style.opacity = '0.6';
                    });
                }
            } else {
                panel.classList.remove('open');
                trigger.classList.remove('open');
                yearDropdown.classList.remove('dropdown-open');

                // Enable all fields
                if (formCard) {
                    formCard.classList.remove('dropdown-open');
                    formCard.querySelectorAll('.form-field').forEach(field => {
                        field.style.pointerEvents = '';
                        field.style.opacity = '';
                    });
                    formCard.querySelectorAll('button[type="submit"]').forEach(btn => {
                        btn.style.pointerEvents = '';
                        btn.style.opacity = '';
                    });
                }
            }
        });

        // Initialize default option click handler
        initYearDropdownOptions(yearDropdown);
    }

    function initModelDropdown() {
        const modelDropdown = document.getElementById('model-dropdown-sell');
        if (!modelDropdown) return;

        const trigger = modelDropdown.querySelector('.hero-custom-dropdown-trigger');
        const panel = modelDropdown.querySelector('.hero-custom-dropdown-panel');
        const formCard = document.querySelector('.hero-form-card');

        // Toggle dropdown
        trigger.addEventListener('click', function(e) {
            // Don't open if disabled
            if (modelDropdown.classList.contains('disabled') || trigger.disabled) return;

            e.stopPropagation();
            e.preventDefault();

            // Close other dropdowns
            document.querySelectorAll('.hero-custom-dropdown-panel.open').forEach(openPanel => {
                if (openPanel !== panel) {
                    openPanel.classList.remove('open');
                    const otherDropdown = openPanel.closest('.hero-custom-dropdown');
                    otherDropdown.querySelector('.hero-custom-dropdown-trigger').classList.remove('open');
                    otherDropdown.classList.remove('dropdown-open');
                }
            });

            // Toggle current dropdown
            const isOpen = panel.classList.contains('open');
            if (!isOpen) {
                panel.classList.add('open');
                trigger.classList.add('open');
                modelDropdown.classList.add('dropdown-open');

                // Disable other fields
                if (formCard) {
                    formCard.classList.add('dropdown-open');
                    const parentField = modelDropdown.closest('.form-field');
                    formCard.querySelectorAll('.form-field').forEach(field => {
                        if (field !== parentField) {
                            field.style.pointerEvents = 'none';
                            field.style.opacity = '0.6';
                        }
                    });
                    formCard.querySelectorAll('button[type="submit"]').forEach(btn => {
                        btn.style.pointerEvents = 'none';
                        btn.style.opacity = '0.6';
                    });
                }
            } else {
                panel.classList.remove('open');
                trigger.classList.remove('open');
                modelDropdown.classList.remove('dropdown-open');

                // Enable all fields
                if (formCard) {
                    formCard.classList.remove('dropdown-open');
                    formCard.querySelectorAll('.form-field').forEach(field => {
                        field.style.pointerEvents = '';
                        field.style.opacity = '';
                    });
                    formCard.querySelectorAll('button[type="submit"]').forEach(btn => {
                        btn.style.pointerEvents = '';
                        btn.style.opacity = '';
                    });
                }
            }
        });

        // Initialize default option click handler
        initModelDropdownOptions(modelDropdown);
    }

    function initHeroCustomDropdowns() {
        const dropdowns = document.querySelectorAll('.hero-custom-dropdown');
        const formCard = document.querySelector('.hero-form-card');

        dropdowns.forEach(dropdown => {
            // Özel handler'ı olan dropdown'ları atla
            if (dropdown.classList.contains('hero-brand-dropdown')) return;
            if (dropdown.classList.contains('hero-year-dropdown')) return;
            if (dropdown.classList.contains('hero-model-dropdown')) return;
            if (dropdown.classList.contains('hero-bodytype-dropdown')) return;
            if (dropdown.classList.contains('hero-fueltype-dropdown')) return;
            if (dropdown.classList.contains('hero-transmission-dropdown')) return;
            if (dropdown.classList.contains('hero-version-dropdown')) return;
            if (dropdown.classList.contains('hero-color-dropdown')) return;

            const trigger = dropdown.querySelector('.hero-custom-dropdown-trigger');
            const panel = dropdown.querySelector('.hero-custom-dropdown-panel');
            const options = panel.querySelectorAll('.hero-custom-dropdown-option');
            const nativeSelect = dropdown.querySelector('.hero-custom-dropdown-native');
            const selectedText = trigger.querySelector('.selected-text');

            if (!trigger || !panel || !nativeSelect) return;
            
            // Function to disable other form fields
            function disableOtherFields() {
                if (formCard) {
                    formCard.classList.add('dropdown-open');
                    // Find the form-field that contains this dropdown
                    const parentField = dropdown.closest('.form-field');
                    
                    // Disable all form fields except the one containing the open dropdown
                    formCard.querySelectorAll('.form-field').forEach(field => {
                        if (field !== parentField && !field.contains(panel)) {
                            field.style.pointerEvents = 'none';
                            field.style.opacity = '0.6';
                        } else if (field === parentField) {
                            // Ensure parent field of open dropdown is fully opaque
                            field.style.opacity = '1';
                            field.style.pointerEvents = 'auto';
                        }
                    });
                    // Disable submit buttons
                    formCard.querySelectorAll('button[type="submit"]').forEach(btn => {
                        if (!btn.closest('.hero-custom-dropdown')) {
                            btn.style.pointerEvents = 'none';
                            btn.style.opacity = '0.6';
                        }
                    });
                }
            }
            
            // Function to enable all form fields
            function enableAllFields() {
                if (formCard) {
                    formCard.classList.remove('dropdown-open');
                    formCard.querySelectorAll('.form-field').forEach(field => {
                        field.style.pointerEvents = '';
                        field.style.opacity = '';
                    });
                    formCard.querySelectorAll('button[type="submit"]').forEach(btn => {
                        btn.style.pointerEvents = '';
                        btn.style.opacity = '';
                    });
                }
            }
            
            // Toggle dropdown
            trigger.addEventListener('click', function(e) {
                e.stopPropagation();
                e.preventDefault();
                
                // Close other dropdowns
                document.querySelectorAll('.hero-custom-dropdown-panel.open').forEach(openPanel => {
                    if (openPanel !== panel) {
                        openPanel.classList.remove('open');
                        const otherDropdown = openPanel.closest('.hero-custom-dropdown');
                        otherDropdown.querySelector('.hero-custom-dropdown-trigger').classList.remove('open');
                        otherDropdown.classList.remove('dropdown-open');
                    }
                });
                
                // Toggle current dropdown
                const isOpen = panel.classList.contains('open');
                if (!isOpen) {
                    panel.classList.add('open');
                    trigger.classList.add('open');
                    dropdown.classList.add('dropdown-open');
                    disableOtherFields();
                    // Focus first option
                    const firstOption = panel.querySelector('.hero-custom-dropdown-option');
                    if (firstOption) {
                        firstOption.focus();
                    }
                } else {
                    panel.classList.remove('open');
                    trigger.classList.remove('open');
                    dropdown.classList.remove('dropdown-open');
                    enableAllFields();
                }
            });
            
            // Keyboard navigation
            let focusedIndex = -1;
            
            trigger.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    trigger.click();
                } else if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (!panel.classList.contains('open')) {
                        panel.classList.add('open');
                        trigger.classList.add('open');
                    }
                    focusedIndex = Math.min(focusedIndex + 1, options.length - 1);
                    options[focusedIndex]?.focus();
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (panel.classList.contains('open')) {
                        focusedIndex = Math.max(focusedIndex - 1, 0);
                        options[focusedIndex]?.focus();
                    }
                }
            });
            
            options.forEach((option, index) => {
                option.setAttribute('tabindex', '0');
                
                option.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        option.click();
                    } else if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        focusedIndex = Math.min(index + 1, options.length - 1);
                        options[focusedIndex]?.focus();
                    } else if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        focusedIndex = Math.max(index - 1, 0);
                        options[focusedIndex]?.focus();
                    } else if (e.key === 'Escape') {
                        e.preventDefault();
                        panel.classList.remove('open');
                        trigger.classList.remove('open');
                        trigger.focus();
                    }
                });
                
                option.addEventListener('focus', function() {
                    focusedIndex = index;
                    this.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
                });
            });
            
            // Select option
            options.forEach(option => {
                option.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const value = this.getAttribute('data-value');
                    const text = this.textContent.trim();
                    
                    // Update trigger
                    selectedText.textContent = text;
                    selectedText.classList.remove('placeholder');
                    trigger.setAttribute('data-value', value);
                    
                    // Update native select
                    nativeSelect.value = value;
                    nativeSelect.dispatchEvent(new Event('change', { bubbles: true }));
                    
                    // Update selected state
                    options.forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');
                    
                    // Close dropdown
                    panel.classList.remove('open');
                    trigger.classList.remove('open');
                    dropdown.classList.remove('dropdown-open');
                    enableAllFields();
                });
            });
            
            // Initialize selected value from native select
            if (nativeSelect.value) {
                const selectedOption = Array.from(options).find(opt => opt.getAttribute('data-value') === nativeSelect.value);
                if (selectedOption) {
                    selectedText.textContent = selectedOption.textContent.trim();
                    selectedText.classList.remove('placeholder');
                    trigger.setAttribute('data-value', nativeSelect.value);
                    selectedOption.classList.add('selected');
                }
            }
        });
        
        // Close dropdowns on outside click
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.hero-custom-dropdown')) {
                document.querySelectorAll('.hero-custom-dropdown-panel.open').forEach(openPanel => {
                    openPanel.classList.remove('open');
                    const dropdown = openPanel.closest('.hero-custom-dropdown');
                    dropdown.querySelector('.hero-custom-dropdown-trigger').classList.remove('open');
                    dropdown.classList.remove('dropdown-open');
                });
                // Enable all fields when closing
                if (formCard) {
                    formCard.classList.remove('dropdown-open');
                    formCard.querySelectorAll('.form-field').forEach(field => {
                        field.style.pointerEvents = '';
                        field.style.opacity = '';
                    });
                    formCard.querySelectorAll('button[type="submit"]').forEach(btn => {
                        btn.style.pointerEvents = '';
                        btn.style.opacity = '';
                    });
                }
            }
        });
        
        // Close dropdowns on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.hero-custom-dropdown-panel.open').forEach(openPanel => {
                    openPanel.classList.remove('open');
                    const dropdown = openPanel.closest('.hero-custom-dropdown');
                    dropdown.querySelector('.hero-custom-dropdown-trigger').classList.remove('open');
                    dropdown.classList.remove('dropdown-open');
                });
                // Enable all fields when closing
                if (formCard) {
                    formCard.classList.remove('dropdown-open');
                    formCard.querySelectorAll('.form-field').forEach(field => {
                        field.style.pointerEvents = '';
                        field.style.opacity = '';
                    });
                    formCard.querySelectorAll('button[type="submit"]').forEach(btn => {
                        btn.style.pointerEvents = '';
                        btn.style.opacity = '';
                    });
                }
            }
        });
    }
    
    // Form validation for Hero "ARABAMI DEĞERLE" button
    // Native form submit - JS only for validation
    document.addEventListener('DOMContentLoaded', function() {
        // Tab switchers handled by app.js (initHeroTabs)
        
        // Initialize custom dropdowns FIRST
        initHeroCustomDropdowns();
        
        // Initialize brand dropdown
        initBrandDropdown();
        
        const sellForm = document.getElementById('sell-form');
        if (!sellForm) {
            // Form bulunamadı, sessizce devam et
            return;
        }
        
        // Check if already bound
        if (sellForm.dataset.submitHandlerBound === 'true') {
            return;
        }
        sellForm.dataset.submitHandlerBound = 'true';
        
        // No validation - allow native form submit always
        // Form will submit to action="{{ route('evaluation.index') }}" with whatever values are filled
    });
</script>
@endpush

@extends('layouts.app')

@section('title', 'Araçlar - ' . ($settings['site_title'] ?? 'GMSGARAGE'))
@section('description', 'Premium ikinci el araçlarımızı inceleyin. Geniş araç yelpazesi, garantili ve bakımlı araçlar.')

@push('styles')
<style>
    /* Vehicle Card Animations */
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
    
    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
    }
    
    .animate-scale-in {
        animation: scaleIn 0.5s ease-out forwards;
    }
    
    /* Staggered animation delays for cards */
    .vehicle-card-wrapper {
        opacity: 0;
        animation: fadeInUp 0.6s ease-out forwards;
    }
    
    /* Enhanced hover effects */
    .card-vehicle {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .card-vehicle:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 40px -10px rgba(220, 38, 38, 0.3), 0 10px 20px -5px rgba(0, 0, 0, 0.1);
    }
    
    .dark .card-vehicle:hover {
        box-shadow: 0 20px 40px -10px rgba(220, 38, 38, 0.4), 0 10px 20px -5px rgba(0, 0, 0, 0.3);
    }
    
    /* Icon animations on hover */
    .card-vehicle:hover .w-5 {
        animation: iconPulse 0.6s ease-in-out;
    }
    
    @keyframes iconPulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.15);
        }
    }
    
    /* Button hover animations */
    .card-vehicle .btn-primary,
    .card-vehicle a[target="_blank"] {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .card-vehicle:hover .btn-primary {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px -5px rgba(220, 38, 38, 0.5);
    }
    
    .card-vehicle:hover a[target="_blank"] {
        transform: translateY(-2px);
    }
    
    /* Image zoom effect */
    .card-vehicle .group-hover\:scale-110 {
        transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Hero Custom Dropdown Styles (Homepage'den) */
    .hero-custom-dropdown {
        position: relative;
        width: 100%;
        z-index: 1;
    }
    
    .hero-custom-dropdown.dropdown-open {
        z-index: 10000;
    }
    
    .hero-custom-dropdown-trigger {
        width: 100%;
        padding: 0.625rem 1rem;
        background: white;
        border: 2px solid #d1d5db;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        font-weight: 500;
        color: #111827;
        transition: all 0.2s;
    }
    
    .hero-custom-dropdown-trigger:hover {
        border-color: #dc2626;
    }
    
    .hero-custom-dropdown-trigger.open {
        border-color: #dc2626;
    }
    
    .hero-custom-dropdown-trigger .selected-text.placeholder {
        color: #9ca3af;
    }
    
    .dark .hero-custom-dropdown-trigger {
        background: #2a2a2a;
        border-color: #333333;
        color: #f3f4f6;
    }
    
    .dark .hero-custom-dropdown-trigger:hover {
        border-color: #dc2626;
    }
    
    .dark .hero-custom-dropdown-trigger.open {
        border-color: #dc2626;
    }
    
    .dark .hero-custom-dropdown-trigger .selected-text.placeholder {
        color: #9ca3af;
    }
    
    .hero-custom-dropdown-panel {
        position: absolute;
        top: calc(100% + 4px);
        left: 0;
        right: 0;
        background: white;
        border: 2px solid #dc2626;
        border-radius: 0.5rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        max-height: 300px;
        overflow-y: auto;
        z-index: 9999;
        display: none;
        margin-top: 4px;
    }
    
    .dark .hero-custom-dropdown-panel {
        background: #252525;
        border-color: #dc2626;
    }
    
    .hero-custom-dropdown-panel.open {
        display: block;
    }
    
    .hero-custom-dropdown-option {
        padding: 0.75rem 1rem;
        cursor: pointer;
        transition: background-color 0.15s;
        color: #111827;
        font-weight: 500;
    }
    
    .hero-custom-dropdown-option:hover {
        background-color: #f3f4f6;
    }
    
    .hero-custom-dropdown-option.selected {
        background-color: #fee2e2;
        color: #dc2626;
    }
    
    .dark .hero-custom-dropdown-option {
        color: #f3f4f6;
    }
    
    .dark .hero-custom-dropdown-option:hover {
        background-color: #333333;
    }
    
    .dark .hero-custom-dropdown-option.selected {
        background-color: #7f1d1d;
        color: #fecaca;
    }
    
    .hero-custom-dropdown-native {
        position: absolute;
        opacity: 0;
        pointer-events: none;
        width: 0;
        height: 0;
    }
    
    /* Accordion Filter Styles */
    .filter-accordion-item {
        border-bottom: 1px solid #e5e7eb;
        position: relative;
        z-index: 1;
    }
    
    .dark .filter-accordion-item {
        border-bottom-color: #333333;
    }
    
    /* Ensure dropdown panel is always on top */
    .hero-custom-dropdown-panel.open {
        z-index: 99999 !important;
        position: absolute !important;
    }
    
    /* Prevent parent containers from clipping dropdown */
    .filter-accordion-content {
        overflow: visible !important;
    }
    
    .filter-accordion-content.open {
        overflow: visible !important;
    }
    
    /* When dropdown is open, elevate parent */
    .filter-accordion-item.dropdown-active {
        z-index: 10000;
        position: relative;
    }
    
    .hero-custom-dropdown.dropdown-open {
        z-index: 10000;
        position: relative;
    }
    
    .filter-accordion-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        cursor: pointer;
        user-select: none;
        transition: color 0.2s;
        color: #374151;
    }
    
    .dark .filter-accordion-header {
        color: #f3f4f6;
    }
    
    .filter-accordion-header:hover {
        color: #dc2626;
    }
    
    .filter-accordion-header.active {
        color: #dc2626;
    }
    
    .filter-accordion-title {
        font-weight: 600;
        font-size: 0.95rem;
        color: #111827;
    }
    
    .filter-accordion-summary {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 500;
        margin-right: 0.5rem;
    }
    
    .filter-accordion-icon {
        width: 1.25rem;
        height: 1.25rem;
        transition: transform 0.3s ease;
        color: #6b7280;
    }
    
    .dark .filter-accordion-title {
        color: #e5e7eb;
    }
    
    .dark .filter-accordion-summary {
        color: #9ca3af;
    }
    
    .dark .filter-accordion-icon {
        color: #9ca3af;
    }
    
    .filter-accordion-header.active .filter-accordion-icon {
        transform: rotate(180deg);
        color: #dc2626;
    }
    
    .filter-accordion-content {
        max-height: 0;
        overflow: visible !important;
        transition: max-height 0.3s ease-out, opacity 0.2s ease-out, padding 0.3s ease-out;
        opacity: 0;
        padding: 0;
        position: relative;
    }
    
    .filter-accordion-content.open {
        max-height: 500px;
        opacity: 1;
        padding-bottom: 1rem;
        overflow: visible !important;
    }
    
    /* Compact Input Styles */
    .compact-input-group {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
    }
    
    .compact-input {
        width: 100%;
        padding: 0.625rem 0.875rem;
        border: 2px solid #d1d5db;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: #111827;
        background: white;
        transition: all 0.2s;
    }
    
    .compact-input:focus {
        outline: none;
        border-color: #dc2626;
        ring: 2px;
        ring-color: #dc2626;
    }
    
    .compact-input:hover {
        border-color: #dc2626;
    }
    
    .dark .compact-input {
        background: #2a2a2a;
        border-color: #333333;
        color: #f3f4f6;
    }
    
    .dark .compact-input:focus {
        border-color: #dc2626;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.2);
    }
    
    .dark .compact-input:hover {
        border-color: #dc2626;
    }
    
    .dark .compact-input::placeholder {
        color: #9ca3af;
    }
    
    /* Single Input */
    .single-input {
        width: 100%;
        padding: 0.625rem 0.875rem;
        border: 2px solid #d1d5db;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: #111827;
        background: white;
        transition: all 0.2s;
    }
    
    .single-input:focus {
        outline: none;
        border-color: #dc2626;
        ring: 2px;
        ring-color: #dc2626;
    }
    
    .single-input:hover {
        border-color: #dc2626;
    }
    
    .dark .single-input {
        background: #2a2a2a;
        border-color: #333333;
        color: #f3f4f6;
    }
    
    .dark .single-input:focus {
        border-color: #dc2626;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.2);
    }
    
    .dark .single-input:hover {
        border-color: #dc2626;
    }
    
    .dark .single-input::placeholder {
        color: #9ca3af;
    }
    
    /* Error Message */
    .filter-error {
        color: #dc2626;
        font-size: 0.75rem;
        margin-top: 0.25rem;
        display: none;
    }
    
    .filter-error.show {
        display: block;
    }
</style>
@endpush

@section('content')
    <!-- Page Header -->
    <section class="bg-gradient-to-br from-primary-800 via-primary-700 to-primary-900 dark:from-primary-900 dark:via-primary-800 dark:to-primary-900 text-white py-16 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;"></div>
        </div>
        <div class="container-custom relative z-10">
            <nav class="flex items-center space-x-2 text-sm mb-4">
                <a href="{{ route('home') }}" class="text-gray-300 hover:text-white">Anasayfa</a>
                <span class="text-gray-400">/</span>
                <span class="text-white font-semibold">Araçlar</span>
            </nav>
            <h1 class="text-4xl md:text-5xl font-bold mb-3">Araçlarımız</h1>
            <p class="text-xl text-gray-200">Geniş araç yelpazemizden size uygun olanı seçin</p>
        </div>
    </section>

    <!-- Filters & Vehicles -->
    <section class="section-padding bg-gray-50 dark:bg-[#1e1e1e] transition-colors duration-200" style="overflow: visible;">
        <div class="container-custom" style="overflow: visible;">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8" style="overflow: visible;">
                <!-- Filters Sidebar -->
                <aside class="lg:col-span-1" style="overflow: visible;">
                    <div class="bg-white dark:bg-[#252525] rounded-xl shadow-lg dark:shadow-xl p-6 sticky top-28 border border-gray-200 dark:border-gray-800 transition-colors duration-200" style="overflow: visible; position: relative;">
                        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200 dark:border-gray-800">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Filtreler</h2>
                            <a href="{{ route('vehicles.index') }}" class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-semibold transition-colors">
                                Temizle
                            </a>
                        </div>
                        
                        <form method="GET" action="{{ route('vehicles.index') }}" id="filter-form">
                            <!-- 1) Marka -->
                            <div class="filter-accordion-item">
                                <div class="filter-accordion-header" onclick="toggleFilter(this)">
                                    <span class="filter-accordion-title">Marka</span>
                                    <div class="flex items-center">
                                        <span class="filter-accordion-summary" id="brand-summary">{{ request('brand') ?: 'Seçiniz' }}</span>
                                        <svg class="filter-accordion-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="filter-accordion-content">
                                    <div class="hero-custom-dropdown" data-dropdown="brand-filter">
                                        <button type="button" class="hero-custom-dropdown-trigger" data-value="{{ request('brand') ?? '' }}">
                                            <span class="selected-text {{ !request('brand') ? 'placeholder' : '' }}">{{ request('brand') ?: 'Marka seçin' }}</span>
                                            <svg class="arrow w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="hero-custom-dropdown-panel">
                                            <div class="hero-custom-dropdown-option {{ !request('brand') ? 'selected' : '' }}" data-value="">Marka seçin</div>
                                            @foreach($brands as $brand)
                                                <div class="hero-custom-dropdown-option {{ request('brand') == $brand->name ? 'selected' : '' }}" data-value="{{ $brand->name }}">{{ $brand->name }}</div>
                                            @endforeach
                                        </div>
                                        <select name="brand" class="hero-custom-dropdown-native">
                                            <option value="">Marka seçin</option>
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand->name }}" {{ request('brand') == $brand->name ? 'selected' : '' }}>{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- 2) Araç Durumu (Sıfır / İkinci El) -->
                            <div class="filter-accordion-item">
                                <div class="filter-accordion-header" onclick="toggleFilter(this)">
                                    <span class="filter-accordion-title">Araç Durumu</span>
                                    <div class="flex items-center">
                                        <span class="filter-accordion-summary" id="condition-summary">
                                            @if(request('condition') == 'zero_km') Sıfır
                                            @elseif(request('condition') == 'second_hand') İkinci El
                                            @else Seçiniz
                                            @endif
                                        </span>
                                        <svg class="filter-accordion-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="filter-accordion-content">
                                    <div class="hero-custom-dropdown" data-dropdown="condition-filter">
                                        <button type="button" class="hero-custom-dropdown-trigger" data-value="{{ request('condition') ?? '' }}">
                                            <span class="selected-text {{ !request('condition') ? 'placeholder' : '' }}">
                                                @if(request('condition') == 'zero_km') Sıfır
                                                @elseif(request('condition') == 'second_hand') İkinci El
                                                @else Araç durumu seçin
                                                @endif
                                            </span>
                                            <svg class="arrow w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="hero-custom-dropdown-panel">
                                            <div class="hero-custom-dropdown-option {{ !request('condition') ? 'selected' : '' }}" data-value="">Araç durumu seçin</div>
                                            <div class="hero-custom-dropdown-option {{ request('condition') == 'zero_km' ? 'selected' : '' }}" data-value="zero_km">Sıfır</div>
                                            <div class="hero-custom-dropdown-option {{ request('condition') == 'second_hand' ? 'selected' : '' }}" data-value="second_hand">İkinci El</div>
                                        </div>
                                        <select name="condition" class="hero-custom-dropdown-native">
                                            <option value="">Araç durumu seçin</option>
                                            <option value="zero_km" {{ request('condition') == 'zero_km' ? 'selected' : '' }}>Sıfır</option>
                                            <option value="second_hand" {{ request('condition') == 'second_hand' ? 'selected' : '' }}>İkinci El</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- 3) Fiyat -->
                            <div class="filter-accordion-item">
                                <div class="filter-accordion-header" onclick="toggleFilter(this)">
                                    <span class="filter-accordion-title">Fiyat</span>
                                    <div class="flex items-center">
                                        <span class="filter-accordion-summary" id="price-summary">
                                            @if(request('min_price') || request('max_price'))
                                                ₺{{ number_format(request('min_price', 0), 0, ',', '.') }} - ₺{{ request('max_price') ? number_format(request('max_price'), 0, ',', '.') : '∞' }}
                                            @else
                                                Seçiniz
                                            @endif
                                        </span>
                                        <svg class="filter-accordion-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="filter-accordion-content">
                                    <div class="compact-input-group">
                                        <input type="number" name="min_price" value="{{ request('min_price') }}" 
                                               placeholder="Min TL"
                                               class="compact-input"
                                               onchange="updatePriceSummary()"
                                               onblur="validatePriceRange()">
                                        <input type="number" name="max_price" value="{{ request('max_price') }}" 
                                               placeholder="Max TL"
                                               class="compact-input"
                                               onchange="updatePriceSummary()"
                                               onblur="validatePriceRange()">
                                    </div>
                                    <div class="filter-error" id="price-error">Min fiyat, max fiyattan büyük olamaz</div>
                                </div>
                            </div>
                            
                            <!-- 4) Yıl -->
                            <div class="filter-accordion-item">
                                <div class="filter-accordion-header" onclick="toggleFilter(this)">
                                    <span class="filter-accordion-title">Yıl</span>
                                    <div class="flex items-center">
                                        <span class="filter-accordion-summary" id="year-summary">
                                            @if(request('min_year') || request('max_year'))
                                                {{ request('min_year') ?: '1990' }} - {{ request('max_year') ?: date('Y') + 1 }}
                                            @else
                                                Seçiniz
                                            @endif
                                        </span>
                                        <svg class="filter-accordion-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="filter-accordion-content">
                                    <div class="compact-input-group">
                                        <div class="hero-custom-dropdown" data-dropdown="min-year-filter">
                                            <button type="button" class="hero-custom-dropdown-trigger" data-value="{{ request('min_year') ?? '' }}">
                                                <span class="selected-text {{ !request('min_year') ? 'placeholder' : '' }}">{{ request('min_year') ?: 'Min Yıl' }}</span>
                                                <svg class="arrow w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>
                                            <div class="hero-custom-dropdown-panel">
                                                <div class="hero-custom-dropdown-option {{ !request('min_year') ? 'selected' : '' }}" data-value="">Min Yıl</div>
                                                @foreach($years as $year)
                                                    <div class="hero-custom-dropdown-option {{ request('min_year') == $year ? 'selected' : '' }}" data-value="{{ $year }}">{{ $year }}</div>
                                                @endforeach
                                            </div>
                                            <select name="min_year" class="hero-custom-dropdown-native" onchange="updateYearSummary()">
                                                <option value="">Min Yıl</option>
                                                @foreach($years as $year)
                                                    <option value="{{ $year }}" {{ request('min_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="hero-custom-dropdown" data-dropdown="max-year-filter">
                                            <button type="button" class="hero-custom-dropdown-trigger" data-value="{{ request('max_year') ?? '' }}">
                                                <span class="selected-text {{ !request('max_year') ? 'placeholder' : '' }}">{{ request('max_year') ?: 'Max Yıl' }}</span>
                                                <svg class="arrow w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>
                                            <div class="hero-custom-dropdown-panel">
                                                <div class="hero-custom-dropdown-option {{ !request('max_year') ? 'selected' : '' }}" data-value="">Max Yıl</div>
                                                @foreach($years as $year)
                                                    <div class="hero-custom-dropdown-option {{ request('max_year') == $year ? 'selected' : '' }}" data-value="{{ $year }}">{{ $year }}</div>
                                                @endforeach
                                            </div>
                                            <select name="max_year" class="hero-custom-dropdown-native" onchange="updateYearSummary()">
                                                <option value="">Max Yıl</option>
                                                @foreach($years as $year)
                                                    <option value="{{ $year }}" {{ request('max_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- 5) Yakıt Tipi -->
                            <div class="filter-accordion-item">
                                <div class="filter-accordion-header" onclick="toggleFilter(this)">
                                    <span class="filter-accordion-title">Yakıt Tipi</span>
                                    <div class="flex items-center">
                                        <span class="filter-accordion-summary" id="fuel-summary">{{ request('fuel_type') ?: 'Seçiniz' }}</span>
                                        <svg class="filter-accordion-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="filter-accordion-content">
                                    <div class="hero-custom-dropdown" data-dropdown="fuel-filter">
                                        <button type="button" class="hero-custom-dropdown-trigger" data-value="{{ request('fuel_type') ?? '' }}">
                                            <span class="selected-text {{ !request('fuel_type') ? 'placeholder' : '' }}">{{ request('fuel_type') ?: 'Yakıt tipi seçin' }}</span>
                                            <svg class="arrow w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="hero-custom-dropdown-panel">
                                            <div class="hero-custom-dropdown-option {{ !request('fuel_type') ? 'selected' : '' }}" data-value="">Yakıt tipi seçin</div>
                                            <div class="hero-custom-dropdown-option {{ request('fuel_type') == 'Benzin' ? 'selected' : '' }}" data-value="Benzin">Benzin</div>
                                            <div class="hero-custom-dropdown-option {{ request('fuel_type') == 'Dizel' ? 'selected' : '' }}" data-value="Dizel">Dizel</div>
                                            <div class="hero-custom-dropdown-option {{ request('fuel_type') == 'LPG/Benzin' ? 'selected' : '' }}" data-value="LPG/Benzin">LPG / Benzin</div>
                                            <div class="hero-custom-dropdown-option {{ request('fuel_type') == 'Hibrit' ? 'selected' : '' }}" data-value="Hibrit">Hibrit</div>
                                            <div class="hero-custom-dropdown-option {{ request('fuel_type') == 'Elektrikli' ? 'selected' : '' }}" data-value="Elektrikli">Elektrikli</div>
                                        </div>
                                        <select name="fuel_type" class="hero-custom-dropdown-native">
                                            <option value="">Yakıt tipi seçin</option>
                                            <option value="Benzin" {{ request('fuel_type') == 'Benzin' ? 'selected' : '' }}>Benzin</option>
                                            <option value="Dizel" {{ request('fuel_type') == 'Dizel' ? 'selected' : '' }}>Dizel</option>
                                            <option value="LPG/Benzin" {{ request('fuel_type') == 'LPG/Benzin' ? 'selected' : '' }}>LPG / Benzin</option>
                                            <option value="Hibrit" {{ request('fuel_type') == 'Hibrit' ? 'selected' : '' }}>Hibrit</option>
                                            <option value="Elektrikli" {{ request('fuel_type') == 'Elektrikli' ? 'selected' : '' }}>Elektrikli</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- 6) Vites -->
                            <div class="filter-accordion-item">
                                <div class="filter-accordion-header" onclick="toggleFilter(this)">
                                    <span class="filter-accordion-title">Vites</span>
                                    <div class="flex items-center">
                                        <span class="filter-accordion-summary" id="transmission-summary">{{ request('transmission') ?: 'Seçiniz' }}</span>
                                        <svg class="filter-accordion-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="filter-accordion-content">
                                    <div class="hero-custom-dropdown" data-dropdown="transmission-filter">
                                        <button type="button" class="hero-custom-dropdown-trigger" data-value="{{ request('transmission') ?? '' }}">
                                            <span class="selected-text {{ !request('transmission') ? 'placeholder' : '' }}">{{ request('transmission') ?: 'Vites tipi seçin' }}</span>
                                            <svg class="arrow w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="hero-custom-dropdown-panel">
                                            <div class="hero-custom-dropdown-option {{ !request('transmission') ? 'selected' : '' }}" data-value="">Vites tipi seçin</div>
                                            <div class="hero-custom-dropdown-option {{ request('transmission') == 'Otomatik' ? 'selected' : '' }}" data-value="Otomatik">Otomatik</div>
                                            <div class="hero-custom-dropdown-option {{ request('transmission') == 'Manuel' ? 'selected' : '' }}" data-value="Manuel">Manuel</div>
                                            <div class="hero-custom-dropdown-option {{ request('transmission') == 'Yarı Otomatik' ? 'selected' : '' }}" data-value="Yarı Otomatik">Yarı Otomatik</div>
                                        </div>
                                        <select name="transmission" class="hero-custom-dropdown-native">
                                            <option value="">Vites tipi seçin</option>
                                            <option value="Otomatik" {{ request('transmission') == 'Otomatik' ? 'selected' : '' }}>Otomatik</option>
                                            <option value="Manuel" {{ request('transmission') == 'Manuel' ? 'selected' : '' }}>Manuel</option>
                                            <option value="Yarı Otomatik" {{ request('transmission') == 'Yarı Otomatik' ? 'selected' : '' }}>Yarı Otomatik</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- 7) KM -->
                            <div class="filter-accordion-item">
                                <div class="filter-accordion-header" onclick="toggleFilter(this)">
                                    <span class="filter-accordion-title">KM</span>
                                    <div class="flex items-center">
                                        <span class="filter-accordion-summary" id="km-summary">
                                            @if(request('min_km') || request('max_km'))
                                                {{ number_format(request('min_km', 0), 0, ',', '.') }} - {{ request('max_km') ? number_format(request('max_km'), 0, ',', '.') : '∞' }} km
                                            @else
                                                Seçiniz
                                            @endif
                                        </span>
                                        <svg class="filter-accordion-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="filter-accordion-content">
                                    <div class="compact-input-group">
                                        <input type="number" name="min_km" value="{{ request('min_km') }}" 
                                               placeholder="Min KM"
                                               class="compact-input"
                                               oninput="normalizeNumber(this)"
                                               onchange="updateKmSummary()"
                                               onblur="validateKmRange()">
                                        <input type="number" name="max_km" value="{{ request('max_km') }}" 
                                               placeholder="Max KM"
                                               class="compact-input"
                                               oninput="normalizeNumber(this)"
                                               onchange="updateKmSummary()"
                                               onblur="validateKmRange()">
                                    </div>
                                    <div class="filter-error" id="km-error">Min KM, max KM'den büyük olamaz</div>
                                </div>
                            </div>
                            
                            <!-- 8) Kasa Tipi -->
                            <div class="filter-accordion-item">
                                <div class="filter-accordion-header" onclick="toggleFilter(this)">
                                    <span class="filter-accordion-title">Kasa Tipi</span>
                                    <div class="flex items-center">
                                        <span class="filter-accordion-summary" id="body-type-summary">{{ request('body_type') ?: 'Seçiniz' }}</span>
                                        <svg class="filter-accordion-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="filter-accordion-content">
                                    <div class="hero-custom-dropdown" data-dropdown="body-type-filter">
                                        <button type="button" class="hero-custom-dropdown-trigger" data-value="{{ request('body_type') ?? '' }}">
                                            <span class="selected-text {{ !request('body_type') ? 'placeholder' : '' }}">{{ request('body_type') ?: 'Kasa tipi seçin' }}</span>
                                            <svg class="arrow w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="hero-custom-dropdown-panel">
                                            <div class="hero-custom-dropdown-option {{ !request('body_type') ? 'selected' : '' }}" data-value="">Kasa tipi seçin</div>
                                            <div class="hero-custom-dropdown-option {{ request('body_type') == 'Sedan' ? 'selected' : '' }}" data-value="Sedan">Sedan</div>
                                            <div class="hero-custom-dropdown-option {{ request('body_type') == 'SUV' ? 'selected' : '' }}" data-value="SUV">SUV</div>
                                            <div class="hero-custom-dropdown-option {{ request('body_type') == 'Hatchback' ? 'selected' : '' }}" data-value="Hatchback">Hatchback</div>
                                            <div class="hero-custom-dropdown-option {{ request('body_type') == 'Station Wagon' ? 'selected' : '' }}" data-value="Station Wagon">Station Wagon</div>
                                            <div class="hero-custom-dropdown-option {{ request('body_type') == 'Coupe' ? 'selected' : '' }}" data-value="Coupe">Coupe</div>
                                            <div class="hero-custom-dropdown-option {{ request('body_type') == 'Cabrio' ? 'selected' : '' }}" data-value="Cabrio">Cabrio</div>
                                            <div class="hero-custom-dropdown-option {{ request('body_type') == 'Van' ? 'selected' : '' }}" data-value="Van">Van</div>
                                            <div class="hero-custom-dropdown-option {{ request('body_type') == 'Minibüs' ? 'selected' : '' }}" data-value="Minibüs">Minibüs</div>
                                            <div class="hero-custom-dropdown-option {{ request('body_type') == 'Pikap' ? 'selected' : '' }}" data-value="Pikap">Pikap</div>
                                            <div class="hero-custom-dropdown-option {{ request('body_type') == 'Kamyonet' ? 'selected' : '' }}" data-value="Kamyonet">Kamyonet</div>
                                            <div class="hero-custom-dropdown-option {{ request('body_type') == 'Diğer' ? 'selected' : '' }}" data-value="Diğer">Diğer</div>
                                        </div>
                                        <select name="body_type" class="hero-custom-dropdown-native">
                                            <option value="">Kasa tipi seçin</option>
                                            <option value="Sedan" {{ request('body_type') == 'Sedan' ? 'selected' : '' }}>Sedan</option>
                                            <option value="SUV" {{ request('body_type') == 'SUV' ? 'selected' : '' }}>SUV</option>
                                            <option value="Hatchback" {{ request('body_type') == 'Hatchback' ? 'selected' : '' }}>Hatchback</option>
                                            <option value="Station Wagon" {{ request('body_type') == 'Station Wagon' ? 'selected' : '' }}>Station Wagon</option>
                                            <option value="Coupe" {{ request('body_type') == 'Coupe' ? 'selected' : '' }}>Coupe</option>
                                            <option value="Cabrio" {{ request('body_type') == 'Cabrio' ? 'selected' : '' }}>Cabrio</option>
                                            <option value="Van" {{ request('body_type') == 'Van' ? 'selected' : '' }}>Van</option>
                                            <option value="Minibüs" {{ request('body_type') == 'Minibüs' ? 'selected' : '' }}>Minibüs</option>
                                            <option value="Pikap" {{ request('body_type') == 'Pikap' ? 'selected' : '' }}>Pikap</option>
                                            <option value="Kamyonet" {{ request('body_type') == 'Kamyonet' ? 'selected' : '' }}>Kamyonet</option>
                                            <option value="Diğer" {{ request('body_type') == 'Diğer' ? 'selected' : '' }}>Diğer</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- 9) Motor Hacmi -->
                            <div class="filter-accordion-item">
                                <div class="filter-accordion-header" onclick="toggleFilter(this)">
                                    <span class="filter-accordion-title">Motor Hacmi</span>
                                    <div class="flex items-center">
                                        <span class="filter-accordion-summary" id="engine-size-summary">{{ request('engine_size') ? request('engine_size') . 'L' : 'Seçiniz' }}</span>
                                        <svg class="filter-accordion-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="filter-accordion-content">
                                    <div class="hero-custom-dropdown" data-dropdown="engine-size-filter">
                                        <button type="button" class="hero-custom-dropdown-trigger" data-value="{{ request('engine_size') ?? '' }}">
                                            <span class="selected-text {{ !request('engine_size') ? 'placeholder' : '' }}">{{ request('engine_size') ? request('engine_size') . 'L' : 'Motor hacmi seçin' }}</span>
                                            <svg class="arrow w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="hero-custom-dropdown-panel">
                                            <div class="hero-custom-dropdown-option {{ !request('engine_size') ? 'selected' : '' }}" data-value="">Motor hacmi seçin</div>
                                            <div class="hero-custom-dropdown-option {{ request('engine_size') == '1.0' ? 'selected' : '' }}" data-value="1.0">1.0L</div>
                                            <div class="hero-custom-dropdown-option {{ request('engine_size') == '1.2' ? 'selected' : '' }}" data-value="1.2">1.2L</div>
                                            <div class="hero-custom-dropdown-option {{ request('engine_size') == '1.3' ? 'selected' : '' }}" data-value="1.3">1.3L</div>
                                            <div class="hero-custom-dropdown-option {{ request('engine_size') == '1.4' ? 'selected' : '' }}" data-value="1.4">1.4L</div>
                                            <div class="hero-custom-dropdown-option {{ request('engine_size') == '1.5' ? 'selected' : '' }}" data-value="1.5">1.5L</div>
                                            <div class="hero-custom-dropdown-option {{ request('engine_size') == '1.6' ? 'selected' : '' }}" data-value="1.6">1.6L</div>
                                            <div class="hero-custom-dropdown-option {{ request('engine_size') == '1.8' ? 'selected' : '' }}" data-value="1.8">1.8L</div>
                                            <div class="hero-custom-dropdown-option {{ request('engine_size') == '2.0' ? 'selected' : '' }}" data-value="2.0">2.0L</div>
                                            <div class="hero-custom-dropdown-option {{ request('engine_size') == '2.5' ? 'selected' : '' }}" data-value="2.5">2.5L</div>
                                            <div class="hero-custom-dropdown-option {{ request('engine_size') == '3.0' ? 'selected' : '' }}" data-value="3.0">3.0L</div>
                                            <div class="hero-custom-dropdown-option {{ request('engine_size') == '3.5' ? 'selected' : '' }}" data-value="3.5">3.5L</div>
                                            <div class="hero-custom-dropdown-option {{ request('engine_size') == '4.0' ? 'selected' : '' }}" data-value="4.0">4.0L</div>
                                        </div>
                                        <select name="engine_size" class="hero-custom-dropdown-native">
                                            <option value="">Motor hacmi seçin</option>
                                            <option value="1.0" {{ request('engine_size') == '1.0' ? 'selected' : '' }}>1.0L</option>
                                            <option value="1.2" {{ request('engine_size') == '1.2' ? 'selected' : '' }}>1.2L</option>
                                            <option value="1.3" {{ request('engine_size') == '1.3' ? 'selected' : '' }}>1.3L</option>
                                            <option value="1.4" {{ request('engine_size') == '1.4' ? 'selected' : '' }}>1.4L</option>
                                            <option value="1.5" {{ request('engine_size') == '1.5' ? 'selected' : '' }}>1.5L</option>
                                            <option value="1.6" {{ request('engine_size') == '1.6' ? 'selected' : '' }}>1.6L</option>
                                            <option value="1.8" {{ request('engine_size') == '1.8' ? 'selected' : '' }}>1.8L</option>
                                            <option value="2.0" {{ request('engine_size') == '2.0' ? 'selected' : '' }}>2.0L</option>
                                            <option value="2.5" {{ request('engine_size') == '2.5' ? 'selected' : '' }}>2.5L</option>
                                            <option value="3.0" {{ request('engine_size') == '3.0' ? 'selected' : '' }}>3.0L</option>
                                            <option value="3.5" {{ request('engine_size') == '3.5' ? 'selected' : '' }}>3.5L</option>
                                            <option value="4.0" {{ request('engine_size') == '4.0' ? 'selected' : '' }}>4.0L</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- 10) Çekiş -->
                            <div class="filter-accordion-item">
                                <div class="filter-accordion-header" onclick="toggleFilter(this)">
                                    <span class="filter-accordion-title">Çekiş</span>
                                    <div class="flex items-center">
                                        <span class="filter-accordion-summary" id="drive-type-summary">{{ request('drive_type') ?: 'Seçiniz' }}</span>
                                        <svg class="filter-accordion-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="filter-accordion-content">
                                    <div class="hero-custom-dropdown" data-dropdown="drive-type-filter">
                                        <button type="button" class="hero-custom-dropdown-trigger" data-value="{{ request('drive_type') ?? '' }}">
                                            <span class="selected-text {{ !request('drive_type') ? 'placeholder' : '' }}">{{ request('drive_type') ?: 'Çekiş tipi seçin' }}</span>
                                            <svg class="arrow w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="hero-custom-dropdown-panel">
                                            <div class="hero-custom-dropdown-option {{ !request('drive_type') ? 'selected' : '' }}" data-value="">Çekiş tipi seçin</div>
                                            <div class="hero-custom-dropdown-option {{ request('drive_type') == 'Önden Çekiş' ? 'selected' : '' }}" data-value="Önden Çekiş">Önden Çekiş</div>
                                            <div class="hero-custom-dropdown-option {{ request('drive_type') == 'Arkadan İtiş' ? 'selected' : '' }}" data-value="Arkadan İtiş">Arkadan İtiş</div>
                                            <div class="hero-custom-dropdown-option {{ request('drive_type') == '4x4' ? 'selected' : '' }}" data-value="4x4">4x4 / AWD</div>
                                        </div>
                                        <select name="drive_type" class="hero-custom-dropdown-native">
                                            <option value="">Çekiş tipi seçin</option>
                                            <option value="Önden Çekiş" {{ request('drive_type') == 'Önden Çekiş' ? 'selected' : '' }}>Önden Çekiş</option>
                                            <option value="Arkadan İtiş" {{ request('drive_type') == 'Arkadan İtiş' ? 'selected' : '' }}>Arkadan İtiş</option>
                                            <option value="4x4" {{ request('drive_type') == '4x4' ? 'selected' : '' }}>4x4 / AWD</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- 11) Renk -->
                            <div class="filter-accordion-item">
                                <div class="filter-accordion-header" onclick="toggleFilter(this)">
                                    <span class="filter-accordion-title">Renk</span>
                                    <div class="flex items-center">
                                        <span class="filter-accordion-summary" id="color-summary">{{ request('color') ?: 'Seçiniz' }}</span>
                                        <svg class="filter-accordion-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="filter-accordion-content">
                                    <div class="hero-custom-dropdown" data-dropdown="color-filter">
                                        <button type="button" class="hero-custom-dropdown-trigger" data-value="{{ request('color') ?? '' }}">
                                            <span class="selected-text {{ !request('color') ? 'placeholder' : '' }}">{{ request('color') ?: 'Renk seçin' }}</span>
                                            <svg class="arrow w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="hero-custom-dropdown-panel">
                                            <div class="hero-custom-dropdown-option {{ !request('color') ? 'selected' : '' }}" data-value="">Renk seçin</div>
                                            <div class="hero-custom-dropdown-option {{ request('color') == 'Siyah' ? 'selected' : '' }}" data-value="Siyah">Siyah</div>
                                            <div class="hero-custom-dropdown-option {{ request('color') == 'Beyaz' ? 'selected' : '' }}" data-value="Beyaz">Beyaz</div>
                                            <div class="hero-custom-dropdown-option {{ request('color') == 'Gri' ? 'selected' : '' }}" data-value="Gri">Gri</div>
                                            <div class="hero-custom-dropdown-option {{ request('color') == 'Kırmızı' ? 'selected' : '' }}" data-value="Kırmızı">Kırmızı</div>
                                            <div class="hero-custom-dropdown-option {{ request('color') == 'Mavi' ? 'selected' : '' }}" data-value="Mavi">Mavi</div>
                                            <div class="hero-custom-dropdown-option {{ request('color') == 'Lacivert' ? 'selected' : '' }}" data-value="Lacivert">Lacivert</div>
                                            <div class="hero-custom-dropdown-option {{ request('color') == 'Yeşil' ? 'selected' : '' }}" data-value="Yeşil">Yeşil</div>
                                            <div class="hero-custom-dropdown-option {{ request('color') == 'Sarı' ? 'selected' : '' }}" data-value="Sarı">Sarı</div>
                                            <div class="hero-custom-dropdown-option {{ request('color') == 'Turuncu' ? 'selected' : '' }}" data-value="Turuncu">Turuncu</div>
                                            <div class="hero-custom-dropdown-option {{ request('color') == 'Kahverengi' ? 'selected' : '' }}" data-value="Kahverengi">Kahverengi</div>
                                            <div class="hero-custom-dropdown-option {{ request('color') == 'Bej' ? 'selected' : '' }}" data-value="Bej">Bej</div>
                                            <div class="hero-custom-dropdown-option {{ request('color') == 'Bordo' ? 'selected' : '' }}" data-value="Bordo">Bordo</div>
                                        </div>
                                        <select name="color" class="hero-custom-dropdown-native">
                                            <option value="">Renk seçin</option>
                                            <option value="Siyah" {{ request('color') == 'Siyah' ? 'selected' : '' }}>Siyah</option>
                                            <option value="Beyaz" {{ request('color') == 'Beyaz' ? 'selected' : '' }}>Beyaz</option>
                                            <option value="Gri" {{ request('color') == 'Gri' ? 'selected' : '' }}>Gri</option>
                                            <option value="Kırmızı" {{ request('color') == 'Kırmızı' ? 'selected' : '' }}>Kırmızı</option>
                                            <option value="Mavi" {{ request('color') == 'Mavi' ? 'selected' : '' }}>Mavi</option>
                                            <option value="Lacivert" {{ request('color') == 'Lacivert' ? 'selected' : '' }}>Lacivert</option>
                                            <option value="Yeşil" {{ request('color') == 'Yeşil' ? 'selected' : '' }}>Yeşil</option>
                                            <option value="Sarı" {{ request('color') == 'Sarı' ? 'selected' : '' }}>Sarı</option>
                                            <option value="Turuncu" {{ request('color') == 'Turuncu' ? 'selected' : '' }}>Turuncu</option>
                                            <option value="Kahverengi" {{ request('color') == 'Kahverengi' ? 'selected' : '' }}>Kahverengi</option>
                                            <option value="Bej" {{ request('color') == 'Bej' ? 'selected' : '' }}>Bej</option>
                                            <option value="Bordo" {{ request('color') == 'Bordo' ? 'selected' : '' }}>Bordo</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- 12) İlan Tarihi -->
                            <div class="filter-accordion-item">
                                <div class="filter-accordion-header" onclick="toggleFilter(this)">
                                    <span class="filter-accordion-title">İlan Tarihi</span>
                                    <div class="flex items-center">
                                        <span class="filter-accordion-summary" id="ad-date-summary">
                                            @if(request('ad_date'))
                                                @php
                                                    $adDateMap = [
                                                        '24h' => 'Son 24 saat',
                                                        '3d' => 'Son 3 gün',
                                                        '7d' => 'Son 7 gün',
                                                        '30d' => 'Son 30 gün'
                                                    ];
                                                @endphp
                                                {{ $adDateMap[request('ad_date')] ?? 'Seçiniz' }}
                                            @else
                                                Seçiniz
                                            @endif
                                        </span>
                                        <svg class="filter-accordion-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="filter-accordion-content">
                                    <div class="hero-custom-dropdown" data-dropdown="ad-date-filter">
                                        <button type="button" class="hero-custom-dropdown-trigger" data-value="{{ request('ad_date') ?? '' }}">
                                            <span class="selected-text {{ !request('ad_date') ? 'placeholder' : '' }}">{{ request('ad_date') ?: 'İlan tarihi seçin' }}</span>
                                            <svg class="arrow w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="hero-custom-dropdown-panel">
                                            <div class="hero-custom-dropdown-option {{ !request('ad_date') ? 'selected' : '' }}" data-value="">İlan tarihi seçin</div>
                                            <div class="hero-custom-dropdown-option {{ request('ad_date') == '24h' ? 'selected' : '' }}" data-value="24h">Son 24 saat</div>
                                            <div class="hero-custom-dropdown-option {{ request('ad_date') == '3d' ? 'selected' : '' }}" data-value="3d">Son 3 gün</div>
                                            <div class="hero-custom-dropdown-option {{ request('ad_date') == '7d' ? 'selected' : '' }}" data-value="7d">Son 7 gün</div>
                                            <div class="hero-custom-dropdown-option {{ request('ad_date') == '30d' ? 'selected' : '' }}" data-value="30d">Son 30 gün</div>
                                        </div>
                                        <select name="ad_date" class="hero-custom-dropdown-native">
                                            <option value="">İlan tarihi seçin</option>
                                            <option value="24h" {{ request('ad_date') == '24h' ? 'selected' : '' }}>Son 24 saat</option>
                                            <option value="3d" {{ request('ad_date') == '3d' ? 'selected' : '' }}>Son 3 gün</option>
                                            <option value="7d" {{ request('ad_date') == '7d' ? 'selected' : '' }}>Son 7 gün</option>
                                            <option value="30d" {{ request('ad_date') == '30d' ? 'selected' : '' }}>Son 30 gün</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- 13) Araç Arama -->
                            <div class="filter-accordion-item">
                                <div class="filter-accordion-header" onclick="toggleFilter(this)">
                                    <span class="filter-accordion-title">Kelime ile Filtre</span>
                                    <div class="flex items-center">
                                        <span class="filter-accordion-summary" id="keyword-summary">{{ request('keyword') ?: 'Seçiniz' }}</span>
                                        <svg class="filter-accordion-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="filter-accordion-content">
                                    <input type="text" name="keyword" value="{{ request('keyword') }}" 
                                           placeholder="Model, paket, özellik arayın…"
                                           class="single-input dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600"
                                           onchange="updateKeywordSummary()">
                                </div>
                            </div>
                            
                            <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2 shadow-md hover:shadow-lg mt-6">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Ara
                            </button>
                        </form>
                    </div>
                </aside>
                
                <!-- Vehicles Grid -->
                <div class="lg:col-span-3">
                    @if($vehicles->count() > 0)
                        <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 animate-fade-in-up">
                            <div>
                                <p class="text-gray-600 dark:text-gray-300 text-lg">
                                    <span class="font-bold text-gray-900 dark:text-gray-100 text-2xl">{{ $vehicles->total() }}</span> araç bulundu
                                </p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-600 dark:text-gray-300 font-medium">Sırala:</span>
                                <div class="hero-custom-dropdown" data-dropdown="sort-filter">
                                    <button type="button" class="hero-custom-dropdown-trigger border-2 border-gray-200 dark:border-gray-700 dark:bg-[#2a2a2a] dark:text-gray-100" data-value="{{ request('sort', 'newest') }}">
                                        <span class="selected-text">
                                            @php
                                                $sortOptions = [
                                                    'newest' => 'En Yeni İlan',
                                                    'price_asc' => 'En Düşük Fiyat',
                                                    'price_desc' => 'En Yüksek Fiyat',
                                                    'km_asc' => 'En Düşük KM',
                                                    'km_desc' => 'En Yüksek KM',
                                                    'year_desc' => 'En Yeni Model Yılı'
                                                ];
                                            @endphp
                                            {{ $sortOptions[request('sort', 'newest')] ?? 'En Yeni İlan' }}
                                        </span>
                                        <svg class="arrow w-5 h-5 text-gray-400 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                    <div class="hero-custom-dropdown-panel">
                                        <div class="hero-custom-dropdown-option {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}" data-value="newest">En Yeni İlan</div>
                                        <div class="hero-custom-dropdown-option {{ request('sort') == 'price_asc' ? 'selected' : '' }}" data-value="price_asc">En Düşük Fiyat</div>
                                        <div class="hero-custom-dropdown-option {{ request('sort') == 'price_desc' ? 'selected' : '' }}" data-value="price_desc">En Yüksek Fiyat</div>
                                        <div class="hero-custom-dropdown-option {{ request('sort') == 'km_asc' ? 'selected' : '' }}" data-value="km_asc">En Düşük KM</div>
                                        <div class="hero-custom-dropdown-option {{ request('sort') == 'km_desc' ? 'selected' : '' }}" data-value="km_desc">En Yüksek KM</div>
                                        <div class="hero-custom-dropdown-option {{ request('sort') == 'year_desc' ? 'selected' : '' }}" data-value="year_desc">En Yeni Model Yılı</div>
                                    </div>
                                    <form method="GET" action="{{ route('vehicles.index') }}" id="sort-form">
                                        @foreach(request()->except('sort') as $key => $value)
                                            @if(is_array($value))
                                                @foreach($value as $v)
                                                    <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                                @endforeach
                                            @else
                                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                            @endif
                                        @endforeach
                                        <select name="sort" class="hero-custom-dropdown-native" style="display: none;">
                                            <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>En Yeni İlan</option>
                                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>En Düşük Fiyat</option>
                                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>En Yüksek Fiyat</option>
                                            <option value="km_asc" {{ request('sort') == 'km_asc' ? 'selected' : '' }}>En Düşük KM</option>
                                            <option value="km_desc" {{ request('sort') == 'km_desc' ? 'selected' : '' }}>En Yüksek KM</option>
                                            <option value="year_desc" {{ request('sort') == 'year_desc' ? 'selected' : '' }}>En Yeni Model Yılı</option>
                                        </select>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                            @foreach($vehicles as $index => $vehicle)
                                <div class="vehicle-card-wrapper animate-fade-in-up" style="animation-delay: {{ ($index % 9) * 0.1 }}s;">
                                    <x-vehicle-card :vehicle="$vehicle" />
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-12">
                            {{ $vehicles->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="bg-white dark:bg-[#252525] rounded-2xl shadow-xl dark:shadow-2xl dark:border dark:border-gray-800 p-16 text-center transition-colors duration-200">
                            <div class="w-24 h-24 bg-gray-100 dark:bg-[#2a2a2a] rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-3">Araç Bulunamadı</h3>
                            <p class="text-gray-600 dark:text-gray-300 mb-8 text-lg">Seçtiğiniz kriterlere uygun araç bulunmamaktadır.</p>
                            <a href="{{ route('vehicles.index') }}" class="btn btn-primary text-lg px-8">
                                Filtreleri Temizle
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // Hero Custom Dropdown Implementation (Homepage'den)
    document.addEventListener('DOMContentLoaded', function() {
        initHeroCustomDropdowns();
        initFilterAccordions();
    });
    
    function initHeroCustomDropdowns() {
        const dropdowns = document.querySelectorAll('.hero-custom-dropdown');
        
        dropdowns.forEach(dropdown => {
            const trigger = dropdown.querySelector('.hero-custom-dropdown-trigger');
            const panel = dropdown.querySelector('.hero-custom-dropdown-panel');
            const options = panel.querySelectorAll('.hero-custom-dropdown-option');
            const nativeSelect = dropdown.querySelector('.hero-custom-dropdown-native');
            const selectedText = trigger.querySelector('.selected-text');
            
            if (!trigger || !panel) return;
            
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
                    }
                });
                
                // Toggle current dropdown
                const isOpen = panel.classList.contains('open');
                if (!isOpen) {
                    // Ensure dropdown is on top
                    dropdown.classList.add('dropdown-open');
                    panel.classList.add('open');
                    trigger.classList.add('open');
                    
                    // Elevate parent accordion item
                    const accordionItem = dropdown.closest('.filter-accordion-item');
                    if (accordionItem) {
                        accordionItem.classList.add('dropdown-active');
                    }
                    
                    // Force z-index update
                    panel.style.zIndex = '99999';
                    panel.style.position = 'absolute';
                } else {
                    dropdown.classList.remove('dropdown-open');
                    panel.classList.remove('open');
                    trigger.classList.remove('open');
                    
                    // Remove elevation from parent
                    const accordionItem = dropdown.closest('.filter-accordion-item');
                    if (accordionItem) {
                        accordionItem.classList.remove('dropdown-active');
                    }
                }
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
                    
                    // Update native select if exists
                    if (nativeSelect) {
                        nativeSelect.value = value;
                        nativeSelect.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                    
                    // Special handling for sort dropdown
                    if (dropdown.dataset.dropdown === 'sort-filter') {
                        const sortForm = document.getElementById('sort-form');
                        if (sortForm) {
                            sortForm.querySelector('select[name="sort"]').value = value;
                            sortForm.submit();
                            return;
                        }
                    }
                    
                    // Update summary for ad_date
                    if (dropdown.dataset.dropdown === 'ad-date-filter') {
                        const summaryMap = {
                            '24h': 'Son 24 saat',
                            '3d': 'Son 3 gün',
                            '7d': 'Son 7 gün',
                            '30d': 'Son 30 gün'
                        };
                        const summaryEl = document.getElementById('ad-date-summary');
                        if (summaryEl) {
                            summaryEl.textContent = value ? (summaryMap[value] || 'Seçiniz') : 'Seçiniz';
                        }
                    }
                    
                    // Update summary for condition
                    if (dropdown.dataset.dropdown === 'condition-filter') {
                        const summaryMap = {
                            'zero_km': 'Sıfır',
                            'second_hand': 'İkinci El'
                        };
                        const summaryEl = document.getElementById('condition-summary');
                        if (summaryEl) {
                            summaryEl.textContent = value ? (summaryMap[value] || 'Seçiniz') : 'Seçiniz';
                        }
                    }
                    
                    // Update selected state
                    options.forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');
                    
                    // Update summary if in accordion
                    updateFilterSummaryFromDropdown(dropdown, value, text);
                    
                    // Close dropdown
                    dropdown.classList.remove('dropdown-open');
                    panel.classList.remove('open');
                    trigger.classList.remove('open');
                    
                    // Remove elevation from parent
                    const accordionItem = dropdown.closest('.filter-accordion-item');
                    if (accordionItem) {
                        accordionItem.classList.remove('dropdown-active');
                    }
                });
            });
            
            // Initialize selected value
            if (nativeSelect && nativeSelect.value) {
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
                    if (dropdown) {
                        dropdown.querySelector('.hero-custom-dropdown-trigger').classList.remove('open');
                        dropdown.classList.remove('dropdown-open');
                        
                        // Remove elevation from parent
                        const accordionItem = dropdown.closest('.filter-accordion-item');
                        if (accordionItem) {
                            accordionItem.classList.remove('dropdown-active');
                        }
                    }
                });
            }
        });
    }
    
    function updateFilterSummaryFromDropdown(dropdown, value, text) {
        const dropdownId = dropdown.dataset.dropdown;
        const summaryMap = {
            'brand-filter': 'brand-summary',
            'condition-filter': 'condition-summary',
            'fuel-filter': 'fuel-summary',
            'transmission-filter': 'transmission-summary',
            'body-type-filter': 'body-type-summary',
            'engine-size-filter': 'engine-size-summary',
            'drive-type-filter': 'drive-type-summary',
            'color-filter': 'color-summary'
        };
        
        const summaryId = summaryMap[dropdownId];
        if (summaryId) {
            const summaryEl = document.getElementById(summaryId);
            if (summaryEl) {
                if (dropdownId === 'engine-size-filter' && value) {
                    summaryEl.textContent = value + 'L';
                } else if (dropdownId === 'condition-filter') {
                    const condMap = { 'zero_km': 'Sıfır', 'second_hand': 'İkinci El' };
                    summaryEl.textContent = value ? (condMap[value] || 'Seçiniz') : 'Seçiniz';
                } else {
                    summaryEl.textContent = value ? text : 'Seçiniz';
                }
            }
        }
    }
    
    // Filter Accordion Toggle
    function toggleFilter(header) {
        const content = header.nextElementSibling;
        const isOpen = content.classList.contains('open');
        
        if (isOpen) {
            // Accordion kapanıyor — içindeki açık dropdown'ı da kapat
            const openPanel = content.querySelector('.hero-custom-dropdown-panel.open');
            if (openPanel) {
                openPanel.classList.remove('open');
                const dd = openPanel.closest('.hero-custom-dropdown');
                if (dd) {
                    dd.querySelector('.hero-custom-dropdown-trigger')?.classList.remove('open');
                    dd.classList.remove('dropdown-open');
                }
                const item = header.closest('.filter-accordion-item');
                if (item) item.classList.remove('dropdown-active');
            }
            content.classList.remove('open');
            header.classList.remove('active');
        } else {
            content.classList.add('open');
            header.classList.add('active');
            
            // Accordion açılıyor — içinde dropdown varsa otomatik aç
            const dropdown = content.querySelector('.hero-custom-dropdown');
            if (dropdown) {
                const trigger = dropdown.querySelector('.hero-custom-dropdown-trigger');
                if (trigger) {
                    // Kısa gecikme: accordion animasyonu tamamlansın
                    setTimeout(() => { trigger.click(); }, 80);
                }
            } else {
                // Dropdown yoksa (input alanları), ilk input'a odaklan
                const firstInput = content.querySelector('input, select');
                if (firstInput) {
                    setTimeout(() => { firstInput.focus(); }, 80);
                }
            }
        }
    }
    
    function initFilterAccordions() {
        // Open accordions that have values
        document.querySelectorAll('.filter-accordion-item').forEach(item => {
            const header = item.querySelector('.filter-accordion-header');
            const content = item.querySelector('.filter-accordion-content');
            const summary = item.querySelector('.filter-accordion-summary');
            
            if (summary && summary.textContent.trim() !== 'Seçiniz' && summary.textContent.trim() !== '') {
                content.classList.add('open');
                header.classList.add('active');
            }
        });
    }
    
    // Update summaries
    function updatePriceSummary() {
        const minPrice = document.querySelector('input[name="min_price"]').value;
        const maxPrice = document.querySelector('input[name="max_price"]').value;
        const summaryEl = document.getElementById('price-summary');
        
        if (minPrice || maxPrice) {
            const min = minPrice ? parseInt(minPrice).toLocaleString('tr-TR') : '0';
            const max = maxPrice ? parseInt(maxPrice).toLocaleString('tr-TR') : '∞';
            summaryEl.textContent = `₺${min} - ₺${max}`;
        } else {
            summaryEl.textContent = 'Seçiniz';
        }
    }
    
    function updateYearSummary() {
        const minYear = document.querySelector('select[name="min_year"]')?.value || document.querySelector('input[name="min_year"]')?.value;
        const maxYear = document.querySelector('select[name="max_year"]')?.value || document.querySelector('input[name="max_year"]')?.value;
        const summaryEl = document.getElementById('year-summary');
        
        if (minYear || maxYear) {
            const min = minYear || '1990';
            const max = maxYear || new Date().getFullYear() + 1;
            summaryEl.textContent = `${min} - ${max}`;
        } else {
            summaryEl.textContent = 'Seçiniz';
        }
    }
    
    function updateKmSummary() {
        const minKm = document.querySelector('input[name="min_km"]').value;
        const maxKm = document.querySelector('input[name="max_km"]').value;
        const summaryEl = document.getElementById('km-summary');
        
        if (minKm || maxKm) {
            const min = minKm ? parseInt(minKm).toLocaleString('tr-TR') : '0';
            const max = maxKm ? parseInt(maxKm).toLocaleString('tr-TR') : '∞';
            summaryEl.textContent = `${min} - ${max} km`;
        } else {
            summaryEl.textContent = 'Seçiniz';
        }
    }
    
    function updateKeywordSummary() {
        const keyword = document.querySelector('input[name="keyword"]').value;
        const summaryEl = document.getElementById('keyword-summary');
        summaryEl.textContent = keyword || 'Seçiniz';
    }
    
    // Normalize number input
    function normalizeNumber(input) {
        input.value = input.value.replace(/[^\d]/g, '');
    }
    
    // Validate ranges
    function validatePriceRange() {
        const minPrice = parseInt(document.querySelector('input[name="min_price"]').value) || 0;
        const maxPrice = parseInt(document.querySelector('input[name="max_price"]').value) || 0;
        const errorEl = document.getElementById('price-error');
        
        if (minPrice > 0 && maxPrice > 0 && minPrice > maxPrice) {
            errorEl.classList.add('show');
            return false;
        } else {
            errorEl.classList.remove('show');
            return true;
        }
    }
    
    function validateKmRange() {
        const minKm = parseInt(document.querySelector('input[name="min_km"]').value) || 0;
        const maxKm = parseInt(document.querySelector('input[name="max_km"]').value) || 0;
        const errorEl = document.getElementById('km-error');
        
        if (minKm > 0 && maxKm > 0 && minKm > maxKm) {
            errorEl.classList.add('show');
            return false;
        } else {
            errorEl.classList.remove('show');
            return true;
        }
    }
    
    // Form validation before submit
    document.getElementById('filter-form').addEventListener('submit', function(e) {
        if (!validatePriceRange() || !validateKmRange()) {
            e.preventDefault();
            return false;
        }
    });
</script>
@endpush

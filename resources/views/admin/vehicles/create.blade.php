@extends('admin.layouts.app')

@section('title', 'Yeni Araç Ekle - Admin Panel')
@section('page-title', 'Yeni Araç Ekle')
@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-primary-600">Anasayfa</a>
    <span>/</span>
    <a href="{{ route('admin.vehicles.index') }}" class="hover:text-primary-600">Araçlar</a>
    <span>/</span>
    <span>Yeni Ekle</span>
@endsection

@push('styles')
<style>
    /* ─── Step Navigation ─── */
    .step-nav { display:flex; position:relative; background:#f9fafb; border-bottom:2px solid #e5e7eb; }
    .step-nav::after { content:''; position:absolute; bottom:-2px; left:0; height:2px; background:#dc2626; transition:left 0.35s ease,width 0.35s ease; }
    .step-btn {
        flex:1; display:flex; align-items:center; justify-content:center; gap:0.5rem;
        padding:0.875rem 0.5rem; font-size:0.8rem; font-weight:600;
        color:#6b7280; background:transparent; border:none; cursor:pointer;
        transition:color 0.2s,background 0.2s; position:relative; white-space:nowrap;
    }
    .step-btn:hover { color:#374151; background:rgba(0,0,0,0.02); }
    .step-btn.active { color:#dc2626; background:#fff; }
    .step-btn .step-status {
        width:18px; height:18px; border-radius:50%; display:none; align-items:center; justify-content:center;
        flex-shrink:0;
    }
    .step-btn.done .step-status { display:inline-flex; background:#16a34a; }
    .step-btn.warn .step-status { display:inline-flex; background:#f59e0b; }
    .step-btn.done { color:#16a34a; }
    .step-btn.warn { color:#f59e0b; }
    .step-btn.done .step-icon-done { display:block; }
    .step-btn.done .step-icon-warn { display:none; }
    .step-btn.warn .step-icon-done { display:none; }
    .step-btn.warn .step-icon-warn { display:block; }

    /* ─── Step Footer Navigation ─── */
    .step-footer { display:flex; align-items:center; justify-content:space-between; border-top:1px solid #e5e7eb; padding:1rem 1.5rem; background:#fafafa; }
    .step-footer-btn {
        display:inline-flex; align-items:center; gap:0.375rem; padding:0.625rem 1.25rem;
        font-size:0.8125rem; font-weight:600; border-radius:0.5rem; transition:all 0.2s; cursor:pointer; border:none;
    }
    .step-footer-btn.primary { background:#dc2626; color:#fff; }
    .step-footer-btn.primary:hover { background:#b91c1c; }
    .step-footer-btn.secondary { background:#fff; color:#374151; border:1px solid #d1d5db; }
    .step-footer-btn.secondary:hover { background:#f3f4f6; }

    /* ─── Gallery ─── */
    .gallery-item { position:relative; border-radius:0.5rem; overflow:hidden; background:#f9fafb; border:2px solid #e5e7eb; transition:all 0.2s; }
    .gallery-item:hover { border-color:#dc2626; }
    .gallery-item .delete-btn { position:absolute; top:4px; right:4px; background:rgba(220,38,38,0.9); color:#fff; border-radius:0.375rem; padding:4px; cursor:pointer; opacity:0; transition:all 0.2s; }
    .gallery-item:hover .delete-btn { opacity:1; }

    /* ─── Cascade DD ─── */
    .adm-dd-btn:disabled { background:#f9fafb!important; cursor:not-allowed!important; border-color:#e5e7eb!important; box-shadow:none!important; }
    .adm-dd-btn:disabled span { color:#9ca3af!important; }
    .adm-dd-btn:disabled svg { opacity:0.3; }
    .cascade-list { max-height:220px; overflow-y:auto; }
    .vehicle-form-card { overflow:visible!important; }
    .vehicle-tab-nav { overflow:hidden; border-radius:0.75rem 0.75rem 0 0; }
    .adm-dd-list { z-index:9999!important; }

    /* ─── Car Diagram Tooltip ─── */
    .car-part { transition:fill 0.2s ease; cursor:pointer; }
    .car-part:hover { opacity:0.8; }
    .admin-car-tooltip {
        position:absolute; background:#fff; border-radius:8px;
        box-shadow:0 4px 20px rgba(0,0,0,0.15); padding:12px;
        z-index:1000; min-width:140px; display:none;
    }
    .admin-car-tooltip.active { display:block; }
    .admin-car-tooltip-title {
        font-weight:600; font-size:13px; color:#111827;
        margin-bottom:10px; padding-bottom:8px; border-bottom:1px solid #e5e7eb;
    }
    .admin-car-tooltip-opt {
        display:flex; align-items:center; gap:8px; padding:6px 0;
        cursor:pointer; font-size:13px; color:#374151; transition:color 0.15s;
    }
    .admin-car-tooltip-opt:hover { color:#111827; }
    .admin-car-tooltip-opt.selected { font-weight:600; }
    .admin-tt-dot {
        width:16px; height:16px; border-radius:3px; border:2px solid #d1d5db; flex-shrink:0;
    }
    .admin-tt-dot.dot-orijinal { background:#fff; border-color:#d1d5db; }
    .admin-tt-dot.dot-boyali   { background:#3b82f6; border-color:#3b82f6; }
    .admin-tt-dot.dot-lokal    { background:#fbbf24; border-color:#fbbf24; }
    .admin-tt-dot.dot-degismis { background:#dc2626; border-color:#dc2626; }
    .admin-car-tooltip-opt.selected .admin-tt-dot { box-shadow:0 0 0 2px #3b82f6; }
</style>
@endpush

@section('content')

{{-- Page Header --}}
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-6">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </div>
                Yeni Araç Ekle
            </h2>
            <a href="{{ route('admin.vehicles.index') }}"
               class="flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-all text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Geri
            </a>
        </div>
        <p class="text-sm text-gray-500 mt-1 ml-[52px]">Tüm araç bilgilerini eksiksiz doldurun</p>
    </div>
</div>

{{-- Error Messages --}}
@if($errors->any())
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
        <h3 class="text-red-800 font-bold text-sm mb-1">Lütfen hataları düzeltin:</h3>
        <ul class="list-disc list-inside text-red-700 text-sm space-y-0.5">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.vehicles.store') }}" method="POST" enctype="multipart/form-data" id="vehicleForm">
    @csrf

    <div>

        {{-- ════════════════════════════════════
             MAIN CONTENT — Step-by-step form
             ════════════════════════════════════ --}}
        <div>
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-20 vehicle-form-card">

                {{-- Step Navigation --}}
                <div class="vehicle-tab-nav">
                @php
                    $steps = [
                        ['id'=>'kimlik',    'label'=>'Araç Kimliği'],
                        ['id'=>'ilan',      'label'=>'İlan Bilgileri'],
                        ['id'=>'teknik',    'label'=>'Teknik Detaylar'],
                        ['id'=>'gorseller', 'label'=>'Görseller'],
                        ['id'=>'donanim',   'label'=>'Donanımlar'],
                        ['id'=>'hasar',     'label'=>'Hasar & Geçmiş'],
                        ['id'=>'yayin',     'label'=>'Yayın Ayarları'],
                    ];
                @endphp
                <nav class="step-nav" id="stepNav">
                    @foreach($steps as $i => $step)
                    <button type="button" data-tab="{{ $step['id'] }}" data-step="{{ $i }}"
                            onclick="goToStep('{{ $step['id'] }}')"
                            class="step-btn {{ $i===0 ? 'active' : '' }}" id="stepBtn-{{ $step['id'] }}">
                        <span class="step-status">
                            <svg class="step-icon-done w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <svg class="step-icon-warn w-2.5 h-2.5 text-white hidden" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01"/></svg>
                        </span>
                        {{ $step['label'] }}
                    </button>
                    @endforeach
                </nav>
                </div>{{-- /vehicle-tab-nav --}}

                {{-- ─── Tab 1: ARAÇ KİMLİĞİ ────────────────────────────────── --}}
                <div id="vtab-kimlik" class="vehicle-tab-content p-6 space-y-5">

                    {{-- Başlık + Manuel Giriş Butonu --}}
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Araç Kimliği</h3>
                            <p id="kimlikHint" class="text-xs text-gray-500 mt-1">Marka ve yılı seçtikten sonra model, ardından kasa / yakıt / vites / paket bilgileri otomatik dolar.</p>
                        </div>
                        <button type="button" id="manualModeBtn" onclick="toggleManualMode()"
                                class="shrink-0 flex items-center gap-1.5 px-3 py-2 text-xs font-medium rounded-lg border border-gray-300 text-gray-600 hover:border-red-400 hover:text-red-600 transition-colors whitespace-nowrap">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            <span id="manualModeBtnText">Manuel Giriş</span>
                        </button>
                    </div>

                    {{-- ══════════ CASCADE MODU (varsayılan) ══════════ --}}
                    <div id="cascadeSection" class="space-y-4">

                        {{-- Satır 1: Marka (2/3) + Model Yılı (1/3) --}}
                        <div class="grid grid-cols-3 gap-4">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Marka <span class="text-red-500">*</span></label>
                                <div class="adm-dd" id="ddWrap-brand">
                                    <input type="hidden" name="brand" id="ddVal-brand" value="{{ old('brand') }}">
                                    <button type="button" class="adm-dd-btn" id="ddBtn-brand" onclick="toggleCascadeDD('brand')" disabled>
                                        <span id="ddLabel-brand">{{ old('brand') ?: 'Yükleniyor...' }}</span>
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    <ul class="adm-dd-list cascade-list" id="ddList-brand"></ul>
                                </div>
                            </div>
                            <div class="col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Model Yılı <span class="text-red-500">*</span></label>
                                <div class="adm-dd" id="ddWrap-year">
                                    <input type="hidden" name="year" id="ddVal-year" value="{{ old('year') }}">
                                    <button type="button" class="adm-dd-btn" id="ddBtn-year" onclick="toggleCascadeDD('year')">
                                        <span id="ddLabel-year">{{ old('year') ?: 'Yıl Seçiniz' }}</span>
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    <ul class="adm-dd-list cascade-list" id="ddList-year"></ul>
                                </div>
                            </div>
                        </div>

                        {{-- Satır 2: Model / Seri (2/3) --}}
                        <div class="grid grid-cols-3 gap-4">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Model / Seri <span class="text-red-500">*</span></label>
                                <div class="adm-dd" id="ddWrap-model">
                                    <input type="hidden" name="model" id="ddVal-model" value="{{ old('model') }}">
                                    <button type="button" class="adm-dd-btn" id="ddBtn-model" onclick="toggleCascadeDD('model')" disabled>
                                        <span id="ddLabel-model">{{ old('model') ?: 'Önce marka ve yıl seçiniz' }}</span>
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    <ul class="adm-dd-list cascade-list" id="ddList-model"></ul>
                                </div>
                            </div>
                            <div class="col-span-1"></div>
                        </div>

                        {{-- Satır 3: Kasa (1/3) + Yakıt (1/3) + Vites (1/3) + Paket (1/3) --}}
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 space-y-4">
                            <p class="text-xs text-gray-500 font-medium">↓ Model seçildikten sonra aşağıdaki alanlar sırasıyla aktif olur</p>
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kasa Tipi</label>
                                    <div class="adm-dd" id="ddWrap-bodyType">
                                        <input type="hidden" id="ddVal-bodyType">
                                        <button type="button" class="adm-dd-btn" id="ddBtn-bodyType" onclick="toggleCascadeDD('bodyType')" disabled>
                                            <span id="ddLabel-bodyType">Önce model seçiniz</span>
                                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </button>
                                        <ul class="adm-dd-list cascade-list" id="ddList-bodyType"></ul>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Yakıt Tipi</label>
                                    <div class="adm-dd" id="ddWrap-fuelType">
                                        <input type="hidden" id="ddVal-fuelType">
                                        <button type="button" class="adm-dd-btn" id="ddBtn-fuelType" onclick="toggleCascadeDD('fuelType')" disabled>
                                            <span id="ddLabel-fuelType">Önce kasa tipi seçiniz</span>
                                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </button>
                                        <ul class="adm-dd-list cascade-list" id="ddList-fuelType"></ul>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Vites Tipi</label>
                                    <div class="adm-dd" id="ddWrap-transmission">
                                        <input type="hidden" id="ddVal-transmission">
                                        <button type="button" class="adm-dd-btn" id="ddBtn-transmission" onclick="toggleCascadeDD('transmission')" disabled>
                                            <span id="ddLabel-transmission">Önce yakıt tipi seçiniz</span>
                                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </button>
                                        <ul class="adm-dd-list cascade-list" id="ddList-transmission"></ul>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Paket / Versiyon</label>
                                    <div class="adm-dd" id="ddWrap-version">
                                        <input type="hidden" id="ddVal-version">
                                        <button type="button" class="adm-dd-btn" id="ddBtn-version" onclick="toggleCascadeDD('version')" disabled>
                                            <span id="ddLabel-version">Önce vites tipi seçiniz</span>
                                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </button>
                                        <ul class="adm-dd-list cascade-list" id="ddList-version"></ul>
                                    </div>
                                </div>
                                <div class="col-span-2"></div>
                            </div>
                        </div>

                    </div>{{-- /cascadeSection --}}

                    {{-- ══════════ MANUEL GİRİŞ MODU (gizli, butona tıklayınca açılır) ══════════ --}}
                    <div id="manualSection" class="hidden space-y-4">
                        <div class="flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-lg px-4 py-3">
                            <svg class="w-4 h-4 text-amber-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-xs text-amber-800">Manuel giriş modundasınız. Araç bilgilerini aşağıya yazabilirsiniz. Otomatik seçim için "Cascade'e Dön"e tıklayın.</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Marka <span class="text-red-500">*</span></label>
                                <input type="text" id="manualInput-brand"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors text-sm"
                                       placeholder="Örn: Renault, Dacia, Hyundai">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Model / Seri <span class="text-red-500">*</span></label>
                                <input type="text" id="manualInput-model"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors text-sm"
                                       placeholder="Örn: Clio, Sandero, i20">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kasa Tipi</label>
                                <input type="text" id="manualInput-bodyType"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors text-sm"
                                       placeholder="Sedan, Hatchback, SUV, Pick-up…">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Yakıt Tipi</label>
                                <input type="text" id="manualInput-fuelType"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors text-sm"
                                       placeholder="Benzin, Dizel, LPG, Hibrit, Elektrik…">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Vites Tipi</label>
                                <input type="text" id="manualInput-transmission"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors text-sm"
                                       placeholder="Otomatik, Manuel, Yarı Otomatik…">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Paket / Versiyon</label>
                                <input type="text" id="manualInput-version"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors text-sm"
                                       placeholder="Örn: 1.5 dCi Executive, 1.6i Comfort">
                            </div>
                        </div>
                    </div>{{-- /manualSection --}}

                    {{-- Step Footer --}}
                    <div class="step-footer mt-6">
                        <div></div>
                        <button type="button" onclick="goToStep('ilan', true)" class="step-footer-btn primary">
                            Devam: İlan Bilgileri
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>

                </div>

                {{-- ─── Tab 2: İLAN BİLGİLERİ ──────────────────────────────── --}}
                <div id="vtab-ilan" class="vehicle-tab-content p-6 space-y-6 hidden">

                    <h3 class="text-lg font-bold text-gray-900">İlan Bilgileri</h3>

                    {{-- İlan Başlığı --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">İlan Başlığı <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                               placeholder="Örn: Volkswagen Passat 1.6 TDI BlueMotion Comfortline"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                        <p class="mt-1 text-xs text-gray-500">Marka, model ve özelliklerini içeren açıklayıcı başlık yazın</p>
                    </div>

                    {{-- SEO Slug --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            SEO Slug
                            <span class="ml-1 text-xs font-normal text-gray-400">(opsiyonel — boş bırakılırsa başlıktan üretilir)</span>
                        </label>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-400 whitespace-nowrap">/araclar/</span>
                            <input type="text" name="slug" id="slug-input" value="{{ old('slug') }}" placeholder="ornek-arac-adi"
                                   class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors font-mono text-sm">
                        </div>
                        @error('slug')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Fiyat + Kilometre --}}
                    <div class="border-t pt-6">
                        <h4 class="text-sm font-bold text-gray-700 mb-4">Fiyat & Kilometre</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Fiyat (₺) <span class="text-red-500">*</span></label>
                                <input type="number" name="price" value="{{ old('price') }}" required placeholder="0"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors font-bold text-lg">
                                <p class="mt-1 text-xs text-gray-500">Satış fiyatı (KDV dahil)</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kilometre <span class="text-red-500">*</span></label>
                                <input type="text" name="kilometer" id="kilometerInput" value="{{ old('kilometer') ? number_format((int)old('kilometer'), 0, '', '.') : '0' }}" required placeholder="0"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                                       inputmode="numeric">
                                <p class="mt-1 text-xs text-gray-500">Araç toplam kilometre değeri</p>
                            </div>
                        </div>
                    </div>

                    {{-- Takas + Pazarlık --}}
                    <div class="border-t pt-6">
                        <h4 class="text-sm font-bold text-gray-700 mb-4">Tercihler</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <label class="flex items-start gap-3 p-4 border-2 rounded-lg cursor-pointer transition-all w-full
                                {{ old('swap') ? 'border-green-400 bg-green-50' : 'border-gray-200 hover:border-green-300 hover:bg-green-50/50' }}">
                                <input type="checkbox" name="swap" value="1"
                                       class="w-4 h-4 mt-0.5 text-green-600 border-gray-300 rounded focus:ring-green-500"
                                       {{ old('swap') ? 'checked' : '' }}>
                                <div>
                                    <p class="font-bold text-gray-900 text-sm">Takasa Uygun</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Araç için takas kabul edilir</p>
                                </div>
                            </label>
                            <label class="flex items-start gap-3 p-4 border-2 rounded-lg cursor-pointer transition-all w-full
                                {{ old('price_negotiable') ? 'border-amber-400 bg-amber-50' : 'border-gray-200 hover:border-amber-300 hover:bg-amber-50/50' }}">
                                <input type="checkbox" name="price_negotiable" value="1"
                                       class="w-4 h-4 mt-0.5 text-amber-600 border-gray-300 rounded focus:ring-amber-500"
                                       {{ old('price_negotiable') ? 'checked' : '' }}>
                                <div>
                                    <p class="font-bold text-gray-900 text-sm">Pazarlık Payı Var</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Fiyat pazarlığa açıktır</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Araç Durumu (Sıfır / İkinci El) --}}
                    <div class="border-t pt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Araç Durumu <span class="text-xs font-normal text-gray-400">(Sıfır / İkinci El)</span></label>
                        <div class="flex gap-3">
                            @foreach(\App\Models\Vehicle::CONDITIONS as $val => $label)
                            <label class="flex-1 flex items-center gap-3 p-3 border-2 rounded-lg cursor-pointer transition-all
                                {{ old('condition') === $val ? 'border-red-500 bg-red-50' : 'border-gray-200 hover:border-red-300 hover:bg-red-50/50' }}">
                                <input type="radio" name="condition" value="{{ $val }}"
                                       class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500"
                                       {{ old('condition') === $val ? 'checked' : '' }}>
                                <span class="font-semibold text-sm text-gray-800">{{ $label }}</span>
                            </label>
                            @endforeach
                            <label class="flex-1 flex items-center gap-3 p-3 border-2 rounded-lg cursor-pointer transition-all
                                {{ !old('condition') ? 'border-gray-300 bg-gray-50' : 'border-gray-200 hover:border-gray-300' }}">
                                <input type="radio" name="condition" value=""
                                       class="w-4 h-4 text-gray-400 border-gray-300 focus:ring-gray-300"
                                       {{ !old('condition') ? 'checked' : '' }}>
                                <span class="font-medium text-sm text-gray-500">Belirtme</span>
                            </label>
                        </div>
                    </div>

                    {{-- Açıklama --}}
                    <div class="border-t pt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Açıklama</label>
                        <textarea name="description" rows="6" placeholder="Aracınız hakkında detaylı bilgi verin..."
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors resize-none">{{ old('description') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Araç özellikleri, bakım geçmişi, ekstralar hakkında bilgi verin</p>
                    </div>

                    {{-- Step Footer --}}
                    <div class="step-footer mt-6">
                        <button type="button" onclick="goToStep('kimlik')" class="step-footer-btn secondary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            Araç Kimliği
                        </button>
                        <button type="button" onclick="goToStep('teknik', true)" class="step-footer-btn primary">
                            Devam: Teknik Detaylar
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>

                </div>

                {{-- ─── Tab 3: TEKNİK DETAYLAR ─────────────────────────────── --}}
                <div id="vtab-teknik" class="vehicle-tab-content p-6 space-y-6 hidden">

                    <h3 class="text-lg font-bold text-gray-900">Teknik Detaylar</h3>

                    {{-- Renk + Renk Tipi --}}
                    <div>
                        <h4 class="text-sm font-bold text-gray-700 mb-4">Renk</h4>
                        @php
                            $colorOptions = ['Beyaz','Siyah','Gri','Gümüş Gri','Kırmızı','Mavi','Lacivert','Yeşil','Bej','Kahverengi','Sarı','Turuncu','Bordo','Mor','Altın','Bronz','Diğer'];
                            $colorTypeOptions = ['Metalik','Mat','İnci','Normal'];
                            $curColor = old('color', '');
                            $colorManual = $curColor !== '' && !in_array($curColor, $colorOptions);
                            $curColorType = old('color_type', '');
                        @endphp
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Renk</label>
                                <div class="adm-dd" id="ddWrap-color">
                                    <input type="hidden" name="color" id="ddVal-color" value="{{ $curColor }}">
                                    <button type="button" class="adm-dd-btn" id="ddBtn-color" onclick="toggleStaticDD('color')">
                                        <span id="ddLabel-color">{{ $curColor ?: 'Seçiniz' }}</span>
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    <ul class="adm-dd-list" id="ddList-color">
                                        <li data-val="" onclick="selectStaticDD('color','','Seçiniz')">Seçiniz</li>
                                        @foreach($colorOptions as $c)
                                            <li data-val="{{ $c }}" onclick="selectStaticDD('color','{{ $c }}','{{ $c }}')" class="{{ $curColor===$c?'selected':'' }}">{{ $c }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <label class="inline-flex items-center mt-2 text-xs text-gray-600 cursor-pointer">
                                    <input type="checkbox" id="manualColorToggle" class="w-3 h-3 text-red-600 border-gray-300 rounded mr-1.5" {{ $colorManual ? 'checked' : '' }}>
                                    <span>Manuel Gir</span>
                                </label>
                                <input type="text" name="color" id="manualColorInput"
                                       value="{{ $colorManual ? $curColor : '' }}"
                                       placeholder="Beyaz, Siyah..."
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors text-sm mt-1.5 {{ $colorManual ? '' : 'hidden' }}"
                                       {{ $colorManual ? '' : 'disabled' }}>
                                @if($colorManual)<p class="mt-1 text-xs text-amber-600">⚠ "<strong>{{ $curColor }}</strong>" listede yok.</p>@endif
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Renk Tipi</label>
                                <div class="adm-dd" id="ddWrap-colorType">
                                    <input type="hidden" name="color_type" id="ddVal-colorType" value="{{ $curColorType }}">
                                    <button type="button" class="adm-dd-btn" id="ddBtn-colorType" onclick="toggleStaticDD('colorType')">
                                        <span id="ddLabel-colorType">{{ $curColorType ?: 'Seçiniz' }}</span>
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    <ul class="adm-dd-list" id="ddList-colorType">
                                        <li data-val="" onclick="selectStaticDD('colorType','','Seçiniz')">Seçiniz</li>
                                        @foreach($colorTypeOptions as $ct)
                                            <li data-val="{{ $ct }}" onclick="selectStaticDD('colorType','{{ $ct }}','{{ $ct }}')" class="{{ $curColorType===$ct?'selected':'' }}">{{ $ct }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Çekiş + Kapı + Koltuk --}}
                    @php
                        $driveOptions = ['Önden Çekiş','Arkadan İtiş','4x4'];
                        $doorOptions  = [2,3,4,5,6,7,8];
                        $seatOptions  = [2,4,5,6,7,8,9,10,12,15];
                        $curDrive = old('drive_type', '');
                        $curDoor  = old('door_count', '');
                        $curSeat  = old('seat_count', '');
                    @endphp
                    <div class="border-t pt-6">
                        <h4 class="text-sm font-bold text-gray-700 mb-4">Şasi & Kapasite</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Çekiş</label>
                                <div class="adm-dd" id="ddWrap-driveType">
                                    <input type="hidden" name="drive_type" id="ddVal-driveType" value="{{ $curDrive }}">
                                    <button type="button" class="adm-dd-btn" id="ddBtn-driveType" onclick="toggleStaticDD('driveType')">
                                        <span id="ddLabel-driveType">{{ $curDrive ?: 'Seçiniz' }}</span>
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    <ul class="adm-dd-list" id="ddList-driveType">
                                        <li data-val="" onclick="selectStaticDD('driveType','','Seçiniz')">Seçiniz</li>
                                        @foreach($driveOptions as $dr)
                                            <li data-val="{{ $dr }}" onclick="selectStaticDD('driveType','{{ $dr }}','{{ $dr }}')" class="{{ $curDrive===$dr?'selected':'' }}">{{ $dr }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kapı Sayısı</label>
                                <div class="adm-dd" id="ddWrap-doorCount">
                                    <input type="hidden" name="door_count" id="ddVal-doorCount" value="{{ $curDoor }}">
                                    <button type="button" class="adm-dd-btn" id="ddBtn-doorCount" onclick="toggleStaticDD('doorCount')">
                                        <span id="ddLabel-doorCount">{{ $curDoor ? $curDoor.' Kapı' : 'Seçiniz' }}</span>
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    <ul class="adm-dd-list" id="ddList-doorCount">
                                        <li data-val="" onclick="selectStaticDD('doorCount','','Seçiniz')">Seçiniz</li>
                                        @foreach($doorOptions as $d)
                                            <li data-val="{{ $d }}" onclick="selectStaticDD('doorCount','{{ $d }}','{{ $d }} Kapı')" class="{{ (int)$curDoor===$d?'selected':'' }}">{{ $d }} Kapı</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Koltuk Sayısı</label>
                                <div class="adm-dd" id="ddWrap-seatCount">
                                    <input type="hidden" name="seat_count" id="ddVal-seatCount" value="{{ $curSeat }}">
                                    <button type="button" class="adm-dd-btn" id="ddBtn-seatCount" onclick="toggleStaticDD('seatCount')">
                                        <span id="ddLabel-seatCount">{{ $curSeat ? $curSeat.' Koltuk' : 'Seçiniz' }}</span>
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    <ul class="adm-dd-list" id="ddList-seatCount">
                                        <li data-val="" onclick="selectStaticDD('seatCount','','Seçiniz')">Seçiniz</li>
                                        @foreach($seatOptions as $s)
                                            <li data-val="{{ $s }}" onclick="selectStaticDD('seatCount','{{ $s }}','{{ $s }} Koltuk')" class="{{ (int)$curSeat===$s?'selected':'' }}">{{ $s }} Koltuk</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Motor Hacmi + Güç + Tork --}}
                    <div class="border-t pt-6">
                        <h4 class="text-sm font-bold text-gray-700 mb-4">Motor Özellikleri</h4>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Motor Hacmi (cc)</label>
                                    <input type="number" name="engine_size" value="{{ old('engine_size') }}" placeholder="1600" min="0"
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Motor Gücü (HP)</label>
                                    <input type="number" name="horse_power" value="{{ old('horse_power') }}" placeholder="120" min="0"
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tork (Nm)</label>
                                    <input type="number" name="torque" value="{{ old('torque') }}" placeholder="250" min="0"
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors text-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Step Footer --}}
                    <div class="step-footer mt-6">
                        <button type="button" onclick="goToStep('ilan')" class="step-footer-btn secondary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            İlan Bilgileri
                        </button>
                        <button type="button" onclick="goToStep('gorseller', true)" class="step-footer-btn primary">
                            Devam: Görseller
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>

                </div>

                {{-- ─── Tab 4: GÖRSELLER ─────────────────────────────────────── --}}
                <div id="vtab-gorseller" class="vehicle-tab-content p-6 space-y-6 hidden">

                    <h3 class="text-lg font-bold text-gray-900">Araç Görselleri</h3>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ana Görsel <span class="text-xs font-normal text-gray-400 ml-1">(yayınlamak için gerekli)</span></label>
                        <input type="file" name="main_image" id="mainImageInput" accept="image/*" class="hidden">
                        <label for="mainImageInput"
                               class="flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-red-400 hover:bg-red-50 transition-all cursor-pointer group">
                            <svg class="w-12 h-12 text-gray-400 group-hover:text-red-500 mb-2 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-sm font-medium text-gray-700 group-hover:text-red-600">Tıklayın veya görseli sürükleyin</p>
                            <p class="text-xs text-gray-500 mt-1">PNG, JPG — Maks. 5MB</p>
                        </label>
                        <div id="mainPreview" class="mt-3 hidden">
                            <div class="relative inline-block rounded-lg overflow-hidden border-2 border-red-500">
                                <img id="mainPreviewImg" src="" alt="Ana Görsel" class="h-48 w-auto object-cover max-w-full">
                                <button type="button" onclick="removeMainImage()" class="absolute top-2 right-2 bg-red-600 hover:bg-red-700 text-white rounded-lg p-1.5 shadow-lg transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </div>
                        <div class="mt-2 flex items-start gap-2 px-3 py-2 bg-amber-50 border border-amber-200 rounded-lg">
                            <svg class="w-4 h-4 text-amber-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-xs text-amber-700"><span class="font-bold">Önerilen:</span> 1200 × 800 piksel (3:2 oran)</p>
                        </div>
                    </div>

                    <div class="border-t pt-6">
                        <div class="flex items-center justify-between mb-3">
                            <label class="text-sm font-medium text-gray-700">Galeri Görselleri <span class="ml-1 text-xs font-normal text-gray-400">(sürükleyerek sıralayabilirsiniz)</span></label>
                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full" id="galleryCount">0/15</span>
                        </div>
                        <input type="file" id="singleImageInput" accept="image/*" class="hidden">
                        <button type="button" onclick="document.getElementById('singleImageInput').click()"
                                class="w-full border-2 border-dashed border-gray-300 rounded-lg p-5 text-center hover:border-red-400 hover:bg-red-50 transition-all group">
                            <svg class="w-8 h-8 mx-auto text-gray-400 group-hover:text-red-500 mb-1.5 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <p class="text-xs font-medium text-gray-700 group-hover:text-red-600">Görsel Ekle</p>
                        </button>
                        <div id="galleryPreview" class="grid grid-cols-3 sm:grid-cols-5 gap-2 mt-3"></div>
                        <input type="file" name="images[]" id="galleryInput" multiple class="hidden">
                    </div>

                    {{-- Step Footer --}}
                    <div class="step-footer mt-6">
                        <button type="button" onclick="goToStep('teknik')" class="step-footer-btn secondary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            Teknik Detaylar
                        </button>
                        <button type="button" onclick="goToStep('donanim', true)" class="step-footer-btn primary">
                            Devam: Donanımlar
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>

                </div>

                {{-- ─── Tab 5: DONANIMLAR ────────────────────────────────────── --}}
                <div id="vtab-donanim" class="vehicle-tab-content p-6 space-y-4 hidden">

                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-900">Donanım & Özellikler</h3>
                        <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full" id="selectedCount">0 özellik seçili</span>
                    </div>

                    <input type="text" id="featureSearch" placeholder="Donanım ara..."
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors text-sm">

                    <div class="space-y-2" id="featuresContainer">
                        @foreach($featureCategories as $category => $features)
                        <div class="border border-gray-200 rounded-lg">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-t-lg cursor-pointer select-none"
                                 onclick="this.nextElementSibling.classList.toggle('hidden')">
                                <span class="text-sm font-semibold text-gray-700">{{ $category }}</span>
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                            <div class="p-3">
                                <div class="grid grid-cols-2 gap-1.5">
                                    @foreach($features as $feature)
                                    <label class="feature-item flex items-center space-x-2 p-2 border border-gray-200 rounded hover:bg-gray-50 cursor-pointer transition-all text-sm">
                                        <input type="checkbox" name="features[]" value="{{ $feature }}"
                                               class="w-3.5 h-3.5 text-red-600 border-gray-300 rounded focus:ring-red-500"
                                               onchange="updateFeatureCount()"
                                               {{ in_array($feature, old('features', [])) ? 'checked' : '' }}>
                                        <span class="text-gray-700">{{ $feature }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Step Footer --}}
                    <div class="step-footer mt-4">
                        <button type="button" onclick="goToStep('gorseller')" class="step-footer-btn secondary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            Görseller
                        </button>
                        <button type="button" onclick="goToStep('hasar', true)" class="step-footer-btn primary">
                            Devam: Hasar & Geçmiş
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>

                </div>

                {{-- ─── Tab 6: HASAR & GEÇMİŞ ──────────────────────────────── --}}
                <div id="vtab-hasar" class="vehicle-tab-content p-6 space-y-6 hidden">

                    <h3 class="text-lg font-bold text-gray-900">Hasar & Geçmiş Bilgileri</h3>

                    @php
                        $tramerOptions = ['Yok'=>'Yok (Temiz)','Var'=>'Var','Bilinmiyor'=>'Bilinmiyor'];
                        $curTramer = old('tramer_status', '');
                    @endphp
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">
                        <h4 class="font-semibold text-gray-900 mb-4">Tramer & Sahip Bilgisi</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tramer Kaydı</label>
                                <div class="adm-dd" id="ddWrap-tramerStatus">
                                    <input type="hidden" name="tramer_status" id="ddVal-tramerStatus" value="{{ $curTramer }}">
                                    <button type="button" class="adm-dd-btn" id="ddBtn-tramerStatus" onclick="toggleStaticDD('tramerStatus')">
                                        <span id="ddLabel-tramerStatus">{{ $tramerOptions[$curTramer] ?? 'Seçiniz' }}</span>
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    <ul class="adm-dd-list" id="ddList-tramerStatus">
                                        <li data-val="" onclick="selectStaticDD('tramerStatus','','Seçiniz')">Seçiniz</li>
                                        @foreach($tramerOptions as $v=>$l)
                                            <li data-val="{{ $v }}" onclick="selectStaticDD('tramerStatus','{{ $v }}','{{ $l }}')" class="{{ $curTramer===$v?'selected':'' }}">{{ $l }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tramer Tutarı (₺)</label>
                                <input type="number" name="tramer_amount" value="{{ old('tramer_amount') }}" placeholder="0" step="0.01" min="0"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kaçıncı Sahip</label>
                                <input type="number" name="owner_number" value="{{ old('owner_number') }}" placeholder="1" min="1"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors text-sm">
                            </div>
                        </div>
                    </div>

                    {{-- Garanti & Muayene --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">
                        <h4 class="font-semibold text-gray-900 mb-4">Garanti & Muayene</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Garanti Durumu</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <label class="flex items-center justify-center gap-2 p-3 border-2 rounded-lg cursor-pointer transition-all text-sm font-medium
                                        {{ old('has_warranty') ? 'border-green-500 bg-green-50 text-green-700' : 'border-gray-200 hover:border-green-300 text-gray-600' }}" id="warrantyYesLabel">
                                        <input type="radio" name="has_warranty" value="1" class="sr-only" {{ old('has_warranty') ? 'checked' : '' }}
                                               onchange="document.getElementById('warrantyYesLabel').classList.add('border-green-500','bg-green-50','text-green-700');document.getElementById('warrantyNoLabel').classList.remove('border-red-500','bg-red-50','text-red-700');document.getElementById('warrantyNoLabel').classList.add('border-gray-200','text-gray-600');">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Var
                                    </label>
                                    <label class="flex items-center justify-center gap-2 p-3 border-2 rounded-lg cursor-pointer transition-all text-sm font-medium
                                        {{ !old('has_warranty') && old('tramer_status') ? 'border-red-500 bg-red-50 text-red-700' : 'border-gray-200 hover:border-red-300 text-gray-600' }}" id="warrantyNoLabel">
                                        <input type="radio" name="has_warranty" value="0" class="sr-only" {{ !old('has_warranty') && old('tramer_status') ? 'checked' : '' }}
                                               onchange="document.getElementById('warrantyNoLabel').classList.add('border-red-500','bg-red-50','text-red-700');document.getElementById('warrantyYesLabel').classList.remove('border-green-500','bg-green-50','text-green-700');document.getElementById('warrantyYesLabel').classList.add('border-gray-200','text-gray-600');">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        Yok
                                    </label>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Muayene Tarihi</label>
                                <input type="date" name="inspection_date" value="{{ old('inspection_date') }}"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Garanti Bitiş Tarihi</label>
                                <input type="date" name="warranty_end_date" value="{{ old('warranty_end_date') }}"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors text-sm">
                            </div>
                        </div>
                    </div>

                    {{-- Ekspertiz Araç Diyagramı --}}
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-5 py-3 border-b border-gray-200">
                            <h4 class="font-semibold text-gray-900 text-sm">Boya & Parça Durumu</h4>
                            <p class="text-xs text-gray-500 mt-0.5">Araç üzerinde tıklayarak parça durumunu belirleyin</p>
                        </div>
                        <div class="p-5">
                            <div class="flex flex-col items-center" style="position:relative;" id="adminCarDiagramWrapper">
                                {{-- Tooltip --}}
                                <div class="admin-car-tooltip" id="adminCarTooltip">
                                    <div class="admin-car-tooltip-title" id="adminTooltipTitle">Parça</div>
                                    <div class="admin-car-tooltip-opt" data-val="ORIJINAL"><span class="admin-tt-dot dot-orijinal"></span>Orijinal</div>
                                    <div class="admin-car-tooltip-opt" data-val="BOYALI"><span class="admin-tt-dot dot-boyali"></span>Boyalı</div>
                                    <div class="admin-car-tooltip-opt" data-val="LOKAL_BOYALI"><span class="admin-tt-dot dot-lokal"></span>Lokal Boyalı</div>
                                    <div class="admin-car-tooltip-opt" data-val="DEGISMIS"><span class="admin-tt-dot dot-degismis"></span>Değişmiş</div>
                                </div>
                                <div style="max-width:320px;width:100%;">
                                    @include('admin.vehicles._car_diagram_svg')
                                </div>
                                <div class="flex gap-4 mt-3 text-xs text-gray-500">
                                    <span class="flex items-center gap-1"><span class="inline-block w-3 h-3 rounded-full bg-blue-500"></span> Boyalı</span>
                                    <span class="flex items-center gap-1"><span class="inline-block w-3 h-3 rounded-full bg-amber-400"></span> Lokal Boyalı</span>
                                    <span class="flex items-center gap-1"><span class="inline-block w-3 h-3 rounded-full bg-red-600"></span> Değişmiş</span>
                                </div>
                            </div>
                            {{-- Hidden inputs for painted_parts[] and replaced_parts[] --}}
                            <div id="adminPartInputs"></div>
                        </div>
                    </div>

                    {{-- Step Footer --}}
                    <div class="step-footer mt-6">
                        <button type="button" onclick="goToStep('donanim')" class="step-footer-btn secondary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            Donanımlar
                        </button>
                        <button type="button" onclick="goToStep('yayin', true)" class="step-footer-btn primary">
                            Devam: Yayın Ayarları
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>

                </div>

                {{-- ─── Tab 7: YAYIN AYARLARI (Son Adım) ─────────────────────── --}}
                <div id="vtab-yayin" class="vehicle-tab-content p-6 space-y-6 hidden">

                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Yayın Ayarları</h3>
                        <p class="text-xs text-gray-500 mt-1">Araç bilgilerinizi tamamladınız. Şimdi yayın durumunu belirleyin ve kaydedin.</p>
                    </div>

                    {{-- Yayın Durumu --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="flex items-start gap-4 p-5 border-2 rounded-xl cursor-pointer transition-all hover:border-red-400 hover:bg-red-50/50
                            {{ old('is_active', true) ? 'border-red-500 bg-red-50' : 'border-gray-200' }}">
                            <input type="checkbox" name="is_active" value="1" class="w-5 h-5 mt-0.5 text-red-600 border-gray-300 rounded focus:ring-red-500"
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <div>
                                <p class="font-bold text-gray-900">Aktif (Yayında)</p>
                                <p class="text-sm text-gray-500 mt-1">Araç web sitesinde ziyaretçilere görünür olacak</p>
                            </div>
                        </label>
                        <label class="flex items-start gap-4 p-5 border-2 rounded-xl cursor-pointer transition-all hover:border-yellow-400 hover:bg-yellow-50/50
                            {{ old('is_featured') ? 'border-yellow-500 bg-yellow-50' : 'border-gray-200' }}">
                            <input type="checkbox" name="is_featured" value="1" class="w-5 h-5 mt-0.5 text-yellow-500 border-gray-300 rounded focus:ring-yellow-400"
                                   {{ old('is_featured') ? 'checked' : '' }}>
                            <div>
                                <p class="font-bold text-gray-900">Öne Çıkarılmış</p>
                                <p class="text-sm text-gray-500 mt-1">Anasayfada vitrin bölümünde gösterilir</p>
                            </div>
                        </label>
                    </div>

                    {{-- Araç Satış Durumu --}}
                    <div class="border-t pt-6">
                        <label class="block text-sm font-bold text-gray-700 mb-3">Araç Durumu</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach(\App\Models\Vehicle::STATUSES as $val => $label)
                            <label class="flex items-center gap-3 p-3 border-2 rounded-lg cursor-pointer transition-all
                                {{ old('vehicle_status','available')===$val ? 'border-red-500 bg-red-50' : 'border-gray-200 hover:border-red-300 hover:bg-red-50/50' }}">
                                <input type="radio" name="vehicle_status" value="{{ $val }}"
                                       class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500"
                                       {{ old('vehicle_status','available')===$val ? 'checked' : '' }}>
                                <span class="text-sm font-semibold text-gray-800">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Entegrasyon --}}
                    <div class="border-t pt-6">
                        <h4 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            Sahibinden.com Entegrasyonu
                            <span class="text-xs font-normal text-gray-400">(opsiyonel)</span>
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">İlan Linki</label>
                                <input type="url" name="sahibinden_url" value="{{ old('sahibinden_url') }}" placeholder="https://www.sahibinden.com/..."
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">İlan No</label>
                                <input type="text" name="sahibinden_id" value="{{ old('sahibinden_id') }}" placeholder="123456789"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors font-mono text-sm">
                            </div>
                        </div>
                    </div>

                    {{-- Kaydet Butonları --}}
                    <div class="border-t pt-6">
                        <input type="hidden" name="action" id="formAction" value="publish">
                        <div class="flex flex-col sm:flex-row gap-3">
                            <button type="submit" data-action="publish"
                                    class="submit-btn flex-1 px-6 py-4 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition-all text-sm flex items-center justify-center gap-2 shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Kaydet ve Yayınla
                            </button>
                            <button type="submit" data-action="draft"
                                    class="submit-btn px-6 py-4 border-2 border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-all text-sm flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                                Taslak Kaydet
                            </button>
                        </div>
                    </div>

                    {{-- Step Footer --}}
                    <div class="step-footer mt-2">
                        <button type="button" onclick="goToStep('hasar')" class="step-footer-btn secondary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            Hasar & Geçmiş
                        </button>
                        <div></div>
                    </div>

                </div>

            </div>
        </div>

    </div>
</form>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
// ─── Step System ──────────────────────────────────────────────────────────────
const STEP_ORDER = ['kimlik','ilan','teknik','gorseller','donanim','hasar','yayin'];
let currentStep = 'kimlik';
const stepVisited = { kimlik: true };

function goToStep(tabId, validate) {
    if (validate && currentStep !== tabId) {
        const warnings = checkStepCompletion(currentStep);
        if (warnings.length > 0) {
            Swal.fire({
                title: 'Eksik Alanlar',
                html: '<p class="text-sm text-gray-500 mb-3">Aşağıdaki alanlar henüz doldurulmadı:</p><ul class="text-left text-sm text-gray-600 space-y-1">' +
                      warnings.map(w => '<li class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-amber-500 rounded-full flex-shrink-0"></span>' + w + '</li>').join('') + '</ul>' +
                      '<p class="text-xs text-gray-400 mt-3">Yine de devam edebilirsiniz.</p>',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Devam Et',
                cancelButtonText: 'Düzelt',
                reverseButtons: true,
                customClass: { popup: 'rounded-xl shadow-2xl', title: 'text-xl font-bold text-gray-900' },
            }).then(r => { if (r.isConfirmed) activateStep(tabId); });
            return;
        }
    }
    activateStep(tabId);
}

function activateStep(tabId) {
    currentStep = tabId;
    stepVisited[tabId] = true;

    document.querySelectorAll('.step-btn').forEach(btn => btn.classList.remove('active'));
    const activeBtn = document.getElementById('stepBtn-' + tabId);
    if (activeBtn) activeBtn.classList.add('active');

    document.querySelectorAll('.vehicle-tab-content').forEach(c => {
        c.classList.toggle('hidden', c.id !== 'vtab-' + tabId);
    });

    updateAllStepStatuses();
    document.querySelector('.vehicle-form-card').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// Backward compat
function switchVehicleTab(tabId) { goToStep(tabId); }

// ─── Step Completion Checks ───────────────────────────────────────────────────
function checkStepCompletion(stepId) {
    const warnings = [];
    switch (stepId) {
        case 'kimlik':
            if (!document.getElementById('ddVal-year').value) warnings.push('Model Yılı');
            if (typeof isManualMode !== 'undefined' && isManualMode) {
                if (!(document.getElementById('manualInput-brand') || {}).value) warnings.push('Marka');
                if (!(document.getElementById('manualInput-model') || {}).value) warnings.push('Model / Seri');
            } else {
                if (!document.getElementById('ddVal-brand').value) warnings.push('Marka');
                if (!document.getElementById('ddVal-model').value) warnings.push('Model / Seri');
            }
            break;
        case 'ilan':
            if (!document.querySelector('[name="title"]').value.trim()) warnings.push('İlan Başlığı');
            if (!document.querySelector('[name="price"]').value) warnings.push('Fiyat');
            if (!document.querySelector('[name="kilometer"]').value) warnings.push('Kilometre');
            break;
        case 'gorseller':
            const hasMain = document.getElementById('mainImageInput').files.length > 0;
            if (!hasMain) warnings.push('Ana Görsel');
            break;
    }
    return warnings;
}

function getStepStatus(stepId) {
    if (!stepVisited[stepId]) return 'pending';
    const w = checkStepCompletion(stepId);
    if (w.length > 0) return 'warn';
    return 'done';
}

function updateAllStepStatuses() {
    STEP_ORDER.forEach(function(id) {
        const btn = document.getElementById('stepBtn-' + id);
        if (!btn) return;
        btn.classList.remove('done', 'warn');
        if (btn.classList.contains('active')) return;
        const status = getStepStatus(id);
        if (status === 'done') btn.classList.add('done');
        else if (status === 'warn') btn.classList.add('warn');
    });
}

// Re-check when user interacts with form fields
document.addEventListener('input', debounce(updateAllStepStatuses, 300));
document.addEventListener('change', debounce(updateAllStepStatuses, 300));

function debounce(fn, ms) {
    let t; return function() { clearTimeout(t); t = setTimeout(fn, ms); };
}

// ─── Document Ready ───────────────────────────────────────────────────────────
$(document).ready(function(){

    // ── CASCADE DD SİSTEMİ ────────────────────────────────────────────────────
    let cascadeData = {
        brandId: null, brandName: null, brandArabamId: null,
        year: null,
        modelId: null, modelName: null, modelArabamId: null,
        bodyTypeId: null, bodyTypeName: null,
        fuelTypeId: null, fuelTypeName: null,
        transmissionId: null, transmissionName: null,
        versionId: null, versionName: null
    };

    // Dropdown aç/kapat
    window.toggleCascadeDD = function(id) {
        const btn  = document.getElementById('ddBtn-' + id);
        const list = document.getElementById('ddList-' + id);
        if (!btn || btn.disabled) return;
        const isOpen = list.classList.contains('open');
        document.querySelectorAll('.adm-dd-list.open').forEach(function(l) {
            l.classList.remove('open');
            const wrap = l.closest('.adm-dd');
            if (wrap) { const b = wrap.querySelector('.adm-dd-btn'); if (b) b.classList.remove('open'); }
        });
        if (!isOpen) {
            list.classList.add('open'); btn.classList.add('open');
            var si = list.querySelector('.adm-dd-search input');
            if (si) { si.value = ''; si.dispatchEvent(new Event('input')); setTimeout(function(){ si.focus(); }, 50); }
        }
    };

    window.toggleStaticDD = function(id) {
        const btn  = document.getElementById('ddBtn-' + id);
        const list = document.getElementById('ddList-' + id);
        if (!btn || !list) return;
        const isOpen = list.classList.contains('open');
        document.querySelectorAll('.adm-dd-list.open').forEach(function(l) {
            l.classList.remove('open');
            const wrap = l.closest('.adm-dd');
            if (wrap) { const b = wrap.querySelector('.adm-dd-btn'); if (b) b.classList.remove('open'); }
        });
        if (!isOpen) {
            list.classList.add('open'); btn.classList.add('open');
            var si = list.querySelector('.adm-dd-search input');
            if (si) { si.value = ''; si.dispatchEvent(new Event('input')); setTimeout(function(){ si.focus(); }, 50); }
        }
    };

    window.selectStaticDD = function(id, val, label) {
        var valEl   = document.getElementById('ddVal-' + id);
        var labelEl = document.getElementById('ddLabel-' + id);
        var list    = document.getElementById('ddList-' + id);
        var btn     = document.getElementById('ddBtn-' + id);
        if (valEl)   valEl.value = val;
        if (labelEl) { labelEl.textContent = label; labelEl.style.color = val ? '#111827' : '#9ca3af'; }
        if (list) {
            list.querySelectorAll('li').forEach(function(l) { l.classList.remove('selected'); });
            var selected = list.querySelector('li[data-val="' + val + '"]');
            if (selected) selected.classList.add('selected');
            list.classList.remove('open');
        }
        if (btn) btn.classList.remove('open');
    };

    // Dropdown durumunu sıfırla (disabled / loading)
    function setCascadeState(id, placeholder) {
        const btn   = document.getElementById('ddBtn-' + id);
        const label = document.getElementById('ddLabel-' + id);
        const list  = document.getElementById('ddList-' + id);
        const val   = document.getElementById('ddVal-' + id);
        if (val)   { val.value = ''; }
        if (label) { label.textContent = placeholder; label.style.color = '#9ca3af'; }
        if (list)  { list.innerHTML = ''; list.classList.remove('open'); }
        if (btn)   { btn.classList.remove('open'); btn.disabled = true; }
    }

    // Dropdown'ı doldur ve aktif et
    function fillCascadeDD(id, items, placeholder, onSelect) {
        const btn   = document.getElementById('ddBtn-' + id);
        const label = document.getElementById('ddLabel-' + id);
        const list  = document.getElementById('ddList-' + id);
        const val   = document.getElementById('ddVal-' + id);
        list.innerHTML = '';
        if (val) val.value = '';
        if (!items || items.length === 0) {
            const li = document.createElement('li');
            li.textContent = 'Sonuç bulunamadı';
            li.style.color = '#9ca3af'; li.style.cursor = 'default';
            list.appendChild(li);
            if (btn)   btn.disabled = true;
            if (label) { label.textContent = 'Bulunamadı'; label.style.color = '#9ca3af'; }
            return;
        }
        // Arama kutusu ekle (5+ seçenek varsa)
        if (items.length >= 5) {
            const searchWrap = document.createElement('div');
            searchWrap.className = 'adm-dd-search';
            const searchInput = document.createElement('input');
            searchInput.type = 'text';
            searchInput.placeholder = 'Ara...';
            searchInput.addEventListener('input', function() {
                const q = this.value.toLowerCase();
                list.querySelectorAll('li').forEach(function(li) {
                    li.classList.toggle('dd-hidden', q && !li.textContent.toLowerCase().includes(q));
                });
            });
            searchInput.addEventListener('click', function(e) { e.stopPropagation(); });
            searchInput.addEventListener('keydown', function(e) { e.stopPropagation(); });
            searchWrap.appendChild(searchInput);
            list.appendChild(searchWrap);
        }
        items.forEach(function(item) {
            const li = document.createElement('li');
            li.dataset.value   = (item.Id !== undefined && item.Id !== null) ? item.Id : (item.value || '');
            li.dataset.name    = item.Name || item.name || '';
            li.dataset.arabamId = item.ArabamId || '';
            li.textContent = item.Name || item.name || '';
            li.addEventListener('click', function() {
                if (val)   val.value = this.dataset.value;
                if (label) { label.textContent = this.textContent; label.style.color = '#111827'; }
                list.querySelectorAll('li').forEach(function(l) { l.classList.remove('selected'); });
                this.classList.add('selected');
                list.classList.remove('open');
                if (btn) btn.classList.remove('open');
                if (onSelect) onSelect({ value: this.dataset.value, name: this.dataset.name, arabamId: this.dataset.arabamId });
            });
            list.appendChild(li);
        });
        if (label) { label.textContent = placeholder; label.style.color = '#9ca3af'; }
        if (btn)   btn.disabled = false;
        if (items.length === 1) list.querySelector('li').click();
    }

    // Cascade zincirini belirtilen adımdan sıfırla
    function resetCascadeFrom(step) {
        const chain = [
            { key: 'year',         placeholder: 'Önce marka seçiniz' },
            { key: 'model',        placeholder: 'Önce marka ve yıl seçiniz' },
            { key: 'bodyType',     placeholder: 'Önce model seçiniz' },
            { key: 'fuelType',     placeholder: 'Önce kasa tipi seçiniz' },
            { key: 'transmission', placeholder: 'Önce yakıt tipi seçiniz' },
            { key: 'version',      placeholder: 'Önce vites tipi seçiniz' },
        ];
        let found = false;
        chain.forEach(function(s) {
            if (s.key === step) found = true;
            if (!found) return;
            setCascadeState(s.key, s.placeholder);
            if (s.key === 'year') { cascadeData.year = null; }
            else {
                cascadeData[s.key + 'Id']   = null;
                cascadeData[s.key + 'Name'] = null;
            }
            if (s.key === 'model') cascadeData.modelArabamId = null;
        });
    }

    // ── YÜKLEME FONKSİYONLARI ────────────────────────────────────────────────
    function loadYears() {
        if (!cascadeData.brandArabamId) {
            // Marka seçilmeden genel yıl listesi göster
            const y = new Date().getFullYear();
            const items = [];
            for (let i = y + 1; i >= 1990; i--) items.push({ Id: i, Name: String(i) });
            fillCascadeDD('year', items, 'Yıl Seçiniz', function(sel) {
                cascadeData.year = sel.value;
                resetCascadeFrom('model');
                if (cascadeData.year && cascadeData.brandArabamId) loadModels();
            });
        } else {
            // Marka seçildiyse arabam_vehicle_configs'ten yılları çek (step=10)
            apiStepFill('year', '10',
                { brandId: cascadeData.brandArabamId },
                'Yıl Seçiniz',
                function(sel) {
                    cascadeData.year = sel.value;
                    resetCascadeFrom('model');
                    if (cascadeData.year && cascadeData.brandArabamId) loadModels();
                }
            );
        }
        // old() değerini geri yükle
        const oldYear = '{{ old("year") }}';
        if (oldYear) {
            document.getElementById('ddVal-year').value = oldYear;
            const lbl = document.getElementById('ddLabel-year');
            lbl.textContent  = oldYear;
            lbl.style.color  = '#111827';
            cascadeData.year = oldYear;
            document.querySelectorAll('#ddList-year li').forEach(function(li) {
                if (String(li.dataset.value) === String(oldYear)) li.classList.add('selected');
            });
        }
    }

    function loadBrands() {
        setCascadeState('brand', 'Yükleniyor...');
        $.ajax({
            url: '{{ route("admin.vehicles.api.brands") }}',
            method: 'GET', timeout: 8000,
            success: function(r) {
                if (r.success && r.data && r.data.Items) {
                    fillCascadeDD('brand', r.data.Items, 'Marka Seçiniz', function(sel) {
                        cascadeData.brandId      = sel.value;
                        cascadeData.brandName    = sel.name;
                        cascadeData.brandArabamId = sel.arabamId;
                        resetCascadeFrom('year');
                        loadYears(); // Marka seçildiğinde o markaya ait yılları yükle
                    });
                    // old() ile geri yükleme: marka seçiliyse otomatik seç
                    var oldBrand = '{{ old("brand") }}';
                    if (oldBrand) {
                        var brandLis = document.querySelectorAll('#ddList-brand li');
                        brandLis.forEach(function(li) {
                            if (li.dataset.name === oldBrand) li.click();
                        });
                    }
                } else {
                    const btn = document.getElementById('ddBtn-brand');
                    const lbl = document.getElementById('ddLabel-brand');
                    if (btn) btn.disabled = true;
                    if (lbl) { lbl.textContent = 'Marka yüklenemedi'; lbl.style.color = '#ef4444'; }
                }
            },
            error: function() {
                const btn = document.getElementById('ddBtn-brand');
                const lbl = document.getElementById('ddLabel-brand');
                if (btn) btn.disabled = true;
                if (lbl) { lbl.textContent = 'Bağlantı hatası'; lbl.style.color = '#ef4444'; }
            }
        });
    }

    function loadModels() {
        // arabam_vehicle_configs tablosundan model listesini çek (step=20)
        apiStepFill('model', '20',
            { brandId: cascadeData.brandArabamId, modelYear: cascadeData.year },
            'Model Seçiniz',
            function(sel) {
                cascadeData.modelId       = sel.value;
                cascadeData.modelName     = sel.name;
                cascadeData.modelArabamId = sel.value; // arabam_vehicle_configs'te Id zaten arabam ID
                resetCascadeFrom('bodyType');
                loadBodyTypes();
            }
        );
    }

    function apiStepFill(ddId, step, data, placeholder, onSelect) {
        setCascadeState(ddId, 'Yükleniyor...');
        $.ajax({
            url: '/api/arabam/step', method: 'GET',
            data: Object.assign({ step: step }, data),
            success: function(r) {
                if (r.success && r.data && r.data.Items && r.data.Items.length > 0) {
                    fillCascadeDD(ddId, r.data.Items, placeholder, onSelect);
                } else {
                    setCascadeState(ddId, 'Bu kombinasyon için veri bulunamadı');
                }
            },
            error: function(xhr) {
                if (xhr.status === 404) {
                    setCascadeState(ddId, 'Bu kombinasyon için veri bulunamadı');
                } else {
                    setCascadeState(ddId, 'Veri yüklenemedi, lütfen tekrar deneyin');
                }
            }
        });
    }

    function loadBodyTypes() {
        apiStepFill('bodyType', '30',
            { brandId: cascadeData.brandArabamId, modelYear: cascadeData.year, modelGroupId: cascadeData.modelArabamId },
            'Kasa Tipi Seçiniz',
            function(sel) {
                cascadeData.bodyTypeId   = sel.value;
                cascadeData.bodyTypeName = sel.name;
                resetCascadeFrom('fuelType');
                loadFuelTypes();
            }
        );
    }
    function loadFuelTypes() {
        apiStepFill('fuelType', '40',
            { brandId: cascadeData.brandArabamId, modelYear: cascadeData.year, modelGroupId: cascadeData.modelArabamId, bodyTypeId: cascadeData.bodyTypeId },
            'Yakıt Tipi Seçiniz',
            function(sel) {
                cascadeData.fuelTypeId   = sel.value;
                cascadeData.fuelTypeName = sel.name;
                resetCascadeFrom('transmission');
                loadTransmissions();
            }
        );
    }
    function loadTransmissions() {
        apiStepFill('transmission', '50',
            { brandId: cascadeData.brandArabamId, modelYear: cascadeData.year, modelGroupId: cascadeData.modelArabamId, bodyTypeId: cascadeData.bodyTypeId, fuelTypeId: cascadeData.fuelTypeId },
            'Vites Tipi Seçiniz',
            function(sel) {
                cascadeData.transmissionId   = sel.value;
                cascadeData.transmissionName = sel.name;
                resetCascadeFrom('version');
                loadVersions();
            }
        );
    }
    function loadVersions() {
        apiStepFill('version', '60',
            { brandId: cascadeData.brandArabamId, modelYear: cascadeData.year, modelGroupId: cascadeData.modelArabamId, bodyTypeId: cascadeData.bodyTypeId, fuelTypeId: cascadeData.fuelTypeId, transmissionTypeId: cascadeData.transmissionId },
            'Versiyon Seçiniz',
            function(sel) {
                cascadeData.versionId   = sel.value;
                cascadeData.versionName = sel.name;
            }
        );
    }

    // Init
    loadYears();
    loadBrands();

    // ── MANUEL GİRİŞ MODU (tek buton ile toggle) ─────────────────────────────
    let isManualMode = false;

    window.toggleManualMode = function() {
        isManualMode = !isManualMode;
        const btn     = document.getElementById('manualModeBtn');
        const btnText = document.getElementById('manualModeBtnText');
        const hint    = document.getElementById('kimlikHint');

        if (isManualMode) {
            document.getElementById('cascadeSection').classList.add('hidden');
            document.getElementById('manualSection').classList.remove('hidden');
            btn.classList.add('bg-red-50', 'border-red-400', 'text-red-600');
            btnText.textContent = 'Cascade\'e Dön';
            if (hint) hint.textContent = 'Manuel giriş modu — araç bilgilerini aşağıya yazınız.';
            // Cascade datasını sıfırla
            cascadeData.brandId = null; cascadeData.brandName = null; cascadeData.brandArabamId = null;
            cascadeData.modelId = null; cascadeData.modelName = null; cascadeData.modelArabamId = null;
            cascadeData.bodyTypeId = null; cascadeData.fuelTypeId = null;
            cascadeData.transmissionId = null; cascadeData.versionId = null;
        } else {
            document.getElementById('cascadeSection').classList.remove('hidden');
            document.getElementById('manualSection').classList.add('hidden');
            btn.classList.remove('bg-red-50', 'border-red-400', 'text-red-600');
            btnText.textContent = 'Manuel Giriş';
            if (hint) hint.textContent = 'Marka ve yılı seçtikten sonra model, ardından kasa / yakıt / vites / paket bilgileri otomatik dolar.';
            // Manuel inputları temizle
            ['brand', 'model', 'bodyType', 'fuelType', 'transmission', 'version'].forEach(function(k) {
                const el = document.getElementById('manualInput-' + k);
                if (el) el.value = '';
            });
        }
    };

    // Renk manuel toggle
    $('#manualColorToggle').on('change', function() {
        if ($(this).is(':checked')) {
            $('#ddWrap-color').hide();
            $('#manualColorInput').show().removeClass('hidden').prop('disabled', false).focus();
            $('#ddVal-color').prop('disabled', true);
        } else {
            $('#ddWrap-color').show();
            $('#manualColorInput').hide().addClass('hidden').prop('disabled', true).val('');
            $('#ddVal-color').prop('disabled', false);
        }
    });

    // ── DONANIM SAYACI & ARAMA ─────────────────────────────────────────────
    window.updateFeatureCount=function(){
        document.getElementById('selectedCount').textContent=document.querySelectorAll('#featuresContainer input[type="checkbox"]:checked').length+' özellik seçili';
    };
    $('#featureSearch').on('keyup',function(){
        const q=$(this).val().toLowerCase();
        $('.feature-item').each(function(){$(this).toggle($(this).text().toLowerCase().indexOf(q)>-1);});
    });

    // ── ANA GÖRSEL ────────────────────────────────────────────────────────────
    $('#mainImageInput').on('change',function(e){
        const file=e.target.files[0];
        if(!file)return;
        const reader=new FileReader();
        reader.onload=function(ev){$('#mainPreviewImg').attr('src',ev.target.result);$('#mainPreview').removeClass('hidden');};
        reader.readAsDataURL(file);
    });
    window.removeMainImage=function(){$('#mainImageInput').val('');$('#mainPreview').addClass('hidden');};

    // ── GALERİ ────────────────────────────────────────────────────────────────
    let galleryFiles=[]; const MAX_G=15;
    $('#singleImageInput').on('change',function(e){
        const file=e.target.files[0]; if(!file)return;
        if(galleryFiles.length>=MAX_G){showWarning('Görsel Limiti',`En fazla ${MAX_G} görsel yükleyebilirsiniz.`);$(this).val('');return;}
        if(file.size>5*1024*1024){showWarning('Dosya Boyutu Aşıldı','Görsel boyutu en fazla 5 MB olabilir.');$(this).val('');return;}
        galleryFiles.push(file);$(this).val('');renderGallery();
    });
    function renderGallery(){
        $('#galleryPreview').empty(); $('#galleryCount').text(galleryFiles.length+'/'+MAX_G);
        if(!galleryFiles.length)return;
        galleryFiles.forEach((file,i)=>{
            const reader=new FileReader();
            reader.onload=function(e){
                $('#galleryPreview').append(`<div class="gallery-item" data-index="${i}"><img src="${e.target.result}" class="w-full h-24 object-cover"><button type="button" onclick="removeGalleryItem(${i})" class="delete-btn"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button><div class="absolute bottom-1 left-1 bg-black/70 text-white text-xs font-bold px-1.5 py-0.5 rounded">${i+1}</div></div>`);
            };
            reader.readAsDataURL(file);
        });
        setTimeout(()=>{if(galleryFiles.length>1)new Sortable(document.getElementById('galleryPreview'),{animation:200,onEnd:function(evt){const m=galleryFiles.splice(evt.oldIndex,1)[0];galleryFiles.splice(evt.newIndex,0,m);renderGallery();}});},100);
    }
    window.removeGalleryItem=function(i){
        Swal.fire({title:'Görseli kaldırmak istediğinize emin misiniz?',icon:'warning',showCancelButton:true,confirmButtonColor:'#dc2626',cancelButtonColor:'#6b7280',confirmButtonText:'Kaldır',cancelButtonText:'Vazgeç',reverseButtons:true,customClass:{popup:'rounded-xl shadow-2xl',title:'text-lg font-bold text-gray-900'}})
        .then(r=>{if(r.isConfirmed){galleryFiles.splice(i,1);renderGallery();}});
    };

    // ── KİLOMETRE FORMATLAMA ──────────────────────────────────────────────────
    (function() {
        var kmInput = document.getElementById('kilometerInput');
        if (kmInput) {
            kmInput.addEventListener('input', function() {
                var raw = this.value.replace(/[^\d]/g, '');
                if (raw) {
                    this.value = parseInt(raw, 10).toLocaleString('tr-TR');
                }
            });
        }
    })();

    // ── FORM SUBMIT ───────────────────────────────────────────────────────────
    $('.submit-btn').on('click', function() { $('#formAction').val($(this).data('action')); });

    $('#vehicleForm').on('submit', function(e) {
        const dt = new DataTransfer();
        galleryFiles.forEach(f => dt.items.add(f));
        document.getElementById('galleryInput').files = dt.files;

        const action = $('#formAction').val();

        // ── Validasyon (yalnızca yayınlama için) ──────────────────────────────
        if (action === 'publish') {
            const brandVal = isManualMode
                ? document.getElementById('manualInput-brand').value.trim()
                : (cascadeData.brandId || document.getElementById('ddVal-brand').value);
            const modelVal = isManualMode
                ? document.getElementById('manualInput-model').value.trim()
                : (cascadeData.modelId || document.getElementById('ddVal-model').value);
            const yearVal  = document.getElementById('ddVal-year').value;

            const missing = [];
            if (!$('[name="title"]').val().trim()) missing.push('Başlık');
            if (!brandVal)                        missing.push('Marka');
            if (!modelVal)                        missing.push('Model');
            if (!yearVal)                         missing.push('Yıl');
            if (!$('[name="kilometer"]').val())   missing.push('Kilometre');
            if (!$('[name="price"]').val())       missing.push('Fiyat');
            if (!$('#mainImageInput')[0].files.length) missing.push('Ana Görsel');

            if (missing.length > 0) {
                e.preventDefault();
                showMissingFields(missing);
                return false;
            }
        }

        // ── Önceki denemelerden kalan geçici inputları temizle ────────────────
        $('#vehicleForm .js-dyn').remove();

        if (isManualMode) {
            // Manuel mod: doğrudan input değerlerini kullan
            const manualFields = {
                brand:          'manualInput-brand',
                model:          'manualInput-model',
                body_type:      'manualInput-bodyType',
                fuel_type:      'manualInput-fuelType',
                transmission:   'manualInput-transmission',
                package_version:'manualInput-version',
            };
            Object.entries(manualFields).forEach(function([fieldName, inputId]) {
                const v = (document.getElementById(inputId) || {}).value;
                if (v && v.trim()) $('<input type="hidden" class="js-dyn">').attr('name', fieldName).val(v.trim()).appendTo('#vehicleForm');
            });
        } else {
            // Cascade mod: hidden input değerlerinden form field'larını oluştur
            const cascadeFields = [
                { name: 'brand',           valId: 'ddVal-brand',        nameKey: 'brandName' },
                { name: 'model',           valId: 'ddVal-model',        nameKey: 'modelName' },
                { name: 'package_version', valId: 'ddVal-version',      nameKey: 'versionName' },
                { name: 'body_type',       valId: 'ddVal-bodyType',     nameKey: 'bodyTypeName' },
                { name: 'fuel_type',       valId: 'ddVal-fuelType',     nameKey: 'fuelTypeName' },
                { name: 'transmission',    valId: 'ddVal-transmission', nameKey: 'transmissionName' },
            ];
            cascadeFields.forEach(function(f) {
                const val  = document.getElementById(f.valId).value;
                const name = cascadeData[f.nameKey] || document.getElementById(f.valId.replace('ddVal-', 'ddLabel-')).textContent;
                if (val) $('<input type="hidden" class="js-dyn">').attr('name', f.name).val(name || val).appendTo('#vehicleForm');
            });
        }

        if (action === 'draft')   $('[name="is_active"]').prop('checked', false);
        else if (action === 'publish') $('[name="is_active"]').prop('checked', true);
    });

    // ── ARAÇ DİYAGRAMI ─────────────────────────────────────────────────────────
    (function() {
        var partStates = {};
        var svgPartNames = {
            'on_tampon': 'Ön Tampon', 'arka_tampon': 'Arka Tampon',
            'motor_kaputu': 'Motor Kaputu', 'arka_kaput': 'Arka Kaput',
            'tavan': 'Tavan',
            'sag_on_kapi': 'Sağ Ön Kapı', 'sag_arka_kapi': 'Sağ Arka Kapı',
            'sol_on_kapi': 'Sol Ön Kapı', 'sol_arka_kapi': 'Sol Arka Kapı',
            'sag_on_camurluk': 'Sağ Ön Çamurluk', 'sag_arka_camurluk': 'Sağ Arka Çamurluk',
            'sol_on_camurluk': 'Sol Ön Çamurluk', 'sol_arka_camurluk': 'Sol Arka Çamurluk'
        };
        var tooltip = document.getElementById('adminCarTooltip');
        var tooltipTitle = document.getElementById('adminTooltipTitle');
        var currentPart = null;

        function getColor(val) {
            if (val === 'BOYALI')       return '#3b82f6';
            if (val === 'LOKAL_BOYALI') return '#fbbf24';
            if (val === 'DEGISMIS')     return '#dc2626';
            return '#FFFFFF';
        }

        function updatePartColor(partKey) {
            var el = document.getElementById('svg-' + partKey);
            if (el) el.style.fill = getColor(partStates[partKey] || 'ORIJINAL');
        }

        function syncHiddenInputs() {
            var container = document.getElementById('adminPartInputs');
            container.innerHTML = '';
            Object.keys(partStates).forEach(function(k) {
                var val = partStates[k];
                var displayName = svgPartNames[k] || k;
                if (val === 'BOYALI' || val === 'LOKAL_BOYALI') {
                    var inp = document.createElement('input');
                    inp.type = 'hidden'; inp.name = 'painted_parts[]'; inp.value = displayName;
                    container.appendChild(inp);
                } else if (val === 'DEGISMIS') {
                    var inp = document.createElement('input');
                    inp.type = 'hidden'; inp.name = 'replaced_parts[]'; inp.value = displayName;
                    container.appendChild(inp);
                }
            });
        }

        document.querySelectorAll('#adminCarDiagramWrapper .car-part').forEach(function(part) {
            part.addEventListener('click', function(e) {
                e.stopPropagation();
                var svgId = this.id;
                var partKey = svgId.replace('svg-', '');
                currentPart = partKey;
                tooltipTitle.textContent = svgPartNames[partKey] || partKey;

                var curVal = partStates[partKey] || 'ORIJINAL';
                tooltip.querySelectorAll('.admin-car-tooltip-opt').forEach(function(opt) {
                    opt.classList.toggle('selected', opt.dataset.val === curVal);
                });

                var wrapper = document.getElementById('adminCarDiagramWrapper');
                var rect = wrapper.getBoundingClientRect();
                var x = e.clientX - rect.left;
                var y = e.clientY - rect.top;
                tooltip.style.left = Math.min(x, rect.offsetWidth - 160) + 'px';
                tooltip.style.top = Math.min(y, rect.offsetHeight - 120) + 'px';
                tooltip.classList.add('active');
            });
        });

        tooltip.querySelectorAll('.admin-car-tooltip-opt').forEach(function(opt) {
            opt.addEventListener('click', function(e) {
                e.stopPropagation();
                if (!currentPart) return;
                var val = this.dataset.val;
                if (val === 'ORIJINAL') { delete partStates[currentPart]; }
                else { partStates[currentPart] = val; }
                updatePartColor(currentPart);
                syncHiddenInputs();
                tooltip.querySelectorAll('.admin-car-tooltip-opt').forEach(function(o) {
                    o.classList.toggle('selected', o.dataset.val === val);
                });
                setTimeout(function() { tooltip.classList.remove('active'); currentPart = null; }, 150);
            });
        });

        document.addEventListener('click', function(e) {
            if (!tooltip.contains(e.target) && !e.target.classList.contains('car-part')) {
                tooltip.classList.remove('active');
                currentPart = null;
            }
        });
    })();

    // Static dropdown'lara (blade-rendered) search input ekle
    document.querySelectorAll('.adm-dd-list:not(.cascade-list)').forEach(function(list) {
        var lis = list.querySelectorAll('li');
        if (lis.length < 5) return;
        var searchWrap = document.createElement('div');
        searchWrap.className = 'adm-dd-search';
        var searchInput = document.createElement('input');
        searchInput.type = 'text';
        searchInput.placeholder = 'Ara...';
        searchInput.addEventListener('input', function() {
            var q = this.value.toLowerCase();
            list.querySelectorAll('li').forEach(function(li) {
                li.classList.toggle('dd-hidden', q && !li.textContent.toLowerCase().includes(q));
            });
        });
        searchInput.addEventListener('click', function(e) { e.stopPropagation(); });
        searchInput.addEventListener('keydown', function(e) { e.stopPropagation(); });
        list.insertBefore(searchWrap, list.firstChild);
        searchWrap.appendChild(searchInput);
    });
});
</script>
@endpush
@endsection

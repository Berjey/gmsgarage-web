@extends('admin.layouts.app')

@section('title', 'Araç Düzenle - Admin Panel')
@section('page-title', 'Araç Düzenle')
@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-primary-600">Anasayfa</a>
    <span>/</span>
    <a href="{{ route('admin.vehicles.index') }}" class="hover:text-primary-600">Araçlar</a>
    <span>/</span>
    <span>Düzenle</span>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                Araç Düzenle
            </h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('vehicles.show', $vehicle->slug ?: $vehicle->id) }}" target="_blank"
                   class="flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-all text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Sitede Görüntüle
                </a>
                <a href="{{ route('admin.vehicles.index') }}"
                   class="flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-all text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Geri
                </a>
            </div>
        </div>
        <p class="text-sm text-gray-500 mt-1 ml-[52px]">{{ $vehicle->title }}</p>
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

<form action="{{ route('admin.vehicles.update', $vehicle->id) }}" method="POST" enctype="multipart/form-data" id="vehicleForm">
    @csrf
    @method('PUT')

    <div>

    {{-- ═══════════════ MAIN CONTENT — Step-by-step form ═══════════════ --}}
    <div>
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6 vehicle-form-card">

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

        {{-- ─── Tab 1: ARAÇ KİMLİĞİ ──────────────────────────────────────── --}}
        <div id="vtab-kimlik" class="vehicle-tab-content p-6 space-y-6">

            <div>
                <h3 class="text-lg font-bold text-gray-900">Araç Kimliği</h3>
                <p class="text-xs text-gray-500 mt-1">Araç tanımlama bilgileri. Kasa / Yakıt / Vites için katalogda olmayan değerler için Manuel Gir'i kullanın.</p>
            </div>

            {{-- Yıl + Marka + Model --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Model Yılı <span class="text-red-500">*</span></label>
                    <input type="number" name="year" value="{{ old('year', $vehicle->year) }}" required
                           min="1900" max="{{ date('Y')+1 }}" placeholder="{{ date('Y') }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Marka <span class="text-red-500">*</span></label>
                    <input type="text" name="brand" value="{{ old('brand', $vehicle->brand) }}" required placeholder="Volkswagen"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Model / Seri <span class="text-red-500">*</span></label>
                    <input type="text" name="model" value="{{ old('model', $vehicle->model) }}" required placeholder="Passat"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                </div>
            </div>

            {{-- Kasa + Yakıt + Vites --}}
            @php
                $bodyTypeOptions    = ['Sedan','SUV','Hatchback','Station Wagon','Coupe','Cabrio','Van','Minibüs','Pikap','Kamyonet','Diğer'];
                $fuelTypeOptions    = ['Benzin','Dizel','LPG/Benzin','Hibrit','Elektrikli'];
                $transmissionOptions= ['Manuel','Otomatik','Yarı Otomatik'];
                $colorOptions       = ['Beyaz','Siyah','Gri','Gümüş Gri','Kırmızı','Mavi','Lacivert','Yeşil','Bej','Kahverengi','Sarı','Turuncu','Bordo','Mor','Altın','Bronz','Diğer'];

                $curBodyType    = old('body_type',    $vehicle->body_type    ?? '');
                $curFuelType    = old('fuel_type',    $vehicle->fuel_type    ?? '');
                $curTransmission= old('transmission', $vehicle->transmission ?? '');
                $curColor       = old('color',        $vehicle->color        ?? '');

                $bodyTypeManual    = $curBodyType     !== '' && !in_array($curBodyType,     $bodyTypeOptions);
                $fuelTypeManual    = $curFuelType     !== '' && !in_array($curFuelType,     $fuelTypeOptions);
                $transmissionManual= $curTransmission !== '' && !in_array($curTransmission, $transmissionOptions);
                $colorManual       = $curColor        !== '' && !in_array($curColor,        $colorOptions);
            @endphp

            <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 space-y-4">
                <p class="text-xs text-gray-500 font-medium">Kasa, Yakıt ve Vites Bilgileri</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    {{-- Kasa Tipi --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kasa Tipi</label>
                        <select name="body_type" id="editBodyTypeSelect"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors text-sm {{ $bodyTypeManual ? 'hidden' : '' }}"
                                {{ $bodyTypeManual ? 'disabled' : '' }}>
                            <option value="">Seçiniz</option>
                            @foreach($bodyTypeOptions as $bt)
                                <option value="{{ $bt }}" {{ $curBodyType === $bt ? 'selected' : '' }}>{{ $bt }}</option>
                            @endforeach
                        </select>
                        <label class="inline-flex items-center mt-2 text-xs text-gray-600 cursor-pointer">
                            <input type="checkbox" id="editManualBodyTypeToggle" class="w-3 h-3 text-red-600 border-gray-300 rounded mr-1.5" {{ $bodyTypeManual ? 'checked' : '' }}>
                            <span>Manuel Gir</span>
                        </label>
                        <input type="text" name="body_type" id="editManualBodyTypeInput"
                               value="{{ $bodyTypeManual ? $curBodyType : '' }}"
                               placeholder="Örn: Sedan, SUV"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors text-sm mt-1.5 {{ $bodyTypeManual ? '' : 'hidden' }}"
                               {{ $bodyTypeManual ? '' : 'disabled' }}>
                        @if($bodyTypeManual)<p class="mt-1 text-xs text-amber-600">⚠ "<strong>{{ $curBodyType }}</strong>" listede yok.</p>@endif
                    </div>

                    {{-- Yakıt Tipi --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Yakıt Tipi</label>
                        <select name="fuel_type" id="editFuelTypeSelect"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors text-sm {{ $fuelTypeManual ? 'hidden' : '' }}"
                                {{ $fuelTypeManual ? 'disabled' : '' }}>
                            <option value="">Seçiniz</option>
                            @foreach($fuelTypeOptions as $ft)
                                <option value="{{ $ft }}" {{ $curFuelType === $ft ? 'selected' : '' }}>{{ $ft }}</option>
                            @endforeach
                        </select>
                        <label class="inline-flex items-center mt-2 text-xs text-gray-600 cursor-pointer">
                            <input type="checkbox" id="editManualFuelTypeToggle" class="w-3 h-3 text-red-600 border-gray-300 rounded mr-1.5" {{ $fuelTypeManual ? 'checked' : '' }}>
                            <span>Manuel Gir</span>
                        </label>
                        <input type="text" name="fuel_type" id="editManualFuelTypeInput"
                               value="{{ $fuelTypeManual ? $curFuelType : '' }}"
                               placeholder="Örn: Benzin, Dizel"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors text-sm mt-1.5 {{ $fuelTypeManual ? '' : 'hidden' }}"
                               {{ $fuelTypeManual ? '' : 'disabled' }}>
                        @if($fuelTypeManual)<p class="mt-1 text-xs text-amber-600">⚠ "<strong>{{ $curFuelType }}</strong>" listede yok.</p>@endif
                    </div>

                    {{-- Vites Tipi --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Vites Tipi</label>
                        <select name="transmission" id="editTransmissionSelect"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors text-sm {{ $transmissionManual ? 'hidden' : '' }}"
                                {{ $transmissionManual ? 'disabled' : '' }}>
                            <option value="">Seçiniz</option>
                            @foreach($transmissionOptions as $tr)
                                <option value="{{ $tr }}" {{ $curTransmission === $tr ? 'selected' : '' }}>{{ $tr }}</option>
                            @endforeach
                        </select>
                        <label class="inline-flex items-center mt-2 text-xs text-gray-600 cursor-pointer">
                            <input type="checkbox" id="editManualTransmissionToggle" class="w-3 h-3 text-red-600 border-gray-300 rounded mr-1.5" {{ $transmissionManual ? 'checked' : '' }}>
                            <span>Manuel Gir</span>
                        </label>
                        <input type="text" name="transmission" id="editManualTransmissionInput"
                               value="{{ $transmissionManual ? $curTransmission : '' }}"
                               placeholder="Örn: Otomatik, Manuel"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors text-sm mt-1.5 {{ $transmissionManual ? '' : 'hidden' }}"
                               {{ $transmissionManual ? '' : 'disabled' }}>
                        @if($transmissionManual)<p class="mt-1 text-xs text-amber-600">⚠ "<strong>{{ $curTransmission }}</strong>" listede yok.</p>@endif
                    </div>
                </div>

                {{-- Paket / Versiyon --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Paket / Versiyon</label>
                        <input type="text" name="package_version" value="{{ old('package_version', $vehicle->package_version) }}"
                               placeholder="1.6 TDI Comfortline"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                    </div>
                </div>
            </div>

            {{-- Step Footer --}}
            <div class="step-footer mt-6">
                <div></div>
                <button type="button" onclick="goToStep('ilan', true)" class="step-footer-btn primary">
                    Devam: İlan Bilgileri
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>

        </div>

        {{-- ─── Tab 2: İLAN BİLGİLERİ ──────────────────────────────────────── --}}
        <div id="vtab-ilan" class="vehicle-tab-content p-6 space-y-6 hidden">

            <h3 class="text-lg font-bold text-gray-900">İlan Bilgileri</h3>

            {{-- Başlık --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">İlan Başlığı <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $vehicle->title) }}" required
                       placeholder="Örn: Volkswagen Passat 1.6 TDI BlueMotion"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                <p class="mt-1 text-xs text-gray-500">Marka, model ve özelliklerini içeren açıklayıcı başlık yazın</p>
            </div>

            {{-- Slug --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    SEO Slug <span class="ml-1 text-xs font-normal text-gray-400">(boş bırakılırsa mevcut slug korunur)</span>
                </label>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-400 whitespace-nowrap">/araclar/</span>
                    <input type="text" name="slug" value="{{ old('slug', $vehicle->slug) }}" placeholder="ornek-arac-adi"
                           class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors font-mono text-sm">
                </div>
                @error('slug')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                <p class="mt-1 text-xs text-gray-500">
                    <a href="{{ route('vehicles.show', $vehicle->slug ?: $vehicle->id) }}" target="_blank" class="text-red-600 hover:underline">Mevcut URL →</a>
                </p>
            </div>

            {{-- Fiyat + Km --}}
            <div class="border-t pt-6">
                <h4 class="text-sm font-bold text-gray-700 mb-4">Fiyat & Kilometre</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fiyat (₺) <span class="text-red-500">*</span></label>
                        <input type="number" name="price" value="{{ old('price', $vehicle->price) }}" required placeholder="0"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors font-bold text-lg">
                        <p class="mt-1 text-xs text-gray-500">Satış fiyatı (KDV dahil)</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kilometre <span class="text-red-500">*</span></label>
                        <input type="text" name="kilometer" id="editKilometerInput" value="{{ number_format((int)old('kilometer', $vehicle->kilometer), 0, '', '.') }}" required placeholder="0"
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
                    <div class="flex items-end pb-1">
                        <label class="flex items-start gap-3 p-4 border-2 rounded-lg cursor-pointer transition-all w-full
                            {{ ($errors->any() ? old('swap') : $vehicle->swap) ? 'border-green-400 bg-green-50' : 'border-gray-200 hover:border-green-300 hover:bg-green-50/50' }}">
                            <input type="checkbox" name="swap" value="1"
                                   class="w-4 h-4 mt-0.5 text-green-600 border-gray-300 rounded focus:ring-green-500"
                                   {{ ($errors->any() ? old('swap') : $vehicle->swap) ? 'checked' : '' }}>
                            <div>
                                <p class="font-bold text-gray-900 text-sm">Takasa Uygun</p>
                                <p class="text-xs text-gray-500 mt-0.5">Araç için takas kabul edilir</p>
                            </div>
                        </label>
                    </div>
                    <div class="flex items-end pb-1">
                        <label class="flex items-start gap-3 p-4 border-2 rounded-lg cursor-pointer transition-all w-full
                            {{ ($errors->any() ? old('price_negotiable') : $vehicle->price_negotiable) ? 'border-amber-400 bg-amber-50' : 'border-gray-200 hover:border-amber-300 hover:bg-amber-50/50' }}">
                            <input type="checkbox" name="price_negotiable" value="1"
                                   class="w-4 h-4 mt-0.5 text-amber-600 border-gray-300 rounded focus:ring-amber-500"
                                   {{ ($errors->any() ? old('price_negotiable') : $vehicle->price_negotiable) ? 'checked' : '' }}>
                            <div>
                                <p class="font-bold text-gray-900 text-sm">Pazarlık Payı Var</p>
                                <p class="text-xs text-gray-500 mt-0.5">Fiyat pazarlığa açıktır</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Araç Durumu (Sıfır / İkinci El) --}}
            <div class="border-t pt-6">
                @php $curCondition = old('condition', $vehicle->condition ?? ''); @endphp
                <label class="block text-sm font-medium text-gray-700 mb-3">Araç Durumu <span class="text-xs font-normal text-gray-400">(Sıfır / İkinci El)</span></label>
                <div class="flex gap-3">
                    @foreach(\App\Models\Vehicle::CONDITIONS as $val => $label)
                    <label class="flex-1 flex items-center gap-3 p-3 border-2 rounded-lg cursor-pointer transition-all
                        {{ $curCondition === $val ? 'border-red-500 bg-red-50' : 'border-gray-200 hover:border-red-300 hover:bg-red-50/50' }}">
                        <input type="radio" name="condition" value="{{ $val }}"
                               class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500"
                               {{ $curCondition === $val ? 'checked' : '' }}>
                        <span class="font-semibold text-sm text-gray-800">{{ $label }}</span>
                    </label>
                    @endforeach
                    <label class="flex-1 flex items-center gap-3 p-3 border-2 rounded-lg cursor-pointer transition-all
                        {{ !$curCondition ? 'border-gray-300 bg-gray-50' : 'border-gray-200 hover:border-gray-300' }}">
                        <input type="radio" name="condition" value=""
                               class="w-4 h-4 text-gray-400 border-gray-300 focus:ring-gray-300"
                               {{ !$curCondition ? 'checked' : '' }}>
                        <span class="font-medium text-sm text-gray-500">Belirtme</span>
                    </label>
                </div>
            </div>

            {{-- Açıklama --}}
            <div class="border-t pt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Açıklama</label>
                <textarea name="description" rows="6" placeholder="Aracınız hakkında detaylı bilgi verin..."
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors resize-none">{{ old('description', $vehicle->description) }}</textarea>
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

        {{-- ─── Tab 3: TEKNİK DETAYLAR ──────────────────────────────────────── --}}
        <div id="vtab-teknik" class="vehicle-tab-content p-6 space-y-6 hidden">

            <h3 class="text-lg font-bold text-gray-900">Teknik Detaylar</h3>

            {{-- Renk + Renk Tipi --}}
            @php
                $editColorOptions     = ['Beyaz','Siyah','Gri','Gümüş Gri','Kırmızı','Mavi','Lacivert','Yeşil','Bej','Kahverengi','Sarı','Turuncu','Bordo','Mor','Altın','Bronz','Diğer'];
                $editColorTypeOptions = ['Metalik','Mat','İnci','Normal'];
                $curColorEdit     = $vehicle->color ?? old('color', '');
                $curColorTypeEdit = $vehicle->color_type ?? old('color_type', '');
            @endphp
            <div>
                <h4 class="text-sm font-bold text-gray-700 mb-4">Renk</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Renk</label>
                        <div class="adm-dd {{ $colorManual ? 'hidden' : '' }}" id="ddWrap-editColor">
                            <input type="hidden" name="color" id="ddVal-editColor" value="{{ $curColorEdit }}">
                            <button type="button" class="adm-dd-btn" id="ddBtn-editColor" onclick="toggleStaticDD('editColor')">
                                <span id="ddLabel-editColor">{{ $curColorEdit ?: 'Seçiniz' }}</span>
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <ul class="adm-dd-list" id="ddList-editColor">
                                <li data-val="" onclick="selectStaticDD('editColor','','Seçiniz')">Seçiniz</li>
                                @foreach($editColorOptions as $c)
                                    <li data-val="{{ $c }}" onclick="selectStaticDD('editColor','{{ $c }}','{{ $c }}')" class="{{ $curColorEdit===$c?'selected':'' }}">{{ $c }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <label class="inline-flex items-center mt-2 text-xs text-gray-600 cursor-pointer">
                            <input type="checkbox" id="editManualColorToggle" class="w-3 h-3 text-red-600 border-gray-300 rounded mr-1.5" {{ $colorManual ? 'checked' : '' }}>
                            <span>Manuel Gir</span>
                        </label>
                        <input type="text" name="color" id="editManualColorInput"
                               value="{{ $colorManual ? $curColorEdit : '' }}"
                               placeholder="Beyaz, Siyah..."
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors text-sm mt-1.5 {{ $colorManual ? '' : 'hidden' }}"
                               {{ $colorManual ? '' : 'disabled' }}>
                        @if($colorManual)<p class="mt-1 text-xs text-amber-600">⚠ "<strong>{{ $curColorEdit }}</strong>" listede yok.</p>@endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Renk Tipi</label>
                        <div class="adm-dd" id="ddWrap-editColorType">
                            <input type="hidden" name="color_type" id="ddVal-editColorType" value="{{ $curColorTypeEdit }}">
                            <button type="button" class="adm-dd-btn" id="ddBtn-editColorType" onclick="toggleStaticDD('editColorType')">
                                <span id="ddLabel-editColorType">{{ $curColorTypeEdit ?: 'Seçiniz' }}</span>
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <ul class="adm-dd-list" id="ddList-editColorType">
                                <li data-val="" onclick="selectStaticDD('editColorType','','Seçiniz')">Seçiniz</li>
                                @foreach($editColorTypeOptions as $ct)
                                    <li data-val="{{ $ct }}" onclick="selectStaticDD('editColorType','{{ $ct }}','{{ $ct }}')" class="{{ $curColorTypeEdit===$ct?'selected':'' }}">{{ $ct }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Çekiş + Kapı + Koltuk --}}
            @php
                $editDriveOptions = ['Önden Çekiş','Arkadan İtiş','4x4'];
                $editDoorOptions  = [2,3,4,5,6,7,8];
                $editSeatOptions  = [2,4,5,6,7,8,9,10,12,15];
                $curDriveEdit = $vehicle->drive_type ?? old('drive_type', '');
                $curDoorEdit  = $vehicle->door_count ?? old('door_count', '');
                $curSeatEdit  = $vehicle->seat_count ?? old('seat_count', '');
            @endphp
            <div class="border-t pt-6">
                <h4 class="text-sm font-bold text-gray-700 mb-4">Şasi & Kapasite</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Çekiş</label>
                        <div class="adm-dd" id="ddWrap-editDriveType">
                            <input type="hidden" name="drive_type" id="ddVal-editDriveType" value="{{ $curDriveEdit }}">
                            <button type="button" class="adm-dd-btn" id="ddBtn-editDriveType" onclick="toggleStaticDD('editDriveType')">
                                <span id="ddLabel-editDriveType">{{ $curDriveEdit ?: 'Seçiniz' }}</span>
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <ul class="adm-dd-list" id="ddList-editDriveType">
                                <li data-val="" onclick="selectStaticDD('editDriveType','','Seçiniz')">Seçiniz</li>
                                @foreach($editDriveOptions as $dr)
                                    <li data-val="{{ $dr }}" onclick="selectStaticDD('editDriveType','{{ $dr }}','{{ $dr }}')" class="{{ $curDriveEdit===$dr?'selected':'' }}">{{ $dr }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kapı Sayısı</label>
                        <div class="adm-dd" id="ddWrap-editDoorCount">
                            <input type="hidden" name="door_count" id="ddVal-editDoorCount" value="{{ $curDoorEdit }}">
                            <button type="button" class="adm-dd-btn" id="ddBtn-editDoorCount" onclick="toggleStaticDD('editDoorCount')">
                                <span id="ddLabel-editDoorCount">{{ $curDoorEdit ? $curDoorEdit.' Kapı' : 'Seçiniz' }}</span>
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <ul class="adm-dd-list" id="ddList-editDoorCount">
                                <li data-val="" onclick="selectStaticDD('editDoorCount','','Seçiniz')">Seçiniz</li>
                                @foreach($editDoorOptions as $d)
                                    <li data-val="{{ $d }}" onclick="selectStaticDD('editDoorCount','{{ $d }}','{{ $d }} Kapı')" class="{{ (int)$curDoorEdit===$d?'selected':'' }}">{{ $d }} Kapı</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Koltuk Sayısı</label>
                        <div class="adm-dd" id="ddWrap-editSeatCount">
                            <input type="hidden" name="seat_count" id="ddVal-editSeatCount" value="{{ $curSeatEdit }}">
                            <button type="button" class="adm-dd-btn" id="ddBtn-editSeatCount" onclick="toggleStaticDD('editSeatCount')">
                                <span id="ddLabel-editSeatCount">{{ $curSeatEdit ? $curSeatEdit.' Koltuk' : 'Seçiniz' }}</span>
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <ul class="adm-dd-list" id="ddList-editSeatCount">
                                <li data-val="" onclick="selectStaticDD('editSeatCount','','Seçiniz')">Seçiniz</li>
                                @foreach($editSeatOptions as $s)
                                    <li data-val="{{ $s }}" onclick="selectStaticDD('editSeatCount','{{ $s }}','{{ $s }} Koltuk')" class="{{ (int)$curSeatEdit===$s?'selected':'' }}">{{ $s }} Koltuk</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Motor --}}
            <div class="border-t pt-6">
                <h4 class="text-sm font-bold text-gray-700 mb-4">Motor Özellikleri</h4>
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Motor Hacmi (cc)</label>
                            <input type="number" name="engine_size" value="{{ old('engine_size',$vehicle->engine_size) }}" placeholder="1600" min="0"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Motor Gücü (HP)</label>
                            <input type="number" name="horse_power" value="{{ old('horse_power',$vehicle->horse_power) }}" placeholder="120" min="0"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tork (Nm)</label>
                            <input type="number" name="torque" value="{{ old('torque',$vehicle->torque) }}" placeholder="250" min="0"
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

        {{-- ─── Tab 4: GÖRSELLER ───────────────────────────────────────────── --}}
        <div id="vtab-gorseller" class="vehicle-tab-content p-6 space-y-6 hidden">

            <h3 class="text-lg font-bold text-gray-900">Araç Görselleri</h3>

            {{-- Mevcut Görseller --}}
            @php
                $existingImages = is_array($vehicle->images) ? $vehicle->images : ($vehicle->image ? [$vehicle->image] : []);
            @endphp
            @if(count($existingImages) > 0)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Mevcut Görseller
                    <span class="ml-1 text-xs font-normal text-gray-400">— Silmek istediklerinize tıklayın, ana görsel için seçin</span>
                </label>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3" id="existingImagesGrid">
                    @foreach($existingImages as $imgPath)
                    @php $displayUrl=\App\Models\Vehicle::resolveImageUrl($imgPath); $isMain=($imgPath===$vehicle->image); @endphp
                    <div class="existing-image-item relative group rounded-lg overflow-hidden border-2 {{ $isMain ? 'border-red-500' : 'border-gray-200' }} transition-all"
                         data-path="{{ $imgPath }}" id="imgItem_{{ $loop->index }}">
                        <img src="{{ $displayUrl }}" alt="Araç görseli" class="w-full h-28 object-cover"
                             onerror="this.src='{{ asset('images/vehicles/default.jpg') }}'">
                        @if($isMain)
                        <span class="absolute top-1 left-1 bg-red-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">Ana</span>
                        @endif
                        <label class="absolute top-1 right-1 cursor-pointer">
                            <input type="checkbox" name="remove_images[]" value="{{ $imgPath }}" class="remove-image-cb sr-only" onchange="toggleRemoveImage(this,{{ $loop->index }})">
                            <span class="flex items-center justify-center w-6 h-6 bg-red-500 text-white rounded-full text-xs font-bold shadow opacity-0 group-hover:opacity-100 transition-opacity" id="removeBtn_{{ $loop->index }}">×</span>
                        </label>
                        <label class="absolute bottom-0 inset-x-0 text-center text-[10px] font-semibold py-1 bg-black/50 text-white cursor-pointer opacity-0 group-hover:opacity-100 transition-opacity">
                            <input type="radio" name="set_main_image" value="{{ $imgPath }}" class="sr-only" {{ $isMain?'checked':'' }}>
                            {{ $isMain?'✓ Ana Görsel':'Ana Görsel Yap' }}
                        </label>
                    </div>
                    @endforeach
                </div>
                <p class="mt-2 text-xs text-gray-500">Üzerine gelip <strong>×</strong> ile silebilir, <strong>Ana Görsel Yap</strong>'a tıklayarak kapak görselini değiştirebilirsiniz.</p>
            </div>
            @endif

            {{-- Yeni Ana Görsel --}}
            <div class="border-t pt-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Yeni Ana Görsel Yükle
                    <span class="ml-1 text-xs font-normal text-gray-400">(mevcut ana görselin yerini alır)</span>
                </label>
                <input type="file" name="main_image" id="editMainImageInput" accept="image/*" class="hidden">
                <label for="editMainImageInput"
                       class="flex items-center gap-3 px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-red-400 hover:bg-red-50 transition-all">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    <span class="text-sm text-gray-500" id="editMainLabel">PNG, JPG — Maks. 5MB</span>
                </label>
                <div id="editMainPreview" class="mt-3 hidden">
                    <div class="relative inline-block rounded-lg overflow-hidden border-2 border-red-500">
                        <img id="editMainPreviewImg" src="" alt="Yeni Ana Görsel" class="h-40 w-auto object-cover">
                        <button type="button" onclick="clearEditMain()" class="absolute top-1 right-1 w-6 h-6 bg-red-500 text-white rounded-full text-xs font-bold hover:bg-red-600">×</button>
                    </div>
                </div>
                <div class="mt-2 flex items-start gap-2 px-3 py-2 bg-amber-50 border border-amber-200 rounded-lg">
                    <svg class="w-4 h-4 text-amber-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-xs text-amber-700"><span class="font-bold">Önerilen:</span> 1200 × 800 piksel (3:2 oran)</p>
                </div>
            </div>

            {{-- Ek Galeri --}}
            <div class="border-t pt-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Ek Galeri Görselleri
                    <span class="ml-1 text-xs font-normal text-gray-400">(mevcut görsellerin üstüne eklenir)</span>
                </label>
                <input type="file" name="images[]" id="editGalleryInput" accept="image/*" multiple class="hidden">
                <label for="editGalleryInput"
                       class="flex items-center gap-3 px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-red-400 hover:bg-red-50 transition-all">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    <span class="text-sm text-gray-500" id="editGalleryLabel">Birden fazla görsel seçebilirsiniz — Maks. 5MB/adet</span>
                </label>
                <div id="editGalleryPreview" class="grid grid-cols-4 sm:grid-cols-6 gap-2 mt-3"></div>
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

        {{-- ─── Tab 5: DONANIMLAR ───────────────────────────────────────────── --}}
        <div id="vtab-donanim" class="vehicle-tab-content p-6 space-y-4 hidden">

            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900">Donanım & Özellikler</h3>
                <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full" id="editSelectedCount">
                    @php $currentFeatures = old('features', $vehicle->features ?? []); @endphp
                    {{ count($currentFeatures) }} özellik seçili
                </span>
            </div>

            <input type="text" id="editFeatureSearch" placeholder="Donanım ara..."
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors text-sm"
                   oninput="filterEditFeatures(this.value)">

            <div class="space-y-2" id="editFeaturesContainer">
                @foreach($featureCategories as $category => $features)
                <div class="border border-gray-200 rounded-lg">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-t-lg cursor-pointer select-none"
                         onclick="this.nextElementSibling.classList.toggle('hidden')">
                        <span class="text-sm font-semibold text-gray-700">{{ $category }}</span>
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                    <div class="p-3">
                        <div class="grid grid-cols-2 gap-1.5">
                            @foreach($features as $feature)
                            <label class="edit-feature-item flex items-center space-x-2 p-2 border border-gray-200 rounded hover:bg-gray-50 cursor-pointer transition-all text-sm">
                                <input type="checkbox" name="features[]" value="{{ $feature }}"
                                       class="w-3.5 h-3.5 text-red-600 border-gray-300 rounded focus:ring-red-500"
                                       onchange="updateEditFeatureCount()"
                                       {{ in_array($feature,$currentFeatures)?'checked':'' }}>
                                <span class="text-gray-700">{{ $feature }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach

                {{-- Orphan features --}}
                @php
                    $allCatalogFeatures = collect($featureCategories)->flatten()->toArray();
                    $orphanFeatures = array_filter($currentFeatures, fn($f) => !in_array($f, $allCatalogFeatures));
                @endphp
                @if(count($orphanFeatures) > 0)
                <div class="border border-amber-200 rounded-lg">
                    <div class="flex items-center justify-between p-3 bg-amber-50 rounded-t-lg cursor-pointer select-none"
                         onclick="this.nextElementSibling.classList.toggle('hidden')">
                        <span class="text-sm font-semibold text-amber-700">Diğer (Katalog Dışı)</span>
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                    <div class="p-3">
                        <p class="text-xs text-amber-600 mb-2">Katalogda yer almayan eski özellikler. Korumak istiyorsanız işaretli bırakın.</p>
                        <div class="grid grid-cols-2 gap-1.5">
                            @foreach($orphanFeatures as $feature)
                            <label class="edit-feature-item flex items-center space-x-2 p-2 border border-amber-200 rounded bg-amber-50 cursor-pointer text-sm">
                                <input type="checkbox" name="features[]" value="{{ $feature }}"
                                       class="w-3.5 h-3.5 text-amber-600 border-amber-300 rounded"
                                       onchange="updateEditFeatureCount()" checked>
                                <span class="text-amber-800">{{ $feature }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Step Footer --}}
            <div class="step-footer mt-6">
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

        {{-- ─── Tab 6: HASAR & GEÇMİŞ ─────────────────────────────────────── --}}
        <div id="vtab-hasar" class="vehicle-tab-content p-6 space-y-6 hidden">

            <h3 class="text-lg font-bold text-gray-900">Hasar & Geçmiş Bilgileri</h3>

            @php
                $editTramerOptions = ['Yok'=>'Yok (Temiz)','Var'=>'Var','Bilinmiyor'=>'Bilinmiyor'];
                $curTramerEdit = $vehicle->tramer_status ?? old('tramer_status', '');
            @endphp
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">
                <h4 class="font-semibold text-gray-900 mb-4">Tramer & Sahip Bilgisi</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tramer Kaydı</label>
                        <div class="adm-dd" id="ddWrap-editTramerStatus">
                            <input type="hidden" name="tramer_status" id="ddVal-editTramerStatus" value="{{ $curTramerEdit }}">
                            <button type="button" class="adm-dd-btn" id="ddBtn-editTramerStatus" onclick="toggleStaticDD('editTramerStatus')">
                                <span id="ddLabel-editTramerStatus">{{ $editTramerOptions[$curTramerEdit] ?? 'Seçiniz' }}</span>
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <ul class="adm-dd-list" id="ddList-editTramerStatus">
                                <li data-val="" onclick="selectStaticDD('editTramerStatus','','Seçiniz')">Seçiniz</li>
                                @foreach($editTramerOptions as $v=>$l)
                                    <li data-val="{{ $v }}" onclick="selectStaticDD('editTramerStatus','{{ $v }}','{{ $l }}')" class="{{ $curTramerEdit===$v?'selected':'' }}">{{ $l }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tramer Tutarı (₺)</label>
                        <input type="number" name="tramer_amount" value="{{ old('tramer_amount',$vehicle->tramer_amount) }}" placeholder="0" step="0.01" min="0"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kaçıncı Sahip</label>
                        <input type="number" name="owner_number" value="{{ old('owner_number',$vehicle->owner_number) }}" placeholder="1" min="1"
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
                                {{ $vehicle->has_warranty ? 'border-green-500 bg-green-50 text-green-700' : 'border-gray-200 hover:border-green-300 text-gray-600' }}" id="editWarrantyYesLabel">
                                <input type="radio" name="has_warranty" value="1" class="sr-only" {{ $vehicle->has_warranty ? 'checked' : '' }}
                                       onchange="document.getElementById('editWarrantyYesLabel').classList.add('border-green-500','bg-green-50','text-green-700');document.getElementById('editWarrantyNoLabel').classList.remove('border-red-500','bg-red-50','text-red-700');document.getElementById('editWarrantyNoLabel').classList.add('border-gray-200','text-gray-600');">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Var
                            </label>
                            <label class="flex items-center justify-center gap-2 p-3 border-2 rounded-lg cursor-pointer transition-all text-sm font-medium
                                {{ !$vehicle->has_warranty ? 'border-red-500 bg-red-50 text-red-700' : 'border-gray-200 hover:border-red-300 text-gray-600' }}" id="editWarrantyNoLabel">
                                <input type="radio" name="has_warranty" value="0" class="sr-only" {{ !$vehicle->has_warranty ? 'checked' : '' }}
                                       onchange="document.getElementById('editWarrantyNoLabel').classList.add('border-red-500','bg-red-50','text-red-700');document.getElementById('editWarrantyYesLabel').classList.remove('border-green-500','bg-green-50','text-green-700');document.getElementById('editWarrantyYesLabel').classList.add('border-gray-200','text-gray-600');">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Yok
                            </label>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Muayene Tarihi</label>
                        <input type="date" name="inspection_date"
                               value="{{ old('inspection_date', $vehicle->inspection_date ? $vehicle->inspection_date->format('Y-m-d') : '') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Garanti Bitiş Tarihi</label>
                        <input type="date" name="warranty_end_date"
                               value="{{ old('warranty_end_date', $vehicle->warranty_end_date ? $vehicle->warranty_end_date->format('Y-m-d') : '') }}"
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
                    <div class="flex flex-col items-center" style="position:relative;" id="editCarDiagramWrapper">
                        <div class="admin-car-tooltip" id="editCarTooltip">
                            <div class="admin-car-tooltip-title" id="editTooltipTitle">Parça</div>
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
                    <div id="editPartInputs"></div>
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

        {{-- ─── Tab 7: YAYIN AYARLARI (Son Adım) ──────────────────────────── --}}
        <div id="vtab-yayin" class="vehicle-tab-content p-6 space-y-6 hidden">

            <div>
                <h3 class="text-lg font-bold text-gray-900">Yayın Ayarları</h3>
                <p class="text-xs text-gray-500 mt-1">Araç bilgilerinizi güncellediniz. Yayın durumunu belirleyin ve kaydedin.</p>
            </div>

            {{-- Yayın Durumu --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="flex items-start gap-4 p-5 border-2 rounded-xl cursor-pointer transition-all hover:border-red-400 hover:bg-red-50/50
                    {{ ($errors->any() ? old('is_active') : $vehicle->is_active) ? 'border-red-500 bg-red-50' : 'border-gray-200' }}">
                    <input type="checkbox" name="is_active" value="1" class="w-5 h-5 mt-0.5 text-red-600 border-gray-300 rounded focus:ring-red-500"
                           {{ ($errors->any() ? old('is_active') : $vehicle->is_active) ? 'checked' : '' }}>
                    <div>
                        <p class="font-bold text-gray-900">Aktif (Yayında)</p>
                        <p class="text-sm text-gray-500 mt-1">Araç web sitesinde ziyaretçilere görünür olacak</p>
                    </div>
                </label>
                <label class="flex items-start gap-4 p-5 border-2 rounded-xl cursor-pointer transition-all hover:border-yellow-400 hover:bg-yellow-50/50
                    {{ ($errors->any() ? old('is_featured') : $vehicle->is_featured) ? 'border-yellow-500 bg-yellow-50' : 'border-gray-200' }}">
                    <input type="checkbox" name="is_featured" value="1" class="w-5 h-5 mt-0.5 text-yellow-500 border-gray-300 rounded focus:ring-yellow-400"
                           {{ ($errors->any() ? old('is_featured') : $vehicle->is_featured) ? 'checked' : '' }}>
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
                        {{ old('vehicle_status',$vehicle->vehicle_status??'available')===$val ? 'border-red-500 bg-red-50' : 'border-gray-200 hover:border-red-300 hover:bg-red-50/50' }}">
                        <input type="radio" name="vehicle_status" value="{{ $val }}"
                               class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500"
                               {{ old('vehicle_status',$vehicle->vehicle_status??'available')===$val ? 'checked' : '' }}>
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
                        <input type="url" name="sahibinden_url" value="{{ old('sahibinden_url', $vehicle->sahibinden_url) }}" placeholder="https://www.sahibinden.com/..."
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">İlan No</label>
                        <input type="text" name="sahibinden_id" value="{{ old('sahibinden_id', $vehicle->sahibinden_id) }}" placeholder="123456789"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors font-mono text-sm">
                    </div>
                </div>
            </div>

            {{-- Kaydet Butonları --}}
            <div class="border-t pt-6">
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="submit"
                            class="flex-1 px-6 py-4 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition-all text-sm flex items-center justify-center gap-2 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Değişiklikleri Kaydet
                    </button>
                    <a href="{{ route('admin.vehicles.index') }}"
                       class="px-6 py-4 border-2 border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-all text-sm flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        İptal
                    </a>
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

    </div>{{-- /tabs card --}}
    </div>

    </div>

</form>

@push('scripts')
<script>
// ═══════════════════════════════════════════════════════════════════════════════
// STEP SYSTEM
// ═══════════════════════════════════════════════════════════════════════════════
const STEP_ORDER = ['kimlik','ilan','teknik','gorseller','donanim','hasar','yayin'];
let currentStep = 'kimlik';
let stepVisited = { kimlik: true };

function goToStep(tabId, validate) {
    if (validate && currentStep !== tabId) {
        const warnings = checkStepCompletion(currentStep);
        if (warnings.length > 0) {
            Swal.fire({
                icon: 'info',
                title: 'Eksik Alanlar',
                html: '<p class="text-sm text-gray-500 mb-3">Aşağıdaki alanlar henüz doldurulmadı:</p><ul class="text-left text-sm text-gray-600 space-y-1">' +
                      warnings.map(w => '<li class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-amber-500 rounded-full flex-shrink-0"></span>' + w + '</li>').join('') + '</ul>' +
                      '<p class="text-xs text-gray-400 mt-3">Yine de devam edebilirsiniz.</p>',
                confirmButtonText: 'Devam Et',
                showCancelButton: true,
                cancelButtonText: 'Düzelt',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
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
    document.querySelectorAll('.step-btn').forEach(btn => {
        btn.classList.toggle('active', btn.dataset.tab === tabId);
    });
    document.querySelectorAll('.vehicle-tab-content').forEach(c => {
        c.classList.toggle('hidden', c.id !== 'vtab-' + tabId);
    });
    updateAllStepStatuses();
    window.scrollTo({ top: document.querySelector('.vehicle-form-card').offsetTop - 20, behavior: 'smooth' });
}

function switchVehicleTab(tabId) { goToStep(tabId); }

function checkStepCompletion(stepId) {
    const w = [];
    switch (stepId) {
        case 'kimlik':
            if (!val('year'))  w.push('Model Yılı');
            if (!val('brand')) w.push('Marka');
            if (!val('model')) w.push('Model / Seri');
            break;
        case 'ilan':
            if (!val('title'))     w.push('İlan Başlığı');
            if (!val('price'))     w.push('Fiyat');
            if (!val('kilometer')) w.push('Kilometre');
            break;
        case 'gorseller':
            const hasExisting = document.querySelectorAll('.existing-image-item').length > 0;
            const hasNew = (document.getElementById('editMainImageInput') || {}).files;
            if (!hasExisting && !(hasNew && hasNew.length > 0)) w.push('Görsel');
            break;
    }
    return w;
}

function val(name) {
    const el = document.querySelector('[name="' + name + '"]');
    return el ? String(el.value).trim() : '';
}

function getStepStatus(stepId) {
    if (!stepVisited[stepId]) return 'pending';
    return checkStepCompletion(stepId).length > 0 ? 'warn' : 'done';
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

function debounce(fn, ms) {
    let t; return function() { clearTimeout(t); t = setTimeout(fn, ms); };
}
document.addEventListener('input', debounce(updateAllStepStatuses, 300));
document.addEventListener('change', debounce(updateAllStepStatuses, 300));

// Edit sayfasında mevcut veriler olduğu için tüm adımları "ziyaret edilmiş" say
STEP_ORDER.forEach(function(id) { stepVisited[id] = true; });
updateAllStepStatuses();

// ═══════════════════════════════════════════════════════════════════════════════
// IMAGE HANDLING
// ═══════════════════════════════════════════════════════════════════════════════
document.getElementById('editMainImageInput').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    document.getElementById('editMainLabel').textContent = file.name;
    const reader = new FileReader();
    reader.onload = e => { document.getElementById('editMainPreviewImg').src = e.target.result; document.getElementById('editMainPreview').classList.remove('hidden'); };
    reader.readAsDataURL(file);
});

function clearEditMain() {
    document.getElementById('editMainImageInput').value = '';
    document.getElementById('editMainLabel').textContent = 'PNG, JPG — Maks. 5MB';
    document.getElementById('editMainPreview').classList.add('hidden');
}

document.getElementById('editGalleryInput').addEventListener('change', function () {
    const preview = document.getElementById('editGalleryPreview');
    preview.innerHTML = '';
    document.getElementById('editGalleryLabel').textContent = this.files.length + ' görsel seçildi';
    Array.from(this.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const div = document.createElement('div');
            div.className = 'relative rounded overflow-hidden border border-gray-200';
            div.innerHTML = `<img src="${e.target.result}" class="w-full h-20 object-cover">`;
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
});

function toggleRemoveImage(cb, index) {
    const item = document.getElementById('imgItem_' + index);
    const btn  = document.getElementById('removeBtn_' + index);
    if (cb.checked) {
        item.classList.add('opacity-40', 'ring-2', 'ring-red-400');
        btn.textContent = '↺'; btn.classList.replace('bg-red-500', 'bg-gray-500'); btn.classList.remove('opacity-0');
    } else {
        item.classList.remove('opacity-40', 'ring-2', 'ring-red-400');
        btn.textContent = '×'; btn.classList.replace('bg-gray-500', 'bg-red-500'); btn.classList.add('opacity-0');
    }
}

// ═══════════════════════════════════════════════════════════════════════════════
// FEATURES
// ═══════════════════════════════════════════════════════════════════════════════
function filterEditFeatures(query) {
    query = query.toLowerCase().trim();
    document.querySelectorAll('.edit-feature-item').forEach(label => {
        label.style.display = (!query || label.textContent.trim().toLowerCase().includes(query)) ? '' : 'none';
    });
}
function updateEditFeatureCount() {
    const count = document.querySelectorAll('input[name="features[]"]:checked').length;
    const el = document.getElementById('editSelectedCount');
    if (el) el.textContent = count + ' özellik seçili';
}

// ═══════════════════════════════════════════════════════════════════════════════
// KILOMETRE FORMATLAMA
// ═══════════════════════════════════════════════════════════════════════════════
(function() {
    var kmInput = document.getElementById('editKilometerInput');
    if (kmInput) {
        kmInput.addEventListener('input', function() {
            var raw = this.value.replace(/[^\d]/g, '');
            if (raw) {
                this.value = parseInt(raw, 10).toLocaleString('tr-TR');
            }
        });
    }
})();

// ═══════════════════════════════════════════════════════════════════════════════
// STATIC DROPDOWN FUNCTIONS
// ═══════════════════════════════════════════════════════════════════════════════
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

document.addEventListener('click', function(e) {
    if (!e.target.closest('.adm-dd')) {
        document.querySelectorAll('.adm-dd-list.open').forEach(function(l) {
            l.classList.remove('open');
            var wrap = l.closest('.adm-dd');
            if (wrap) { var b = wrap.querySelector('.adm-dd-btn'); if (b) b.classList.remove('open'); }
        });
    }
});

// ═══════════════════════════════════════════════════════════════════════════════
// MANUAL INPUT TOGGLES
// ═══════════════════════════════════════════════════════════════════════════════
(function () {
    const pairs = [
        { toggle: 'editManualBodyTypeToggle',     select: 'editBodyTypeSelect',     input: 'editManualBodyTypeInput' },
        { toggle: 'editManualFuelTypeToggle',     select: 'editFuelTypeSelect',     input: 'editManualFuelTypeInput' },
        { toggle: 'editManualTransmissionToggle', select: 'editTransmissionSelect', input: 'editManualTransmissionInput' },
    ];
    pairs.forEach(({ toggle, select, input }) => {
        const toggleEl = document.getElementById(toggle);
        const selectEl = document.getElementById(select);
        const inputEl  = document.getElementById(input);
        if (!toggleEl || !selectEl || !inputEl) return;
        toggleEl.addEventListener('change', function () {
            const isManual = this.checked;
            selectEl.disabled = isManual;
            selectEl.classList.toggle('hidden', isManual);
            inputEl.disabled = !isManual;
            inputEl.classList.toggle('hidden', !isManual);
            if (isManual) inputEl.focus();
            else inputEl.value = '';
        });
    });

    // Color manual toggle (uses adm-dd wrapper instead of select)
    var colorToggle = document.getElementById('editManualColorToggle');
    var colorWrap   = document.getElementById('ddWrap-editColor');
    var colorInput  = document.getElementById('editManualColorInput');
    var colorHidden = document.getElementById('ddVal-editColor');
    if (colorToggle && colorWrap && colorInput) {
        colorToggle.addEventListener('change', function () {
            if (this.checked) {
                colorWrap.classList.add('hidden');
                colorInput.classList.remove('hidden');
                colorInput.disabled = false;
                colorInput.focus();
                if (colorHidden) colorHidden.disabled = true;
            } else {
                colorWrap.classList.remove('hidden');
                colorInput.classList.add('hidden');
                colorInput.disabled = true;
                colorInput.value = '';
                if (colorHidden) colorHidden.disabled = false;
            }
        });
    }
})();

// ═══════════════════════════════════════════════════════════════════════════════
// CAR DIAGRAM (HASAR)
// ═══════════════════════════════════════════════════════════════════════════════
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
    var tooltip = document.getElementById('editCarTooltip');
    var tooltipTitle = document.getElementById('editTooltipTitle');
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
        var container = document.getElementById('editPartInputs');
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

    // Initialize from existing data
    var existingPainted = @json($vehicle->painted_parts ?? []);
    var existingReplaced = @json($vehicle->replaced_parts ?? []);
    var nameToKey = {};
    Object.keys(svgPartNames).forEach(function(k) { nameToKey[svgPartNames[k]] = k; });
    existingPainted.forEach(function(name) { var k = nameToKey[name]; if(k) partStates[k] = 'BOYALI'; });
    existingReplaced.forEach(function(name) { var k = nameToKey[name]; if(k) partStates[k] = 'DEGISMIS'; });
    Object.keys(partStates).forEach(updatePartColor);
    syncHiddenInputs();

    document.querySelectorAll('#editCarDiagramWrapper .car-part').forEach(function(part) {
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

            var wrapper = document.getElementById('editCarDiagramWrapper');
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

// ═══════════════════════════════════════════════════════════════════════════════
// FORM VALIDATION
// ═══════════════════════════════════════════════════════════════════════════════
document.getElementById('vehicleForm').addEventListener('submit', function (e) {
    const required = ['title', 'brand', 'model', 'year', 'kilometer', 'price'];
    let missing = [];
    required.forEach(field => {
        const input = document.querySelector('[name="' + field + '"]');
        if (!input || !String(input.value).trim()) missing.push(field);
    });
    if (missing.length > 0) {
        e.preventDefault();
        showWarning('Eksik Alanlar', 'Lütfen zorunlu alanları doldurun.');
        return false;
    }
});

// Static dropdown'lara search input ekle (5+ seçenek olanlar)
document.querySelectorAll('.adm-dd-list').forEach(function(list) {
    var lis = list.querySelectorAll('li');
    if (lis.length < 5) return;
    if (list.querySelector('.adm-dd-search')) return;
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
</script>
@endpush
@endsection

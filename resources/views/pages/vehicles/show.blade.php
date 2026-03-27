@extends('layouts.app')

@section('title', $vehicle->title . ' - ' . ($settings['site_title'] ?? 'GMSGARAGE'))
@section('description', $vehicle->brand . ' ' . $vehicle->model . ' ' . $vehicle->year . ' - ' . number_format($vehicle->kilometer ?? 0, 0, ',', '.') . ' km - ' . $vehicle->formatted_price . ' - ' . ($vehicle->fuel_type ?? '') . ' ' . ($vehicle->transmission ?? '') . ' - GMSGARAGE Kağıthane')
@section('keywords', implode(', ', array_filter([$vehicle->brand, $vehicle->model, $vehicle->year, $vehicle->fuel_type, $vehicle->transmission, 'ikinci el araç', 'oto galeri'])))
@section('og_type', 'product')
@section('og_url', route('vehicles.show', $vehicle->slug ?: $vehicle->id))
@section('og_title', $vehicle->title . ' - ' . $vehicle->formatted_price)
@section('og_description', Str::limit(strip_tags($vehicle->description ?? $vehicle->title), 160))
@section('og_image', count($vehicle->all_images) > 0 ? $vehicle->all_images[0] : asset('images/light-mode-logo.png'))
@section('canonical', route('vehicles.show', $vehicle->slug ?: $vehicle->id))

@push('meta')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Car",
    "name": "{{ $vehicle->title }}",
    "description": "{{ Str::limit(strip_tags($vehicle->description ?? ''), 300) }}",
    "brand": { "@type": "Brand", "name": "{{ $vehicle->brand }}" },
    "model": "{{ $vehicle->model }}",
    "vehicleModelDate": "{{ $vehicle->year }}",
    "fuelType": "{{ $vehicle->fuel_type }}",
    "vehicleTransmission": "{{ $vehicle->transmission }}",
    "mileageFromOdometer": { "@type": "QuantitativeValue", "value": "{{ $vehicle->kilometer }}", "unitCode": "KMT" },
    "color": "{{ $vehicle->color }}",
    "image": @json(count($vehicle->all_images) > 0 ? $vehicle->all_images : [asset('images/light-mode-logo.png')]),
    "offers": {
        "@type": "Offer",
        "price": "{{ $vehicle->price }}",
        "priceCurrency": "TRY",
        "availability": "https://schema.org/InStock",
        "url": "{{ route('vehicles.show', $vehicle->slug ?: $vehicle->id) }}"
    },
    "url": "{{ route('vehicles.show', $vehicle->slug ?: $vehicle->id) }}"
}
</script>
@endpush

@section('content')
    <!-- Breadcrumb -->
    <section class="bg-gray-50 dark:bg-[#1e1e1e] py-4 border-b border-gray-200 dark:border-[#333333] transition-colors duration-200">
        <div class="container-custom">
            <nav class="flex items-center space-x-2 text-sm">
                <a href="{{ route('vehicles.index') }}" class="text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium transition-colors">Araç Listesi</a>
                <span class="text-gray-400 dark:text-gray-600">/</span>
                <span class="text-gray-600 dark:text-gray-300">{{ $vehicle->brand ?? 'Marka' }}</span>
                <span class="text-gray-400 dark:text-gray-600">/</span>
                <span class="text-gray-600 dark:text-gray-300">{{ $vehicle->model ?? 'Model' }}</span>
                <span class="text-gray-400 dark:text-gray-600">/</span>
                <span class="text-gray-900 dark:text-gray-100 font-semibold">İlan No: {{ $vehicle->id ?? 'XXXXX' }}</span>
            </nav>
        </div>
    </section>

    <!-- Vehicle Details -->
    <section class="section-padding bg-white dark:bg-[#1e1e1e] transition-colors duration-200">
        <div class="container-custom">
            <div class="grid grid-cols-1 gap-6 mb-12" id="vehicle-detail-grid">
                <!-- Images Gallery - 70% -->
                <div id="gallery-section">
                    @php
                        $vehicleImages = $vehicle->all_images;
                        $defaultImage  = asset('images/vehicles/default.jpg');
                        $totalImages   = count($vehicleImages);
                    @endphp
                    
                    @if($totalImages > 0)
                        <!-- Main Image with Lightbox -->
                        <div class="mb-4 rounded-2xl overflow-hidden shadow-2xl group relative bg-gray-100 dark:bg-[#252525]">
                            <img src="{{ $vehicleImages[0] }}" 
                                 alt="{{ $vehicle->title }}"
                                 id="main-image"
                                 class="w-full h-[600px] object-cover transition-all duration-300 cursor-zoom-in hover:scale-105"
                                 onclick="openLightbox()"
                                 onerror="this.src='{{ $defaultImage }}';">
                            
                            <!-- Navigation Arrows (on hover) -->
                            @if($totalImages > 1)
                                <button onclick="event.stopPropagation(); previousImage();" 
                                        class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/90 dark:bg-[#252525]/90 hover:bg-white dark:hover:bg-[#252525] text-gray-900 dark:text-gray-100 rounded-full p-3 shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </button>
                                <button onclick="event.stopPropagation(); nextImage();" 
                                        class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/90 dark:bg-[#252525]/90 hover:bg-white dark:hover:bg-[#252525] text-gray-900 dark:text-gray-100 rounded-full p-3 shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            @endif
                            
                            <!-- Lightbox Icon Overlay (Büyüt) -->
                            <div class="absolute top-4 right-4 bg-black/70 hover:bg-black/80 text-white rounded-full p-3 cursor-pointer transition-all z-10 opacity-0 group-hover:opacity-100" onclick="event.stopPropagation(); openLightbox();">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                </svg>
                            </div>
                            
                            <!-- Image Counter -->
                            @if($totalImages > 1)
                                <div class="absolute bottom-4 right-4 bg-black/70 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                                    <span id="image-counter">1</span> / {{ $totalImages }}
                                </div>
                            @endif
                        </div>
                        
                        <!-- Thumbnail Carousel (Yatay Scroll) -->
                        @if($totalImages > 1)
                            <div class="relative group">
                                <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-hide" id="thumbnail-container" style="scroll-behavior: smooth;">
                                    @foreach($vehicleImages as $index => $image)
                                        <img src="{{ $image }}" 
                                             alt="{{ $vehicle->title }} - Görsel {{ $index + 1 }}"
                                             onclick="changeImage({{ $index }})"
                                             class="thumbnail-item w-24 h-24 object-cover rounded-lg cursor-pointer transition-all duration-300 border-2 flex-shrink-0 {{ $index === 0 ? 'border-primary-600 dark:border-primary-500 shadow-md scale-105' : 'border-gray-200 dark:border-[#333333] hover:border-primary-400 dark:hover:border-primary-500 hover:scale-105' }}"
                                             onerror="this.src='{{ $defaultImage }}';"
                                             id="thumb-{{ $index }}"
                                             data-index="{{ $index }}">
                                    @endforeach
                                </div>
                                
                                <!-- Scroll Indicators (if many images) -->
                                @if($totalImages > 5)
                                    <button onclick="scrollThumbnails('left')" class="absolute left-0 top-1/2 -translate-y-1/2 bg-white/90 dark:bg-[#252525]/90 hover:bg-white dark:hover:bg-[#252525] text-gray-700 dark:text-gray-200 rounded-full p-2 shadow-lg opacity-0 group-hover:opacity-100 transition-opacity hidden lg:block z-10">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                    </button>
                                    <button onclick="scrollThumbnails('right')" class="absolute right-0 top-1/2 -translate-y-1/2 bg-white/90 dark:bg-[#252525]/90 hover:bg-white dark:hover:bg-[#252525] text-gray-700 dark:text-gray-200 rounded-full p-2 shadow-lg opacity-0 group-hover:opacity-100 transition-opacity hidden lg:block z-10">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        @endif
                    @else
                        <div class="w-full h-[600px] bg-gradient-to-br from-gray-200 to-gray-300 rounded-2xl flex items-center justify-center shadow-2xl">
                            <svg class="w-32 h-32 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                </div>
                
                <!-- Vehicle Info Card - 30% -->
                <div class="lg:sticky lg:top-6 lg:self-start" id="info-section">
                    @php
                        // Alt başlık için title'dan marka ve modeli çıkar, kalan kısmı al
                        $subtitle = '';
                        $title = $vehicle->title ?? '';
                        $brand = $vehicle->brand ?? '';
                        $model = $vehicle->model ?? '';
                        
                        // Title'dan marka ve modeli temizle
                        $title = str_ireplace($brand, '', $title);
                        $title = str_ireplace($model, '', $title);
                        $title = str_ireplace($vehicle->year ?? '', '', $title);
                        $title = trim($title);
                        
                        // Eğer kalan kısım varsa alt başlık yap
                        if (!empty($title) && strlen($title) > 2) {
                            $subtitle = $title;
                        }
                        
                        // Specs line için verileri hazırla
                        $specs = [];
                        if ($vehicle->year) $specs[] = $vehicle->year;
                        if ($vehicle->transmission) $specs[] = $vehicle->transmission;
                        if ($vehicle->fuel_type) $specs[] = $vehicle->fuel_type;
                        if ($vehicle->kilometer) $specs[] = number_format($vehicle->kilometer, 0, ',', '.') . ' KM';
                        if ($vehicle->engine_size) {
                            $engineSize = (float) $vehicle->engine_size;
                            $specs[] = number_format($engineSize, 0, ',', '.') . ' cc';
                        }
                        if ($vehicle->horse_power) $specs[] = $vehicle->horse_power . ' hp';
                        $specsLine = implode(' / ', $specs);
                    @endphp
                    
                    <!-- Red Info Card -->
                    <div class="bg-primary-600 dark:bg-primary-700 rounded-t-2xl p-6 text-white mb-0 transition-colors duration-200">
                        <!-- Başlık: Marka Model -->
                        <h1 class="text-3xl md:text-4xl font-bold mb-2 leading-tight">
                            {{ $vehicle->brand ?? 'Marka' }} {{ $vehicle->model ?? 'Model' }}
                        </h1>
                        
                        <!-- Alt Başlık -->
                        @if($subtitle)
                            <div class="text-xl md:text-2xl font-medium mb-4 text-white/90">
                                {{ $subtitle }}
                            </div>
                        @endif
                        
                        <!-- Specs Line -->
                        @if($specsLine)
                            <div class="text-sm md:text-base mb-3 text-white/90 font-medium">
                                {{ $specsLine }}
                            </div>
                        @endif

                        <!-- Konum -->
                        @if($vehicle->city)
                            <div class="flex items-center gap-1.5 text-sm text-white/80 mb-2">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>{{ $vehicle->city }}</span>
                            </div>
                        @endif

                        <!-- Görüntülenme -->
                        @if(($vehicle->views ?? 0) > 0)
                            <div class="flex items-center gap-1.5 text-xs text-white/60 mb-6">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                {{ number_format($vehicle->views) }} kez görüntülendi
                            </div>
                        @else
                            <div class="mb-6"></div>
                        @endif
                        
                        <!-- Badges: Öne Çıkan + Araç Durumu -->
                        @if($vehicle->is_featured || ($vehicle->vehicle_status ?? 'available') !== 'available')
                            <div class="flex flex-wrap gap-2 mb-3">
                                @if($vehicle->is_featured)
                                    <span class="inline-flex items-center gap-1.5 text-sm font-bold px-4 py-1.5 rounded-full border bg-yellow-100 text-yellow-700 border-yellow-300">
                                        ⭐ Öne Çıkan
                                    </span>
                                @endif
                                @if(($vehicle->vehicle_status ?? 'available') !== 'available')
                                    <span class="inline-flex items-center text-sm font-bold px-4 py-1.5 rounded-full border
                                        {{ match($vehicle->vehicle_status ?? 'available') {
                                            'sold'        => 'bg-red-100 text-red-700 border-red-300',
                                            'reserved'    => 'bg-yellow-100 text-yellow-700 border-yellow-300',
                                            'opportunity' => 'bg-green-100 text-green-700 border-green-300',
                                            default       => 'bg-blue-100 text-blue-700 border-blue-300'
                                        } }}">
                                        {{ $vehicle->status_label }}
                                    </span>
                                @endif
                            </div>
                        @endif

                        <!-- Fiyat -->
                        <div class="text-4xl md:text-5xl font-bold">
                            {{ $vehicle->formatted_price }}
                        </div>
                        @if($vehicle->price_negotiable)
                            <div class="mt-2">
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1 rounded-full bg-amber-100 text-amber-700 border border-amber-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                    Pazarlık Payı Var
                                </span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-col gap-3 bg-white dark:bg-[#252525] rounded-b-2xl p-6 border-x border-b border-gray-200 dark:border-[#333333] mb-3 transition-colors duration-200">
                        <!-- Arabayı Yerinde Gör - Primary CTA -->
                        <a href="{{ route('contact') }}" 
                           class="w-full bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-bold py-4 px-6 rounded-xl text-center transition-all duration-300 uppercase text-sm shadow-lg hover:shadow-xl hover:shadow-primary-500/30 hover:scale-[1.01] relative overflow-hidden group">
                            <span class="relative z-10">ARABAYI YERİNDE GÖR</span>
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                        </a>
                        
                        <!-- Sahibinden.com'da Gör -->
                        @if($vehicle->sahibinden_url)
                            <a href="{{ $vehicle->sahibinden_url }}" 
                               target="_blank"
                               style="background: linear-gradient(135deg, #FFD400 0%, #FFDB4D 50%, #FFD400 100%);"
                               class="w-full hover:opacity-90 text-gray-900 dark:text-gray-900 font-bold py-4 px-6 rounded-xl text-center transition-all duration-300 uppercase text-sm shadow-lg hover:shadow-xl hover:scale-[1.01] relative overflow-hidden group flex items-center justify-center">
                                <span class="relative z-10 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                    SAHİBİNDEN.COM'DA GÖR
                                </span>
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                            </a>
                        @endif
                        
                        <!-- WhatsApp'ta Paylaş -->
                        <a href="https://wa.me/?text={{ urlencode($vehicle->title . ' - ' . $vehicle->formatted_price . ' - ' . url()->current()) }}" 
                           target="_blank"
                           style="background: linear-gradient(135deg, #25D366 0%, #20BA5A 50%, #25D366 100%); box-shadow: 0 10px 25px -5px rgba(37, 211, 102, 0.3);"
                           class="w-full hover:opacity-90 text-white font-bold py-4 px-6 rounded-xl text-center transition-all duration-300 uppercase text-sm hover:shadow-2xl hover:scale-[1.01] relative overflow-hidden group flex items-center justify-center">
                            <span class="relative z-10 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                                WHATSAPP'TA PAYLAŞ
                            </span>
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                        </a>
                    </div>
                    
                    <!-- Galeri İletişim Kartı -->
                    @php
                        $siteName    = $settings['site_title']   ?? 'GMSGARAGE';
                        $sitePhone   = $settings['contact_phone'] ?? null;
                        $siteEmail   = $settings['contact_email'] ?? null;
                        $totalActive = \App\Models\Vehicle::where('is_active', true)->count();
                    @endphp

                    <div class="bg-white dark:bg-[#252525] rounded-2xl border border-gray-200 dark:border-[#333333] shadow-md dark:shadow-xl hover:shadow-lg transition-all duration-300 overflow-hidden">
                        <!-- Header Section -->
                        <div class="p-5 pb-4">
                            <div class="flex items-start gap-3 mb-4">
                                <!-- Logo / Amblem -->
                                <div class="relative flex-shrink-0">
                                    <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center shadow-md ring-2 ring-primary-100">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                    </div>
                                    <!-- Onaylı Galeri Rozeti -->
                                    <div class="absolute -bottom-1 -right-1 bg-green-500 rounded-full p-1.5 shadow-md ring-2 ring-white">
                                        <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>

                                <!-- Galeri Adı ve Rozeti -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                                        <h3 class="font-bold text-gray-900 dark:text-gray-100 text-base leading-tight">{{ $siteName }}</h3>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold bg-primary-50 text-primary-700 border border-primary-200">
                                            Resmi Galeri
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Satış Danışmanı</p>
                                </div>
                            </div>

                            <!-- Galeri Bilgi Rozetleri -->
                            <div class="flex items-center gap-3 flex-wrap">
                                <div class="flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 dark:bg-[#2a2a2a] rounded-lg border border-gray-200 dark:border-[#333333]">
                                    <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Güvenilir Satıcı</span>
                                </div>
                                @if($totalActive > 0)
                                <div class="flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 dark:bg-[#2a2a2a] rounded-lg border border-gray-200 dark:border-[#333333]">
                                    <svg class="w-4 h-4 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ $totalActive }} Aktif İlan</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="border-t border-gray-200 dark:border-[#333333]"></div>

                        <!-- İletişim Aksiyonları -->
                        <div class="p-5 pt-4 space-y-2.5">
                            <a href="{{ route('contact') }}"
                               class="block w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 px-4 rounded-lg text-center transition-colors text-sm shadow-sm hover:shadow-md">
                                İletişime Geç
                            </a>
                            @if($sitePhone)
                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $sitePhone) }}"
                                   class="flex items-center justify-center gap-2 w-full text-center text-xs text-primary-600 dark:text-primary-400 hover:text-primary-700 font-semibold py-2.5 border border-primary-200 dark:border-primary-800 rounded-lg hover:bg-primary-50 dark:hover:bg-primary-900/30 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    {{ $sitePhone }}
                                </a>
                            @else
                                <a href="{{ route('vehicles.index') }}"
                                   class="block w-full text-center text-xs text-primary-600 dark:text-primary-400 hover:text-primary-700 font-semibold py-2.5 border border-primary-200 dark:border-primary-800 rounded-lg hover:bg-primary-50 dark:hover:bg-primary-900/30 transition-colors">
                                    Tüm İlanları Gör
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Hızlı Bilgi Kartları -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <!-- Yıl -->
                <div class="bg-gray-50 dark:bg-[#252525] rounded-xl p-4 border border-gray-200 dark:border-[#333333] flex items-center gap-3 transition-colors duration-200">
                    <div class="w-10 h-10 rounded-lg bg-primary-100 dark:bg-primary-900/20 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-300 mb-0.5">Yıl</p>
                        <p class="text-base font-bold text-gray-900 dark:text-gray-100">{{ $vehicle->year ?? '-' }}</p>
                    </div>
                </div>
                
                <!-- Kilometre -->
                <div class="bg-gray-50 dark:bg-[#252525] rounded-xl p-4 border border-gray-200 dark:border-[#333333] flex items-center gap-3 transition-colors duration-200">
                    <div class="w-10 h-10 rounded-lg bg-primary-100 dark:bg-primary-900/20 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-300 mb-0.5">Kilometre</p>
                        <p class="text-base font-bold text-gray-900 dark:text-gray-100">{{ number_format($vehicle->kilometer ?? 0, 0, ',', '.') }} km</p>
                    </div>
                </div>
                
                <!-- Yakıt -->
                <div class="bg-gray-50 dark:bg-[#252525] rounded-xl p-4 border border-gray-200 dark:border-[#333333] flex items-center gap-3 transition-colors duration-200">
                    <div class="w-10 h-10 rounded-lg bg-primary-100 dark:bg-primary-900/20 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-300 mb-0.5">Yakıt</p>
                        <p class="text-base font-bold text-gray-900 dark:text-gray-100">{{ $vehicle->fuel_type ?? '-' }}</p>
                    </div>
                </div>
                
                <!-- Vites -->
                <div class="bg-gray-50 dark:bg-[#252525] rounded-xl p-4 border border-gray-200 dark:border-[#333333] flex items-center gap-3 transition-colors duration-200">
                    <div class="w-10 h-10 rounded-lg bg-primary-100 dark:bg-primary-900/20 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-300 mb-0.5">Vites</p>
                        <p class="text-base font-bold text-gray-900 dark:text-gray-100">{{ $vehicle->transmission ?? '-' }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Tab Navigation -->
            <div class="bg-white dark:bg-[#252525] rounded-2xl shadow-xl dark:shadow-2xl border border-gray-100 dark:border-[#333333] mb-8 overflow-hidden transition-colors duration-200">
                <div class="flex overflow-x-auto border-b border-gray-200 dark:border-[#333333] bg-gray-50 dark:bg-[#1e1e1e]">
                    <button onclick="switchDetailTab('specs')" id="tab-btn-specs" class="detail-tab-btn active flex items-center gap-2 px-6 py-4 text-sm font-bold whitespace-nowrap border-b-3 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Araç Özellikleri
                    </button>
                    @php
                        $paintedParts = is_array($vehicle->painted_parts) ? $vehicle->painted_parts : [];
                        $replacedParts = is_array($vehicle->replaced_parts) ? $vehicle->replaced_parts : [];
                        $hasDamageData = count($paintedParts) > 0 || count($replacedParts) > 0 || $vehicle->tramer_status;
                    @endphp
                    @if($hasDamageData)
                    <button onclick="switchDetailTab('expertise')" id="tab-btn-expertise" class="detail-tab-btn flex items-center gap-2 px-6 py-4 text-sm font-bold whitespace-nowrap border-b-3 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Ekspertiz
                    </button>
                    @endif
                    @if(!empty($featureGroups) || (!empty($vehicle->features) && count($vehicle->features) > 0))
                    <button onclick="switchDetailTab('features')" id="tab-btn-features" class="detail-tab-btn flex items-center gap-2 px-6 py-4 text-sm font-bold whitespace-nowrap border-b-3 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Donanımlar
                    </button>
                    @endif
                    <button onclick="switchDetailTab('description')" id="tab-btn-description" class="detail-tab-btn flex items-center gap-2 px-6 py-4 text-sm font-bold whitespace-nowrap border-b-3 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                        Açıklama
                    </button>
                </div>

                <!-- Tab Content: Araç Özellikleri -->
                <div id="tab-specs" class="detail-tab-content p-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Araç Özellikleri</h2>
                {{--
                    SOL: Kimlik / Genel bilgiler (condition, marka, model, kasa, renk, kapı, koltuk)
                    SAĞ: Teknik + Geçmiş (motor, güç, tork, çekiş, paket, garanti, tramer, sahip, muayene, takas, pazarlık)
                    Konum: üst info kartında gösteriliyor, burada tekrar etmiyoruz.
                --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- SOL: Genel / Kimlik Bilgileri -->
                    <div class="space-y-4">
                        <!-- Araç Durumu (Sıfır / İkinci El) -->
                        @if($vehicle->condition)
                        <div class="flex justify-between items-center py-3 border-b border-gray-200 dark:border-[#333333]">
                            <span class="text-gray-600 dark:text-gray-300 font-medium">Araç Durumu</span>
                            <span class="inline-flex items-center font-bold text-sm px-3 py-1 rounded-full
                                {{ $vehicle->condition === 'zero_km'
                                    ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'
                                    : 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' }}">
                                {{ $vehicle->condition_label }}
                            </span>
                        </div>
                        @endif

                        <!-- Marka -->
                        @if($vehicle->brand)
                        <div class="flex justify-between items-center py-3 border-b border-gray-200 dark:border-[#333333]">
                            <span class="text-gray-600 dark:text-gray-300 font-medium">Marka</span>
                            <span class="font-bold text-gray-900 dark:text-gray-100 text-right">{{ $vehicle->brand }}</span>
                        </div>
                        @endif

                        <!-- Model -->
                        @if($vehicle->model)
                        <div class="flex justify-between items-center py-3 border-b border-gray-200 dark:border-[#333333]">
                            <span class="text-gray-600 dark:text-gray-300 font-medium">Model</span>
                            <span class="font-bold text-gray-900 dark:text-gray-100 text-right">{{ $vehicle->model }}</span>
                        </div>
                        @endif

                        <!-- Kasa / Gövde Tipi -->
                        @if($vehicle->body_type)
                        <div class="flex justify-between items-center py-3 border-b border-gray-200 dark:border-[#333333]">
                            <span class="text-gray-600 dark:text-gray-300 font-medium">Kasa Tipi</span>
                            <span class="font-bold text-gray-900 dark:text-gray-100 text-right">{{ $vehicle->body_type }}</span>
                        </div>
                        @endif

                        <!-- Renk -->
                        @if($vehicle->color)
                        <div class="flex justify-between items-center py-3 border-b border-gray-200 dark:border-[#333333]">
                            <span class="text-gray-600 dark:text-gray-300 font-medium">Renk</span>
                            <span class="font-bold text-gray-900 dark:text-gray-100 text-right">
                                {{ $vehicle->color }}
                                @if($vehicle->color_type) ({{ $vehicle->color_type }}) @endif
                            </span>
                        </div>
                        @endif

                        <!-- Kapı Sayısı -->
                        @if($vehicle->door_count)
                        <div class="flex justify-between items-center py-3 border-b border-gray-200 dark:border-[#333333]">
                            <span class="text-gray-600 dark:text-gray-300 font-medium">Kapı Sayısı</span>
                            <span class="font-bold text-gray-900 dark:text-gray-100 text-right">{{ $vehicle->door_count }} Kapı</span>
                        </div>
                        @endif

                        <!-- Koltuk Sayısı -->
                        @if($vehicle->seat_count)
                        <div class="flex justify-between items-center py-3 border-b border-gray-200 dark:border-[#333333]">
                            <span class="text-gray-600 dark:text-gray-300 font-medium">Koltuk Sayısı</span>
                            <span class="font-bold text-gray-900 dark:text-gray-100 text-right">{{ $vehicle->seat_count }} Kişilik</span>
                        </div>
                        @endif

                        <!-- Takas -->
                        @if($vehicle->swap)
                        <div class="flex justify-between items-center py-3 border-b border-gray-200 dark:border-[#333333]">
                            <span class="text-gray-600 dark:text-gray-300 font-medium">Takas</span>
                            <span class="inline-flex items-center gap-1.5 font-bold text-sm px-3 py-1 rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Takasa Uygun
                            </span>
                        </div>
                        @endif
                    </div>

                    <!-- SAĞ: Teknik + Geçmiş Bilgileri -->
                    <div class="space-y-4">
                        <!-- Motor Hacmi -->
                        @if($vehicle->engine_size)
                        <div class="flex justify-between items-center py-3 border-b border-gray-200 dark:border-[#333333]">
                            <span class="text-gray-600 dark:text-gray-300 font-medium">Motor Hacmi</span>
                            <span class="font-bold text-gray-900 dark:text-gray-100 text-right">
                                @php
                                    $engineSize = (float) $vehicle->engine_size;
                                    echo $engineSize >= 1000
                                        ? number_format($engineSize / 1000, 1, ',', '.') . ' L'
                                        : number_format($engineSize, 0, ',', '.') . ' cc';
                                @endphp
                            </span>
                        </div>
                        @endif

                        <!-- Motor Gücü -->
                        @if($vehicle->horse_power)
                        <div class="flex justify-between items-center py-3 border-b border-gray-200 dark:border-[#333333]">
                            <span class="text-gray-600 dark:text-gray-300 font-medium">Motor Gücü</span>
                            <span class="font-bold text-gray-900 dark:text-gray-100 text-right">{{ $vehicle->horse_power }} HP</span>
                        </div>
                        @endif

                        <!-- Tork -->
                        @if($vehicle->torque)
                        <div class="flex justify-between items-center py-3 border-b border-gray-200 dark:border-[#333333]">
                            <span class="text-gray-600 dark:text-gray-300 font-medium">Tork</span>
                            <span class="font-bold text-gray-900 dark:text-gray-100 text-right">{{ $vehicle->torque }} Nm</span>
                        </div>
                        @endif

                        <!-- Çekiş -->
                        @if($vehicle->drive_type)
                        <div class="flex justify-between items-center py-3 border-b border-gray-200 dark:border-[#333333]">
                            <span class="text-gray-600 dark:text-gray-300 font-medium">Çekiş</span>
                            <span class="font-bold text-gray-900 dark:text-gray-100 text-right">{{ $vehicle->drive_type }}</span>
                        </div>
                        @endif

                        <!-- Paket / Versiyon -->
                        @if($vehicle->package_version)
                        <div class="flex justify-between items-center py-3 border-b border-gray-200 dark:border-[#333333]">
                            <span class="text-gray-600 dark:text-gray-300 font-medium">Paket / Versiyon</span>
                            <span class="font-bold text-gray-900 dark:text-gray-100 text-right">{{ $vehicle->package_version }}</span>
                        </div>
                        @endif

                        <!-- Garanti -->
                        @if($vehicle->has_warranty)
                        <div class="flex justify-between items-center py-3 border-b border-gray-200 dark:border-[#333333]">
                            <span class="text-gray-600 dark:text-gray-300 font-medium">Garanti</span>
                            <span class="font-bold text-green-600 dark:text-green-400 text-right">
                                Var
                                @if($vehicle->warranty_end_date)
                                    ({{ $vehicle->warranty_end_date->format('d.m.Y') }}'e kadar)
                                @endif
                            </span>
                        </div>
                        @endif

                        <!-- Tramer (sadece bilinen değerlerde) -->
                        @if($vehicle->tramer_status && $vehicle->tramer_status !== 'Bilinmiyor')
                        <div class="flex justify-between items-center py-3 border-b border-gray-200 dark:border-[#333333]">
                            <span class="text-gray-600 dark:text-gray-300 font-medium">Tramer Kaydı</span>
                            <span class="font-bold {{ $vehicle->tramer_status === 'Yok' ? 'text-green-600 dark:text-green-400' : 'text-amber-600 dark:text-amber-400' }} text-right">
                                {{ $vehicle->tramer_status }}
                                @if($vehicle->tramer_amount && $vehicle->tramer_status === 'Var')
                                    ({{ number_format($vehicle->tramer_amount, 0, ',', '.') }} ₺)
                                @endif
                            </span>
                        </div>
                        @endif

                        <!-- Kaçıncı Sahip -->
                        @if($vehicle->owner_number)
                        <div class="flex justify-between items-center py-3 border-b border-gray-200 dark:border-[#333333]">
                            <span class="text-gray-600 dark:text-gray-300 font-medium">Kaçıncı Sahip</span>
                            <span class="font-bold text-gray-900 dark:text-gray-100 text-right">{{ $vehicle->owner_number }}. Sahip</span>
                        </div>
                        @endif

                        <!-- Muayene Tarihi -->
                        @if($vehicle->inspection_date)
                        <div class="flex justify-between items-center py-3 border-b border-gray-200 dark:border-[#333333]">
                            <span class="text-gray-600 dark:text-gray-300 font-medium">Muayene Tarihi</span>
                            <span class="font-bold text-gray-900 dark:text-gray-100 text-right">{{ $vehicle->inspection_date->format('d.m.Y') }}</span>
                        </div>
                        @endif

                        <!-- Pazarlık -->
                        @if($vehicle->price_negotiable)
                        <div class="flex justify-between items-center py-3 border-b border-gray-200 dark:border-[#333333]">
                            <span class="text-gray-600 dark:text-gray-300 font-medium">Pazarlık</span>
                            <span class="inline-flex items-center gap-1.5 font-bold text-sm px-3 py-1 rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                Pazarlık Payı Var
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
                </div>

                <!-- Tab Content: Ekspertiz -->
                @if($hasDamageData)
                <div id="tab-expertise" class="detail-tab-content p-8 hidden">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Ekspertiz Raporu</h2>

                    @php
                        $allParts = [
                            'Motor Kaputu','Ön Tampon','Arka Tampon','Arka Kaput','Tavan',
                            'Sağ Ön Kapı','Sağ Arka Kapı','Sol Ön Kapı','Sol Arka Kapı',
                            'Sağ Ön Çamurluk','Sağ Arka Çamurluk','Sol Ön Çamurluk','Sol Arka Çamurluk'
                        ];
                        $svgPartMap = [
                            'Motor Kaputu' => 'svg-motor_kaputu',
                            'Ön Tampon' => 'svg-on_tampon',
                            'Arka Tampon' => 'svg-arka_tampon',
                            'Arka Kaput' => 'svg-arka_kaput',
                            'Tavan' => 'svg-tavan',
                            'Sağ Ön Kapı' => 'svg-sag_on_kapi',
                            'Sağ Arka Kapı' => 'svg-sag_arka_kapi',
                            'Sol Ön Kapı' => 'svg-sol_on_kapi',
                            'Sol Arka Kapı' => 'svg-sol_arka_kapi',
                            'Sağ Ön Çamurluk' => 'svg-sag_on_camurluk',
                            'Sağ Arka Çamurluk' => 'svg-sag_arka_camurluk',
                            'Sol Ön Çamurluk' => 'svg-sol_on_camurluk',
                            'Sol Arka Çamurluk' => 'svg-sol_arka_camurluk',
                        ];
                    @endphp

                    <!-- Tramer Bilgisi -->
                    @if($vehicle->tramer_status)
                    <div class="mb-6 p-4 rounded-xl border-2 {{ $vehicle->tramer_status === 'Yok' ? 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800' : ($vehicle->tramer_status === 'Var' ? 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800' : 'bg-gray-50 dark:bg-[#2a2a2a] border-gray-200 dark:border-[#333333]') }}">
                        <div class="flex items-center gap-3">
                            @if($vehicle->tramer_status === 'Yok')
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                <span class="font-bold text-green-700 dark:text-green-400">Tramer Kaydı Yok</span>
                            @elseif($vehicle->tramer_status === 'Var')
                                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                <div>
                                    <span class="font-bold text-red-700 dark:text-red-400">Tramer Kaydı Var</span>
                                    @if($vehicle->tramer_amount)
                                        <span class="ml-2 text-red-600 dark:text-red-400 font-semibold">{{ number_format($vehicle->tramer_amount, 0, ',', '.') }} ₺</span>
                                    @endif
                                </div>
                            @else
                                <span class="font-bold text-gray-600 dark:text-gray-300">Tramer: {{ $vehicle->tramer_status }}</span>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- SVG Diyagram + Grid yan yana -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Sol: SVG Araç Diyagramı -->
                        <div class="flex flex-col items-center">
                            <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-4">Görsel Ekspertiz</h3>
                            <div class="w-full max-w-sm mx-auto" id="expertiseDiagram">
                                @include('admin.vehicles._car_diagram_svg')
                            </div>
                            <style>
                                #expertiseDiagram .car-part { transition: fill 0.3s; }
                                /* Dark mode SVG cizgi renkleri */
                                .dark #expertiseDiagram svg path[stroke="#D3D2D2"] { stroke: #444 !important; }
                                .dark #expertiseDiagram svg path[fill="#F0F0F0"] { fill: #2a2a2a !important; }
                                .dark #expertiseDiagram svg path[fill="#D3D2D2"] { fill: #444 !important; }
                                .dark #expertiseDiagram svg path[fill="#D8D8D8"] { fill: #3a3a3a !important; }
                                @foreach($allParts as $part)
                                    @php
                                        $svgId = $svgPartMap[$part] ?? '';
                                        $isPainted = in_array($part, $paintedParts);
                                        $isReplaced = in_array($part, $replacedParts);
                                    @endphp
                                    @if($svgId && $isReplaced)
                                        #expertiseDiagram #{{ $svgId }} { fill: #dc2626 !important; }
                                    @elseif($svgId && $isPainted)
                                        #expertiseDiagram #{{ $svgId }} { fill: #3b82f6 !important; }
                                    @elseif($svgId)
                                        {{-- Orijinal: varsayilan renkte kalsin --}}
                                    @endif
                                @endforeach
                            </style>
                        </div>

                        <!-- Sağ: Parça Grid -->
                        <div>
                            <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-4">Parça Detayları</h3>
                            <div class="grid grid-cols-2 gap-3">
                                @foreach($allParts as $part)
                                    @php
                                        $isPainted = in_array($part, $paintedParts);
                                        $isReplaced = in_array($part, $replacedParts);
                                    @endphp
                                    <div class="flex items-center gap-2 p-3 rounded-xl border-2
                                        {{ $isReplaced ? 'border-red-200 bg-red-50 dark:bg-red-900/20 dark:border-red-800' :
                                           ($isPainted ? 'border-blue-200 bg-blue-50 dark:bg-blue-900/20 dark:border-blue-800' :
                                                         'border-gray-100 bg-gray-50 dark:bg-[#2a2a2a] dark:border-[#333333]') }}">
                                        <span class="w-3 h-3 rounded-full flex-shrink-0 {{ $isReplaced ? 'bg-red-600' : ($isPainted ? 'bg-blue-500' : 'bg-gray-400 dark:bg-gray-500') }}"></span>
                                        <div>
                                            <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">{{ $part }}</span>
                                            <span class="block text-xs {{ $isReplaced ? 'text-red-600 dark:text-red-400 font-bold' : ($isPainted ? 'text-blue-600 dark:text-blue-400 font-bold' : 'text-gray-500 dark:text-gray-400') }}">
                                                {{ $isReplaced ? 'Değişmiş' : ($isPainted ? 'Boyalı' : 'Orijinal') }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Lejant -->
                    <div class="mt-6 pt-4 border-t border-gray-200 dark:border-[#333333] flex flex-wrap gap-6 text-sm">
                        <span class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                            <span class="w-4 h-4 rounded-full bg-gray-400 dark:bg-gray-500 inline-block"></span> Orijinal
                        </span>
                        <span class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                            <span class="w-4 h-4 rounded-full bg-blue-500 inline-block"></span> Boyalı
                        </span>
                        <span class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                            <span class="w-4 h-4 rounded-full bg-amber-400 inline-block"></span> Lokal Boyalı
                        </span>
                        <span class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                            <span class="w-4 h-4 rounded-full bg-red-600 inline-block"></span> Değişmiş
                        </span>
                    </div>
                </div>
                @endif

                <!-- Tab Content: Donanımlar -->
                @if(!empty($featureGroups))
                <div id="tab-features" class="detail-tab-content p-8 hidden">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Donanımlar</h2>
                        <span class="text-sm text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-[#2a2a2a] px-3 py-1 rounded-full">
                            {{ count($vehicle->features) }} özellik
                        </span>
                    </div>
                    <div class="space-y-6">
                        @foreach($featureGroups as $category => $features)
                            <div>
                                <h3 class="text-sm font-bold text-primary-600 dark:text-primary-400 uppercase tracking-wide mb-3 pb-1 border-b border-gray-100 dark:border-[#333333]">
                                    {{ $category }}
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    @foreach($features as $feature)
                                        <div class="flex items-center gap-3 py-1.5">
                                            <svg class="w-4 h-4 text-green-600 dark:text-green-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="text-gray-700 dark:text-gray-300 text-sm">{{ $feature }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @elseif(is_array($vehicle->features) && count($vehicle->features) > 0)
                <div id="tab-features" class="detail-tab-content p-8 hidden">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Donanımlar</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($vehicle->features as $feature)
                            <div class="flex items-center gap-3 py-2">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300 text-sm">{{ $feature }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Tab Content: Açıklama -->
                <div id="tab-description" class="detail-tab-content p-8 hidden">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Açıklama</h2>
                    <div class="text-gray-700 dark:text-gray-300 leading-relaxed">
                        {{ $vehicle->description ?? 'Açıklama bulunmamaktadır.' }}
                    </div>
                </div>
            </div>
            
            <!-- Related Vehicles -->
            @if(isset($relatedVehicles) && $relatedVehicles->count() > 0)
                <div class="mt-16">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Benzer Araçlar</h2>
                        <a href="{{ route('vehicles.index', ['brand' => $vehicle->brand]) }}" class="text-primary-600 dark:text-primary-400 font-semibold hover:text-primary-700 dark:hover:text-primary-300 flex items-center transition-colors">
                            Tümünü Gör
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($relatedVehicles as $relatedVehicle)
                            <x-vehicle-card :vehicle="$relatedVehicle" />
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Lightbox -->
    <div id="lightbox" style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0, 0, 0, 0.95); z-index: 99999; cursor: pointer; align-items: center; justify-content: center; padding: 0; margin: 0; overflow: hidden;" onclick="closeLightbox()">
        <!-- Close Button -->
        <button style="position: absolute; top: 16px; right: 16px; background: rgba(0, 0, 0, 0.6); color: white; border: none; border-radius: 50%; width: 48px; height: 48px; font-size: 32px; cursor: pointer; z-index: 100001; display: flex; align-items: center; justify-content: center; transition: background 0.3s;" onclick="event.stopPropagation(); closeLightbox();" title="Kapat (ESC)">&times;</button>
        
        <!-- Previous Button -->
        <button style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); background: rgba(255, 255, 255, 0.2); color: white; border: none; border-radius: 50%; width: 48px; height: 48px; cursor: pointer; z-index: 100001; display: flex; align-items: center; justify-content: center; transition: background 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'" onclick="event.stopPropagation(); previousLightboxImage();" title="Önceki (←)">
            <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
        
        <!-- Image Container -->
        <div style="width: 96vw; height: 92vh; max-width: 96vw; max-height: 92vh; display: flex; align-items: center; justify-content: center; padding: 0; margin: 0;">
            <img id="lightbox-image" style="max-width: 96vw; max-height: 92vh; width: auto; height: auto; object-fit: contain; cursor: default; z-index: 100000; display: block;" src="" alt="{{ $vehicle->title }}" onclick="event.stopPropagation();">
        </div>
        
        <!-- Next Button -->
        <button style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); background: rgba(255, 255, 255, 0.2); color: white; border: none; border-radius: 50%; width: 48px; height: 48px; cursor: pointer; z-index: 100001; display: flex; align-items: center; justify-content: center; transition: background 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'" onclick="event.stopPropagation(); nextLightboxImage();" title="Sonraki (→)">
            <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
        
        <!-- Image Counter -->
        <div id="lightbox-counter" style="position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); background: rgba(0, 0, 0, 0.7); color: white; padding: 12px 24px; border-radius: 8px; font-size: 18px; font-weight: 600; z-index: 100001;">
            <span id="lightbox-counter-current">1</span> / <span id="lightbox-counter-total"></span>
        </div>
    </div>

    <script>
        // Global variables
        var vehicleImages = @json($vehicleImages);
        var currentImageIndex = 0;
        var isLightboxOpen = false;
        
        // Change main image when thumbnail is clicked
        function changeImage(index) {
            if (index < 0 || index >= vehicleImages.length) return;
            
            currentImageIndex = index;
            var mainImage = document.getElementById('main-image');
            var counter = document.getElementById('image-counter');
            
            if (mainImage) {
                mainImage.src = vehicleImages[index];
            }
            
            if (counter) {
                counter.textContent = (index + 1);
            }
            
            // Update thumbnail borders
            var thumbs = document.querySelectorAll('.thumbnail-item');
            for (var i = 0; i < thumbs.length; i++) {
                if (i === index) {
                    thumbs[i].classList.remove('border-gray-200', 'border-primary-400');
                    thumbs[i].classList.add('border-primary-600', 'shadow-md', 'scale-105');
                } else {
                    thumbs[i].classList.remove('border-primary-600', 'shadow-md', 'scale-105');
                    thumbs[i].classList.add('border-gray-200');
                }
            }
            
            // Scroll thumbnail into view
            var activeThumb = document.getElementById('thumb-' + index);
            if (activeThumb) {
                activeThumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            }
        }
        
        // Previous image
        function previousImage() {
            var newIndex = currentImageIndex > 0 ? currentImageIndex - 1 : vehicleImages.length - 1;
            changeImage(newIndex);
        }
        
        // Next image
        function nextImage() {
            var newIndex = currentImageIndex < vehicleImages.length - 1 ? currentImageIndex + 1 : 0;
            changeImage(newIndex);
        }
        
        // Open lightbox
        function openLightbox() {
            isLightboxOpen = true;
            var lightbox = document.getElementById('lightbox');
            var lightboxImage = document.getElementById('lightbox-image');
            
            if (lightbox && lightboxImage && vehicleImages[currentImageIndex]) {
                lightboxImage.src = vehicleImages[currentImageIndex];
                updateLightboxCounter();
                lightbox.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        }
        
        // Close lightbox
        function closeLightbox() {
            isLightboxOpen = false;
            var lightbox = document.getElementById('lightbox');
            if (lightbox) {
                lightbox.style.display = 'none';
                document.body.style.overflow = '';
            }
        }
        
        // Update lightbox counter
        function updateLightboxCounter() {
            var current = document.getElementById('lightbox-counter-current');
            var total = document.getElementById('lightbox-counter-total');
            if (current) {
                current.textContent = (currentImageIndex + 1);
            }
            if (total) {
                total.textContent = vehicleImages.length;
            }
        }
        
        // Previous lightbox image
        function previousLightboxImage() {
            currentImageIndex = currentImageIndex > 0 ? currentImageIndex - 1 : vehicleImages.length - 1;
            var lightboxImage = document.getElementById('lightbox-image');
            if (lightboxImage && vehicleImages[currentImageIndex]) {
                lightboxImage.src = vehicleImages[currentImageIndex];
                updateLightboxCounter();
            }
        }
        
        // Next lightbox image
        function nextLightboxImage() {
            currentImageIndex = currentImageIndex < vehicleImages.length - 1 ? currentImageIndex + 1 : 0;
            var lightboxImage = document.getElementById('lightbox-image');
            if (lightboxImage && vehicleImages[currentImageIndex]) {
                lightboxImage.src = vehicleImages[currentImageIndex];
                updateLightboxCounter();
            }
        }
        
        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (isLightboxOpen) {
                if (e.key === 'ArrowLeft') {
                    e.preventDefault();
                    previousLightboxImage();
                } else if (e.key === 'ArrowRight') {
                    e.preventDefault();
                    nextLightboxImage();
                } else if (e.key === 'Escape') {
                    e.preventDefault();
                    closeLightbox();
                }
            } else {
                if (e.key === 'ArrowLeft') {
                    e.preventDefault();
                    previousImage();
                } else if (e.key === 'ArrowRight') {
                    e.preventDefault();
                    nextImage();
                } else if (e.key === 'Escape') {
                    // ESC key handled by lightbox
                }
            }
        });
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            var totalCounter = document.getElementById('lightbox-counter-total');
            if (totalCounter) {
                totalCounter.textContent = vehicleImages.length;
            }
        });
        
        // Thumbnail scroll
        function scrollThumbnails(direction) {
            var container = document.getElementById('thumbnail-container');
            if (container) {
                var scrollAmount = 300;
                container.scrollBy({
                    left: direction === 'left' ? -scrollAmount : scrollAmount,
                    behavior: 'smooth'
                });
            }
        }
        
    </script>
    
    <style>
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        
        /* Smooth scroll for anchor links */
        html {
            scroll-behavior: smooth;
        }
        
        /* Vehicle Detail Grid - 70% / 30% Layout */
        @media (min-width: 1024px) {
            #vehicle-detail-grid {
                grid-template-columns: 70% 30% !important;
                display: grid !important;
            }
            #gallery-section {
                grid-column: 1 !important;
            }
            #info-section {
                grid-column: 2 !important;
            }
        }
        
        /* Lightbox responsive styles */
        @media (max-width: 768px) {
            #lightbox > div[style*="96vw"] {
                width: 98vw !important;
                height: 88vh !important;
                max-width: 98vw !important;
                max-height: 88vh !important;
            }
            #lightbox img#lightbox-image {
                max-width: 98vw !important;
                max-height: 88vh !important;
            }
        }
        
        @media (max-width: 480px) {
            #lightbox > div[style*="96vw"] {
                width: 100vw !important;
                height: 90vh !important;
                max-width: 100vw !important;
                max-height: 90vh !important;
            }
            #lightbox img#lightbox-image {
                max-width: 100vw !important;
                max-height: 90vh !important;
            }
        }
        /* Detail Tab System */
        .detail-tab-btn {
            color: #6b7280;
            border-bottom: 3px solid transparent;
        }
        .detail-tab-btn:hover {
            color: #dc2626;
            background: rgba(220, 38, 38, 0.05);
        }
        .detail-tab-btn.active {
            color: #dc2626;
            border-bottom-color: #dc2626;
            background: rgba(220, 38, 38, 0.05);
        }
        .dark .detail-tab-btn {
            color: #9ca3af;
        }
        .dark .detail-tab-btn:hover {
            color: #f87171;
            background: rgba(220, 38, 38, 0.1);
        }
        .dark .detail-tab-btn.active {
            color: #f87171;
            border-bottom-color: #f87171;
            background: rgba(220, 38, 38, 0.1);
        }
    </style>

    <script>
        function switchDetailTab(tabId) {
            document.querySelectorAll('.detail-tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.detail-tab-btn').forEach(el => el.classList.remove('active'));
            const content = document.getElementById('tab-' + tabId);
            const btn = document.getElementById('tab-btn-' + tabId);
            if (content) content.classList.remove('hidden');
            if (btn) btn.classList.add('active');
        }
    </script>
@endsection

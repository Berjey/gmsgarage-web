@props(['vehicle'])

<div class="card-vehicle group bg-white dark:bg-[#252525] rounded-2xl shadow-lg dark:shadow-xl border-2 border-transparent dark:border-[#333333] transition-all duration-300 flex flex-col h-full hover:border-primary-600 dark:hover:border-primary-500 hover:shadow-2xl hover:-translate-y-1">
    <!-- Temsili Görsel Bölümü -->
        <div class="relative h-56 bg-gradient-to-br from-gray-100 via-gray-50 to-gray-100 dark:from-[#2a2a2a] dark:via-[#1e1e1e] dark:to-[#2a2a2a] overflow-hidden rounded-t-2xl">
            @if(count($vehicle->all_images) > 0)
                <!-- Gerçek Görsel Varsa -->
                <img src="{{ $vehicle->all_images[0] }}" 
                     alt="{{ $vehicle->title }}"
                     loading="lazy"
                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                     onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-900/20 dark:to-primary-800/20\'><svg class=\'w-24 h-24 text-primary-600 dark:text-primary-400 opacity-50\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9z\'></path><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M9 22V12h6v10\'></path><circle cx=\'12\' cy=\'8\' r=\'1\' fill=\'currentColor\'></circle></svg></div>';">
            @else
                <!-- Temsili Görsel (Placeholder) -->
                <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-900/20 dark:to-primary-800/20">
                    <!-- Araç İkonu -->
                    <svg class="w-24 h-24 text-primary-600 dark:text-primary-400 opacity-50 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 22V12h6v10"></path>
                        <circle cx="12" cy="8" r="1" fill="currentColor"></circle>
                    </svg>
                    <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">{{ $vehicle->brand }} {{ $vehicle->model }}</span>
                </div>
            @endif
            
            @if($vehicle->is_featured)
                <span class="absolute top-3 left-3 bg-accent-600 text-white px-3 py-1.5 rounded-full text-xs font-bold shadow-lg z-10">
                    ⭐ Öne Çıkan
                </span>
            @elseif($vehicle->condition === 'zero_km')
                <span class="absolute top-3 left-3 bg-green-600 text-white px-3 py-1.5 rounded-full text-xs font-bold shadow-lg z-10">
                    Sıfır
                </span>
            @endif

            @if(($vehicle->vehicle_status ?? 'available') !== 'available')
                <span class="absolute top-3 right-3 text-xs font-bold px-2.5 py-1 rounded-full shadow-md z-10 border
                    {{ match($vehicle->vehicle_status ?? 'available') {
                        'sold'        => 'bg-red-600 text-white border-red-700',
                        'reserved'    => 'bg-yellow-500 text-white border-yellow-600',
                        'opportunity' => 'bg-green-600 text-white border-green-700',
                        default       => ''
                    } }}">
                    {{ $vehicle->status_label }}
                </span>
            @endif
            
            <!-- Hover Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300"></div>
        </div>
        
        <!-- İçerik -->
        <div class="p-6 flex flex-col flex-grow">
            <!-- Başlık -->
            <div class="mb-3">
                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-1 line-clamp-2 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                    {{ $vehicle->title }}
                </h3>
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $vehicle->brand }} • {{ $vehicle->model }}</p>
                    @if($vehicle->city)
                        <span class="flex items-center gap-1 text-xs text-gray-400 dark:text-gray-500">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $vehicle->city }}
                        </span>
                    @elseif($vehicle->created_at)
                        <span class="text-xs text-gray-400 dark:text-gray-500">{{ $vehicle->created_at->format('d.m.Y') }}</span>
                    @endif
                </div>
            </div>
            
            <!-- Fiyat -->
            <div class="mb-4">
                <div class="text-3xl font-bold text-primary-600 dark:text-primary-400">
                    {{ $vehicle->formatted_price }}
                </div>
            </div>
            
            <!-- Özellikler -->
            <div class="grid grid-cols-2 gap-3 mb-4 pb-4 border-b border-gray-100 dark:border-[#333333]">
                @if($vehicle->year)
                <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-300">
                    {{-- takvim / yıl ikonu --}}
                    <svg class="w-5 h-5 text-primary-600 dark:text-primary-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="font-medium">{{ $vehicle->year }}</span>
                </div>
                @endif

                @if($vehicle->kilometer !== null)
                <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-300">
                    {{-- hız / kilometre ikonu --}}
                    <svg class="w-5 h-5 text-primary-600 dark:text-primary-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <span class="font-medium">{{ number_format($vehicle->kilometer, 0, ',', '.') }} km</span>
                </div>
                @endif

                @if($vehicle->fuel_type)
                <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-300">
                    {{-- yakıt / damla ikonu --}}
                    <svg class="w-5 h-5 text-primary-600 dark:text-primary-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3C8 8.5 6 11.8 6 15a6 6 0 0012 0c0-3.2-2-6.5-6-12z"/>
                    </svg>
                    <span class="font-medium">{{ $vehicle->fuel_type }}</span>
                </div>
                @endif

                {{-- body_type varsa göster; yoksa transmission fallback --}}
                @if($vehicle->body_type)
                <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-300">
                    {{-- kasa tipi / etiket ikonu --}}
                    <svg class="w-5 h-5 text-primary-600 dark:text-primary-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    <span class="font-medium">{{ $vehicle->body_type }}</span>
                </div>
                @elseif($vehicle->transmission)
                <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-300">
                    {{-- vites / dişli ikonu --}}
                    <svg class="w-5 h-5 text-primary-600 dark:text-primary-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                    <span class="font-medium">{{ $vehicle->transmission }}</span>
                </div>
                @endif
            </div>
            
            <!-- Butonlar -->
            <div class="flex gap-2.5 mt-auto pt-4">
                <a href="{{ route('vehicles.show', $vehicle->slug ?: $vehicle->id) }}" 
                   class="flex-1 px-4 py-3 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white rounded-xl font-semibold transition-all duration-300 text-sm text-center whitespace-nowrap flex items-center justify-center gap-1.5 shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <span>Detaylar</span>
                </a>
                @if($vehicle->sahibinden_url)
                    <a href="{{ $vehicle->sahibinden_url }}" 
                       target="_blank"
                       class="px-4 py-3 bg-gradient-to-r from-yellow-400/80 via-yellow-500/80 to-yellow-600/80 dark:from-yellow-400/85 dark:via-yellow-500/85 dark:to-yellow-600/85 hover:from-yellow-500 hover:via-yellow-600 hover:to-yellow-700 dark:hover:from-yellow-500 dark:hover:via-yellow-600 dark:hover:to-yellow-700 text-gray-900 dark:text-gray-900 rounded-xl font-semibold transition-all duration-300 text-sm text-center whitespace-nowrap flex items-center justify-center gap-1.5 backdrop-blur-sm border border-yellow-400/30 dark:border-yellow-400/40 shadow-md hover:shadow-lg">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                        <span>Sahibinden</span>
                    </a>
                @endif
            </div>
        </div>
</div>

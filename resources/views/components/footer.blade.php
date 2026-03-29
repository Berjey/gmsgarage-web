<footer class="bg-[#141414] text-gray-200 mt-20 border-t border-[#252525] relative overflow-hidden">
    <!-- Subtle animated gradient overlay -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute inset-0 footer-gradient-animation"></div>
    </div>
    
    <div class="container-custom py-16 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <!-- Sol: Logo + AI ve Güven Vurgulu Marka Metni -->
            <div>
                <a href="{{ route('home') }}" class="inline-block mb-6">
                    <img src="{{ asset('images/light-mode-logo.png') }}" alt="{{ $settings['site_title'] ?? 'GMSGARAGE' }} Logo" class="h-16 md:h-20 w-auto brightness-0 invert object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <div class="text-4xl md:text-5xl font-bold text-white" style="display:none;">{{ $settings['site_title'] ?? 'GMSGARAGE' }}</div>
                </a>
                <p class="text-gray-300 mb-4 text-lg leading-relaxed">
                    {{ $settings['footer_about_text'] ?? 'AI destekli araç değerleme ve güvenli alışveriş deneyimi. Premium ikinci el araçlar için güvenilir adresiniz.' }}
                </p>
                <div class="flex items-center gap-2 text-sm text-gray-400 mb-6">
                    <svg class="w-5 h-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    <span>Güvenli ve Şeffaf İşlemler</span>
                </div>
            </div>
            
            <!-- Orta: Hızlı Linkler (hover animasyonlu) -->
            <div>
                <h3 class="text-xl font-bold mb-6 text-white">Hızlı Linkler</h3>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition-all duration-300 font-medium inline-flex items-center group">
                            <span class="group-hover:translate-x-1 transition-transform duration-300">Anasayfa</span>
                            <svg class="w-4 h-4 ml-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('vehicles.index') }}" class="text-gray-300 hover:text-white transition-all duration-300 font-medium inline-flex items-center group">
                            <span class="group-hover:translate-x-1 transition-transform duration-300">Araçlar</span>
                            <svg class="w-4 h-4 ml-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('about') }}" class="text-gray-300 hover:text-white transition-all duration-300 font-medium inline-flex items-center group">
                            <span class="group-hover:translate-x-1 transition-transform duration-300">Hakkımızda</span>
                            <svg class="w-4 h-4 ml-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('contact') }}" class="text-gray-300 hover:text-white transition-all duration-300 font-medium inline-flex items-center group">
                            <span class="group-hover:translate-x-1 transition-transform duration-300">İletişim</span>
                            <svg class="w-4 h-4 ml-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Sağ: İletişim + Sosyal Medya -->
            <div>
                <h3 class="text-xl font-bold mb-6 text-white">İletişim</h3>
                <ul class="space-y-4 mb-6">
                    <li class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-primary-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-gray-400 text-sm">Telefon</div>
                            <a href="tel:{{ preg_replace('/[^0-9]/', '', $settings['contact_phone'] ?? '0555 123 45 67') }}" class="text-white font-semibold hover:text-primary-300 transition-colors">{{ $settings['contact_phone'] ?? '0555 123 45 67' }}</a>
                        </div>
                    </li>
                    <li class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-primary-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-gray-400 text-sm">E-posta</div>
                            <a href="mailto:{{ $settings['contact_email'] ?? 'info@gmsgarage.com' }}" class="text-white font-semibold hover:text-primary-300 transition-colors">{{ $settings['contact_email'] ?? 'info@gmsgarage.com' }}</a>
                        </div>
                    </li>
                    <li class="flex items-start space-x-3">
                        <div class="w-10 h-10 bg-primary-600 rounded-lg flex items-center justify-center mt-1 flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-gray-400 text-sm">Adres</div>
                            <span class="text-white font-semibold">{{ $settings['contact_address'] ?? 'Gürsel Mah. Kağıthane Cad. No: 26/1A KAĞITHANE/İSTANBUL' }}</span>
                        </div>
                    </li>
                </ul>
                
                <!-- Sosyal Medya -->
                <div class="flex space-x-3">
                    @if(!empty($settings['social_instagram']))
                    @php
                        $instagramUrl = str_starts_with($settings['social_instagram'], 'http') 
                            ? $settings['social_instagram'] 
                            : 'https://instagram.com/' . ltrim($settings['social_instagram'], '@');
                    @endphp
                    <a href="{{ $instagramUrl }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-primary-600 rounded-full flex items-center justify-center text-white hover:bg-primary-700 transition-all duration-300 hover:scale-110">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.057-1.275-.07-1.65-.07-4.859 0-3.21.015-3.586.074-4.859.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.057 1.65-.07 4.859-.07zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/>
                        </svg>
                    </a>
                    @endif
                    
                    @if(!empty($settings['social_facebook']))
                    @php
                        $facebookUrl = str_starts_with($settings['social_facebook'], 'http') 
                            ? $settings['social_facebook'] 
                            : 'https://facebook.com/' . ltrim($settings['social_facebook'], '@');
                    @endphp
                    <a href="{{ $facebookUrl }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-primary-600 rounded-full flex items-center justify-center text-white hover:bg-primary-700 transition-all duration-300 hover:scale-110">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    @endif
                    
                    @if(!empty($settings['social_youtube']))
                    @php
                        $youtubeUsername = $settings['social_youtube'];
                        if (str_starts_with($youtubeUsername, 'http')) {
                            $youtubeUrl = $youtubeUsername;
                        } else {
                            // @ ile başlıyorsa aynen kullan, yoksa @ ekle
                            $youtubeHandle = str_starts_with($youtubeUsername, '@') 
                                ? $youtubeUsername 
                                : '@' . $youtubeUsername;
                            $youtubeUrl = 'https://youtube.com/' . $youtubeHandle;
                        }
                    @endphp
                    <a href="{{ $youtubeUrl }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-primary-600 rounded-full flex items-center justify-center text-white hover:bg-primary-700 transition-all duration-300 hover:scale-110">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                    </a>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Alt Bar -->
        <div class="border-t border-[#2a2a2a] mt-12 pt-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-gray-400">{{ $settings['footer_copyright'] ?? '© 2026 GMSGARAGE. Tüm hakları saklıdır.' }}</p>
                <div class="flex items-center flex-wrap justify-center gap-4 text-sm">
                    {{-- Merkezi Sistem: Sadece legal_pages tablosundan çek --}}
                    @php
                        $footerPages = \App\Models\LegalPage::getFooterPages();
                    @endphp
                    @if($footerPages->count() > 0)
                        @foreach($footerPages as $page)
                            <a href="{{ route('legal.show', $page->slug) }}" class="text-gray-400 hover:text-white transition-colors">{{ $page->title }}</a>
                            @if(!$loop->last)
                                <span class="text-gray-600">|</span>
                            @endif
                        @endforeach
                    @else
                        {{-- Fallback: Eğer hiçbir sayfa işaretlenmemişse --}}
                        <span class="text-gray-500 text-xs">Admin panelden yasal sayfaları "Footer'da Göster" olarak işaretleyin</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <style>
        @keyframes footerGradient {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        .footer-gradient-animation {
            background: linear-gradient(-45deg, #dc2626, #991b1b, #7f1d1d, #dc2626);
            background-size: 400% 400%;
            animation: footerGradient 15s ease infinite;
        }
    </style>
</footer>

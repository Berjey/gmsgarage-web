<header class="bg-white/95 dark:bg-[#1e1e1e]/95 backdrop-blur-modern shadow-lg sticky top-0 z-50 border-b border-gray-100 dark:border-[#333333] transition-all duration-300">
    <nav class="container-custom">
        <div class="flex items-center justify-between h-28">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center space-x-3 group relative h-16 md:h-20">
                <!-- Light Mode Logo -->
                <img src="{{ asset('images/light-mode-logo.png') }}" alt="{{ $settings['site_title'] ?? 'GMSGARAGE' }} Logo" class="h-16 md:h-20 w-auto transition-transform duration-300 group-hover:scale-105 dark:hidden object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <!-- Dark Mode Logo -->
                <img src="{{ asset('images/dark-mode-logo.png') }}" alt="{{ $settings['site_title'] ?? 'GMSGARAGE' }} Logo" class="h-16 md:h-20 w-auto transition-transform duration-300 group-hover:scale-105 hidden dark:block object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <div class="text-4xl md:text-5xl font-bold text-primary-600 dark:text-primary-400" style="display:none;">{{ $settings['site_title'] ?? 'GMSGARAGE' }}</div>
            </a>
            
            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center space-x-1">
                <a href="{{ route('home') }}" 
                   class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-gray-50 dark:hover:bg-[#252525] font-medium transition-all duration-200 rounded-lg {{ request()->routeIs('home') ? 'text-primary-600 dark:text-primary-400 bg-gray-50 dark:bg-[#252525]' : '' }}">
                    Anasayfa
                </a>
                <a href="{{ route('vehicles.index') }}" 
                   class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-gray-50 dark:hover:bg-[#252525] font-medium transition-all duration-200 rounded-lg {{ request()->routeIs('vehicles.*') ? 'text-primary-600 dark:text-primary-400 bg-gray-50 dark:bg-[#252525]' : '' }}">
                    Araçlar
                </a>
                <a href="{{ route('blog.index') }}" 
                   class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-gray-50 dark:hover:bg-[#252525] font-medium transition-all duration-200 rounded-lg {{ request()->routeIs('blog.*') ? 'text-primary-600 dark:text-primary-400 bg-gray-50 dark:bg-[#252525]' : '' }}">
                    Blog
                </a>
                <a href="{{ route('about') }}" 
                   class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-gray-50 dark:hover:bg-[#252525] font-medium transition-all duration-200 rounded-lg {{ request()->routeIs('about') ? 'text-primary-600 dark:text-primary-400 bg-gray-50 dark:bg-[#252525]' : '' }}">
                    Hakkımızda
                </a>
                <a href="{{ route('contact') }}" 
                   class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-gray-50 dark:hover:bg-[#252525] font-medium transition-all duration-200 rounded-lg {{ request()->routeIs('contact') ? 'text-primary-600 dark:text-primary-400 bg-gray-50 dark:bg-[#252525]' : '' }}">
                    İletişim
                </a>
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $settings['contact_phone'] ?? '') }}" class="ml-4 btn btn-primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    Hemen Ara
                </a>
                <!-- Dark Mode Toggle -->
                <button id="dark-mode-toggle" class="ml-4 p-2 rounded-lg text-gray-700 hover:text-primary-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:text-gray-100 dark:hover:bg-[#252525] transition-colors" aria-label="Dark mode toggle">
                    <svg id="dark-mode-icon-sun" class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <svg id="dark-mode-icon-moon" class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Mobile Menu Button -->
            <button id="mobile-menu-button" class="lg:hidden text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-[#252525] transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
        
        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="hidden lg:hidden pb-4 border-t border-gray-100 dark:border-[#333333] mt-2">
            <div class="flex flex-col space-y-3">
                <a href="{{ route('home') }}" 
                   class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium transition-colors {{ request()->routeIs('home') ? 'text-primary-600 dark:text-primary-400' : '' }}">
                    Anasayfa
                </a>
                <a href="{{ route('vehicles.index') }}" 
                   class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium transition-colors {{ request()->routeIs('vehicles.*') ? 'text-primary-600 dark:text-primary-400' : '' }}">
                    Araçlar
                </a>
                <a href="{{ route('blog.index') }}" 
                   class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium transition-colors {{ request()->routeIs('blog.*') ? 'text-primary-600 dark:text-primary-400' : '' }}">
                    Blog
                </a>
                <a href="{{ route('about') }}" 
                   class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium transition-colors {{ request()->routeIs('about') ? 'text-primary-600 dark:text-primary-400' : '' }}">
                    Hakkımızda
                </a>
                <a href="{{ route('contact') }}" 
                   class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium transition-colors {{ request()->routeIs('contact') ? 'text-primary-600 dark:text-primary-400' : '' }}">
                    İletişim
                </a>
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $settings['contact_phone'] ?? '') }}" class="btn btn-primary w-full text-center">
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    Hemen Ara
                </a>
                <!-- Dark Mode Toggle Mobile -->
                <button id="dark-mode-toggle-mobile" class="mt-3 p-2 rounded-lg text-gray-700 hover:text-primary-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:text-gray-100 dark:hover:bg-[#252525] transition-colors w-full text-left" aria-label="Dark mode toggle">
                    <div class="flex items-center space-x-2">
                        <svg id="dark-mode-icon-sun-mobile" class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <svg id="dark-mode-icon-moon-mobile" class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                        <span class="font-medium">Karanlık Mod</span>
                    </div>
                </button>
            </div>
        </div>
    </nav>
</header>

<script>
    document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    });

    // Dark Mode Toggle Handlers
    document.getElementById('dark-mode-toggle')?.addEventListener('click', function() {
        if (typeof window.toggleTheme === 'function') {
            window.toggleTheme();
        }
    });

    document.getElementById('dark-mode-toggle-mobile')?.addEventListener('click', function() {
        if (typeof window.toggleTheme === 'function') {
            window.toggleTheme();
        }
    });
</script>

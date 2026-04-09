<aside class="w-64 bg-white border-r border-gray-200 flex flex-col relative z-50">
    <!-- Logo -->
    <div class="p-6 border-b border-gray-200 flex justify-center">
        <a href="{{ route('admin.dashboard') }}" class="block">
            <img src="{{ asset('images/light-mode-logo.png') }}" alt="GMSGARAGE Logo" class="h-16 w-auto">
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
        <!-- Anasayfa -->
        <a href="{{ route('admin.dashboard') }}" 
           class="group flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-primary-600 to-primary-700 text-white shadow-lg shadow-primary-500/30' : 'text-gray-700 hover:bg-gray-50 hover:translate-x-1' }}">
            <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-gray-500 group-hover:text-primary-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span>Anasayfa</span>
        </a>

        <!-- İçerik Yönetimi -->
        <div class="pt-4 pb-2">
            <p class="px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">İçerik Yönetimi</p>
        </div>

        <a href="{{ route('admin.vehicles.index') }}" 
           class="group flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('admin.vehicles.*') ? 'bg-primary-50 text-primary-600 border-l-4 border-primary-600' : 'text-gray-700 hover:bg-gray-50 hover:translate-x-1' }}">
            <svg class="w-5 h-5 {{ request()->routeIs('admin.vehicles.*') ? 'text-primary-600' : 'text-gray-500 group-hover:text-primary-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <span>Araçlar</span>
        </a>

        <a href="{{ route('admin.blog.index') }}" 
           class="group flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('admin.blog.*') ? 'bg-primary-50 text-primary-600 border-l-4 border-primary-600' : 'text-gray-700 hover:bg-gray-50 hover:translate-x-1' }}">
            <svg class="w-5 h-5 {{ request()->routeIs('admin.blog.*') ? 'text-primary-600' : 'text-gray-500 group-hover:text-primary-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span>Blog</span>
        </a>

        <a href="{{ route('admin.legal-pages.index') }}" 
           class="group flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('admin.legal-pages.*') ? 'bg-primary-50 text-primary-600 border-l-4 border-primary-600' : 'text-gray-700 hover:bg-gray-50 hover:translate-x-1' }}">
            <svg class="w-5 h-5 {{ request()->routeIs('admin.legal-pages.*') ? 'text-primary-600' : 'text-gray-500 group-hover:text-primary-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span>Yasal Sayfalar</span>
        </a>

        <!-- Mesajlar -->
        <div class="pt-6 pb-2">
            <p class="px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Mesajlar</p>
        </div>

        <a href="{{ route('admin.contact-messages.index') }}" 
           class="group flex items-center justify-between px-4 py-3 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('admin.contact-messages.*') ? 'bg-primary-50 text-primary-600 border-l-4 border-primary-600' : 'text-gray-700 hover:bg-gray-50 hover:translate-x-1' }}">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.contact-messages.*') ? 'text-primary-600' : 'text-gray-500 group-hover:text-primary-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <span>İletişim</span>
            </div>
            @php $unreadContactCount = \Illuminate\Support\Facades\Cache::remember('sidebar.unread_contacts', 30, fn() => \App\Models\ContactMessage::where('is_read', false)->count()); @endphp
            @if($unreadContactCount > 0)
                <span class="bg-primary-600 text-white text-xs font-bold px-2 py-1 rounded-full animate-pulse">{{ $unreadContactCount }}</span>
            @endif
        </a>

        <a href="{{ route('admin.evaluation-requests.index') }}" 
           class="group flex items-center justify-between px-4 py-3 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('admin.evaluation-requests.*') ? 'bg-primary-50 text-primary-600 border-l-4 border-primary-600' : 'text-gray-700 hover:bg-gray-50 hover:translate-x-1' }}">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.evaluation-requests.*') ? 'text-primary-600' : 'text-gray-500 group-hover:text-primary-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <span>Değerleme İstekleri</span>
            </div>
            @php $unreadEvalCount = \Illuminate\Support\Facades\Cache::remember('sidebar.unread_evals', 30, fn() => \App\Models\EvaluationRequest::where('is_read', false)->count()); @endphp
            @if($unreadEvalCount > 0)
                <span class="bg-primary-600 text-white text-xs font-bold px-2 py-1 rounded-full animate-pulse">{{ $unreadEvalCount }}</span>
            @endif
        </a>

        <!-- Müşteri Portföyü -->
        <div class="pt-6 pb-2">
            <p class="px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Müşteri Portföyü</p>
        </div>

        <a href="{{ route('admin.customers.index') }}" 
           class="group flex items-center justify-between px-4 py-3 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('admin.customers.*') ? 'bg-primary-50 text-primary-600 border-l-4 border-primary-600' : 'text-gray-700 hover:bg-gray-50 hover:translate-x-1' }}">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.customers.*') ? 'text-primary-600' : 'text-gray-500 group-hover:text-primary-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span>Müşteri Listesi</span>
            </div>
            @php $newCustomers = \Illuminate\Support\Facades\Cache::remember('sidebar.new_customers', 30, fn() => \App\Models\Customer::where('is_new', true)->count()); @endphp
            @if($newCustomers > 0)
                <span class="bg-primary-600 text-white text-xs font-bold px-2 py-1 rounded-full animate-pulse">{{ $newCustomers }}</span>
            @endif
        </a>

        <!-- Sistem -->
        <div class="pt-6 pb-2">
            <p class="px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Sistem</p>
        </div>

        <a href="{{ route('admin.sitemap.index') }}"
           class="group flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('admin.sitemap.*') ? 'bg-primary-50 text-primary-600 border-l-4 border-primary-600' : 'text-gray-700 hover:bg-gray-50 hover:translate-x-1' }}">
            <svg class="w-5 h-5 {{ request()->routeIs('admin.sitemap.*') ? 'text-primary-600' : 'text-gray-500 group-hover:text-primary-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
            </svg>
            <span>Sitemap</span>
        </a>

        <a href="{{ route('admin.settings.index') }}"
           class="group flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('admin.settings.*') ? 'bg-primary-50 text-primary-600 border-l-4 border-primary-600' : 'text-gray-700 hover:bg-gray-50 hover:translate-x-1' }}">
            <svg class="w-5 h-5 {{ request()->routeIs('admin.settings.*') ? 'text-primary-600' : 'text-gray-500 group-hover:text-primary-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <span>Ayarlar</span>
        </a>

        <a href="{{ route('admin.users.index') }}" 
           class="group flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-primary-50 text-primary-600 border-l-4 border-primary-600' : 'text-gray-700 hover:bg-gray-50 hover:translate-x-1' }}">
            <svg class="w-5 h-5 {{ request()->routeIs('admin.users.*') ? 'text-primary-600' : 'text-gray-500 group-hover:text-primary-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            <span>Kullanıcılar</span>
        </a>

        <a href="{{ route('admin.activity-logs.index') }}" 
           class="group flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('admin.activity-logs.*') ? 'bg-primary-50 text-primary-600 border-l-4 border-primary-600' : 'text-gray-700 hover:bg-gray-50 hover:translate-x-1' }}">
            <svg class="w-5 h-5 {{ request()->routeIs('admin.activity-logs.*') ? 'text-primary-600' : 'text-gray-500 group-hover:text-primary-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span>Aktivite Logları</span>
        </a>

        <!-- Siteyi Görüntüle -->
        <div class="pt-4">
            <a href="{{ route('home') }}" 
               target="_blank"
               class="group flex items-center space-x-3 px-4 py-3 rounded-xl font-medium text-gray-700 hover:bg-gradient-to-r hover:from-green-50 hover:to-green-100 hover:text-green-700 transition-all duration-200 border border-gray-200 hover:border-green-300">
                <svg class="w-5 h-5 text-gray-500 group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                </svg>
                <span>Siteyi Görüntüle</span>
            </a>
        </div>
    </nav>

</aside>

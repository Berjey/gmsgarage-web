@extends('layouts.app')

@php
    $siteTitle = $settings['site_title'] ?? 'GMSGARAGE';
    $pageTitle = isset($category) ? $category . ' Kategorisi - Blog - ' . $siteTitle : 'Blog - ' . $siteTitle;
    $pageDescription = isset($category) 
        ? "{$siteTitle} blog'da {$category} kategorisindeki makaleleri keşfedin. Araç bakımı, satın alma ipuçları ve sektör haberleri."
        : "{$siteTitle} blog - İkinci el araç satın alma rehberi, araç bakım ipuçları, sektör haberleri ve daha fazlası.";
@endphp

@section('title', $pageTitle)
@section('description', $pageDescription)
@section('keywords', 'blog, araç blog, ikinci el araç ipuçları, araç bakımı, oto galeri blog')
@section('og_type', 'website')
@section('og_url', route('blog.index', request()->query()))
@section('og_title', $pageTitle)
@section('og_description', $pageDescription)
@section('og_image', asset('images/light-mode-logo.png'))
@section('canonical', route('blog.index', request()->query()))

@push('meta')
<!-- Structured Data - Blog Listing -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Blog",
    "name": "{{ $siteTitle }} Blog",
    "description": "{{ $pageDescription }}",
    "url": "{{ route('blog.index') }}",
    "publisher": {
        "@type": "Organization",
        "name": "{{ $siteTitle }}",
        "logo": {
            "@type": "ImageObject",
            "url": "{{ asset('images/light-mode-logo.png') }}"
        }
    }
}
</script>
@endpush

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
    
    @keyframes iconPulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.15);
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
    
    .animate-fade-in {
        opacity: 0;
        animation: fadeIn 0.8s ease-out forwards;
    }
    
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
    }
    
    .animate-scale-in {
        animation: scaleIn 0.5s ease-out forwards;
    }
    
    .animate-slide-in-left {
        opacity: 0;
        animation: slideInLeft 0.6s ease-out forwards;
    }
    
    /* Blog Card Animations */
    .blog-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid transparent;
    }
    
    .blog-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 40px -10px rgba(220, 38, 38, 0.3), 0 10px 20px -5px rgba(0, 0, 0, 0.1);
        border-color: #dc2626;
    }
    
    .dark .blog-card:hover {
        box-shadow: 0 20px 40px -10px rgba(220, 38, 38, 0.4), 0 10px 20px -5px rgba(0, 0, 0, 0.3);
        border-color: #f87171;
    }
    
    .blog-card-wrapper {
        opacity: 0;
        animation: fadeInUp 0.6s ease-out forwards;
    }
    
    /* Image Zoom Effect */
    .blog-card img {
        transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .blog-card:hover img {
        transform: scale(1.1);
    }
    
    /* Icon Animations */
    .blog-card:hover svg {
        animation: iconPulse 0.6s ease-in-out;
    }
    
    /* Featured Badge */
    .featured-badge {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.8;
        }
    }
    
    /* Category Badge Hover */
    .blog-card:hover .text-primary-600 {
        transform: scale(1.05);
        transition: transform 0.3s ease;
    }
    
    /* Sidebar Animations */
    .sidebar-item {
        opacity: 0;
        animation: slideInLeft 0.6s ease-out forwards;
    }
    
    .sidebar-item:nth-child(1) { animation-delay: 0.1s; }
    .sidebar-item:nth-child(2) { animation-delay: 0.2s; }
    .sidebar-item:nth-child(3) { animation-delay: 0.3s; }
    .sidebar-item:nth-child(4) { animation-delay: 0.4s; }
    .sidebar-item:nth-child(5) { animation-delay: 0.5s; }
    .sidebar-item:nth-child(6) { animation-delay: 0.6s; }
    
    /* Featured Post Hover */
    .featured-post-item {
        transition: all 0.3s ease;
    }
    
    .featured-post-item:hover {
        transform: translateX(5px);
    }
    
    .featured-post-item:hover img {
        transform: scale(1.1);
    }
    
    .featured-post-item img {
        transition: transform 0.3s ease;
    }
    
    /* Search Bar Focus Animation */
    .search-input:focus {
        transform: scale(1.02);
        transition: transform 0.3s ease;
    }
    
    /* Category Link Hover */
    .category-link {
        transition: all 0.3s ease;
        position: relative;
    }
    
    .category-link:hover {
        transform: translateX(5px);
    }
    
    .category-link::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 0;
        height: 2px;
        background: #dc2626;
        transition: width 0.3s ease;
    }
    
    .category-link:hover::before {
        width: 4px;
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-primary-600 via-primary-700 to-primary-800 dark:from-primary-800 dark:via-primary-900 dark:to-primary-950 text-white py-20">
    <div class="container-custom">
        <div class="text-center max-w-3xl mx-auto">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6" style="opacity: 0; animation: fadeIn 0.8s ease-out 0.1s forwards;">
                {{ $siteTitle }} Blog
            </h1>
            <p class="text-xl md:text-2xl text-primary-100 dark:text-primary-200 mb-8" style="opacity: 0; animation: fadeIn 0.8s ease-out 0.2s forwards;">
                Araç dünyasından son haberler, ipuçları ve rehberler
            </p>
            
            <!-- Search Bar -->
            <form method="GET" action="{{ route('blog.index') }}" class="max-w-2xl mx-auto" style="opacity: 0; animation: fadeInUp 0.6s ease-out 0.3s forwards;">
                <div class="flex gap-2">
                    <input 
                        type="text" 
                        name="arama" 
                        value="{{ request('arama') }}"
                        placeholder="Blog yazılarında ara..." 
                        class="search-input flex-1 px-6 py-4 rounded-xl text-gray-900 dark:text-gray-100 bg-white dark:bg-[#2a2a2a] border-2 border-transparent focus:border-primary-400 focus:outline-none transition-all"
                    >
                    <button type="submit" class="px-8 py-4 bg-white dark:bg-[#252525] text-primary-600 dark:text-primary-400 rounded-xl font-semibold hover:bg-primary-50 dark:hover:bg-[#2a2a2a] transition-all hover:scale-105">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Breadcrumbs -->
<section class="bg-white dark:bg-[#1e1e1e] border-b border-gray-200 dark:border-[#333333] py-4">
    <div class="container-custom">
        <nav class="flex items-center space-x-2 text-sm">
            <a href="{{ route('home') }}" class="text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Anasayfa</a>
            <span class="text-gray-400 dark:text-gray-600">/</span>
            <span class="text-gray-900 dark:text-gray-100 font-medium">Blog</span>
            @if(isset($category))
                <span class="text-gray-400 dark:text-gray-600">/</span>
                <span class="text-gray-900 dark:text-gray-100 font-medium">{{ $category }}</span>
            @endif
        </nav>
    </div>
</section>

<!-- Main Content -->
<section class="section-padding bg-white dark:bg-[#1e1e1e]">
    <div class="container-custom">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar -->
            <aside class="lg:col-span-1">
                <!-- Categories -->
                <div class="bg-white dark:bg-[#252525] rounded-2xl border border-gray-200 dark:border-[#333333] p-6 mb-6 sidebar-item">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Kategoriler</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('blog.index') }}" 
                               class="category-link block px-4 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-[#2a2a2a] hover:text-primary-600 dark:hover:text-primary-400 transition-all {{ !isset($category) && !request('kategori') ? 'bg-primary-50 dark:bg-[#2a2a2a] text-primary-600 dark:text-primary-400 font-semibold' : '' }}">
                                Tümü
                            </a>
                        </li>
                        @foreach($categories as $index => $cat)
                            <li class="sidebar-item" style="animation-delay: {{ ($index + 1) * 0.1 }}s;">
                                <a href="{{ route('blog.index', ['kategori' => $cat]) }}" 
                                   class="category-link block px-4 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-[#2a2a2a] hover:text-primary-600 dark:hover:text-primary-400 transition-all {{ (isset($category) && $category === $cat) || request('kategori') === $cat ? 'bg-primary-50 dark:bg-[#2a2a2a] text-primary-600 dark:text-primary-400 font-semibold' : '' }}">
                                    {{ $cat }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Featured Posts -->
                @if($featuredPosts->count() > 0)
                <div class="bg-white dark:bg-[#252525] rounded-2xl border border-gray-200 dark:border-[#333333] p-6 sidebar-item" style="animation-delay: 0.2s;">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Öne Çıkanlar</h3>
                    <div class="space-y-4">
                        @foreach($featuredPosts as $index => $featured)
                            <a href="{{ route('blog.show', $featured->slug) }}" class="featured-post-item block group" style="opacity: 0; animation: fadeInUp 0.5s ease-out {{ 0.3 + ($index * 0.1) }}s forwards;">
                                <div class="flex gap-3">
                                    @if($featured->featured_image)
                                        <img src="{{ $featured->featured_image }}" alt="{{ $featured->title }}" class="w-20 h-20 object-cover rounded-lg">
                                    @else
                                        <div class="w-20 h-20 bg-gradient-to-br from-primary-500 to-primary-700 rounded-lg flex items-center justify-center overflow-hidden">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors line-clamp-2">
                                            {{ $featured->title }}
                                        </h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ $featured->published_at ? $featured->published_at->format('d M Y') : $featured->created_at->format('d M Y') }}
                                        </p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </aside>

            <!-- Blog Posts -->
            <div class="lg:col-span-3">
                @if($posts->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($posts as $index => $post)
                            <article class="blog-card-wrapper blog-card bg-white dark:bg-[#252525] rounded-2xl border border-gray-200 dark:border-[#333333] overflow-hidden" style="animation-delay: {{ ($index % 6) * 0.1 }}s;">
                                <a href="{{ route('blog.show', $post->slug) }}" class="block">
                                    @if($post->featured_image)
                                        <div class="relative h-48 overflow-hidden">
                                            <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                                            @if($post->is_featured)
                                                <span class="absolute top-4 right-4 featured-badge text-white text-xs font-bold px-3 py-1 rounded-full">
                                                    Öne Çıkan
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <div class="relative h-48 bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center">
                                            <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                            </svg>
                                            @if($post->is_featured)
                                                <span class="absolute top-4 right-4 featured-badge text-white text-xs font-bold px-3 py-1 rounded-full">
                                                    Öne Çıkan
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                    
                                    <div class="p-6">
                                        <div class="flex items-center gap-3 mb-3">
                                            <span class="text-xs font-semibold text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/30 px-3 py-1 rounded-full">
                                                {{ $post->category }}
                                            </span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $post->reading_time }} dk okuma
                                            </span>
                                        </div>
                                        
                                        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-3 line-clamp-2 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                            {{ $post->title }}
                                        </h2>
                                        
                                        @if($post->excerpt)
                                            <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-3">
                                                {{ $post->excerpt }}
                                            </p>
                                        @endif
                                        
                                        <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                <span>{{ $post->author }}</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span>{{ $post->published_at ? $post->published_at->format('d M Y') : $post->created_at->format('d M Y') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </article>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-12">
                        {{ $posts->links('vendor.pagination.default') }}
                    </div>
                @else
                    <div class="text-center py-20">
                        <svg class="w-24 h-24 text-gray-400 dark:text-gray-600 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">Henüz blog yazısı bulunmuyor</h3>
                        <p class="text-gray-600 dark:text-gray-400">Yakında yeni içerikler eklenecek.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

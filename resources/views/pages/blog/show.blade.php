@extends('layouts.app')

@php $siteTitle = $settings['site_title'] ?? 'GMSGARAGE'; @endphp
@section('title', $post->title . ' - Blog - ' . $siteTitle)
@section('description', $post->meta_description)
@section('keywords', $post->meta_keywords ? implode(', ', $post->meta_keywords) : 'blog, araç blog')
@section('og_type', 'article')
@section('og_url', route('blog.show', $post->slug))
@section('og_title', $post->meta_title)
@section('og_description', $post->meta_description)
@section('og_image', $post->featured_image ?: asset('images/light-mode-logo.png'))
@section('canonical', route('blog.show', $post->slug))

@push('meta')
<!-- Structured Data - Article -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BlogPosting",
    "headline": "{{ $post->title }}",
    "description": "{{ $post->meta_description }}",
    "image": "{{ $post->featured_image ?: asset('images/light-mode-logo.png') }}",
    "datePublished": "{{ $post->published_at ? $post->published_at->toIso8601String() : $post->created_at->toIso8601String() }}",
    "dateModified": "{{ $post->updated_at->toIso8601String() }}",
    "author": {
        "@type": "Organization",
        "name": "{{ $post->author }}"
    },
    "publisher": {
        "@type": "Organization",
        "name": "{{ $siteTitle }}",
        "logo": {
            "@type": "ImageObject",
            "url": "{{ asset('images/light-mode-logo.png') }}"
        }
    },
    "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "{{ route('blog.show', $post->slug) }}"
    },
    "articleSection": "{{ $post->category }}",
    "keywords": "{{ $post->meta_keywords ? implode(', ', $post->meta_keywords) : '' }}"
}
</script>

<!-- Breadcrumb Structured Data -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {
            "@type": "ListItem",
            "position": 1,
            "name": "Anasayfa",
            "item": "{{ route('home') }}"
        },
        {
            "@type": "ListItem",
            "position": 2,
            "name": "Blog",
            "item": "{{ route('blog.index') }}"
        },
        {
            "@type": "ListItem",
            "position": 3,
            "name": "{{ $post->category }}",
            "item": "{{ route('blog.index', ['kategori' => $post->category]) }}"
        },
        {
            "@type": "ListItem",
            "position": 4,
            "name": "{{ $post->title }}",
            "item": "{{ route('blog.show', $post->slug) }}"
        }
    ]
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
    
    .animate-fade-in {
        opacity: 0;
        animation: fadeIn 0.8s ease-out forwards;
    }
    
    .animate-fade-in-up {
        opacity: 0;
        animation: fadeInUp 0.6s ease-out forwards;
    }
    
    .animate-scale-in {
        opacity: 0;
        animation: scaleIn 0.5s ease-out forwards;
    }
    
    .blog-content {
        color: #374151;
    }
    
    .dark .blog-content {
        color: #d4d4d8; /* gray-300 */
    }
    
    .blog-content h1,
    .blog-content h2,
    .blog-content h3,
    .blog-content h4,
    .blog-content h5,
    .blog-content h6 {
        color: #111827 !important;
    }
    
    .dark .blog-content h1,
    .dark .blog-content h2,
    .dark .blog-content h3,
    .dark .blog-content h4,
    .dark .blog-content h5,
    .dark .blog-content h6 {
        color: #f3f4f6 !important; /* gray-100 */
    }
    
    .blog-content p {
        color: #374151 !important;
        line-height: 1.75;
    }
    
    .dark .blog-content p {
        color: #d4d4d8 !important; /* gray-300 */
    }
    
    .blog-content a {
        color: #dc2626 !important;
    }
    
    .dark .blog-content a {
        color: #f87171 !important; /* primary-400 */
    }
    
    .blog-content a:hover {
        color: #b91c1c !important; /* primary-700 */
    }
    
    .dark .blog-content a:hover {
        color: #fca5a5 !important; /* primary-300 */
    }
    
    .blog-content img {
        border-radius: 0.75rem;
        box-shadow: 0 10px 15px -3px rgba(0,0,0,.1), 0 4px 6px -2px rgba(0,0,0,.05);
    }
    
    .blog-content ul,
    .blog-content ol {
        color: #374151 !important;
    }
    
    .dark .blog-content ul,
    .dark .blog-content ol {
        color: #d4d4d8 !important; /* gray-300 */
    }
    
    .blog-content li {
        color: #374151 !important;
    }
    
    .dark .blog-content li {
        color: #d4d4d8 !important; /* gray-300 */
    }
    
    .blog-content strong,
    .blog-content b {
        color: #111827 !important;
        font-weight: 600;
    }
    
    .dark .blog-content strong,
    .dark .blog-content b {
        color: #f3f4f6 !important; /* gray-100 */
    }
    
    .blog-content em,
    .blog-content i {
        color: #374151 !important;
    }
    
    .dark .blog-content em,
    .dark .blog-content i {
        color: #d4d4d8 !important; /* gray-300 */
    }
    
    .blog-content blockquote {
        border-left: 4px solid #ef4444;
        background-color: #f9fafb;
        padding: 1rem;
        border-radius: 0 0.5rem 0.5rem 0;
        font-style: italic;
        color: #374151 !important;
    }
    
    .dark .blog-content blockquote {
        color: #d4d4d8 !important; /* gray-300 */
    }
    
    .blog-content blockquote p {
        color: #374151 !important; /* gray-700 */
    }
    
    .dark .blog-content blockquote p {
        color: #d4d4d8 !important; /* gray-300 */
    }
    
    .blog-content code {
        background-color: #f3f4f6;
        color: #111827 !important;
        padding: 0.125rem 0.5rem;
        border-radius: 0.25rem;
    }
    
    .dark .blog-content code {
        color: #f3f4f6 !important; /* gray-100 */
    }
    
    .blog-content pre {
        background-color: #f3f4f6;
        color: #111827;
        padding: 1rem;
        border-radius: 0.5rem;
        overflow-x: auto;
    }

    .blog-content pre code {
        background-color: transparent;
        padding: 0;
    }

    .blog-content table {
        width: 100%;
        border-collapse: collapse;
    }

    .blog-content table th,
    .blog-content table td {
        border: 1px solid #d4d4d8;
        padding: 0.5rem 1rem;
        color: #111827;
    }

    .blog-content table th {
        background-color: #f3f4f6;
        font-weight: 600;
    }
    
    .related-post-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid transparent;
    }
    
    .related-post-card:hover {
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 10px 25px -5px rgba(220, 38, 38, 0.3);
        border-color: #dc2626;
    }
    
    .dark .related-post-card:hover {
        box-shadow: 0 10px 25px -5px rgba(220, 38, 38, 0.4);
        border-color: #f87171;
    }
    
    .related-post-card img {
        transition: transform 0.5s ease;
    }
    
    .related-post-card:hover img {
        transform: scale(1.1);
    }
    
    /* Share Buttons Animation */
    .share-button {
        transition: all 0.3s ease;
    }
    
    .share-button:hover {
        transform: translateY(-3px) scale(1.05);
    }
    
    /* Sidebar Animation */
    .sidebar-sticky {
        opacity: 0;
        animation: slideInLeft 0.6s ease-out 0.3s forwards;
    }
    
    .recent-post-item {
        transition: all 0.3s ease;
        opacity: 0;
    }
    
    .recent-post-item:nth-child(1) { animation: fadeInUp 0.5s ease-out 0.4s forwards; }
    .recent-post-item:nth-child(2) { animation: fadeInUp 0.5s ease-out 0.5s forwards; }
    .recent-post-item:nth-child(3) { animation: fadeInUp 0.5s ease-out 0.6s forwards; }
    .recent-post-item:nth-child(4) { animation: fadeInUp 0.5s ease-out 0.7s forwards; }
    .recent-post-item:nth-child(5) { animation: fadeInUp 0.5s ease-out 0.8s forwards; }
    
    .recent-post-item:hover {
        transform: translateX(5px);
    }
    
    .recent-post-item:hover img {
        transform: scale(1.1);
    }
    
    .recent-post-item img {
        transition: transform 0.3s ease;
    }
    
    /* Article Header Animation */
    .article-header {
        opacity: 0;
        animation: fadeInUp 0.6s ease-out 0.2s forwards;
    }
    
    /* Content Animation */
    .blog-content {
        opacity: 0;
        animation: fadeIn 0.8s ease-out 0.4s forwards;
    }
</style>
@endpush

@section('content')
<!-- Breadcrumbs -->
<section class="bg-white dark:bg-[#1e1e1e] border-b border-gray-200 dark:border-[#333333] py-4">
    <div class="container-custom">
        <nav class="flex items-center space-x-2 text-sm">
            <a href="{{ route('home') }}" class="text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Anasayfa</a>
            <span class="text-gray-400 dark:text-gray-600">/</span>
            <a href="{{ route('blog.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Blog</a>
            <span class="text-gray-400 dark:text-gray-600">/</span>
            <a href="{{ route('blog.index', ['kategori' => $post->category]) }}" class="text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">{{ $post->category }}</a>
            <span class="text-gray-400 dark:text-gray-600">/</span>
            <span class="text-gray-900 dark:text-gray-100 font-medium line-clamp-1">{{ $post->title }}</span>
        </nav>
    </div>
</section>

<!-- Hero Image -->
@if($post->featured_image)
<section class="relative h-96 overflow-hidden" style="opacity: 0; animation: fadeIn 0.8s ease-out 0.1s forwards;">
    <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
    @if($post->is_featured)
        <span class="absolute top-6 right-6 bg-gradient-to-r from-primary-600 to-primary-700 text-white text-sm font-bold px-4 py-2 rounded-full shadow-lg">
            Öne Çıkan
        </span>
    @endif
</section>
@endif

<!-- Main Content -->
<section class="section-padding bg-white dark:bg-[#1e1e1e]">
    <div class="container-custom">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Main Article -->
            <article class="lg:col-span-3">
                <!-- Article Header -->
                <header class="article-header mb-8">
                    <div class="flex items-center gap-3 mb-4">
                        <a href="{{ route('blog.index', ['kategori' => $post->category]) }}" 
                           class="text-sm font-semibold text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/30 px-4 py-2 rounded-full hover:bg-primary-100 dark:hover:bg-primary-900/50 transition-colors">
                            {{ $post->category }}
                        </a>
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $post->reading_time }} dk okuma
                        </span>
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $post->views }} görüntülenme
                        </span>
                    </div>
                    
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-gray-100 mb-6">
                        {{ $post->title }}
                    </h1>
                    
                    <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400 mb-6">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span>{{ $post->author }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <time datetime="{{ $post->published_at ? $post->published_at->toIso8601String() : $post->created_at->toIso8601String() }}">
                                {{ $post->published_at ? $post->published_at->format('d F Y') : $post->created_at->format('d F Y') }}
                            </time>
                        </div>
                    </div>
                </header>

                <!-- Article Content -->
                <div class="blog-content mb-12">
                    {!! $post->content !!}
                </div>

                <!-- Share Buttons -->
                <div class="border-t border-gray-200 dark:border-[#333333] pt-8 mb-12">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Paylaş</h3>
                    <div class="flex items-center gap-4">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.show', $post->slug)) }}" 
                           target="_blank"
                           class="share-button flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            <span>Facebook</span>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.show', $post->slug)) }}&text={{ urlencode($post->title) }}" 
                           target="_blank"
                           class="share-button flex items-center gap-2 px-4 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                            <span>Twitter</span>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('blog.show', $post->slug)) }}" 
                           target="_blank"
                           class="share-button flex items-center gap-2 px-4 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-800 transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                            <span>LinkedIn</span>
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($post->title . ' - ' . route('blog.show', $post->slug)) }}" 
                           target="_blank"
                           class="share-button flex items-center gap-2 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                            <span>WhatsApp</span>
                        </a>
                    </div>
                </div>

                <!-- Related Posts -->
                @if($relatedPosts->count() > 0)
                <div class="border-t border-gray-200 dark:border-[#333333] pt-8">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">İlgili Yazılar</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($relatedPosts as $related)
                            <a href="{{ route('blog.show', $related->slug) }}" class="related-post-card block bg-white dark:bg-[#252525] rounded-xl border border-gray-200 dark:border-[#333333] overflow-hidden">
                                @if($related->featured_image)
                                    <img src="{{ $related->featured_image }}" alt="{{ $related->title }}" class="w-full h-40 object-cover">
                                @else
                                    <div class="w-full h-40 bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="p-4">
                                    <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2 line-clamp-2 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                        {{ $related->title }}
                                    </h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $related->published_at ? $related->published_at->format('d M Y') : $related->created_at->format('d M Y') }}
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </article>

            <!-- Sidebar -->
            <aside class="lg:col-span-1">
                <!-- Recent Posts -->
                @if($recentPosts->count() > 0)
                <div class="sidebar-sticky bg-white dark:bg-[#252525] rounded-2xl border border-gray-200 dark:border-[#333333] p-6 mb-6 sticky top-24">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Son Yazılar</h3>
                    <div class="space-y-4">
                        @foreach($recentPosts as $recent)
                            <a href="{{ route('blog.show', $recent->slug) }}" class="recent-post-item block group">
                                <div class="flex gap-3">
                                    @if($recent->featured_image)
                                        <img src="{{ $recent->featured_image }}" alt="{{ $recent->title }}" class="w-20 h-20 object-cover rounded-lg">
                                    @else
                                        <div class="w-20 h-20 bg-gradient-to-br from-primary-500 to-primary-700 rounded-lg flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors line-clamp-2">
                                            {{ $recent->title }}
                                        </h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ $recent->published_at ? $recent->published_at->format('d M Y') : $recent->created_at->format('d M Y') }}
                                        </p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Back to Blog -->
                <a href="{{ route('blog.index') }}" 
                   class="block w-full text-center px-6 py-3 bg-primary-600 dark:bg-primary-700 text-white rounded-xl font-semibold hover:bg-primary-700 dark:hover:bg-primary-600 transition-colors">
                    Tüm Yazılar
                </a>
            </aside>
        </div>
    </div>
</section>
@endsection

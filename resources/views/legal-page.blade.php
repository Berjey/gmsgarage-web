@extends('layouts.app')

@section('title', $page->title . ' - ' . ($settings['site_title'] ?? 'GMSGARAGE'))
@section('description', $page->title . ' - ' . ($settings['site_title'] ?? 'GMSGARAGE') . ' yasal bilgilendirme sayfası.')

@section('content')
<div class="container mx-auto px-4 py-12 dark:bg-neutral-950 transition-colors duration-200 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-gray-600 dark:text-neutral-400 mb-6">
            <a href="{{ route('home') }}" class="hover:text-red-600 dark:hover:text-red-500 transition-colors">Anasayfa</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-gray-900 dark:text-white font-semibold">{{ $page->title }}</span>
        </nav>

        <!-- Page Header - KIRMIZI GRADIENT -->
        <div class="bg-gradient-to-r from-red-600 to-red-700 dark:from-red-600 dark:to-red-700 rounded-xl p-8 mb-8 text-white shadow-lg">
            <h1 class="text-3xl md:text-4xl font-bold mb-2 font-sans">{{ $page->title }}</h1>
            <p class="text-red-100 text-sm font-sans">Son güncelleme: {{ $page->updated_at->format('d.m.Y') }} | Versiyon: {{ $page->version }}</p>
        </div>

        <!-- Page Content - SİYAH TEMA -->
        <div class="bg-white dark:bg-neutral-900 rounded-xl shadow-sm border border-gray-200 dark:border-neutral-800 p-8 md:p-12 transition-colors duration-200">
            <div class="prose prose-lg dark:prose-invert max-w-none legal-content">
                {!! $page->content !!}
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-8 text-center">
            <a href="javascript:history.back()" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-200 dark:bg-neutral-700 text-gray-700 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-neutral-600 transition-colors font-semibold font-sans">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Geri Dön
            </a>
        </div>
    </div>
</div>

<style>
/* GMS GARAGE - SIYAH & KIRMIZI KURUMSAL TEMA */
.legal-content {
    font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

/* ===== LIGHT MODE ===== */

/* Başlıklar - Kurumsal Kırmızı Vurgu */
.legal-content h2 {
    font-size: 1.75rem;
    font-weight: 700;
    color: #dc2626;
    margin-top: 2.5rem;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 3px solid #dc2626;
    line-height: 1.3;
}

.legal-content h3 {
    font-size: 1.35rem;
    font-weight: 600;
    color: #991b1b;
    margin-top: 2rem;
    margin-bottom: 1rem;
    line-height: 1.4;
}

.legal-content h4 {
    font-size: 1.125rem;
    font-weight: 600;
    color: #4b5563;
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
}

/* Paragraflar - Nefes Alabilir Spacing */
.legal-content p {
    margin-bottom: 1.5rem;
    line-height: 1.9;
    color: #4b5563;
    font-size: 1rem;
}

/* Listeler */
.legal-content ul, .legal-content ol {
    margin-left: 2rem;
    margin-bottom: 1.75rem;
}

.legal-content li {
    margin-bottom: 0.875rem;
    line-height: 1.9;
    color: #4b5563;
}

/* Vurgular */
.legal-content strong {
    color: #111827;
    font-weight: 600;
}

.legal-content em {
    color: #6b7280;
}

/* Linkler */
.legal-content a {
    color: #dc2626;
    text-decoration: underline;
    transition: color 0.2s;
}

.legal-content a:hover {
    color: #991b1b;
}

/* Tablolar */
.legal-content table {
    border-color: #e5e7eb;
}

.legal-content thead {
    background-color: #f9fafb;
}

.legal-content th, .legal-content td {
    border-color: #e5e7eb;
}

/* ===== DARK MODE - SİYAH & KIRMIZI TEMA ===== */

/* Başlıklar - Beyaz ve Kırmızı */
.dark .legal-content h2 {
    color: #ffffff;
    border-bottom-color: #dc2626;
    text-shadow: 0 0 10px rgba(220, 38, 38, 0.3);
}

.dark .legal-content h3 {
    color: #fef2f2;
}

.dark .legal-content h4 {
    color: #f3f4f6;
}

/* Metin - Neutral Gri (MAVİ YOK!) */
.dark .legal-content p {
    color: #d4d4d8; /* neutral-300 */
}

.dark .legal-content li {
    color: #d4d4d8; /* neutral-300 */
}

.dark .legal-content strong {
    color: #fafafa; /* neutral-50 */
    font-weight: 600;
}

.dark .legal-content em {
    color: #a3a3a3; /* neutral-400 */
}

/* Linkler - Kırmızı */
.dark .legal-content a {
    color: #ef4444;
}

.dark .legal-content a:hover {
    color: #f87171;
}

/* Tablolar - Siyah Tema */
.dark .legal-content table {
    border-color: #404040; /* neutral-700 */
}

.dark .legal-content thead {
    background-color: #171717; /* neutral-900 */
}

.dark .legal-content th {
    color: #fafafa; /* neutral-50 */
    border-color: #404040;
}

.dark .legal-content td {
    border-color: #404040;
    color: #d4d4d8;
}

.dark .legal-content tbody tr {
    background-color: transparent;
}

.dark .legal-content tbody tr:nth-child(even) {
    background-color: #262626; /* neutral-800 */
}

/* UYARI KUTULARI - KIRMIZI TEMA */
.dark .legal-content div[style*="background-color: #fef2f2"] {
    background-color: rgba(127, 29, 29, 0.2) !important; /* Koyu Kırmızı Transparan */
    border: 2px solid #dc2626 !important;
    border-left: 4px solid #dc2626 !important;
    color: #fecaca !important; /* Açık Kırmızı */
}

.dark .legal-content div[style*="background-color: #fef2f2"] p,
.dark .legal-content div[style*="background-color: #fef2f2"] strong {
    color: #fecaca !important;
}

/* Info Kutuları - Nötr */
.dark .legal-content div[style*="background-color: #eff6ff"] {
    background-color: rgba(64, 64, 64, 0.3) !important; /* Koyu Gri */
    border: 2px solid #525252 !important;
    border-left: 4px solid #737373 !important;
    color: #d4d4d8 !important;
}

.dark .legal-content div[style*="background-color: #eff6ff"] p,
.dark .legal-content div[style*="background-color: #eff6ff"] strong {
    color: #d4d4d8 !important;
}

/* Warning Kutuları - Sarı */
.dark .legal-content div[style*="background-color: #fffbeb"] {
    background-color: rgba(120, 53, 15, 0.2) !important;
    border: 2px solid #f59e0b !important;
    border-left: 4px solid #f59e0b !important;
    color: #fcd34d !important;
}

.dark .legal-content div[style*="background-color: #fffbeb"] p,
.dark .legal-content div[style*="background-color: #fffbeb"] strong {
    color: #fcd34d !important;
}

/* Success Kutuları - Yeşil */
.dark .legal-content div[style*="background-color: #f0fdf4"] {
    background-color: rgba(20, 83, 45, 0.2) !important;
    border: 2px solid #22c55e !important;
    border-left: 4px solid #22c55e !important;
    color: #86efac !important;
}

.dark .legal-content div[style*="background-color: #f0fdf4"] p,
.dark .legal-content div[style*="background-color: #f0fdf4"] strong {
    color: #86efac !important;
}

/* Gri Bilgi Kutuları */
.dark .legal-content div[style*="background-color: #f3f4f6"] {
    background-color: #262626 !important; /* neutral-800 */
    color: #d4d4d8 !important;
}

.dark .legal-content div[style*="background-color: #f3f4f6"] p {
    color: #d4d4d8 !important;
}
</style>
@endsection

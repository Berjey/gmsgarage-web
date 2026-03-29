@extends('admin.layouts.app')

@section('title', 'Müşteri Yönetimi - Admin Panel')
@section('page-title', 'Müşteri Yönetimi')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-primary-600">Dashboard</a>
    <span>/</span>
    <span>Müşteri Listesi</span>
@endsection

@section('content')

<!-- İstatistik Kartları -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl p-5 border-2 border-primary-100 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Toplam Müşteri</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
            </div>
            <div class="w-12 h-12 bg-primary-50 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl p-5 border-2 border-gray-100 shadow-sm hover:border-green-300 transition-all group">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">KVKK Onaylı</p>
                <p class="text-3xl font-bold text-gray-900 group-hover:text-green-600 transition-colors">{{ $stats['kvkk'] }}</p>
            </div>
            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl p-5 border-2 border-gray-100 shadow-sm hover:border-blue-300 transition-all group">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Bugün Eklenen</p>
                <p class="text-3xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $stats['today'] }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl p-5 border-2 border-gray-100 shadow-sm hover:border-orange-300 transition-all group">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Bu Ay Eklenen</p>
                <p class="text-3xl font-bold text-gray-900 group-hover:text-orange-600 transition-colors">{{ $stats['thisMonth'] }}</p>
            </div>
            <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <!-- Başlık -->
    <div class="p-6 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    Müşteri Listesi (CRM)
                </h2>
                <p class="text-sm text-gray-600 mt-2">Toplam <span class="font-bold text-primary-600">{{ $stats['total'] }}</span> müşteri kayıtlı</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.customers.export', request()->all()) }}"
                   class="px-5 py-2.5 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors flex items-center gap-2 font-semibold shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Excel'e Aktar
                </a>
                <button type="button" onclick="openBulkEmailModal()"
                        class="px-5 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors flex items-center gap-2 font-semibold shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Toplu Mail Gönder
                </button>
                @if($stats['total'] > 0)
                <button type="button" onclick="confirmDeleteAll({{ $stats['total'] }})"
                        class="px-5 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors flex items-center gap-2 font-semibold shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Tümünü Sil
                </button>
                <form id="delete-all-form" action="{{ route('admin.customers.destroy-all') }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Arama & Filtre -->
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <form id="customers-filter-form" method="GET" action="{{ route('admin.customers.index') }}" class="flex flex-wrap gap-3 items-center">
            <div class="flex-1 min-w-[250px]">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="🔍 Ad, e-posta veya telefon ara..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            </div>
            <div class="adm-dd" data-adm-dd data-submit="customers-filter-form" style="width:210px;flex-shrink:0;">
                <input type="hidden" name="source" value="{{ request('source') }}">
                <button type="button" class="adm-dd-btn" data-adm-trigger>
                    <span data-adm-label>
                        {{ $sourceBadges[request('source')]['label'] ?? 'Tüm Kaynaklar' }}
                    </span>
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <ul class="adm-dd-list" data-adm-list>
                    <li data-value="" class="{{ !request('source') ? 'selected' : '' }}">Tüm Kaynaklar</li>
                    @foreach($availableSources as $src)
                    <li data-value="{{ $src }}" class="{{ request('source') === $src ? 'selected' : '' }}">
                        {{ $sourceBadges[$src]['label'] ?? $src }}
                    </li>
                    @endforeach
                </ul>
            </div>
            <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors font-medium">
                Filtrele
            </button>
            @if(request('search') || request('source'))
            <a href="{{ route('admin.customers.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                Temizle
            </a>
            @endif
        </form>
    </div>

    <!-- Tablo -->
    <div class="overflow-x-auto">
        @if($customers->count() > 0)
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left">
                        <input type="checkbox" id="select-all" class="form-checkbox h-4 w-4 text-primary-600 rounded border-gray-300" onclick="toggleSelectAll(this)">
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ad Soyad</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">İletişim</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kaynak</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">KVKK</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kayıt Tarihi</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">İşlemler</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($customers as $customer)
                @php
                    $badge = $sourceBadges[$customer->source] ?? ['label' => 'Bilinmiyor', 'class' => 'bg-gray-100 text-gray-800'];
                @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" name="customer_ids[]" value="{{ $customer->id }}" class="customer-checkbox form-checkbox h-4 w-4 text-primary-600 rounded border-gray-300">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                                <span class="text-sm font-bold text-primary-700">{{ strtoupper(substr($customer->name, 0, 1)) }}</span>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">{{ $customer->name }}</div>
                                @if($customer->notes)
                                <div class="text-xs text-gray-400 truncate max-w-[160px]" title="{{ $customer->notes }}">{{ $customer->notes }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm space-y-1">
                            <a href="mailto:{{ $customer->email }}" class="text-primary-600 hover:text-primary-700 flex items-center gap-1">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                {{ $customer->email }}
                            </a>
                            @if($customer->phone)
                            <a href="{{ $customer->whatsapp_link }}" target="_blank" class="text-green-600 hover:text-green-700 flex items-center gap-1">
                                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                                {{ $customer->phone }}
                            </a>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $badge['class'] }}">
                            {{ $badge['label'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($customer->kvkk_consent)
                            <span class="text-green-600 flex items-center gap-1">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-xs font-medium">Onaylı</span>
                            </span>
                        @else
                            <span class="text-red-500 flex items-center gap-1">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-xs font-medium">Yok</span>
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $customer->created_at->format('d.m.Y') }}
                        <div class="text-xs text-gray-400">{{ $customer->created_at->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="flex items-center justify-end gap-1.5">
                            <a href="{{ route('admin.customers.show', $customer->id) }}"
                               class="inline-flex items-center p-1.5 bg-gray-50 text-gray-500 rounded-lg hover:bg-gray-100 transition-colors" title="Detay">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <a href="{{ route('admin.customers.edit', $customer->id) }}"
                               class="inline-flex items-center p-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors" title="Düzenle">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" class="inline-block"
                                  onsubmit="return confirmDelete(this, '{{ addslashes($customer->name) }} müşterisini')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center p-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors" title="Sil">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        @if($customers->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/50 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-sm text-gray-500">
                <strong>{{ $customers->firstItem() }}-{{ $customers->lastItem() }}</strong> arası gösteriliyor
                (toplam <strong>{{ $customers->total() }}</strong> müşteri)
            </p>
            {{ $customers->links() }}
        </div>
        @endif
        @else
        <div class="p-12 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                @if(request('search') || request('source'))
                    Arama sonucu bulunamadı
                @else
                    Henüz müşteri yok
                @endif
            </h3>
            <p class="text-gray-500">
                @if(request('search') || request('source'))
                    Farklı bir filtre deneyin veya <a href="{{ route('admin.customers.index') }}" class="text-primary-600 hover:underline">tüm müşterileri</a> görüntüleyin.
                @else
                    Web sitesinden form dolduran kullanıcılar burada görünecek.
                @endif
            </p>
        </div>
        @endif
    </div>
</div>

<!-- Toplu E-posta Modal -->
<div id="bulk-email-modal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-[9999] opacity-0 invisible transition-all duration-300">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full m-4 relative transform scale-95 opacity-0 transition-all duration-300">
        <button type="button" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors z-10" onclick="closeBulkEmailModal()">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <div class="p-6">
            <h3 class="text-2xl font-bold text-gray-900 mb-1">Toplu E-posta Gönder</h3>
            <p class="text-gray-500 text-sm mb-6">Seçili müşterilere e-posta gönderin.</p>

            <form action="{{ route('admin.customers.send-bulk-email') }}" method="POST" id="bulk-email-form">
                @csrf
                <div id="selected-customers-container"></div>

                <div class="space-y-4">
                    <div>
                        <label for="email-subject" class="block text-sm font-semibold text-gray-700 mb-2">E-posta Konusu <span class="text-red-500">*</span></label>
                        <input type="text" name="subject" id="email-subject" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                               placeholder="Örn: Özel Kampanya Duyurusu">
                    </div>
                    <div>
                        <label for="email-message" class="block text-sm font-semibold text-gray-700 mb-2">Mesaj <span class="text-red-500">*</span></label>
                        <textarea name="message" id="email-message" rows="8" required
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                  placeholder="E-posta içeriğinizi buraya yazın..."></textarea>
                        <p class="text-xs text-gray-400 mt-1">Not: HTML etiketleri desteklenmez, düz metin olarak gönderilir.</p>
                    </div>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm text-blue-800">
                            <strong id="selected-count">0</strong> müşteri seçildi.
                        </p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeBulkEmailModal()"
                            class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-semibold">
                        İptal
                    </button>
                    <button type="submit"
                            class="px-6 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors font-semibold flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        E-posta Gönder
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDeleteAll(totalCount) {
    Swal.fire({
        title: 'Tümünü Silmek İstediğinize Emin Misiniz?',
        html: `<p class="text-gray-600 mb-3">Toplam <strong class="text-red-600">${totalCount} müşteri kaydı</strong> kalıcı olarak silinecek.</p>
               <p class="text-sm text-red-500 font-semibold">Bu işlem geri alınamaz!</p>
               <p class="text-sm text-gray-500 mt-3">Onaylamak için aşağıya <strong>SİL</strong> yazın:</p>`,
        input: 'text',
        inputPlaceholder: 'SİL',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fas fa-trash-alt mr-2"></i>Evet, Tümünü Sil',
        cancelButtonText: '<i class="fas fa-times mr-2"></i>İptal',
        reverseButtons: true,
        customClass: {
            popup: 'rounded-xl shadow-2xl',
            title: 'text-xl font-bold text-gray-900',
            confirmButton: 'px-6 py-3 rounded-lg font-bold shadow-lg',
            cancelButton: 'px-6 py-3 rounded-lg font-bold shadow-sm',
        },
        focusCancel: true,
        showClass: { popup: 'animate__animated animate__fadeInDown animate__faster' },
        hideClass: { popup: 'animate__animated animate__fadeOutUp animate__faster' },
        preConfirm: (value) => {
            if (value.trim().toUpperCase() !== 'SİL') {
                Swal.showValidationMessage('Lütfen tam olarak <strong>SİL</strong> yazın.');
                return false;
            }
            return true;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Siliniyor...',
                html: 'Tüm kayıtlar siliniyor, lütfen bekleyin.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => Swal.showLoading()
            });
            document.getElementById('delete-all-form').submit();
        }
    });
}

function toggleSelectAll(checkbox) {
    document.querySelectorAll('.customer-checkbox').forEach(cb => cb.checked = checkbox.checked);
}

function openBulkEmailModal() {
    const selectedCheckboxes = document.querySelectorAll('.customer-checkbox:checked');

    if (selectedCheckboxes.length === 0) {
        showWarning('Müşteri Seçilmedi', 'Lütfen e-posta göndermek istediğiniz en az bir müşteri seçin.');
        return;
    }

    document.getElementById('selected-count').textContent = selectedCheckboxes.length;

    const container = document.getElementById('selected-customers-container');
    container.innerHTML = '';
    selectedCheckboxes.forEach(checkbox => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'customer_ids[]';
        input.value = checkbox.value;
        container.appendChild(input);
    });

    const modal = document.getElementById('bulk-email-modal');
    modal.classList.remove('invisible', 'opacity-0');
    modal.querySelector('div').classList.remove('scale-95', 'opacity-0');
    document.body.style.overflow = 'hidden';
}

function closeBulkEmailModal() {
    const modal = document.getElementById('bulk-email-modal');
    modal.classList.add('invisible', 'opacity-0');
    modal.querySelector('div').classList.add('scale-95', 'opacity-0');
    document.body.style.overflow = '';
    document.getElementById('bulk-email-form').reset();
    document.getElementById('selected-customers-container').innerHTML = '';
}

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !document.getElementById('bulk-email-modal').classList.contains('invisible')) {
        closeBulkEmailModal();
    }
});

document.getElementById('bulk-email-modal')?.addEventListener('click', (e) => {
    if (e.target.id === 'bulk-email-modal') closeBulkEmailModal();
});
</script>
@endpush
@endsection

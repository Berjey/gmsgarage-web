@extends('admin.layouts.app')

@section('title', $user->name . ' - Aktiviteleri')
@section('page-title', $user->name . ' - Aktivite Geçmişi')
@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-primary-600">Dashboard</a>
    <span>/</span>
    <a href="{{ route('admin.activity-logs.index') }}" class="hover:text-primary-600">Aktivite Logları</a>
    <span>/</span>
    <span>{{ $user->name }}</span>
@endsection

@section('content')
<div class="space-y-6">
    
    <!-- Kullanıcı Bilgisi -->
    <div class="bg-gradient-to-br from-{{ $user->role_badge_color }}-50 to-white rounded-xl border border-{{ $user->role_badge_color }}-200 shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-{{ $user->role_badge_color }}-100 rounded-full flex items-center justify-center">
                    <span class="text-{{ $user->role_badge_color }}-600 font-bold text-2xl">
                        {{ substr($user->name, 0, 1) }}
                    </span>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-gray-600 mt-1">{{ $user->email }}</p>
                    <div class="flex items-center space-x-3 mt-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-{{ $user->role_badge_color }}-100 text-{{ $user->role_badge_color }}-700">
                            {{ $user->role_name }}
                        </span>
                        @if($user->last_login_at)
                            <span class="text-xs text-gray-500">
                                Son giriş: {{ \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex space-x-3">
                <button type="button" onclick="clearUserLogs({{ $user->id }}, '{{ $user->name }}')" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Logları Temizle
                </button>
                <a href="{{ route('admin.activity-logs.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    ← Tüm Aktiviteler
                </a>
            </div>
        </div>
    </div>

    <!-- İstatistikler -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Toplam Aktivite</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $activities->total() }}</p>
                </div>
                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">📊</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Giriş Sayısı</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats->get('login', 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">🔐</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Oluşturma</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats->get('created', 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">➕</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Silme</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats->get('deleted', 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">🗑️</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Aktivite Timeline -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-900">Aktivite Zaman Çizelgesi</h2>
            <p class="text-sm text-gray-500 mt-1">Tüm işlemler kronolojik sırayla</p>
        </div>

        @if($activities->count() > 0)
            <div class="p-6">
                <div class="flow-root">
                    <ul class="-mb-8">
                        @foreach($activities as $index => $activity)
                            <li>
                                <div class="relative pb-8">
                                    @if(!$loop->last)
                                        <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex items-start space-x-3">
                                        <div class="relative">
                                            <div class="h-10 w-10 rounded-full bg-{{ $activity->color }}-100 flex items-center justify-center ring-8 ring-white">
                                                <span class="text-xl">{{ $activity->icon }}</span>
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div>
                                                <div class="text-sm">
                                                    <span class="font-medium text-gray-900">{{ $activity->description }}</span>
                                                </div>
                                                <p class="mt-1 text-xs text-gray-500">
                                                    {{ $activity->created_at->format('d.m.Y H:i:s') }} 
                                                    <span class="mx-1">•</span>
                                                    {{ $activity->created_at->diffForHumans() }}
                                                    @if($activity->ip_address)
                                                        <span class="mx-1">•</span>
                                                        IP: {{ $activity->ip_address }}
                                                    @endif
                                                </p>
                                                @if($activity->model_type)
                                                    <p class="mt-1 text-xs text-gray-400">
                                                        {{ class_basename($activity->model_type) }} #{{ $activity->model_id }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $activities->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aktivite bulunamadı</h3>
                <p class="mt-1 text-sm text-gray-500">Bu kullanıcıya ait henüz bir aktivite kaydı yok.</p>
            </div>
        @endif
    </div>
</div>

<!-- Hidden Form for Clear User Logs -->
<form id="clear-user-form" method="POST" action="{{ route('admin.activity-logs.clear-user', $user->id) }}" style="display: none;">
    @csrf
</form>
@endsection

@push('scripts')
<script>
function clearUserLogs(userId, userName) {
    Swal.fire({
        title: 'Kullanıcı Loglarını Temizle?',
        html: '<strong>' + userName + '</strong> kullanıcısının tüm aktivite logları silinecek!<br>Bu işlem geri alınamaz.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Evet, Temizle!',
        cancelButtonText: 'İptal',
        customClass: {
            popup: 'rounded-xl',
            confirmButton: 'rounded-lg px-6 py-2.5 font-semibold',
            cancelButton: 'rounded-lg px-6 py-2.5 font-semibold'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('clear-user-form').submit();
        }
    });
}
</script>
@endpush

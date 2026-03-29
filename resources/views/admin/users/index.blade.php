@extends('admin.layouts.app')

@section('title', 'Kullanıcı Yönetimi - Admin Panel')
@section('page-title', 'Kullanıcı Yönetimi')
@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-primary-600">Dashboard</a>
    <span>/</span>
    <span>Kullanıcılar</span>
@endsection

@section('content')
@php
    $totalUsers   = \App\Models\User::count();
    $adminUsers   = \App\Models\User::where('role', 'admin')->count();
    $managerUsers = \App\Models\User::where('role', 'manager')->count();
    $editorUsers  = \App\Models\User::where('role', 'editor')->count();
@endphp

<!-- İstatistik Kartları -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl p-5 border-2 border-primary-100 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Toplam Kullanıcı</p>
                <p class="text-3xl font-bold text-gray-900">{{ $totalUsers }}</p>
            </div>
            <div class="w-12 h-12 bg-primary-50 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl p-5 border-2 border-gray-100 shadow-sm hover:border-red-300 transition-all group">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Admin</p>
                <p class="text-3xl font-bold text-gray-900 group-hover:text-red-600 transition-colors">{{ $adminUsers }}</p>
            </div>
            <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl p-5 border-2 border-gray-100 shadow-sm hover:border-blue-300 transition-all group">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Yönetici</p>
                <p class="text-3xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $managerUsers }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl p-5 border-2 border-gray-100 shadow-sm hover:border-green-300 transition-all group">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Editör</p>
                <p class="text-3xl font-bold text-gray-900 group-hover:text-green-600 transition-colors">{{ $editorUsers }}</p>
            </div>
            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    Kullanıcılar
                </h2>
                <p class="text-sm text-gray-600 mt-2">Toplam <span class="font-bold text-primary-600">{{ $totalUsers }}</span> kullanıcı</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-semibold transition-colors flex items-center gap-2 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Yeni Kullanıcı
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ad Soyad</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">E-posta</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Rol</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kayıt Tarihi</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">İşlemler</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($users as $user)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-primary-100 text-primary-700 rounded-full flex items-center justify-center font-bold">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="text-sm font-bold text-gray-900">{{ $user->name }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $user->email }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $badgeColors = [
                                'admin' => 'bg-red-100 text-red-800',
                                'manager' => 'bg-blue-100 text-blue-800',
                                'editor' => 'bg-green-100 text-green-800',
                            ];
                            $badgeColor = $badgeColors[$user->role] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $badgeColor }}">
                            {{ $user->role_name }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $user->created_at->format('d.m.Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.activity-logs.user', $user->id) }}" 
                               class="p-1.5 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-600 hover:text-white transition-all shadow-sm" 
                               title="Aktiviteleri Gör">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </a>
                            <a href="{{ route('admin.users.edit', $user->id) }}" 
                               class="p-1.5 text-amber-600 bg-amber-50 rounded-lg hover:bg-amber-600 hover:text-white transition-all shadow-sm" 
                               title="Düzenle">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            @php
                                $isCurrentUser = auth()->id() == $user->id;
                                $isLastAdmin = $user->role === 'admin' && \App\Models\User::where('role', 'admin')->count() === 1;
                                $cannotDelete = $isCurrentUser || $isLastAdmin;
                                $deleteTitle = $isCurrentUser ? 'Kendinizi silemezsiniz' : ($isLastAdmin ? 'Son Süper Yönetici silinemez' : 'Sil');
                            @endphp
                            <form action="{{ route('admin.users.destroy', $user->id) }}" 
                                  method="POST" 
                                  class="inline-block delete-user-form" 
                                  data-user-name="{{ $user->name }}"
                                  data-user-role="{{ $user->role_name }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" 
                                        onclick="confirmUserDelete(this)" 
                                        class="p-1.5 text-red-600 bg-red-50 rounded-lg hover:bg-red-600 hover:text-white transition-all shadow-sm {{ $cannotDelete ? 'opacity-50 cursor-not-allowed' : '' }}" 
                                        {{ $cannotDelete ? 'disabled' : '' }} 
                                        title="{{ $deleteTitle }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if(count($users) == 0)
    <div class="p-12 text-center">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
        </svg>
        <p class="text-gray-500">Henüz kullanıcı yok.</p>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function confirmUserDelete(button) {
    // Disabled butonlara tıklamayı engelle
    if (button.disabled) {
        return false;
    }
    
    const form = button.closest('form');
    const userName = form.getAttribute('data-user-name');
    const userRole = form.getAttribute('data-user-role');
    
    Swal.fire({
        title: 'Kullanıcıyı silmek istediğinize emin misiniz?',
        html: `
            <div class="text-left space-y-3 mt-4">
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <p class="text-sm text-gray-600 mb-1">Kullanıcı:</p>
                    <p class="text-lg font-bold text-gray-900">${userName}</p>
                    <p class="text-xs text-gray-500 mt-1">Rol: ${userRole}</p>
                </div>
                <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                    <p class="text-sm font-semibold text-red-700 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        DİKKAT: Bu işlem geri alınamaz!
                    </p>
                    <p class="text-xs text-gray-600 mt-1">
                        Kullanıcının tüm verileri ve aktivite geçmişi kalıcı olarak silinecektir.
                    </p>
                </div>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sil',
        cancelButtonText: 'Vazgeç',
        reverseButtons: true,
        customClass: {
            popup: 'rounded-2xl shadow-2xl',
            title: 'text-xl font-bold',
            htmlContainer: 'text-sm',
            confirmButton: 'rounded-xl px-8 py-3 font-bold shadow-lg hover:shadow-xl transition-all',
            cancelButton: 'rounded-xl px-8 py-3 font-semibold transition-all'
        },
        focusCancel: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Silme işlemi animasyonu
            Swal.fire({
                title: 'Siliniyor...',
                html: 'Lütfen bekleyin',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            form.submit();
        }
    });
}
</script>
@endpush

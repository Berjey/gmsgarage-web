<!DOCTYPE html>
<html lang="tr" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/light-mode-logo.png') }}">
    <title>@yield('title', 'Admin Panel - GMSGARAGE')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Animate.css for SweetAlert animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    
    <style>
        /* Admin Panel Ferah Layout */
        /* xl: 1400px */
        @media (min-width: 1366px) {
            .admin-content-wrapper {
                max-width: 1400px;
                margin: 0 auto;
                padding-left: 2rem;
                padding-right: 2rem;
            }
        }
        
        /* 2xl: 1600px (2K/4K için) */
        @media (min-width: 1536px) {
            .admin-content-wrapper {
                max-width: 1600px;
            }
        }
        
        /* 1366px altında normal akış - minimum padding koru */
        @media (max-width: 1365px) {
            .admin-content-wrapper {
                max-width: 100%;
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }
        
        /* Mobilde padding azalt */
        @media (max-width: 640px) {
            .admin-content-wrapper {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }
        }
        
        /* Tablo satır yüksekliği - sadece admin-table class'ı olan tablolar için */
        .admin-table thead th {
            padding-top: 1.25rem;
            padding-bottom: 1.25rem;
        }
        
        .admin-table tbody td {
            padding-top: 1.25rem;
            padding-bottom: 1.25rem;
        }
        
        /* Form boşlukları */
        .admin-form-group {
            margin-bottom: 1.5rem;
        }
        
        .admin-form-label {
            margin-bottom: 0.75rem;
            display: block;
        }
        
        /* Grid gap artırma */
        .admin-grid {
            gap: 1.5rem;
        }
        
        @media (min-width: 1024px) {
            .admin-grid {
                gap: 2rem;
            }
        }
        
        /* Section margin */
        .admin-section {
            margin-bottom: 2rem;
        }
        
        @media (min-width: 1024px) {
            .admin-section {
                margin-bottom: 2.5rem;
            }
        }
        
        /* Kartlar arası boşluk */
        .admin-card-spacing {
            margin-bottom: 1.5rem;
        }
        
        @media (min-width: 1024px) {
            .admin-card-spacing {
                margin-bottom: 2rem;
            }
        }
    </style>
    
    <!-- adm-dd: Minimal Admin Dropdown -->
    <style>
        .adm-dd { position: relative; width: 100%; }
        .adm-dd-btn {
            display: flex; align-items: center; justify-content: space-between;
            width: 100%; height: 42px;
            padding: 0 0.875rem;
            background: #fff;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.875rem; font-weight: 500; color: #111827;
            cursor: pointer;
            transition: border-color 0.15s, box-shadow 0.15s;
            text-align: left;
        }
        .adm-dd-btn:hover,
        .adm-dd-btn.open { border-color: #dc2626; box-shadow: 0 0 0 3px rgba(220,38,38,0.1); }
        .adm-dd-btn svg  { flex-shrink: 0; color: #9ca3af; transition: transform 0.15s; }
        .adm-dd-btn.open svg { transform: rotate(180deg); }
        .adm-dd-list {
            display: none;
            position: absolute; top: calc(100% + 4px); left: 0; right: 0;
            background: #fff;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            z-index: 200; overflow: hidden;
        }
        .adm-dd-list.open { display: block; }
        .adm-dd-list li {
            padding: 0.6rem 0.875rem;
            font-size: 0.875rem; font-weight: 500; color: #374151;
            cursor: pointer; list-style: none;
            transition: background 0.1s, color 0.1s;
        }
        .adm-dd-list li:hover    { background: rgba(220,38,38,0.07); color: #dc2626; }
        .adm-dd-list li.selected { background: rgba(220,38,38,0.1);  color: #dc2626; font-weight: 600; }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-50 admin-body">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        @include('admin.layouts.sidebar')
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            @include('admin.layouts.navbar')
            
            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6 lg:p-8">
                <div class="admin-content-wrapper">
                    @if(session('success'))
                        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    
    @stack('scripts')
    
    <!-- Global adm-dd handler -->
    <script>
    (function () {
        function initAdmDd(root) {
            (root || document).querySelectorAll('[data-adm-dd]:not([data-adm-ready])').forEach(function (dd) {
                dd.setAttribute('data-adm-ready', '1');
                var btn   = dd.querySelector('[data-adm-trigger]');
                var list  = dd.querySelector('[data-adm-list]');
                var input = dd.querySelector('input[type=hidden]');
                var label = dd.querySelector('[data-adm-label]');
                if (!btn || !list) return;

                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    var opening = !list.classList.contains('open');
                    // close all others
                    document.querySelectorAll('[data-adm-list].open').forEach(function (l) {
                        if (l !== list) { l.classList.remove('open'); l.closest('[data-adm-dd]').querySelector('[data-adm-trigger]').classList.remove('open'); }
                    });
                    list.classList.toggle('open', opening);
                    btn.classList.toggle('open', opening);
                });

                list.querySelectorAll('li[data-value]').forEach(function (li) {
                    li.addEventListener('click', function () {
                        // data-href: navigate directly (per_page selectors)
                        if (li.dataset.href) { window.location.href = li.dataset.href; return; }
                        if (input)  input.value = li.dataset.value;
                        if (label)  { label.textContent = li.textContent.trim(); label.style.color = li.dataset.value ? '#111827' : ''; }
                        list.querySelectorAll('li').forEach(function (l) { l.classList.remove('selected'); });
                        li.classList.add('selected');
                        list.classList.remove('open'); btn.classList.remove('open');
                        // auto-submit if data-submit attribute set
                        var formId = dd.getAttribute('data-submit');
                        if (formId) { var f = document.getElementById(formId); if (f) f.submit(); }
                    });
                });
            });
        }

        document.addEventListener('DOMContentLoaded', function () { initAdmDd(); });
        document.addEventListener('click', function () {
            document.querySelectorAll('[data-adm-list].open').forEach(function (l) {
                l.classList.remove('open');
                var t = l.closest('[data-adm-dd]');
                if (t) { var b = t.querySelector('[data-adm-trigger]'); if (b) b.classList.remove('open'); }
            });
        });
        // expose for dynamic additions (e.g. addNewCategory)
        window.initAdmDd = initAdmDd;
    })();
    </script>

    <!-- Admin Confirm Modal Script -->
    @vite('resources/js/admin-confirm.js')

    <!-- Send Email Modal Script (global, reused across pages) -->
    <script>
    function openSendEmailModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            // Focus konu alanına
            setTimeout(() => {
                const subjectInput = document.getElementById(modalId + '-subject');
                if (subjectInput) subjectInput.focus();
            }, 100);
        }
    }

    function closeSendEmailModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            // Alanları temizle (readonly alıcı hariç)
            const subjectInput = document.getElementById(modalId + '-subject');
            const messageInput = document.getElementById(modalId + '-message');
            if (messageInput) messageInput.value = '';
            // Subject'i sadece boşsa temizleme (default subject korunur)
            // Butonu sıfırla
            const btn = document.getElementById(modalId + '-submit-btn');
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg> E-posta Gönder';
            }
        }
    }

    function submitSendEmailModal(modalId, postUrl, recipientEmail) {
        const subject = document.getElementById(modalId + '-subject').value.trim();
        const message = document.getElementById(modalId + '-message').value.trim();
        const btn     = document.getElementById(modalId + '-submit-btn');

        if (!subject) {
            Swal.fire({ icon: 'warning', title: 'Eksik Alan', text: 'E-posta konusu zorunludur.', confirmButtonColor: '#e11d48' });
            return;
        }
        if (!message) {
            Swal.fire({ icon: 'warning', title: 'Eksik Alan', text: 'Mesaj alanı zorunludur.', confirmButtonColor: '#e11d48' });
            return;
        }

        // Loading state + double-click koruması
        btn.disabled = true;
        btn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Gönderiliyor...';

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(postUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept':       'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ subject, message }),
        })
        .then(res => res.json().then(data => ({ ok: res.ok, data })))
        .then(({ ok, data }) => {
            closeSendEmailModal(modalId);
            if (ok && data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Gönderildi!',
                    text: data.message,
                    confirmButtonColor: '#e11d48',
                    timer: 3000,
                    timerProgressBar: true,
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gönderilemedi',
                    text: data.message || 'Bir hata oluştu. Lütfen tekrar deneyin.',
                    confirmButtonColor: '#e11d48',
                });
            }
        })
        .catch(() => {
            closeSendEmailModal(modalId);
            Swal.fire({
                icon: 'error',
                title: 'Bağlantı Hatası',
                text: 'Sunucuya ulaşılamadı. Lütfen tekrar deneyin.',
                confirmButtonColor: '#e11d48',
            });
        });
    }
    </script>
</body>
</html>

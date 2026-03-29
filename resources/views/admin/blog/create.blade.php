@extends('admin.layouts.app')

@section('title', 'Yeni Blog Yazısı Ekle - Admin Panel')
@section('page-title', 'Yeni Blog Yazısı')

@push('styles')
<!-- Quill Editor CSS -->
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
    /* Quill Editor Özelleştirme */
    .ql-container {
        font-size: 16px;
        font-family: inherit;
        border-bottom-left-radius: 0.75rem;
        border-bottom-right-radius: 0.75rem;
        border-color: #e5e7eb;
    }

    .ql-toolbar {
        border-top-left-radius: 0.75rem;
        border-top-right-radius: 0.75rem;
        border-color: #e5e7eb;
        background: #f9fafb;
    }

    .ql-editor {
        min-height: 300px;
        max-height: 600px;
        overflow-y: auto;
    }

    .ql-editor.ql-blank::before {
        color: #9ca3af;
        font-style: normal;
    }

    .ql-snow .ql-picker {
        color: #374151;
    }

    .ql-snow .ql-stroke {
        stroke: #374151;
    }

    .ql-snow .ql-fill {
        fill: #374151;
    }

    .ql-snow.ql-toolbar button:hover,
    .ql-snow .ql-toolbar button:hover,
    .ql-snow.ql-toolbar button.ql-active,
    .ql-snow .ql-toolbar button.ql-active {
        color: #e11d48;
    }

    .ql-snow.ql-toolbar button:hover .ql-stroke,
    .ql-snow .ql-toolbar button:hover .ql-stroke,
    .ql-snow.ql-toolbar button.ql-active .ql-stroke,
    .ql-snow .ql-toolbar button.ql-active .ql-stroke {
        stroke: #e11d48;
    }

    .ql-snow.ql-toolbar button:hover .ql-fill,
    .ql-snow .ql-toolbar button:hover .ql-fill,
    .ql-snow.ql-toolbar button.ql-active .ql-fill,
    .ql-snow .ql-toolbar button.ql-active .ql-fill {
        fill: #e11d48;
    }

    .collapse-trigger.active .arrow {
        transform: rotate(180deg);
    }

    #new-category-input { border-top: 2px solid #e5e7eb; }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Yeni Blog Yazısı Ekle</h1>
            <p class="mt-1 text-sm text-gray-500 font-medium tracking-wide uppercase">YENİ İÇERİK OLUŞTUR</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.blog.index') }}" 
               class="inline-flex items-center px-5 py-2.5 bg-white text-gray-700 font-bold rounded-xl border border-gray-200 hover:bg-gray-50 transition-all shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Geri Dön
            </a>
        </div>
    </div>

    <!-- Validation Errors -->
    @if ($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-bold text-red-800">Lütfen aşağıdaki hataları düzeltin:</h3>
                <ul class="mt-2 text-sm text-red-700 list-disc list-inside space-y-1">
                    @foreach (array_unique($errors->all()) as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Temel Bilgiler -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-900">Temel Bilgiler</h2>
                <p class="text-sm text-gray-500 mt-0.5">Yazının temel bilgilerini girin</p>
            </div>
            <div class="p-6 space-y-6">
                <!-- Başlık ve Kategori -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Başlık <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="title" 
                               id="title-input"
                               value="{{ old('title') }}" 
                               required 
                               placeholder="Blog yazısının başlığını girin..."
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <!-- Slug Preview -->
                        <div class="mt-2 text-sm text-gray-500">
                            <span class="font-medium">URL Önizleme:</span>
                            <span class="text-primary-600" id="slug-preview">{{ config('app.url') }}/blog/</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <div class="adm-dd" data-adm-dd>
                            <input type="hidden" name="category" id="category-input" value="{{ old('category') }}" required>
                            <button type="button" class="adm-dd-btn" data-adm-trigger>
                                <span data-adm-label style="color:#9ca3af;">{{ old('category') ?: 'Kategori Seçin' }}</span>
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <ul class="adm-dd-list" data-adm-list style="max-height:260px;overflow-y:auto;">
                                @foreach($categories as $cat)
                                <li data-value="{{ $cat }}" class="{{ old('category') === $cat ? 'selected' : '' }}">{{ $cat }}</li>
                                @endforeach
                                <li id="new-category-input" style="padding:10px 12px;background:#f9fafb;cursor:default;" onclick="event.stopPropagation()">
                                    <div class="text-xs font-bold text-gray-500 mb-1.5">YENİ KATEGORİ EKLE</div>
                                    <div class="flex gap-2">
                                        <input type="text" id="new-category-field" placeholder="Kategori adı..."
                                               onclick="event.stopPropagation()"
                                               class="flex-1 px-2 py-1.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-red-400" style="font-weight:400;color:#111827;">
                                        <button type="button" onclick="addNewCategory()" style="padding:5px 12px;background:#dc2626;color:#fff;font-size:12px;font-weight:700;border-radius:6px;border:none;cursor:pointer;">Ekle</button>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Kısa Özet -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Kısa Özet <span class="text-red-500">*</span>
                    </label>
                    <textarea name="excerpt" 
                              rows="3" 
                              required 
                              placeholder="Yazınızın kısa bir özetini girin (160 karakter önerilir)..."
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all resize-none">{{ old('excerpt') }}</textarea>
                    @error('excerpt')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- İçerik -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        İçerik <span class="text-red-500">*</span>
                    </label>
                    <div id="quill-editor" class="bg-white"></div>
                    <script>window.__quillInitialContent = @json(old('content', ''));</script>
                    <input type="hidden" name="content" id="content-input">
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Görsel -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-900">Öne Çıkan Görsel</h2>
                <p class="text-sm text-gray-500 mt-0.5">Blog yazısının kapak görselini ekleyin</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Dosya Yükleme -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Dosya Yükle</label>
                        <div class="relative">
                            <input type="file" 
                                   name="featured_image" 
                                   id="image-upload"
                                   accept="image/*"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 transition-all border border-gray-200 rounded-xl">
                        </div>
                        <p class="mt-2 text-xs text-gray-500">PNG, JPG, GIF · Maks. 5MB</p>
                        <div class="mt-2 flex items-start gap-2 px-3 py-2 bg-amber-50 border border-amber-200 rounded-lg">
                            <svg class="w-3.5 h-3.5 text-amber-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-xs text-amber-700 leading-relaxed">
                                <span class="font-bold">Önerilen boyut:</span> 1200 × 630 piksel (16:9 oran)<br>
                                <span class="text-amber-600">Kare (1:1) görseller için: 1200 × 1200 piksel</span>
                            </p>
                        </div>
                    </div>

                    <!-- URL ile Görsel -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Veya Görsel URL'i</label>
                        <input type="url" 
                               name="featured_image_url" 
                               id="image-url"
                               placeholder="https://example.com/image.jpg"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                        <p class="mt-2 text-xs text-gray-500">Harici bir görsel URL'i girebilirsiniz</p>
                    </div>
                </div>

                <!-- Görsel Önizleme -->
                <div id="image-preview" class="hidden mt-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Önizleme</label>
                    <div class="relative inline-block">
                        <img id="preview-img" src="" alt="Önizleme" class="max-w-sm w-full h-auto rounded-xl border border-gray-200 shadow-sm">
                        <button type="button" 
                                onclick="clearImagePreview()"
                                class="absolute top-2 right-2 p-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- SEO & Meta Bilgiler (Collapsible) -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <button type="button" 
                    class="collapse-trigger w-full px-6 py-4 bg-gray-50/50 border-b border-gray-100 flex items-center justify-between hover:bg-gray-100/50 transition-colors"
                    onclick="toggleCollapse('seo-section')">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 text-left">SEO & Meta Bilgiler</h2>
                    <p class="text-sm text-gray-500 mt-0.5 text-left">Arama motoru optimizasyonu ayarları (İsteğe Bağlı)</p>
                </div>
                <svg class="arrow w-5 h-5 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div id="seo-section" class="hidden">
                <div class="p-6 space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Meta Başlık</label>
                        <input type="text" 
                               name="meta_title" 
                               value="{{ old('meta_title') }}"
                               placeholder="SEO için optimize edilmiş başlık (boş bırakılırsa otomatik oluşturulur)"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                        <p class="mt-1 text-xs text-gray-500">Optimal uzunluk: 50-60 karakter</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Meta Açıklama</label>
                        <textarea name="meta_description" 
                                  rows="3"
                                  placeholder="Arama motorlarında görünecek açıklama (boş bırakılırsa otomatik oluşturulur)"
                                  class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all resize-none">{{ old('meta_description') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Optimal uzunluk: 150-160 karakter</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Meta Anahtar Kelimeler</label>
                        <input type="text" 
                               name="meta_keywords_string" 
                               value="{{ old('meta_keywords_string') }}"
                               placeholder="anahtar kelime, blog, yazı (virgülle ayırın)"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                        <p class="mt-1 text-xs text-gray-500">Virgülle ayrılmış anahtar kelimeler</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Yayın Ayarları -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-900">Yayın Ayarları</h2>
                <p class="text-sm text-gray-500 mt-0.5">Yazının yayın durumunu ve tarihini ayarlayın</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Durum Checkboxları -->
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-3">Durum</label>
                        <div class="flex flex-wrap gap-4">
                            <label class="inline-flex items-center px-4 py-3 bg-gray-50 rounded-xl cursor-pointer hover:bg-gray-100 transition-colors">
                                <input type="checkbox" 
                                       name="is_published" 
                                       value="1" 
                                       checked 
                                       class="w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                                <span class="ml-3 text-sm font-bold text-gray-900">Yayında</span>
                            </label>
                            <label class="inline-flex items-center px-4 py-3 bg-gray-50 rounded-xl cursor-pointer hover:bg-gray-100 transition-colors">
                                <input type="checkbox" 
                                       name="is_featured" 
                                       value="1" 
                                       class="w-5 h-5 text-amber-500 border-gray-300 rounded focus:ring-amber-500">
                                <span class="ml-3 text-sm font-bold text-gray-900">Öne Çıkar</span>
                            </label>
                        </div>
                    </div>

                    <!-- Yayın Tarihi -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Yayın Tarihi</label>
                        <input type="datetime-local" 
                               name="published_at" 
                               value="{{ old('published_at') }}"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                        <p class="mt-1 text-xs text-gray-500">Boş bırakılırsa şimdi yayınlanır</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-4 pb-8">
            <a href="{{ route('admin.blog.index') }}"
               class="px-8 py-3 bg-white text-gray-700 font-bold rounded-xl border border-gray-200 hover:bg-gray-50 transition-all shadow-sm">
                İptal
            </a>
            <button type="submit"
                    onclick="document.querySelector('[name=is_published]').checked = false;"
                    class="px-8 py-3 bg-gray-700 text-white font-bold rounded-xl hover:bg-gray-800 transition-all shadow-sm">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                Taslak Kaydet
            </button>
            <button type="submit"
                    onclick="document.querySelector('[name=is_published]').checked = true;"
                    class="px-8 py-3 bg-primary-600 text-white font-bold rounded-xl hover:bg-primary-700 transition-all shadow-lg shadow-primary-500/25">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Yayınla
            </button>
        </div>
    </form>
</div>

@push('scripts')
<!-- Quill Editor JS -->
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Quill Editor Başlatma
        const quill = new Quill('#quill-editor', {
            theme: 'snow',
            placeholder: 'Blog yazınızı buraya yazın...',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    [{ 'font': [] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    [{ 'align': [] }],
                    ['blockquote', 'code-block'],
                    ['link', 'image', 'video'],
                    ['clean']
                ]
            }
        });

        const form = document.querySelector('form');
        const contentInput = document.getElementById('content-input');

        // Quill yüklendiğinde eski içeriği güvenli şekilde yükle
        if (window.__quillInitialContent) {
            quill.root.innerHTML = window.__quillInitialContent;
        }
        contentInput.value = quill.root.innerHTML;

        // Her değişiklikte içeriği güncelle
        quill.on('text-change', function() {
            contentInput.value = quill.root.innerHTML;
        });

        form.addEventListener('submit', function(e) {
            // Gönderim öncesi son kez senkronize et
            contentInput.value = quill.root.innerHTML;

            const formData = new FormData(form);

            // Eksik alanları kontrol et
            const missingFields = [];
            if (!formData.get('title')?.trim()) missingFields.push('Başlık');
            if (!formData.get('category')?.trim()) missingFields.push('Kategori');
            if (!formData.get('excerpt')?.trim()) missingFields.push('Kısa Özet');
            if (!formData.get('content')?.trim() || quill.getText().trim().length === 0) missingFields.push('İçerik');
            
            if (missingFields.length > 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Eksik Alanlar',
                    html: '<ul class="text-left text-sm mt-2 space-y-1">' + missingFields.map(f => `<li>• ${f}</li>`).join('') + '</ul>',
                    confirmButtonText: 'Tamam',
                    confirmButtonColor: '#e11d48',
                });
                return false;
            }
        });

    });

    function addNewCategory() {
        const input = document.getElementById('new-category-field');
        const newCategory = input.value.trim();
        if (!newCategory) { showWarning('Eksik Alan', 'Lütfen kategori adı girin.'); return; }

        const dd       = document.querySelector('[data-adm-dd]');
        const list     = dd.querySelector('[data-adm-list]');
        const hiddenInput = document.getElementById('category-input');
        const label    = dd.querySelector('[data-adm-label]');
        const btn      = dd.querySelector('[data-adm-trigger]');
        const newCatLi = document.getElementById('new-category-input');

        const li = document.createElement('li');
        li.dataset.value = newCategory;
        li.textContent = newCategory;
        li.className = 'selected';
        li.addEventListener('click', () => {
            hiddenInput.value = newCategory;
            label.textContent = newCategory;
            label.style.color = '#111827';
            list.querySelectorAll('li').forEach(l => l.classList.remove('selected'));
            li.classList.add('selected');
            list.classList.remove('open'); btn.classList.remove('open');
        });

        list.querySelectorAll('li').forEach(l => l.classList.remove('selected'));
        list.insertBefore(li, newCatLi);

        hiddenInput.value = newCategory;
        label.textContent = newCategory;
        label.style.color = '#111827';
        input.value = '';
        list.classList.remove('open'); btn.classList.remove('open');
    }

    // Allow Enter key to add category
    document.getElementById('new-category-field')?.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            addNewCategory();
        }
    });

    // Slug Preview
    const titleInput = document.getElementById('title-input');
    const slugPreview = document.getElementById('slug-preview');
    const baseUrl = '{{ config("app.url") }}/blog/';
    
    if (titleInput) {
        titleInput.addEventListener('input', function() {
            const slug = this.value
                .toLowerCase()
                .replace(/ğ/g, 'g')
                .replace(/ü/g, 'u')
                .replace(/ş/g, 's')
                .replace(/ı/g, 'i')
                .replace(/ö/g, 'o')
                .replace(/ç/g, 'c')
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
            
            slugPreview.textContent = baseUrl + (slug || 'ornek-baslik');
        });
    }

    // Image Preview
    const imageUpload = document.getElementById('image-upload');
    const imageUrl = document.getElementById('image-url');
    const imagePreview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');

    if (imageUpload) {
        imageUpload.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
                imageUrl.value = '';
            }
        });
    }

    if (imageUrl) {
        imageUrl.addEventListener('input', function() {
            if (this.value) {
                previewImg.src = this.value;
                imagePreview.classList.remove('hidden');
                imageUpload.value = '';
            }
        });
    }

    function clearImagePreview() {
        imagePreview.classList.add('hidden');
        previewImg.src = '';
        imageUpload.value = '';
        imageUrl.value = '';
    }

    // Collapsible Sections
    function toggleCollapse(sectionId) {
        const section = document.getElementById(sectionId);
        const trigger = event.currentTarget;
        
        if (section.classList.contains('hidden')) {
            section.classList.remove('hidden');
            trigger.classList.add('active');
        } else {
            section.classList.add('hidden');
            trigger.classList.remove('active');
        }
    }
</script>
@endpush
@endsection

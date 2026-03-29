/**
 * GMSGARAGE Admin Panel - SweetAlert2 Global Konfigürasyon
 * Tutarlı renk, ikon ve stil standardı
 */

// ── Ortak stil sabitleri ─────────────────────────────────────────────────────
const SWAL_COLORS = {
    confirm: '#dc2626',
    cancel:  '#6b7280',
    info:    '#2563eb',
};

const SWAL_CLASSES = {
    popup:         'rounded-xl shadow-2xl',
    title:         'text-xl font-bold text-gray-900',
    confirmButton: 'px-5 py-2.5 rounded-lg font-semibold',
    cancelButton:  'px-5 py-2.5 rounded-lg font-semibold',
};

// ── Tekli silme onayı ────────────────────────────────────────────────────────
window.confirmDelete = function(form, itemName = 'bu öğeyi') {
    Swal.fire({
        title: 'Silmek istediğinize emin misiniz?',
        html: `<p class="text-gray-600 text-sm">${itemName} kalıcı olarak silinecek.</p><p class="text-xs text-gray-400 mt-2">Bu işlem geri alınamaz.</p>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: SWAL_COLORS.confirm,
        cancelButtonColor: SWAL_COLORS.cancel,
        confirmButtonText: 'Sil',
        cancelButtonText: 'Vazgeç',
        reverseButtons: true,
        customClass: SWAL_CLASSES,
        focusCancel: true,
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Siliniyor...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => Swal.showLoading(),
            });
            form.submit();
        }
    });
    return false;
};

// ── Toplu silme onayı ────────────────────────────────────────────────────────
window.confirmBulkDelete = function(count) {
    return Swal.fire({
        title: 'Toplu Silme',
        html: `<p class="text-gray-600 text-sm"><strong>${count} öğe</strong> kalıcı olarak silinecek.</p><p class="text-xs text-gray-400 mt-2">Bu işlem geri alınamaz.</p>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: SWAL_COLORS.confirm,
        cancelButtonColor: SWAL_COLORS.cancel,
        confirmButtonText: `${count} Öğeyi Sil`,
        cancelButtonText: 'Vazgeç',
        reverseButtons: true,
        customClass: SWAL_CLASSES,
        focusCancel: true,
    });
};

// ── Başarı Toast ─────────────────────────────────────────────────────────────
window.showSuccess = function(message) {
    Swal.fire({
        icon: 'success',
        title: message,
        toast: true,
        position: 'top-end',
        timer: 3500,
        timerProgressBar: true,
        showConfirmButton: false,
        customClass: { popup: 'rounded-lg shadow-lg' },
    });
};

// ── Hata mesajı ──────────────────────────────────────────────────────────────
window.showError = function(message) {
    Swal.fire({
        icon: 'error',
        title: 'Hata',
        text: message,
        confirmButtonColor: SWAL_COLORS.confirm,
        confirmButtonText: 'Tamam',
        customClass: SWAL_CLASSES,
    });
};

// ── Uyarı mesajı ─────────────────────────────────────────────────────────────
window.showWarning = function(title, message) {
    Swal.fire({
        icon: 'warning',
        title: title,
        html: message ? `<p class="text-gray-600 text-sm">${message}</p>` : undefined,
        confirmButtonColor: SWAL_COLORS.confirm,
        confirmButtonText: 'Tamam',
        customClass: SWAL_CLASSES,
    });
};

// ── Bilgi mesajı ─────────────────────────────────────────────────────────────
window.showInfo = function(title, message) {
    Swal.fire({
        icon: 'info',
        title: title,
        html: message ? `<p class="text-gray-600 text-sm">${message}</p>` : undefined,
        confirmButtonColor: SWAL_COLORS.info,
        confirmButtonText: 'Tamam',
        customClass: SWAL_CLASSES,
    });
};

// ── Eksik alan uyarısı (form validation) ─────────────────────────────────────
window.showMissingFields = function(fields) {
    Swal.fire({
        icon: 'warning',
        title: 'Eksik Alanlar',
        html: '<ul class="text-left text-sm text-gray-600 mt-2 space-y-1">' + fields.map(f => `<li class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-red-500 rounded-full flex-shrink-0"></span>${f}</li>`).join('') + '</ul>',
        confirmButtonColor: SWAL_COLORS.confirm,
        confirmButtonText: 'Tamam',
        customClass: SWAL_CLASSES,
    });
};

// ── Loading spinner ──────────────────────────────────────────────────────────
window.showLoading = function(title = 'İşleniyor...') {
    Swal.fire({
        title: title,
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => Swal.showLoading(),
    });
};

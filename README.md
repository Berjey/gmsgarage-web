# GMS Garage - Otogaleri Web Sitesi

Premium ikinci el araç alım-satım platformu. Laravel tabanlı tam kapsamlı otogaleri yönetim sistemi.

**Canlı Site:** [www.gmsgarage.com](https://www.gmsgarage.com)

---

## Teknolojiler

- **Backend:** Laravel 10, PHP 8.2
- **Frontend:** Tailwind CSS, Alpine.js, Vite
- **Veritabanı:** SQLite (local) / MySQL (production)
- **Hosting:** Hostinger Shared Hosting

---

## Özellikler

### Web Sitesi
- Araç listeleme ve detay sayfaları
- Gelişmiş araç arama ve filtreleme
- Araç değerleme formu (arabam.com API entegrasyonu)
- Blog sistemi (kategoriler, makaleler)
- Yasal sayfalar (KVKK, Kullanım Şartları, Çerez Politikası)
- İletişim formu
- Dark mode desteği
- SEO optimizasyonu (sitemap, meta tags, canonical)
- Özel 404 sayfası

### Admin Paneli
- Araç yönetimi (ekle, düzenle, sil, aktif/pasif)
- Blog yazısı yönetimi
- Müşteri yönetimi
- İletişim mesajları
- Araç değerleme talepleri
- Yasal sayfa yönetimi
- Site ayarları (logo, iletişim bilgileri, SEO, popup)
- Sitemap yönetimi
- Kullanıcı yönetimi (admin, manager, editor rolleri)
- Aktivite logları

---

## Kurulum (Local)

```bash
# Repoyu klonla
git clone https://github.com/Berjey/gmsgarage.git
cd gmsgarage

# Bağımlılıkları yükle
composer install
npm install

# Ortam dosyasını oluştur
cp .env.example .env
php artisan key:generate

# Veritabanını hazırla
php artisan migrate --seed

# Arabam.com verilerini çek
php artisan db:seed --class=CarBrandSeeder

# Assets derle
npm run dev

# Sunucuyu başlat
php artisan serve
```

---

## Production Deployment (Hostinger)

### Gereksinimler
- PHP 8.2+
- MySQL 8.0+
- mod_rewrite aktif

### Adımlar

1. Proje dosyalarını (vendor ve .env hariç) hosting root'una yükle
2. `public/` içeriğini web root'una kopyala
3. `.env` dosyasını oluştur (MySQL bilgileriyle)
4. `php artisan migrate --seed` çalıştır
5. `php artisan storage:link` çalıştır
6. `php artisan config:cache && php artisan view:cache` çalıştır

### Önemli .env Ayarları (Production)
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://www.gmsgarage.com
DB_CONNECTION=mysql
SESSION_SECURE_COOKIE=true
```

---

## Dizin Yapısı

```
├── app/
│   ├── Http/Controllers/     # Route controller'ları
│   ├── Models/               # Eloquent modeller
│   └── Services/             # Servis sınıfları (ArabamApiService)
├── database/
│   ├── migrations/           # Veritabanı migration'ları
│   └── seeders/              # Seed verileri
├── public/                   # Web root (index.php, assets)
├── resources/
│   ├── views/                # Blade şablonları
│   └── css/ js/              # Frontend kaynakları
└── routes/
    ├── web.php               # Web rotaları
    └── admin.php             # Admin panel rotaları
```

---

## Admin Paneli

**URL:** `/admin`

---

## Notlar

- Araç marka/model verileri arabam.com API'den çekilip veritabanına kaydedilmiştir (41 marka, 160 model)
- Araç değerleme formu arabam.com API ile gerçek zamanlı fiyat tahmini yapar
- CSS/JS assets Vite ile derlenir — production'da `npm run build` sonrası `public/build/` klasörü yüklenir
- Admin araç formunda marka/model DB'den, kasa/yakıt/vites/versiyon arabam.com API'den gelir

# GMSGARAGE — Premium İkinci El Araç Galerisi

**Canlı Site:** [www.gmsgarage.com](https://www.gmsgarage.com)

Modern, tam özellikli otomotiv galerisi web uygulaması. Laravel 10 + Tailwind CSS + Vite ile geliştirilmiştir.

---

## Özellikler

### Web Sitesi
- **Anasayfa** — Hero bölümü, araç arama/filtreleme, öne çıkan araçlar
- **Araç Listesi** — Gelişmiş filtreler (marka, model, yıl, fiyat, yakıt, vites)
- **Araç Detay** — Galeri, özellik tablosu, WhatsApp entegrasyonu
- **Araç Değerleme Sihirbazı** — arabam.com cascade API ile 7 adımlı wizard (tip → marka → yıl → model → kasa → yakıt → vites → versiyon)
- **Blog** — SEO optimizeli blog sistemi, kategori desteği
- **İletişim** — Form + Google Maps entegrasyonu
- **Yasal Sayfalar** — KVKK, Çerez Politikası, Kullanım Şartları (merkezi yönetim)
- **Dark Mode** — Sistem tercihi + manuel toggle
- **WhatsApp Butonu** — Sabit, animasyonlu
- **Sitemap.xml** — Otomatik üretim

### Admin Paneli
- **Dashboard** — Özet istatistikler, son aktiviteler
- **Araç Yönetimi** — CRUD, çoklu fotoğraf, durum yönetimi (aktif / rezerve / satıldı / arşiv)
- **Blog Yönetimi** — Zengin metin editörü, SEO alanları, öne çıkarma
- **İletişim Mesajları** — Okundu/okunmadı, toplu işlem, CSV export, e-posta ile yanıt
- **Araç Değerleme Talepleri** — PDF export, e-posta bildirimi
- **Müşteri CRM** — Otomatik kayıt, KVKK onay takibi, toplu e-posta
- **Kullanıcı Yönetimi** — 3 rol: Süper Admin / Galeri Yöneticisi / İçerik Editörü
- **Ayarlar** — Site bilgileri, SEO, iletişim, sosyal medya, Google Analytics, popup kampanya
- **Yasal Sayfa Editörü** — KVKK ve diğer metinler
- **Aktivite Logları** — Kullanıcı bazlı işlem geçmişi

---

## Teknoloji Yığını

| Katman | Teknoloji |
|--------|-----------|
| Backend | PHP 8.2, Laravel 10 |
| Frontend | Blade, Tailwind CSS 3 |
| Build | Vite 5 |
| Veritabanı | MySQL (production), SQLite (development) |
| Hosting | Hostinger Shared Hosting |
| Fonts | Bunny Fonts (Inter — privacy-friendly) |

---

## Kurulum

### Gereksinimler
- PHP 8.2+
- Composer
- Node.js 18+
- MySQL 8 veya SQLite

### Yerel Geliştirme

```bash
git clone https://github.com/Berjey/gmsgarage.git
cd gmsgarage

composer install
npm install

cp .env.example .env
php artisan key:generate

php artisan migrate
npm run dev
php artisan serve
```

### Arabam.com Araç Verisi Senkronizasyonu

Araç değerleme sihirbazı için DB'ye araç konfigürasyon verisi çekilmesi gerekir:

```bash
# Sadece markaları çek
php artisan arabam:sync --brands-only

# Tam cascade verisi (yıl, model, kasa, yakıt, vites, versiyon)
php artisan arabam:sync --full

# Mevcut veriyi silmeden eksik markaları tamamla
php artisan arabam:sync --resume
```

### Production Deployment

```bash
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
php artisan arabam:sync --full
```

---

## Proje Yapısı

```
app/
├── Http/Controllers/
│   └── Admin/                 # Admin panel controller'ları
├── Models/                    # Eloquent modelleri
├── Services/                  # EmailService, ArabamApiService
└── Console/Commands/          # arabam:sync Artisan komutu

resources/
├── css/app.css
├── js/                        # app.js, legal-modal.js
└── views/
    ├── layouts/               # Public ve admin layout'lar
    ├── components/            # Header, footer
    ├── pages/                 # Public sayfalar
    └── admin/                 # Admin panel view'ları

database/
├── migrations/
└── seeders/
```

---

## Güvenlik

- CSRF koruması tüm POST isteklerinde aktif
- Rate limiting: iletişim (5/dk), değerleme (10/dk)
- Ayarlar whitelist ile mass-assignment koruması
- Admin paneli rol bazlı yetkilendirme (RBAC)
- APP_DEBUG production'da kapalı

---

## Lisans

© 2026 GMSGARAGE. Tüm hakları saklıdır.

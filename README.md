# HyperTech Product & Cart Demo (Sadeleştirilmiş README)

Bu demo uygulama, **api.hyperteknoloji.com.tr** üzerinden ürün verilerini çekerek basit bir ürün listeleme ve sepet yönetimi deneyimi sunar. Tüm dış istekler backend'de bulunan **HyperTechApiService** üzerinden yapılır.

---

## 🚀 Hızlı Başlangıç

1. Ortam dosyasını oluşturun ve bağımlılıkları yükleyin:

```bash
cp .env.example .env
composer install
```

2. `.env` içine gerekli API anahtarlarını ekleyin:

```
HYPERTECH_API_KEY=xxx
HYPERTECH_API_TOKEN=xxx
```

3. Veritabanı ayarlarını yapın (PostgreSQL):

```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=project_db
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

4. Uygulamayı başlatın:

```bash
php artisan key:generate
php artisan migrate
php artisan serve
```

Uygulama: **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## 🔧 Önemli Ortam Değişkenleri

* `HYPERTECH_API_BASE_URL` — Varsayılan: `https://api.hyperteknoloji.com.tr`
* `HYPERTECH_API_KEY` — API erişim anahtarı
* `HYPERTECH_API_TOKEN` — Bearer/JWT token
* `HYPERTECH_PAGE_SIZE` — Sayfa boyutu (varsayılan 12)
* `HYPERTECH_CACHE_TTL` — Cache süresi (varsayılan 300 saniye)

---

## 🗄️ Cache Stratejisi

* Ürün listeleme ve detay talepleri cache'e alınır.
* Cache anahtar örnekleri:

  * Sayfa: `hypertech.products.page_1_size_12`
  * Detay: `hypertech.product.{id}`
* TTL: `HYPERTECH_CACHE_TTL`

Gerçek bir projede cache invalidation; stok değişimleri, ürün güncellemeleri veya webhook tetiklemeleriyle yapılabilir.

---

## 🛒 Sepet Mimarisi — Neden Veritabanı?

Sepet verileri **cart_items** tablosunda saklanır.

### Avantajlar

* Server-side olduğu için daha güvenli
* Çoklu sunucu yapılarında tutarlı
* Raporlama için ideal
* Kullanıcı manipülasyonuna karşı korumalı

### Alternatifler

* Cookie / localStorage → hızlı ama güvenlik ve tutarlılık açısından zayıf.

---

## 📁 Uygulama Bileşenleri

* app/Services/HyperTechApiService → API çağrıları, cache, hata yönetimi

* app/Repositories/CartRepository → Sepet işlemleri

* app/Http/Controllers/ProductController → Ürün listeleme

* app/Http/Controllers/CartController → Sepet CRUD işlemleri

---

## 🐞 Hata Yönetimi

* API hataları `HyperTechApiException` ile ele alınır.
* Loglar `storage/logs/laravel.log` altında tutulur.

---

## 🧩 Geliştirme Notları

* Varsayılan DB sqlite olabilir → `.env` ile Postgres/MySQL'e geçebilirsiniz.
* CSS dosyası: `public/css/app.css`

---

Her aşamada geliştirme desteği için bana ulaşabilirsiniz.

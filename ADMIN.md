# Admin Panel Kullanım Kılavuzu

## Giriş Bilgileri

**URL:** http://localhost/admin/login

**Varsayılan Admin Kullanıcısı:**
- E-posta: `admin@pauline.com`
- Şifre: `admin123`

## Özellikler

### 1. JWT Token ile Kimlik Doğrulama
- Admin paneli JWT token kullanarak güvenli giriş sağlar
- Token localStorage'da saklanır
- Token süresi dolduğunda otomatik çıkış yapılır

### 2. Dashboard
- Toplam ürün sayısı
- Aktif ürün sayısı
- Sayfa bölümleri sayısı
- Hızlı işlem butonları

### 3. Ürün Yönetimi
- **Ürün Listesi:** Tüm ürünleri görüntüleme
- **Yeni Ürün Ekleme:** 
  - Ürün adı, açıklama, fiyat
  - Görsel URL
  - Badge (Yeni, Popüler, Özel, Trend)
  - Boyutlar (JSON formatında)
  - Lezzetler (JSON formatında)
  - Aktif/Pasif durumu
  - Sıralama
- **Ürün Düzenleme:** Mevcut ürünleri güncelleme
- **Ürün Silme:** Ürünleri silme

### 4. Sayfa İçerik Yönetimi
Ana sayfa içeriklerini düzenleme:
- **Hero Bölümü:**
  - Başlık
  - Alt başlık
  - Hero görsel URL
- **Bölüm Başlıkları:**
  - Top Picks başlık ve alt başlık
  - Lezzetler başlık ve alt başlık
- **Hakkımızda Bölümü:**
  - Başlık
  - İçerik (2 paragraf)
  - Görsel URL

## API Endpoints

### Authentication
- `POST /api/auth/login` - Admin girişi
- `GET /api/auth/me` - Kullanıcı bilgileri
- `POST /api/auth/logout` - Çıkış
- `POST /api/auth/refresh` - Token yenileme

### Products (Admin Only)
- `GET /api/admin/products` - Ürün listesi
- `POST /api/admin/products` - Yeni ürün ekle
- `GET /api/admin/products/{id}` - Ürün detayı
- `PUT /api/admin/products/{id}` - Ürün güncelle
- `DELETE /api/admin/products/{id}` - Ürün sil

### Page Sections (Admin Only)
- `GET /api/admin/pages` - Sayfa bölümleri
- `PUT /api/admin/pages/{page}` - Sayfa bölümlerini güncelle

## Kullanım

### Admin Paneline Giriş
1. http://localhost/admin/login adresine gidin
2. E-posta ve şifre ile giriş yapın
3. Token otomatik olarak kaydedilir ve dashboard'a yönlendirilirsiniz

### Ürün Ekleme
1. Dashboard'dan "Yeni Ürün Ekle" butonuna tıklayın
2. Ürün bilgilerini doldurun
3. Boyutlar ve lezzetler için JSON formatı kullanın:
   ```json
   [
     {
       "id": "s",
       "name": "S size (1.5 kg - 5-6 kişilik)",
       "price": 850,
       "description": "5-6 kişilik"
     }
   ]
   ```
4. "Kaydet" butonuna tıklayın

### Sayfa İçeriklerini Düzenleme
1. Dashboard'dan "Ana Sayfa Düzenle" butonuna tıklayın
2. İstediğiniz bölümleri düzenleyin
3. "Kaydet" butonuna tıklayın
4. Değişiklikler anında ana sayfada görünecektir

## Notlar

- Tüm API istekleri JWT token gerektirir
- Token header'da şu şekilde gönderilir: `Authorization: Bearer {token}`
- Admin olmayan kullanıcılar panele erişemez
- Ürünler ana sayfada otomatik olarak gösterilir (aktif olanlar)
- Sayfa içerikleri veritabanından çekilir, yoksa varsayılan değerler kullanılır

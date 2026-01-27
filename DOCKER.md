# Docker Kurulum Talimatları

Bu proje Docker ve Laravel Sail kullanarak çalıştırılabilir.

## Gereksinimler

- Docker Desktop (Mac/Windows) veya Docker Engine + Docker Compose (Linux)
- Git

## Kurulum Adımları

### 1. Docker Container'ları Başlatma

```bash
# Laravel Sail ile container'ları başlat
./vendor/bin/sail up -d

# Veya docker-compose ile direkt başlat
docker-compose up -d
```

### 2. Composer Bağımlılıklarını Yükleme

```bash
# Sail içinde composer install
./vendor/bin/sail composer install

# Veya docker-compose ile
docker-compose exec laravel.test composer install
```

### 3. NPM Bağımlılıklarını Yükleme ve Build

```bash
# NPM install
./vendor/bin/sail npm install

# Assets build
./vendor/bin/sail npm run build

# Veya development modunda çalıştır
./vendor/bin/sail npm run dev
```

### 4. Veritabanı Migrasyonları

```bash
# Migration çalıştır
./vendor/bin/sail artisan migrate

# Veya seed ile birlikte
./vendor/bin/sail artisan migrate --seed
```

### 5. Uygulamaya Erişim

- **Web Uygulaması**: http://localhost
- **MySQL**: localhost:3306
  - Database: `cake_db`
  - Username: `sail`
  - Password: `password`
- **Redis**: localhost:6379

## Kullanışlı Komutlar

### Container'ları Durdurma

```bash
./vendor/bin/sail down
# veya
docker-compose down
```

### Container'ları Yeniden Başlatma

```bash
./vendor/bin/sail restart
# veya
docker-compose restart
```

### Logları Görüntüleme

```bash
./vendor/bin/sail logs
# veya belirli bir servis için
./vendor/bin/sail logs mysql
```

### Artisan Komutları

```bash
# Tüm artisan komutları sail ile çalıştırılabilir
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan cache:clear
./vendor/bin/sail artisan config:clear
```

### Veritabanına Bağlanma

```bash
# MySQL'e bağlan
./vendor/bin/sail mysql
# veya
docker-compose exec mysql mysql -u sail -ppassword cake_db
```

### Shell Erişimi

```bash
# Container içine gir
./vendor/bin/sail shell
# veya
docker-compose exec laravel.test bash
```

## Sorun Giderme

### Port Çakışması

Eğer portlar kullanılıyorsa, `.env` dosyasında portları değiştirebilirsiniz:

```env
APP_PORT=8080
FORWARD_DB_PORT=3307
FORWARD_REDIS_PORT=6380
```

### Veritabanı Bağlantı Hatası

1. MySQL container'ının çalıştığından emin olun:
   ```bash
   docker-compose ps
   ```

2. Container'ları yeniden başlatın:
   ```bash
   docker-compose restart mysql
   ```

### Cache Sorunları

```bash
./vendor/bin/sail artisan cache:clear
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan view:clear
```

### Veritabanını Sıfırlama

```bash
# Tüm migration'ları geri al ve tekrar çalıştır
./vendor/bin/sail artisan migrate:fresh
# Seed ile birlikte
./vendor/bin/sail artisan migrate:fresh --seed
```

## Veritabanı Yapılandırması

MySQL veritabanı ayarları `.env` dosyasında:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=cake_db
DB_USERNAME=sail
DB_PASSWORD=password
```

## Production Ortamı

Production için:

1. `.env` dosyasında `APP_DEBUG=false` yapın
2. `APP_ENV=production` olarak ayarlayın
3. `APP_KEY` oluşturun: `./vendor/bin/sail artisan key:generate`
4. Optimizasyon yapın:
   ```bash
   ./vendor/bin/sail artisan config:cache
   ./vendor/bin/sail artisan route:cache
   ./vendor/bin/sail artisan view:cache
   ```

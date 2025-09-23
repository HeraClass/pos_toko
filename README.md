# SISTEM INFORMASI POS TOKO KELONTONG PAK DEDY

## Instalasi

### Persyaratan
Untuk persyaratan sistem silakan cek di sini: [Laravel Requirement](https://laravel.com/docs/10.x/deployment#server-requirements)

### Clone Repository dari GitHub
```bash
git clone https://github.com/HeraClass/pos_toko
````

### Install Dependencies

Laravel menggunakan [Composer](https://getcomposer.org/) untuk mengelola dependensinya. Pastikan Composer sudah terpasang di perangkat Anda.

```bash
cd NamaDirektoriAnda
composer install
```

### File Konfigurasi

1. Ubah nama atau salin file `.env.example` menjadi `.env`
2. Jalankan perintah berikut untuk membuat app key:

   ```bash
   php artisan key:generate
   ```
3. Atur kredensial database di file `.env`
4. Atur juga `APP_URL` di file `.env`

### Database

1. Jalankan migrasi tabel:

   ```bash
   php artisan migrate
   ```
2. Jalankan seeder:

   ```bash
   php artisan db:seed
   ```

   Seeder akan menginisialisasi pengaturan dan membuat akun admin default:

   * **Email:** `admin@gmail.com`
   * **Password:** `admin123`

### Install Node Dependencies

1. Install dependencies Node.js:

   ```bash
   npm install
   ```
2. Untuk mode development:

   ```bash
   npm run dev
   ```

   Untuk mode production:

   ```bash
   npm run build
   ```

### Buat Storage Link

```bash
php artisan storage:link
```

### Menjalankan Server

1. Jalankan perintah:

   ```bash
   php artisan serve
   ```

   atau gunakan **Laravel Homestead**
2. Buka `http://localhost:8000` di browser
   Login menggunakan akun admin:

   * **Email:** `admin@gmail.com`
   * **Password:** `admin123`
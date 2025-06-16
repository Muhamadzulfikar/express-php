# ExpressPHP Framework

Framework minimal berbasis PHP native + Laravel Illuminate components. Sangat ringan, cocok untuk proyek kecil, cepat, dan fleksibel.

konsepnya sama seperti php biasa kalian bisa handle seperti php umumnya atau campur dengan package pakage laravel seperti ORM, request, dan validation. Tergantung pada selera.

---

## 📦 Struktur Folder

```
express-php/
├── Controllers/
├── Migrations/
├── Models/
├── Views/
├── routes.php
├── index.php
├── migrate.php
├── .env
├── composer.json
└── vendor/
```

---

## ⚙️ Setup Awal

### 1. Clone Project

```bash
git clone https://github.com/muhamadzulfikar/express-php.git
cd express-php
```

### 2. Install Dependency

```bash
composer install
```

> Composer diperlukan. [Download Composer](https://getcomposer.org/download/)

Isi konfigurasi database seperti:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=root
DB_PASSWORD=
```

---

## 🛠️ Jalankan Server

### **Linux / macOS:**

```bash
php -S localhost:8000
```

### **Windows + XAMPP:**

**Tambahkan path PHP ke environment variable:**

   - Klik Start → ketik `Environment Variables`
   - Edit `PATH`
   - Tambahkan: `C:\xampp\php`
   - Kemudian running script dibawah atau letak ke dalam htdocs seperti php biasa
   
```bash
php -S localhost:8000
```
---

## 🧱 Routing

Semua routing ditulis dalam `routes.php`:

```php
use Controllers\UserController;

$router->get('/users', [UserController::class, 'index']);

$router->get('/users/create', [UserController::class, 'create']);

$router->post('/users', [UserController::class, 'store']);

$router->get('/users/{id}', [UserController::class, 'show']);

$router->post('/users/{id}/delete', [UserController::class, 'destroy']);
```

---

## Frontend

Semua UI frontend ditulis ke dalam folder Views.
untuk cara pemanggilan

- Menggunakan sub folder
```php
return view('subfolder/namafile.php');
```

- Menggunakan langsung file
```php
return view('namafile.php')
```

- Parsing data ke UI
```php
$variable = 'isi variable';
return view('subfolder/namafile.php', [
    'variable' => $variable
])
```

## 📂 Controller

Contoh `Controllers/UserController.php`:

```php
namespace Controllers;

use Models\User;
use Illuminate\Http\Request;

class UserController {
    public function index() {
        $users = User::all();
        return view('users/index.php', ['users' => $users]);
    }

    public function store() {
        $request = Request::capture();
        $data = validate($request, [
            'name' => 'required',
            'email' => 'required|email',
        ]);

        User::create($data);
        header('Location: /users');
        exit;
    }
}
```

---

## 💾 Migration

Taruh file migration di `Migrations/` dan jalankan dengan:

```bash
php migrate.php
```

untuk rollback migration jalankan perintah:
```bash
php rollback.php
```

Contoh `Migrations/create_users_table.php`:

```php
use Illuminate\Database\Capsule\Manager as Capsule;

return new class {
    public function up() {
        Capsule::schema()->create('users', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->timestamps();
        });
    }
};
```

---

## 🧪 Validasi

```php
$data = validate($request, [
    'name' => 'required',
    'email' => 'required|email',
]);
```

---

## 👨‍💻 Requirements

- PHP >= 8.0
- Composer
- MySQL
- XAMPP (untuk pengguna Windows)

---
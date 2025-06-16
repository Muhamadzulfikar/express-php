<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Dotenv\Dotenv;

// Load .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

function env($key, $default = null) {
    return $_ENV[$key] ?? $default;
}

// Setup DB
$app = new Container();
$db = new DB;
$db->addConnection([
    'driver' => env('DB_CONNECTION', 'mysql'),
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_DATABASE', 'test'),
    'username' => env('DB_USERNAME', 'root'),
    'password' => env('DB_PASSWORD', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
]);

$db->setEventDispatcher(new Dispatcher($app));
$db->setAsGlobal();
$db->bootEloquent();

// Jalankan semua file di /migrations
$migrationsPath = __DIR__ . '/Migrations';
$files = glob($migrationsPath . '/*.php');

foreach ($files as $file) {
    echo "Running: " . basename($file) . PHP_EOL;
    $migration = require $file;
    $migration->up();
}
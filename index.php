<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use Illuminate\Translation\Translator;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Validation\Factory as ValidationFactory;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\CallableDispatcher;
use Illuminate\Routing\Contracts\CallableDispatcher as CallableDispatcherContract;
use Dotenv\Dotenv;

// Load Environment Variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Environment Helper Function
function env($key, $default = null) {
    return $_ENV[$key] ?? $default;
}

// Setup Container
$app = new Container;
Facade::setFacadeApplication($app);
$loader = new ArrayLoader();
$translator = new Translator($loader, 'en');
$validationFactory = new ValidationFactory($translator);

$app->bind(CallableDispatcherContract::class, CallableDispatcher::class);
$app->singleton('db', function () use ($capsule) {
    return $capsule->getDatabaseManager();
});
$app->singleton('validator', function () use ($validationFactory) {
    return $validationFactory;
});


// Setup Database
$db = new DB;
$db->addConnection([
    'driver' => env('DB_CONNECTION', 'mysql'),
    'host' => env('DB_HOST', 'localhost'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_DATABASE', 'test_db'),
    'username' => env('DB_USERNAME', 'root'),
    'password' => env('DB_PASSWORD', ''),
    'charset' => 'utf8mb4',
    'prefix' => '',
]);
$db->setAsGlobal();
$db->bootEloquent();

// JSON Response Helper
function json($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Validation Helper
function validate($request, $rules) {
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
        json(['errors' => $validator->errors()], 422);
    }
    return $request->only(array_keys($rules));
}

function view($file, $data = []) {
    extract($data);
    header('Content-Type: text/html');
    include __DIR__ . '/Views/' . $file;
    exit;
}

// Setup Router
$events = new Dispatcher($app);
$router = new Router($events, $app);

// Load Routes
require_once 'routes.php';

// Handle Request
$request = Request::capture();
try {
    $response = $router->dispatch($request);
    $response->send();
} catch (Exception $e) {
    // Show detailed error only in development
    $error = env('APP_DEBUG', false) ? $e->getMessage() : 'Internal Server Error';
    json(['error' => $error], 500);
}
<?php
// app/bootstrap.php

// Prevenir acceso directo al archivo
if (!defined('BASEPATH')) {
    define('BASEPATH', TRUE);
}

// Zona horaria por defecto
date_default_timezone_set('America/Mexico_City');

// Configuración de sesión
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_lifetime', 3600);
    ini_set('session.gc_maxlifetime', 3600);
    session_set_cookie_params([
        'lifetime' => 3600,
        'path' => '/',
        'domain' => '',
        'secure' => isset($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

// Configuración de errores en desarrollo
if ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Definir constantes de rutas
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_PATH', realpath(__DIR__ . DS . '..'));
define('APP_PATH', ROOT_PATH . DS . 'app');
define('CONFIG_PATH', APP_PATH . DS . 'config');
define('CONTROLLER_PATH', APP_PATH . DS . 'controllers');
define('MODEL_PATH', APP_PATH . DS . 'models');
define('VIEW_PATH', APP_PATH . DS . 'views');
define('HELPER_PATH', APP_PATH . DS . 'helpers');
define('PUBLIC_PATH', ROOT_PATH . DS . 'public');
define('UPLOAD_PATH', PUBLIC_PATH . DS . 'uploads');
define('ENVIRONMENT', 'development');
// Detectar URL base de manera más robusta
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];
$scriptName = $_SERVER['SCRIPT_NAME'];
$baseDir = str_replace('\\', '/', dirname($scriptName));
$baseDir = $baseDir !== '/' ? rtrim($baseDir, '/') : '';
define('BASE_URL', $protocol . $host . $baseDir);

// Cargar archivos esenciales
$requiredFiles = [
    CONFIG_PATH . DS . 'config.php',
    HELPER_PATH . DS . 'functions.php',
    CONFIG_PATH . DS . 'Database.php',
    CONTROLLER_PATH . DS . 'Controller.php'
];

foreach ($requiredFiles as $file) {
    if (!file_exists($file)) {
        die("Error crítico: Archivo requerido no encontrado: " . $file);
    }
    require_once $file;
}

// Autoloader mejorado para clases
spl_autoload_register(function($className) {
    // Lista de directorios donde buscar las clases
    $directories = [
        CONTROLLER_PATH => 'Controller',
        MODEL_PATH => '',
        HELPER_PATH => 'Helper'
    ];
    
    foreach ($directories as $directory => $suffix) {
        // Si la clase debe tener un sufijo específico pero no lo tiene, continuar
        if ($suffix && !str_ends_with($className, $suffix)) {
            continue;
        }
        
        // Construir la ruta completa del archivo
        $file = $directory . DS . $className . '.php';
        
        // Si el archivo existe, cargarlo
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
    }
    
    // Si no se encuentra la clase, registrar el error
    error_log("Clase no encontrada: " . $className);
    return false;
});

// Función mejorada para cargar vistas
function loadView($view, $data = []) {
    if (is_array($data)) {
        extract($data, EXTR_SKIP);
    }
    
    $viewFile = VIEW_PATH . DS . str_replace('/', DS, $view) . '.php';
    
    if (!file_exists($viewFile)) {
        throw new Exception("Vista no encontrada: " . $view);
    }
    
    ob_start();
    include $viewFile;
    return ob_get_clean();
}

// Función de debug mejorada
function dd($data, $die = true) {
    echo '<pre style="background-color: #f4f4f4; padding: 15px; border: 1px solid #ddd; border-radius: 4px;">';
    var_dump($data);
    echo '</pre>';
    if ($die) die();
}

// Función de sanitización mejorada
function sanitize($data, $type = 'string') {
    switch ($type) {
        case 'email':
            return filter_var($data, FILTER_SANITIZE_EMAIL);
        case 'url':
            return filter_var($data, FILTER_SANITIZE_URL);
        case 'int':
            return filter_var($data, FILTER_SANITIZE_NUMBER_INT);
        case 'float':
            return filter_var($data, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        default:
            return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }
}

// Función URL mejorada
function url($path = '') {
    $baseUrl = rtrim(BASE_URL, '/');
    $path = ltrim($path, '/');
    return $baseUrl . ($path ? '/' . $path : '');
}

// Registrar manejador de errores personalizado
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    if (!(error_reporting() & $errno)) {
        return false;
    }
    
    $errorType = match ($errno) {
        E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE => 'Error',
        E_WARNING, E_CORE_WARNING, E_COMPILE_WARNING => 'Warning',
        E_NOTICE => 'Notice',
        E_DEPRECATED => 'Deprecated',
        default => 'Unknown'
    };
    
    error_log("$errorType: $errstr in $errfile on line $errline");
    
    if ($errno === E_ERROR) {
        die("Fatal Error: $errstr");
    }
    
    return true;
});

// Verificar requisitos mínimos del servidor
if (version_compare(PHP_VERSION, '8.0.0', '<')) {
    die('Se requiere PHP 8.0 o superior para ejecutar esta aplicación.');
}
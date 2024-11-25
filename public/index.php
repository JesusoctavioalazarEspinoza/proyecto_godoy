<?php
// public/index.php

// Definir que este es el punto de entrada
define('ENTRY_POINT', true);

// Mostrar errores en desarrollo
if ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// Cargar el bootstrap
require_once __DIR__ . '/../app/bootstrap.php';

class Router {
    private $defaultController = 'HomeController';
    private $defaultAction = 'index';
    private $errorController = 'ErrorController';
    
    public function dispatch() {
        try {
            // Obtener y sanitizar la URL
            $url = $this->getUrl();
            
            // Obtener controlador, acción y parámetros
            list($controllerName, $action, $params) = $this->parseUrl($url);
            
            // Verificar y cargar el controlador
            $controller = $this->loadController($controllerName);
            
            // Verificar si el método existe y es llamable
            if (!is_callable([$controller, $action])) {
                throw new Exception("Método no encontrado o no accesible: {$action}");
            }
            
            // Ejecutar el método del controlador
            $response = call_user_func_array([$controller, $action], $params);
            
            // Enviar la respuesta
            echo $response;
            
        } catch (Exception $e) {
            $this->handleError($e);
        }
    }
    
    private function getUrl() {
        $url = isset($_GET['url']) ? $_GET['url'] : '';
        return filter_var(rtrim($url, '/'), FILTER_SANITIZE_URL);
    }
    
    private function parseUrl($url) {
        if (empty($url)) {
            return [$this->defaultController, $this->defaultAction, []];
        }
        
        $parts = explode('/', $url);
        
        // Rutas especiales para cliente/propiedades
        if ($parts[0] === 'cliente' && isset($parts[1]) && $parts[1] === 'propiedades') {
            if (isset($parts[2])) {
                switch($parts[2]) {
                    case 'publicar':
                        return ['ClienteController', 'publicar', []];
                    case 'editar':
                        return ['ClienteController', 'editar', array_slice($parts, 3)];
                    case 'ver':
                        return ['ClienteController', 'ver', array_slice($parts, 3)];
                    case 'eliminar':
                        return ['ClienteController', 'eliminar', array_slice($parts, 3)];
                    case 'actualizar':
                        return ['ClienteController', 'actualizar', array_slice($parts, 3)];
                }
            }
            return ['ClienteController', 'propiedades', []];
        }
        if ($parts[0] === 'cliente' && isset($parts[1]) && $parts[1] === 'citas') {
            switch($parts[2] ?? '') {
                case '':
                    return ['ClienteCitasController', 'misCitas', []];
                case 'nueva':
                    return ['ClienteCitasController', 'nueva', []];
                case 'ver':
                    return ['ClienteCitasController', 'ver', array_slice($parts, 3)];
                case 'confirmar':
                    return ['ClienteCitasController', 'confirmar', array_slice($parts, 3)];
                case 'cancelar':
                    return ['ClienteCitasController', 'cancelar', array_slice($parts, 3)];
            }
        }
        if ($parts[1] === 'perfil') {
            return $parts[2] === 'actualizar' ? 
                ['ClienteController', 'actualizarPerfil', []] : 
                ['ClienteController', 'perfil', []];
        }
        // En public/index.php, dentro del método parseUrl()

// Rutas para asesor/propiedades
if ($parts[0] === 'asesor' && isset($parts[1]) && $parts[1] === 'propiedades') {
    switch($parts[2] ?? '') {
        case 'nueva':
            return ['AsesorPropiedadesController', 'nueva', []];
        case 'editar':
            return ['AsesorPropiedadesController', 'editar', array_slice($parts, 3)];
        case 'ver':
            return ['AsesorPropiedadesController', 'ver', array_slice($parts, 3)];
        default:
            return ['AsesorPropiedadesController', 'index', []];
    }
}
        
        // Obtener controlador
        $controllerName = ucfirst(strtolower($parts[0])) . 'Controller';
        
        // Obtener acción
        $action = isset($parts[1]) ? strtolower($parts[1]) : $this->defaultAction;
        
        // Obtener parámetros adicionales
        $params = array_slice($parts, 2);
        
        return [$controllerName, $action, $params];
    }
    
    private function loadController($controllerName) {
        $controllerFile = CONTROLLER_PATH . DS . $controllerName . '.php';
        
        if (!file_exists($controllerFile)) {
            throw new Exception("Controlador no encontrado: {$controllerName}");
        }
        
        require_once $controllerFile;
        
        if (!class_exists($controllerName)) {
            throw new Exception("Clase del controlador no encontrada: {$controllerName}");
        }
        
        return new $controllerName();
    }
    
    private function handleError($exception) {
        // Registrar el error
        error_log($exception->getMessage());
        
        try {
            // Intentar cargar el controlador de errores
            if (class_exists($this->errorController)) {
                $errorController = new $this->errorController();
                
                // Determinar el código de error HTTP
                $errorCode = $exception instanceof HttpException ? $exception->getCode() : 404;
                
                // Llamar al método correspondiente en el controlador de errores
                echo $errorController->show($errorCode, $exception->getMessage());
                
            } else {
                // Si no hay controlador de errores, mostrar una vista de error básica
                http_response_code(404);
                require_once VIEW_PATH . DS . 'error' . DS . '404.php';
            }
            
        } catch (Exception $e) {
            // Si todo falla, mostrar un mensaje de error básico
            http_response_code(500);
            echo "Error del servidor: " . ($this->isDevMode() ? $e->getMessage() : 'Error interno del servidor');
        }
    }
    
    private function isDevMode() {
        return isset($_SERVER['SERVER_NAME']) && 
               in_array($_SERVER['SERVER_NAME'], ['localhost', '127.0.0.1']);
    }
}

// Clase personalizada para excepciones HTTP
class HttpException extends Exception {
    public function __construct($message = "", $code = 404) {
        parent::__construct($message, $code);
    }
}

// Iniciar el router
$router = new Router();
$router->dispatch();
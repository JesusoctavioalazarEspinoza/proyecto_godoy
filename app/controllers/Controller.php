// app/controllers/Controller.php

<?php
abstract class Controller {
    protected $db;
    protected $data = [];
    protected $layout = 'layouts/main'; // Definir un layout por defecto

    
    public function __construct() {
        $this->db = Database::obtenerInstancia();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    protected function render($view, $data = []) {
        try {
            $this->data = array_merge($this->data, $data);
            
            // Verificar que la vista existe
            $viewFile = VIEW_PATH . '/' . $view . '.php';
            if (!file_exists($viewFile)) {
                throw new Exception("Vista no encontrada: {$view}.php");
            }
            
            // Cargar la vista en un buffer
            $viewContent = $this->loadView($view, $this->data);
            
            // Si hay un layout definido, usarlo
            if (isset($this->layout)) {
                $layoutFile = VIEW_PATH . '/' . $this->layout . '.php';
                if (!file_exists($layoutFile)) {
                    throw new Exception("Layout no encontrado: {$this->layout}.php");
                }
                $this->data['content'] = $viewContent;
                return $this->loadView($this->layout, $this->data);
            }
            
            return $viewContent;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return $this->render('error/500', ['error' => $e->getMessage()]);
        }
    }
    
    protected function loadView($view, $data = []) {
        // Asegurarse de que las funciones helper estén disponibles
        require_once APP_PATH . '/helpers/functions.php';
        
        if (is_array($data)) {
            extract($data);
        }
        
        $viewFile = VIEW_PATH . '/' . $view . '.php';
        if (!file_exists($viewFile)) {
            throw new Exception("Vista no encontrada: " . $view);
        }
        
        ob_start();
        include $viewFile;
        return ob_get_clean();
    }
    
    protected function json($data) {
        header('Content-Type: application/json');
        return json_encode($data);
    }
    
    protected function redirect($url) {
        header('Location: ' . $url);
        exit;
    }

    // Métodos agregados para manejar peticiones POST y GET
    protected function esPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function esGet() {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    // Métodos para obtener datos de POST y GET
    protected function obtenerPost($clave = null) {
        if ($clave === null) {
            return $_POST;
        }
        return isset($_POST[$clave]) ? $_POST[$clave] : null;
    }

    protected function obtenerGet($clave = null) {
        if ($clave === null) {
            return $_GET;
        }
        return isset($_GET[$clave]) ? $_GET[$clave] : null;
    }

    // Método para establecer datos para la vista
    protected function setDato($clave, $valor) {
        $this->data[$clave] = $valor;
    }

    // Método para obtener datos establecidos
    protected function getDato($clave) {
        return isset($this->data[$clave]) ? $this->data[$clave] : null;
    }

    // Método para redirección con URL base
    protected function redireccionar($ruta) {
        $url = BASE_URL . '/' . ltrim($ruta, '/');
        header("Location: $url");
        exit;
    }

    // Método para validar que exista una sesión
    protected function requiereAutenticacion() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redireccionar('auth/login');
        }
    }

    // Método para validar rol de usuario
    protected function validarRol($roles) {
        if (!isset($_SESSION['usuario_tipo']) || 
            !in_array($_SESSION['usuario_tipo'], (array)$roles)) {
            $this->redireccionar('error/403');
        }
    }

    // Método para limpiar datos de entrada
    protected function limpiarDato($dato) {
        return htmlspecialchars(trim($dato), ENT_QUOTES, 'UTF-8');
    }
    protected function verificarAutenticacion() {
        if (!isset($_SESSION['usuario_id'])) {
            $_SESSION['error'] = 'Debe iniciar sesión para acceder a esta sección';
            $this->redireccionar('auth/login');
            exit;
        }
        return true;
    }
    protected function setLayout($layout) {
        $this->layout = $layout;
        return $this;
    }
    protected function esCliente() {
        return isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'cliente';
    }

    // Método para validar email
    protected function validarEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    // Método para manejar errores
    protected function manejarError($mensaje, $codigo = 500) {
        error_log($mensaje);
        return $this->render('error/' . $codigo, [
            'mensaje' => $mensaje,
            'titulo' => 'Error ' . $codigo
        ]);
    }
}
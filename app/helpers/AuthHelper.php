//app/helpers/AuthHelper.php
<?php
class AuthHelper {
    public static function estaAutenticado() {
        return isset($_SESSION['usuario_id']);
    }
    
    public static function obtenerUsuarioId() {
        return $_SESSION['usuario_id'] ?? null;
    }
    
    public static function obtenerTipoUsuario() {
        return $_SESSION['usuario_tipo'] ?? null;
    }
    
    public static function esAdmin() {
        return self::obtenerTipoUsuario() === 'administrador';
    }
    
    public static function esAsesor() {
        return self::obtenerTipoUsuario() === 'asesor';
    }
    
    public static function esCliente() {
        return self::obtenerTipoUsuario() === 'cliente';
    }
    
    public static function verificarAcceso($tiposPermitidos) {
        if (!self::estaAutenticado()) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }
        
        if (!in_array(self::obtenerTipoUsuario(), $tiposPermitidos)) {
            header('Location: ' . BASE_URL . '/error/403');
            exit;
        }
    }
}
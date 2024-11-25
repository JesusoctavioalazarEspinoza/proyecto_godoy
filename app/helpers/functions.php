<?php
/**
 * app/helpers/functions.php
 * Funciones helper globales para la aplicación
 */

/**
 * Verifica si la ruta actual coincide con la ruta especificada
 * @param string $path Ruta a verificar
 * @return bool
 */
function url_is($path) {
    $current_path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    $base_path = trim(parse_url(BASE_URL, PHP_URL_PATH), '/');
    
    if (strpos($current_path, $base_path) === 0) {
        $current_path = substr($current_path, strlen($base_path));
    }
    
    $current_path = trim($current_path, '/');
    return $current_path === trim($path, '/');
}

/**
 * Genera una URL completa y segura para la aplicación
 * @param string $path Ruta relativa
 * @param array $params Parámetros GET opcionales
 * @param bool $absolute Si se debe devolver una URL absoluta
 * @return string URL formateada
 */
if (!function_exists('url')) {
    function url($path = '', array $params = [], $absolute = true) {
        $base_url = rtrim(BASE_URL, '/');
        
        // Si la ruta está vacía, devolver la URL base
        if (empty($path)) {
            $url = $base_url;
        } else {
            // Normalizar la ruta eliminando slashes múltiples y al inicio/final
            $path = trim($path, '/');
            $url = $base_url . '/' . $path;
        }
        
        // Agregar parámetros GET si existen
        if (!empty($params)) {
            $query = http_build_query($params);
            $url .= (parse_url($url, PHP_URL_QUERY) ? '&' : '?') . $query;
        }
        
        // Sanitizar la URL final
        $url = filter_var($url, FILTER_SANITIZE_URL);
        
        // Convertir a URL relativa si se solicita
        if (!$absolute) {
            $url = parse_url($url, PHP_URL_PATH);
            if (!empty($params)) {
                $url .= '?' . http_build_query($params);
            }
        }
        
        return $url;
    }
}

/**
 * Redirecciona a una URL y termina la ejecución
 * @param string $path Ruta a redireccionar
 * @param array $params Parámetros GET opcionales
 * @param int $code Código de estado HTTP
 */
if (!function_exists('redirect')) {
    function redirect($path, array $params = [], $code = 302) {
        $url = url($path, $params);
        
        if (!headers_sent()) {
            http_response_code($code);
            header("Location: $url");
        } else {
            echo '<script>window.location.href="' . $url . '";</script>';
        }
        
        exit;
    }
}

/**
 * Obtiene la ruta completa de una vista
 * @param string $view Nombre de la vista
 * @return string Ruta completa del archivo de vista
 */
if (!function_exists('view_path')) {
    function view_path($view) {
        return VIEW_PATH . DS . str_replace('/', DS, $view) . '.php';
    }
}

/**
 * Función mejorada para debug y die
 * @param mixed $data Datos a mostrar
 * @param bool $die Detener la ejecución después de mostrar
 */
if (!function_exists('dd')) {
    function dd($data, $die = true) {
        echo '<div style="background-color: #f4f4f4; padding: 15px; margin: 10px; border-radius: 5px; font-family: monospace;">';
        echo '<pre style="margin: 0; white-space: pre-wrap;">';
        
        if (is_array($data) || is_object($data)) {
            print_r($data);
        } else {
            var_dump($data);
        }
        
        echo '</pre>';
        
        // Mostrar backtrace en desarrollo
        if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            echo '<div style="margin-top: 10px; font-size: 0.9em; color: #666;">';
            echo '<strong>Called from:</strong><br>';
            foreach ($backtrace as $trace) {
                if (isset($trace['file'], $trace['line'])) {
                    echo basename($trace['file']) . ':' . $trace['line'] . '<br>';
                }
            }
            echo '</div>';
        }
        
        echo '</div>';
        
        if ($die) die(1);
    }
}

/**
 * Alias para generar URLs de assets (CSS, JS, imágenes)
 * @param string $path Ruta al asset
 * @return string URL del asset
 */
if (!function_exists('asset')) {
    function asset($path = '') {
        return url('assets/' . ltrim($path, '/'), [], true);
    }
}

/**
 * Verifica si la URL actual coincide con un patrón
 * @param string $pattern Patrón a verificar
 * @return bool
 */
if (!function_exists('url_matches')) {
    function url_matches($pattern) {
        $current = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        return preg_match('#^' . str_replace('*', '.*', $pattern) . '$#i', $current);
    }
}

/**
 * Obtiene la URL actual completa
 * @param bool $withQuery Incluir parámetros GET
 * @return string
 */
if (!function_exists('current_url')) {
    function current_url($withQuery = true) {
        $url = url(trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
        
        if ($withQuery && !empty($_SERVER['QUERY_STRING'])) {
            $url .= '?' . $_SERVER['QUERY_STRING'];
        }
        
        return $url;
    }
}

/**
 * Verifica si una URL es externa
 * @param string $url URL a verificar
 * @return bool
 */
if (!function_exists('is_external_url')) {
    function is_external_url($url) {
        $urlHost = parse_url($url, PHP_URL_HOST);
        $baseHost = parse_url(BASE_URL, PHP_URL_HOST);
        
        return !empty($urlHost) && $urlHost !== $baseHost;
    }
}
<?php
// route-diagnostic.php
//solo es para verificar 
class RouteDiagnostic {
    private $errors = [];
    private $warnings = [];
    
    public function checkDirectoryStructure() {
        $requiredDirs = [
            'app',
            'app/config',
            'app/controllers',
            'app/models',
            'app/views',
            'app/helpers',
            'public',
            'public/css',
            'public/js',
            'public/uploads'
        ];
        
        foreach ($requiredDirs as $dir) {
            if (!is_dir($dir)) {
                $this->errors[] = "Directorio no encontrado: {$dir}";
            }
        }
    }
    
    public function checkRequiredFiles() {
        $requiredFiles = [
            'app/bootstrap.php',
            'app/config/config.php',
            'app/config/Database.php',
            'app/helpers/functions.php',
            'public/index.php',
            'public/.htaccess',
            '.htaccess'
        ];
        
        foreach ($requiredFiles as $file) {
            if (!file_exists($file)) {
                $this->errors[] = "Archivo requerido no encontrado: {$file}";
            }
        }
    }
    
    public function checkControllerRoutes() {
        $controllers = glob('app/controllers/*Controller.php');
        foreach ($controllers as $controller) {
            $content = file_get_contents($controller);
            // Verificar que los render() apunten a vistas existentes
            preg_match_all('/->render\([\'"]([^\'"]+)[\'"]\)/', $content, $matches);
            foreach ($matches[1] as $view) {
                $viewFile = 'app/views/' . $view . '.php';
                if (!file_exists($viewFile)) {
                    $this->warnings[] = "Vista no encontrada para {$controller}: {$viewFile}";
                }
            }
        }
    }
    
    public function checkHtaccess() {
        $rootHtaccess = file_exists('.htaccess');
        $publicHtaccess = file_exists('public/.htaccess');
        
        if (!$rootHtaccess || !$publicHtaccess) {
            $this->errors[] = "Archivos .htaccess faltantes";
        }
    }
    
    public function checkBaseUrlConfig() {
        $bootstrap = file_get_contents('app/bootstrap.php');
        if (!strpos($bootstrap, 'BASE_URL')) {
            $this->errors[] = "Configuración de BASE_URL no encontrada en bootstrap.php";
        }
    }
    
    public function run() {
        $this->checkDirectoryStructure();
        $this->checkRequiredFiles();
        $this->checkControllerRoutes();
        $this->checkHtaccess();
        $this->checkBaseUrlConfig();
        
        echo "\nDiagnóstico de Rutas\n";
        echo "===================\n\n";
        
        if (!empty($this->errors)) {
            echo "Errores encontrados:\n";
            foreach ($this->errors as $error) {
                echo "❌ {$error}\n";
            }
        }
        
        if (!empty($this->warnings)) {
            echo "\nAdvertencias:\n";
            foreach ($this->warnings as $warning) {
                echo "⚠️ {$warning}\n";
            }
        }
        
        if (empty($this->errors) && empty($this->warnings)) {
            echo "✅ No se encontraron problemas con las rutas.\n";
        }
    }
}

// Ejecutar diagnóstico
$diagnostic = new RouteDiagnostic();
$diagnostic->run();
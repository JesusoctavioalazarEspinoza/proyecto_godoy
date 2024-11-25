// verificacion_proyecto.php
<?php 
class VerificadorProyecto {
    private $estructuraRequerida;
    private $archivosExistentes = [];
    private $archivosFaltantes = [];
    private $archivosNuevos = [];
    private $directorioBase;

    public function __construct($directorioBase) {
        $this->directorioBase = $directorioBase;
        $this->definirEstructuraRequerida();
    }

    private function definirEstructuraRequerida() {
        $this->estructuraRequerida = [
            'controllers' => [
                'AdminController.php',
                'AsesorController.php',
                'AsesorCitasController.php',
                'AsesorPropiedadesController.php',
                'AuthController.php',
                'ClienteController.php',
                'ClienteCitasController.php',
                'ContratosController.php',
                'Controller.php',
                'HomeController.php',
                'PropiedadesController.php',
                'ReportesController.php'
            ],
            'models' => [
                'Cita.php',
                'Contrato.php',
                'Estancia.php',
                'Propiedad.php',
                'Usuario.php'
            ],
            'views' => [
                'admin' => [
                    'asesores' => ['index.php', 'editar.php', 'ver.php'],
                    'clientes' => ['index.php', 'editar.php', 'ver.php'],
                    'dashboard.php',
                    'sidebar.php'
                ],
                'asesor' => [
                    'citas' => ['index.php', 'nueva.php', 'editar.php', 'ver.php'],
                    'propiedades' => ['index.php', 'nueva.php', 'editar.php', 'ver.php'],
                    'dashboard.php',
                    'sidebar.php'
                ],
                'auth' => ['login.php', 'registro.php'],
                'cliente' => [
                    'citas' => ['index.php', 'ver.php'],
                    'propiedades' => ['index.php', 'publicar-propiedad.php', 'ver.php'],
                    'dashboard.php',
                    'sidebar.php'
                ],
                'contratos' => ['index.php', 'crear.php', 'ver.php'],
                'layouts' => ['admin.php', 'asesor.php', 'cliente.php', 'main.php'],
                'home' => ['index.php'],
                'propiedades' => ['index.php', 'buscar.php', 'ver.php']
            ],
            'config' => [
                'config.php',
                'Database.php'
            ],
            'helpers' => [
                'AuthHelper.php',
                'functions.php'
            ]
        ];
    }

    public function verificar() {
        foreach ($this->estructuraRequerida as $carpeta => $archivos) {
            $rutaCarpeta = $this->directorioBase . '/' . $carpeta;
            
            if (!is_dir($rutaCarpeta)) {
                $this->archivosFaltantes[] = "Directorio faltante: $carpeta";
                continue;
            }

            $this->verificarArchivos($rutaCarpeta, $archivos, $carpeta);
        }

        return $this->generarReporte();
    }

    private function verificarArchivos($ruta, $archivos, $carpetaActual) {
        foreach ($archivos as $key => $valor) {
            if (is_array($valor)) {
                $nuevaRuta = $ruta . '/' . $key;
                if (!is_dir($nuevaRuta)) {
                    $this->archivosFaltantes[] = "Directorio faltante: $carpetaActual/$key";
                    continue;
                }
                $this->verificarArchivos($nuevaRuta, $valor, "$carpetaActual/$key");
            } else {
                $rutaArchivo = $ruta . '/' . $valor;
                if (file_exists($rutaArchivo)) {
                    $this->archivosExistentes[] = "$carpetaActual/$valor";
                } else {
                    $this->archivosFaltantes[] = "$carpetaActual/$valor";
                }
            }
        }
    }

    private function generarReporte() {
        $reporte = "=== REPORTE DE VERIFICACIÓN DEL PROYECTO ===\n\n";
        
        $reporte .= "ARCHIVOS EXISTENTES (" . count($this->archivosExistentes) . "):\n";
        foreach ($this->archivosExistentes as $archivo) {
            $reporte .= "✓ $archivo\n";
        }
        
        $reporte .= "\nARCHIVOS FALTANTES (" . count($this->archivosFaltantes) . "):\n";
        foreach ($this->archivosFaltantes as $archivo) {
            $reporte .= "✗ $archivo\n";
        }
        
        $reporte .= "\nRESUMEN:\n";
        $reporte .= "Total archivos esperados: " . 
                   (count($this->archivosExistentes) + count($this->archivosFaltantes)) . "\n";
        $reporte .= "Archivos existentes: " . count($this->archivosExistentes) . "\n";
        $reporte .= "Archivos faltantes: " . count($this->archivosFaltantes) . "\n";
        
        return $reporte;
    }
}

// Uso
$verificador = new VerificadorProyecto(__DIR__ . '/app');
echo $verificador->verificar();
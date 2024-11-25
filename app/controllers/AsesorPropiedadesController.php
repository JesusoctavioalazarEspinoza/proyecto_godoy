// app/controllers/AsesorPropiedadesController.php
<?php
class AsesorPropiedadesController extends Controller {
    public function __construct() {
        parent::__construct();
        $this->requiereAutenticacion();
        $this->setLayout('layouts/asesor');
        $this->validarRol('asesor');
    }

    public function nueva() {
        return $this->render('asesor/propiedades/nueva', [
            'titulo' => 'Nueva Propiedad'
        ]);
    }

    public function guardar() {
        if (!$this->esPost()) {
            $this->redireccionar('asesor/propiedades');
        }

        try {
            // Iniciar transacción
            $this->db->getConnection()->beginTransaction();

            // Guardar información básica del inmueble
            $idInmueble = $this->guardarInmueble();

            // Crear directorio para las imágenes
            $directorioImagenes = PUBLIC_PATH . '/uploads/propiedades/' . $idInmueble;
            if (!file_exists($directorioImagenes)) {
                mkdir($directorioImagenes, 0777, true);
            }

            // Procesar imágenes principales
            $this->procesarImagenes($_FILES['imagenes'], $idInmueble);

            // Procesar estancias
            $this->procesarEstancias($_POST['estancias'], $_FILES['estancias'], $idInmueble);

            // Confirmar transacción
            $this->db->getConnection()->commit();

            $this->setDato('exito', 'Propiedad registrada exitosamente');
            $this->redireccionar('asesor/propiedades');

        } catch (Exception $e) {
            // Revertir transacción en caso de error
            $this->db->getConnection()->rollBack();
            $this->setDato('error', 'Error al registrar la propiedad: ' . $e->getMessage());
            $this->redireccionar('asesor/propiedades/nueva');
        }
    }

    private function guardarInmueble() {
        $sql = "INSERT INTO inmuebles (
                    titulo, tipo_inmueble, descripcion, precio, superficie,
                    num_habitaciones, num_baños, estacionamientos,
                    direccion_completa, ciudad, estado, codigo_postal,
                    id_asesor, tipo_servicio, estado_inmueble
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'disponible')";

        $params = [
            $this->obtenerPost('titulo'),
            $this->obtenerPost('tipo_inmueble'),
            $this->obtenerPost('descripcion'),
            $this->obtenerPost('precio'),
            $this->obtenerPost('superficie'),
            $this->obtenerPost('num_habitaciones'),
            $this->obtenerPost('num_baños'),
            $this->obtenerPost('estacionamientos'),
            $this->obtenerPost('direccion_completa'),
            $this->obtenerPost('ciudad'),
            $this->obtenerPost('estado'),
            $this->obtenerPost('codigo_postal'),
            $_SESSION['usuario_id'],
            $this->obtenerPost('tipo_servicio')
        ];

        $this->db->consulta($sql, $params);
        return $this->db->getConnection()->lastInsertId();
    }

    private function procesarImagenes($archivos, $idInmueble) {
        if (empty($archivos['tmp_name'][0])) {
            throw new Exception('Se requiere al menos una imagen');
        }

        foreach ($archivos['tmp_name'] as $key => $tmpName) {
            $nombreArchivo = uniqid() . '_' . $archivos['name'][$key];
            $rutaDestino = PUBLIC_PATH . '/uploads/propiedades/' . $idInmueble . '/' . $nombreArchivo;

            if (!move_uploaded_file($tmpName, $rutaDestino)) {
                throw new Exception('Error al subir la imagen');
            }

            // Registrar imagen en la base de datos
            $sql = "INSERT INTO imagenes_inmueble (id_inmueble, url_imagen, orden) VALUES (?, ?, ?)";
            $this->db->consulta($sql, [$idInmueble, $nombreArchivo, $key]);
        }
    }

    private function procesarEstancias($datosEstancias, $imagenesEstancias, $idInmueble) {
        foreach ($datosEstancias['tipo'] as $key => $tipo) {
            // Insertar estancia
            $sql = "INSERT INTO estancias (id_inmueble, tipo_estancia, descripcion) VALUES (?, ?, ?)";
            $this->db->consulta($sql, [
                $idInmueble,
                $tipo,
                $datosEstancias['descripcion'][$key]
            ]);

            $idEstancia = $this->db->getConnection()->lastInsertId();

            // Procesar imágenes de la estancia si existen
            if (!empty($imagenesEstancias['tmp_name'][$key])) {
                foreach ($imagenesEstancias['tmp_name'][$key] as $imgKey => $tmpName) {
                    $nombreArchivo = uniqid() . '_' . $imagenesEstancias['name'][$key][$imgKey];
                    $rutaDestino = PUBLIC_PATH . '/uploads/propiedades/' . $idInmueble . '/estancias/' . $nombreArchivo;

                    if (!move_uploaded_file($tmpName, $rutaDestino)) {
                        throw new Exception('Error al subir la imagen de la estancia');
                    }

                    $sql = "INSERT INTO imagenes_estancia (id_estancia, url_imagen, orden) VALUES (?, ?, ?)";
                    $this->db->consulta($sql, [$idEstancia, $nombreArchivo, $imgKey]);
                }
            }
        }
    }
    public function index() {
        $propiedades = $this->db->obtenerTodos(
            "SELECT i.*, u.nombre as nombre_propietario 
             FROM inmuebles i 
             JOIN usuarios u ON i.id_propietario = u.id_usuario 
             WHERE i.id_asesor = ?",
            [$_SESSION['usuario_id']]
        );
        
        return $this->render('asesor/propiedades/index', [
            'titulo' => 'Propiedades',
            'propiedades' => $propiedades
        ]);
    }
    public function editar($id) {
        $propiedad = $this->db->obtenerUno(
            "SELECT * FROM inmuebles WHERE id_inmueble = ? AND id_asesor = ?",
            [$id, $_SESSION['usuario_id']]
        );
        
        if (!$propiedad) {
            $this->redireccionar('error/404');
        }
        
        if ($this->esPost()) {
            // Lógica para actualizar propiedad
            // Similar a guardar() pero actualizando
        }
        
        return $this->render('asesor/propiedades/editar', [
            'titulo' => 'Editar Propiedad',
            'propiedad' => $propiedad
        ]);
    }
    public function ver($id) {
        $propiedad = $this->db->obtenerUno(
            "SELECT i.*, u.nombre as nombre_propietario 
             FROM inmuebles i 
             JOIN usuarios u ON i.id_propietario = u.id_usuario 
             WHERE i.id_inmueble = ? AND i.id_asesor = ?",
            [$id, $_SESSION['usuario_id']]
        );
        
        if (!$propiedad) {
            $this->redireccionar('error/404');
        }
        
        return $this->render('asesor/propiedades/ver', [
            'titulo' => 'Detalles de Propiedad',
            'propiedad' => $propiedad
        ]);
    }
}
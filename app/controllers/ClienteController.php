// app/controllers/ClienteController.php
<?php
class ClienteController extends Controller {
    protected $layout = 'layouts/cliente';

    public function __construct() {
        parent::__construct();
        $this->requiereAutenticacion();
        $this->validarRol('cliente');
        $this->verificarSesion();
    }

    private function verificarSesion() {
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'cliente') {
            $_SESSION['error'] = 'Debe iniciar sesión como cliente';
            $this->redireccionar('auth/login');
            exit;
        }
    }
    
    public function dashboard() {
        $idCliente = $_SESSION['usuario_id'];
        
        // Obtener propiedades del cliente
        $propiedades = $this->db->obtenerTodos(
            "SELECT * FROM inmuebles WHERE id_propietario = ?",
            [$idCliente]
        );

        // Obtener citas pendientes
        $citas = $this->db->obtenerTodos(
            "SELECT c.*, i.titulo as propiedad, u.nombre as asesor 
             FROM citas c 
             JOIN inmuebles i ON c.id_inmueble = i.id_inmueble 
             JOIN usuarios u ON c.id_asesor = u.id_usuario 
             WHERE c.id_cliente = ? AND c.fecha_hora >= NOW() 
             ORDER BY c.fecha_hora ASC",
            [$idCliente]
        );

        return $this->render('cliente/dashboard', [
            'titulo' => 'Mi Panel',
            'propiedades' => $propiedades,
            'citas' => $citas
        ]);
    }

    public function publicar() {
        if ($this->esPost()) {
            try {
                // Validación de datos
                $errores = $this->validarDatosPropiedad($_POST, $_FILES);
                if (!empty($errores)) {
                    $this->setDato('errores', $errores);
                    return $this->render('cliente/propiedades/publicar', [
                        'titulo' => 'Publicar Propiedad',
                        'datos' => $_POST
                    ]);
                }
    
                $this->db->getConnection()->beginTransaction();
    
                // Preparar los datos en el orden correcto según la estructura de la tabla
                $datos = [
                    'id_propietario' => $_SESSION['usuario_id'],
                    'titulo' => $this->obtenerPost('titulo'),
                    'descripcion' => $this->obtenerPost('descripcion'),
                    'tipo_inmueble' => $this->obtenerPost('tipo_inmueble'),
                    'precio' => $this->obtenerPost('precio'),
                    'superficie' => $this->obtenerPost('superficie'),
                    'num_habitaciones' => $this->obtenerPost('num_habitaciones'),
                    'num_baños' => $this->obtenerPost('num_baños'),
                    'estacionamientos' => $this->obtenerPost('estacionamientos'),
                    'estado_inmueble' => 'disponible',
                    'tipo_servicio' => $this->obtenerPost('tipo_servicio'),
                    'direccion_completa' => $this->obtenerPost('direccion_completa'),
                    'ciudad' => $this->obtenerPost('ciudad'),
                    'estado' => $this->obtenerPost('estado'),
                    'codigo_postal' => $this->obtenerPost('codigo_postal')
                ];
    
                // Insertar propiedad especificando las columnas
                $sql = "INSERT INTO inmuebles (
                    id_propietario, titulo, descripcion, tipo_inmueble,
                    precio, superficie, num_habitaciones, num_baños,
                    estacionamientos, estado_inmueble, tipo_servicio,
                    direccion_completa, ciudad, estado, codigo_postal
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                )";
    
                // Preparar el array de valores en el mismo orden que las columnas
                $valores = [
                    $datos['id_propietario'],
                    $datos['titulo'],
                    $datos['descripcion'],
                    $datos['tipo_inmueble'],
                    $datos['precio'],
                    $datos['superficie'],
                    $datos['num_habitaciones'],
                    $datos['num_baños'],
                    $datos['estacionamientos'],
                    $datos['estado_inmueble'],
                    $datos['tipo_servicio'],
                    $datos['direccion_completa'],
                    $datos['ciudad'],
                    $datos['estado'],
                    $datos['codigo_postal']
                ];
    
                $this->db->consulta($sql, $valores);
                $idInmueble = $this->db->getConnection()->lastInsertId();
    
                // Procesar imágenes
                if (!empty($_FILES['imagenes']['tmp_name'][0])) {
                    $resultado = $this->procesarImagenes($_FILES['imagenes'], $idInmueble);
                    if (!$resultado) {
                        throw new Exception('Error al procesar las imágenes');
                    }
                }
    
                $this->db->getConnection()->commit();
                $this->setDato('exito', 'Propiedad publicada exitosamente');
                $this->redireccionar('cliente/propiedades');
    
            } catch (Exception $e) {
                $this->db->getConnection()->rollBack();
                error_log("Error al publicar propiedad: " . $e->getMessage());
                $this->setDato('error', 'Error al publicar la propiedad: ' . $e->getMessage());
                return $this->render('cliente/propiedades/publicar', [
                    'titulo' => 'Publicar Propiedad',
                    'datos' => $_POST
                ]);
            }
        }
    
        return $this->render('cliente/propiedades/publicar', [
            'titulo' => 'Publicar Propiedad'
        ]);
    }
    
    // Función para procesar las imágenes
    private function procesarImagenes($archivos, $idInmueble) {
        $directorio = PUBLIC_PATH . '/uploads/propiedades/' . $idInmueble;
        
        if (!file_exists($directorio)) {
            mkdir($directorio, 0755, true);
        }
    
        foreach ($archivos['tmp_name'] as $key => $tmpName) {
            if (empty($tmpName)) continue;
    
            $extension = pathinfo($archivos['name'][$key], PATHINFO_EXTENSION);
            $nombreArchivo = uniqid() . '.' . $extension;
            $rutaCompleta = $directorio . '/' . $nombreArchivo;
    
            if (!move_uploaded_file($tmpName, $rutaCompleta)) {
                throw new Exception('Error al subir imagen: ' . $archivos['name'][$key]);
            }
    
            $this->db->consulta(
                "INSERT INTO imagenes_inmueble (id_inmueble, url_imagen) VALUES (?, ?)",
                [$idInmueble, $nombreArchivo]
            );
        }
    }

    private function validarDatosPropiedad($post, $files) {
        $errores = [];
        
        // Validar campos requeridos
        $camposRequeridos = ['titulo', 'descripcion', 'tipo_inmueble', 'precio', 
                            'superficie', 'direccion_completa', 'ciudad', 'estado', 
                            'codigo_postal'];
        foreach ($camposRequeridos as $campo) {
            if (empty($post[$campo])) {
                $errores[] = "El campo " . ucfirst($campo) . " es requerido";
            }
        }
    
        // Validar precio
        if (!empty($post['precio']) && (!is_numeric($post['precio']) || $post['precio'] <= 0)) {
            $errores[] = "El precio debe ser un número mayor a 0";
        }
    
        // Validar superficie
        if (!empty($post['superficie']) && (!is_numeric($post['superficie']) || $post['superficie'] <= 0)) {
            $errores[] = "La superficie debe ser un número mayor a 0";
        }
    
        // Validar imágenes
        if (empty($files['imagenes']['tmp_name'][0])) {
            $errores[] = "Debe subir al menos una imagen";
        } else {
            foreach ($files['imagenes']['tmp_name'] as $key => $tmp_name) {
                if (!empty($tmp_name)) {
                    $tipoArchivo = $files['imagenes']['type'][$key];
                    if (!in_array($tipoArchivo, ['image/jpeg', 'image/png', 'image/jpg'])) {
                        $errores[] = "El archivo " . $files['imagenes']['name'][$key] . " no es una imagen válida";
                    }
                }
            }
        }
    
        return $errores;
    }


    public function propiedades() {
        return $this->render('cliente/propiedades/index', [
            'titulo' => 'Mis Propiedades',
            'propiedades' => $this->obtenerPropiedadesCliente()
        ]);
    }

    private function obtenerPropiedadesCliente() {
        return $this->db->obtenerTodos(
            "SELECT i.*, 
                    (SELECT url_imagen FROM imagenes_inmueble WHERE id_inmueble = i.id_inmueble LIMIT 1) as imagen
             FROM inmuebles i 
             WHERE i.id_propietario = ?
             ORDER BY i.id_inmueble DESC",
            [$_SESSION['usuario_id']]
        );
    }
    
    
    public function ver($id) {
        // Verificar autenticación
        $this->requiereAutenticacion();
        
        // Obtener la propiedad
        $propiedad = $this->db->obtenerUno(
            "SELECT i.*, u.nombre as nombre_propietario 
             FROM inmuebles i 
             LEFT JOIN usuarios u ON i.id_propietario = u.id_usuario 
             WHERE i.id_inmueble = ? AND i.id_propietario = ?",
            [$id, $_SESSION['usuario_id']]
        );
        
        if (!$propiedad) {
            $this->redireccionar('error/404');
        }
        
        // Obtener imágenes
        $imagenes = $this->db->obtenerTodos(
            "SELECT * FROM imagenes_inmueble WHERE id_inmueble = ? ORDER BY orden",
            [$id]
        );
        
        // Obtener estancias
        $estancias = $this->db->obtenerTodos(
            "SELECT * FROM estancias WHERE id_inmueble = ?",
            [$id]
        );
        
        return $this->render('cliente/propiedades/ver', [
            'titulo' => $propiedad['titulo'],
            'propiedad' => $propiedad,
            'imagenes' => $imagenes,
            'estancias' => $estancias
        ]);
    }
// En ClienteController.php

public function editar($id) {
    $this->requiereAutenticacion();
    $idCliente = $_SESSION['usuario_id'];
 
    if ($this->esPost()) {
        try {
            $this->db->getConnection()->beginTransaction();
 
            // Actualizar datos básicos
            $sql = "UPDATE inmuebles SET 
                titulo = ?, descripcion = ?, tipo_inmueble = ?,
                precio = ?, superficie = ?, num_habitaciones = ?, 
                num_baños = ?, estacionamientos = ?,
                direccion_completa = ?, ciudad = ?, estado = ?, 
                codigo_postal = ?
                WHERE id_inmueble = ? AND id_propietario = ?";
 
            $this->db->consulta($sql, [
                $this->obtenerPost('titulo'),
                $this->obtenerPost('descripcion'),
                $this->obtenerPost('tipo_inmueble'),
                $this->obtenerPost('precio'),
                $this->obtenerPost('superficie'),
                $this->obtenerPost('num_habitaciones'),
                $this->obtenerPost('num_baños'), 
                $this->obtenerPost('estacionamientos'),
                $this->obtenerPost('direccion_completa'),
                $this->obtenerPost('ciudad'),
                $this->obtenerPost('estado'),
                $this->obtenerPost('codigo_postal'),
                $id,
                $idCliente
            ]);
 
            // Procesar nuevas imágenes
            if (!empty($_FILES['imagenes']['tmp_name'][0])) {
                $this->procesarImagenes($_FILES['imagenes'], $id);
            }
 
            $this->db->getConnection()->commit();
            $this->setDato('exito', 'Propiedad actualizada exitosamente');
            $this->redireccionar('cliente/propiedades');
 
        } catch (Exception $e) {
            $this->db->getConnection()->rollBack();
            $this->setDato('error', 'Error al actualizar la propiedad: ' . $e->getMessage());
        }
    }
 
    // Obtener datos de la propiedad
    $propiedad = $this->db->obtenerUno(
        "SELECT * FROM inmuebles WHERE id_inmueble = ? AND id_propietario = ?",
        [$id, $idCliente]
    );
 
    if (!$propiedad) {
        $this->redireccionar('error/404');
    }
 
    // Obtener imágenes
    $imagenes = $this->db->obtenerTodos(
        "SELECT * FROM imagenes_inmueble WHERE id_inmueble = ? ORDER BY orden",
        [$id]
    );
 
    return $this->render('cliente/propiedades/editar', [
        'titulo' => 'Editar Propiedad',
        'propiedad' => $propiedad,
        'imagenes' => $imagenes
    ]);
 }
 
 // Método auxiliar para eliminar imagen
 public function eliminarImagen($idImagen) {
    if (!$this->esPost()) {
        return json_encode(['success' => false]);
    }
 
    try {
        $imagen = $this->db->obtenerUno(
            "SELECT im.*, i.id_propietario 
             FROM imagenes_inmueble im 
             JOIN inmuebles i ON im.id_inmueble = i.id_inmueble 
             WHERE im.id_imagen = ?", 
            [$idImagen]
        );
 
        if (!$imagen || $imagen['id_propietario'] != $_SESSION['usuario_id']) {
            throw new Exception('No autorizado');
        }
 
        $this->db->consulta(
            "DELETE FROM imagenes_inmueble WHERE id_imagen = ?",
            [$idImagen]
        );
 
        return json_encode(['success' => true]);
 
    } catch (Exception $e) {
        return json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
 }

 public function actualizar($id) {
    $this->requiereAutenticacion();
    
    if (!$this->esPost()) {
        $this->redireccionar('cliente/propiedades');
    }

    try {
        $this->db->getConnection()->beginTransaction();

        $sql = "UPDATE inmuebles SET 
                titulo = ?,
                descripcion = ?,
                tipo_inmueble = ?,
                precio = ?,
                superficie = ?,
                num_habitaciones = ?,
                num_baños = ?,
                estacionamientos = ?,
                direccion_completa = ?,
                ciudad = ?,
                estado = ?,
                codigo_postal = ?
                WHERE id_inmueble = ? AND id_propietario = ?";

        $this->db->consulta($sql, [
            $this->obtenerPost('titulo'),
            $this->obtenerPost('descripcion'),
            $this->obtenerPost('tipo_inmueble'),
            $this->obtenerPost('precio'),
            $this->obtenerPost('superficie'),
            $this->obtenerPost('num_habitaciones'),
            $this->obtenerPost('num_baños'),
            $this->obtenerPost('estacionamientos'),
            $this->obtenerPost('direccion_completa'),
            $this->obtenerPost('ciudad'),
            $this->obtenerPost('estado'),
            $this->obtenerPost('codigo_postal'),
            $id,
            $_SESSION['usuario_id']
        ]);

        $this->db->getConnection()->commit();
        $_SESSION['exito'] = 'Propiedad actualizada exitosamente';

    } catch (Exception $e) {
        $this->db->getConnection()->rollBack();
        $_SESSION['error'] = 'Error al actualizar: ' . $e->getMessage();
    }

    $this->redireccionar('cliente/propiedades');
}
// En ClienteController.php
public function perfil() {
    $usuario = $this->db->obtenerUno(
        "SELECT * FROM usuarios WHERE id_usuario = ?",
        [$_SESSION['usuario_id']]
    );

    return $this->render('cliente/perfil', [
        'titulo' => 'Mi Perfil',
        'usuario' => $usuario
    ]);
}

public function actualizarPerfil() {
    if (!$this->esPost()) {
        $this->redireccionar('cliente/perfil');
    }

    try {
        $datos = [
            'nombre' => $this->obtenerPost('nombre'),
            'apellidos' => $this->obtenerPost('apellidos'),
            'telefono' => $this->obtenerPost('telefono')
        ];

        $sql = "UPDATE usuarios SET nombre = ?, apellidos = ?, telefono = ?";
        $params = [$datos['nombre'], $datos['apellidos'], $datos['telefono']];

        if ($password = $this->obtenerPost('password')) {
            $sql .= ", password = ?";
            $params[] = password_hash($password, PASSWORD_DEFAULT);
        }

        $sql .= " WHERE id_usuario = ?";
        $params[] = $_SESSION['usuario_id'];

        $this->db->consulta($sql, $params);
        $_SESSION['exito'] = 'Perfil actualizado exitosamente';

    } catch (Exception $e) {
        $_SESSION['error'] = 'Error al actualizar el perfil';
    }

    $this->redireccionar('cliente/perfil');
}
}
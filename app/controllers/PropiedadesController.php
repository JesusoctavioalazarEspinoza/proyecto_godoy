// app/controllers/PropiedadesController.php
<?php
class PropiedadesController extends Controller {
    public function index() {
        // Obtener filtros de búsqueda
        $tipo = $this->obtenerGet('tipo');
        $ciudad = $this->obtenerGet('ciudad');
        $precioMin = $this->obtenerGet('precio_min');
        $precioMax = $this->obtenerGet('precio_max');
        
        // Construir consulta base
        $sql = "SELECT i.*, u.nombre as nombre_asesor, u.apellidos as apellidos_asesor,
                (SELECT url_imagen FROM imagenes_inmueble WHERE id_inmueble = i.id_inmueble LIMIT 1) as imagen
                FROM inmuebles i 
                LEFT JOIN usuarios u ON i.id_asesor = u.id_usuario 
                WHERE i.estado_inmueble = 'disponible'";
        $params = [];
        
        // Agregar filtros
        if ($tipo) {
            $sql .= " AND i.tipo_inmueble = ?";
            $params[] = $tipo;
        }
        if ($ciudad) {
            $sql .= " AND i.ciudad LIKE ?";
            $params[] = "%$ciudad%";
        }
        if ($precioMin) {
            $sql .= " AND i.precio >= ?";
            $params[] = $precioMin;
        }
        if ($precioMax) {
            $sql .= " AND i.precio <= ?";
            $params[] = $precioMax;
        }
        
        // Obtener propiedades
        $propiedades = $this->db->obtenerTodos($sql, $params);
        
        // Obtener ciudades y tipos para filtros
        $ciudades = $this->db->obtenerTodos(
            "SELECT DISTINCT ciudad FROM inmuebles WHERE estado_inmueble = 'disponible' ORDER BY ciudad"
        );
        
        return $this->render('propiedades/index', [
            'titulo' => 'Propiedades Disponibles',
            'propiedades' => $propiedades,
            'ciudades' => $ciudades,
            'filtros' => [
                'tipo' => $tipo,
                'ciudad' => $ciudad,
                'precio_min' => $precioMin,
                'precio_max' => $precioMax
            ]
        ]);
    }

    public function ver($id) {
        // Obtener detalles de la propiedad
        $propiedad = $this->db->obtenerUno(
            "SELECT i.*, u.nombre as nombre_asesor, u.apellidos as apellidos_asesor,
                    u.email as email_asesor, u.telefono as telefono_asesor
             FROM inmuebles i 
             LEFT JOIN usuarios u ON i.id_asesor = u.id_usuario 
             WHERE i.id_inmueble = ? AND i.estado_inmueble = 'disponible'",
            [$id]
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
            "SELECT e.*, 
                    (SELECT url_imagen FROM imagenes_estancia WHERE id_estancia = e.id_estancia LIMIT 1) as imagen
             FROM estancias e 
             WHERE e.id_inmueble = ? 
             ORDER BY e.tipo_estancia",
            [$id]
        );

        // Obtener propiedades similares
        $similares = $this->db->obtenerTodos(
            "SELECT i.*, 
                    (SELECT url_imagen FROM imagenes_inmueble WHERE id_inmueble = i.id_inmueble LIMIT 1) as imagen
             FROM inmuebles i 
             WHERE i.id_inmueble != ? 
             AND i.tipo_inmueble = ? 
             AND i.estado_inmueble = 'disponible'
             AND i.precio BETWEEN ? AND ?
             LIMIT 3",
            [
                $id,
                $propiedad['tipo_inmueble'],
                $propiedad['precio'] * 0.8, // 20% menos
                $propiedad['precio'] * 1.2  // 20% más
            ]
        );

        return $this->render('propiedades/ver', [
            'titulo' => $propiedad['titulo'],
            'propiedad' => $propiedad,
            'imagenes' => $imagenes,
            'estancias' => $estancias,
            'similares' => $similares
        ]);
    }

    public function contactar($id) {
        if (!$this->esPost()) {
            $this->redireccionar('propiedades/ver/' . $id);
        }

        try {
            $nombre = $this->obtenerPost('nombre');
            $email = $this->obtenerPost('email');
            $telefono = $this->obtenerPost('telefono');
            $mensaje = $this->obtenerPost('mensaje');

            // Validar datos
            if (empty($nombre) || empty($email) || empty($mensaje)) {
                throw new Exception('Todos los campos son obligatorios');
            }

            // Obtener información de la propiedad y asesor
            $propiedad = $this->db->obtenerUno(
                "SELECT i.*, u.email as email_asesor 
                 FROM inmuebles i 
                 LEFT JOIN usuarios u ON i.id_asesor = u.id_usuario 
                 WHERE i.id_inmueble = ?",
                [$id]
            );

            if (!$propiedad) {
                throw new Exception('Propiedad no encontrada');
            }

            // Registrar contacto
            $sql = "INSERT INTO contactos_propiedad (
                        id_inmueble, nombre, email, telefono, 
                        mensaje, fecha_contacto
                    ) VALUES (?, ?, ?, ?, ?, NOW())";
            
            $this->db->consulta($sql, [
                $id, $nombre, $email, $telefono, $mensaje
            ]);

            // Enviar email al asesor
            $emailAsesor = $propiedad['tipo_servicio'] === 'con_asesoria' ? 
                          $propiedad['email_asesor'] : 'ventas@godoyhouses.com';

            mail(
                $emailAsesor,
                "Nuevo contacto para propiedad: " . $propiedad['titulo'],
                "Nombre: $nombre\nEmail: $email\nTeléfono: $telefono\n\nMensaje:\n$mensaje",
                "From: contacto@godoyhouses.com"
            );

            $this->setDato('exito', 'Mensaje enviado correctamente');

        } catch (Exception $e) {
            $this->setDato('error', $e->getMessage());
        }

        $this->redireccionar('propiedades/ver/' . $id);
    }

    public function buscar() {
        $termino = $this->obtenerGet('q');
        
        if (empty($termino)) {
            $this->redireccionar('propiedades');
        }

        $sql = "SELECT i.*, 
                (SELECT url_imagen FROM imagenes_inmueble WHERE id_inmueble = i.id_inmueble LIMIT 1) as imagen 
                FROM inmuebles i 
                WHERE i.estado_inmueble = 'disponible' 
                AND (
                    i.titulo LIKE ? OR 
                    i.descripcion LIKE ? OR 
                    i.direccion_completa LIKE ? OR 
                    i.ciudad LIKE ?
                )";
        
        $termino = "%$termino%";
        $propiedades = $this->db->obtenerTodos($sql, [
            $termino, $termino, $termino, $termino
        ]);

        return $this->render('propiedades/buscar', [
            'titulo' => 'Resultados de búsqueda',
            'termino' => $this->obtenerGet('q'),
            'propiedades' => $propiedades
        ]);
    }
}
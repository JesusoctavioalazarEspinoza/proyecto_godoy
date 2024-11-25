// app/controllers/AsesorController.php
<?php
class AsesorController extends Controller {
    public function __construct() {
        parent::__construct();
        // Verificar que el usuario sea asesor
        $this->requiereAutenticacion();
        $this->setLayout('layouts/asesor');
        $this->validarRol('asesor');
    }

    public function dashboard() {
        // Obtener estadísticas del asesor
        $id_asesor = $_SESSION['usuario_id'];
        $stats = $this->obtenerEstadisticas($id_asesor);
        
        return $this->render('asesor/dashboard', [
            'titulo' => 'Panel del Asesor',
            'stats' => $stats
        ]);
    }

    public function propiedades() {
        $id_asesor = $_SESSION['usuario_id'];
        $propiedades = $this->db->obtenerTodos(
            "SELECT i.*, u.nombre as nombre_propietario 
             FROM inmuebles i 
             JOIN usuarios u ON i.id_propietario = u.id_usuario 
             WHERE i.id_asesor = ?",
            [$id_asesor]
        );
        
        return $this->render('asesor/propiedades', [
            'titulo' => 'Mis Propiedades',
            'propiedades' => $propiedades
        ]);
    }

    public function citas() {
        $id_asesor = $_SESSION['usuario_id'];
        $citas = $this->db->obtenerTodos(
            "SELECT c.*, u.nombre as nombre_cliente, i.titulo as titulo_propiedad 
             FROM citas c 
             JOIN usuarios u ON c.id_cliente = u.id_usuario 
             JOIN inmuebles i ON c.id_inmueble = i.id_inmueble 
             WHERE c.id_asesor = ? 
             ORDER BY c.fecha_hora ASC",
            [$id_asesor]
        );
        
        return $this->render('asesor/citas', [
            'titulo' => 'Gestión de Citas',
            'citas' => $citas
        ]);
    }

    public function clientes() {
        $id_asesor = $_SESSION['usuario_id'];
        $clientes = $this->db->obtenerTodos(
            "SELECT DISTINCT u.*, 
                    (SELECT COUNT(*) FROM contratos WHERE id_propietario = u.id_usuario) as total_contratos,
                    (SELECT COUNT(*) FROM citas WHERE id_cliente = u.id_usuario) as total_citas
             FROM usuarios u 
             JOIN contratos c ON u.id_usuario = c.id_propietario 
             WHERE c.id_asesor = ?",
            [$id_asesor]
        );
        
        return $this->render('asesor/clientes', [
            'titulo' => 'Mis Clientes',
            'clientes' => $clientes
        ]);
    }

    private function obtenerEstadisticas($id_asesor) {
        $stats = [];
        
        // Total de propiedades asignadas
        $stats['total_propiedades'] = $this->db->obtenerUno(
            "SELECT COUNT(*) as total FROM inmuebles WHERE id_asesor = ?",
            [$id_asesor]
        )['total'];
        
        // Propiedades por estado
        $stats['propiedades_estado'] = $this->db->obtenerTodos(
            "SELECT estado_inmueble, COUNT(*) as total 
             FROM inmuebles 
             WHERE id_asesor = ? 
             GROUP BY estado_inmueble",
            [$id_asesor]
        );
        
        // Citas próximas
        $stats['proximas_citas'] = $this->db->obtenerTodos(
            "SELECT c.*, u.nombre as nombre_cliente, i.titulo as titulo_propiedad 
             FROM citas c 
             JOIN usuarios u ON c.id_cliente = u.id_usuario 
             JOIN inmuebles i ON c.id_inmueble = i.id_inmueble 
             WHERE c.id_asesor = ? AND c.fecha_hora >= NOW() 
             ORDER BY c.fecha_hora ASC 
             LIMIT 5",
            [$id_asesor]
        );
        
        // Total de clientes activos
        $stats['total_clientes'] = $this->db->obtenerUno(
            "SELECT COUNT(DISTINCT id_propietario) as total 
             FROM contratos 
             WHERE id_asesor = ? AND estado_contrato = 'en_proceso'",
            [$id_asesor]
        )['total'];
        
        return $stats;
    }
}
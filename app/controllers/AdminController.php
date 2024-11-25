// app/controllers/AdminController.php
<?php
class AdminController extends Controller {
    public function __construct() {
        parent::__construct();
        // Verificar que el usuario sea administrador
        $this->requiereAutenticacion();
        $this->setLayout('layouts/admin');
        $this->validarRol('administrador');
        // Verificar si el usuario está autenticado y es administrador
        $this->verificarSesion();
    }
    private function verificarSesion() {
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'administrador') {
            // Guardar mensaje de error en la sesión
            $_SESSION['error'] = 'Debe iniciar sesión como administrador';
            // Redirigir al login
            header('Location: ' . url('auth/login'));
            exit;
        }
    }

    public function dashboard() {
        // Obtener estadísticas para el dashboard
        $stats = $this->obtenerEstadisticas();
        
        return $this->render('admin/dashboard', [
            'titulo' => 'Panel de Administración',
            'stats' => $stats
        ]);
    }

    public function asesores() {
        $asesores = $this->db->obtenerTodos(
            "SELECT * FROM usuarios WHERE tipo_usuario = 'asesor'"
        );
        
        return $this->render('admin/asesores', [
            'titulo' => 'Gestión de Asesores',
            'asesores' => $asesores
        ]);
    }

    public function clientes() {
        $clientes = $this->db->obtenerTodos(
            "SELECT * FROM usuarios WHERE tipo_usuario = 'cliente'"
        );
        
        return $this->render('admin/clientes', [
            'titulo' => 'Gestión de Clientes',
            'clientes' => $clientes
        ]);
    }

    private function obtenerEstadisticas() {
        $stats = [];
        
        // Total de propiedades
        $stats['total_propiedades'] = $this->db->obtenerUno(
            "SELECT COUNT(*) as total FROM inmuebles"
        )['total'];
        
        // Propiedades por estado
        $stats['propiedades_estado'] = $this->db->obtenerTodos(
            "SELECT estado_inmueble, COUNT(*) as total 
                FROM inmuebles 
                GROUP BY estado_inmueble"
        );
        
        // Total de usuarios por tipo
        $stats['usuarios_tipo'] = $this->db->obtenerTodos(
            "SELECT tipo_usuario, COUNT(*) as total 
                FROM usuarios 
                GROUP BY tipo_usuario"
        );
        
        // Últimas transacciones
        $stats['ultimas_transacciones'] = $this->db->obtenerTodos(
            "SELECT c.*, i.titulo as propiedad, 
                    u1.nombre as propietario, 
                    u2.nombre as comprador 
                FROM contratos c 
                JOIN inmuebles i ON c.id_inmueble = i.id_inmueble 
                JOIN usuarios u1 ON c.id_propietario = u1.id_usuario 
                JOIN usuarios u2 ON c.id_comprador = u2.id_usuario 
                ORDER BY c.fecha_inicio DESC 
                LIMIT 5"
        );
        
        return $stats;
    }
}
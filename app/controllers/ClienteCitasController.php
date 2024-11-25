// app/controllers/ClienteCitasController.php
<?php
class ClienteCitasController extends Controller {
    public function __construct() {
        parent::__construct();
        $this->requiereAutenticacion();
        $this->setLayout('layouts/cliente');
    }
    

    public function misCitas() {
        $id_cliente = $_SESSION['usuario_id'];
        $citas = $this->db->obtenerTodos(
            "SELECT c.*, i.titulo as titulo_propiedad, 
                    u.nombre as nombre_asesor, u.apellidos as apellidos_asesor
             FROM citas c
             JOIN inmuebles i ON c.id_inmueble = i.id_inmueble
             JOIN usuarios u ON c.id_asesor = u.id_usuario
             WHERE c.id_cliente = ?
             ORDER BY c.fecha_hora DESC",
            [$id_cliente]
        );

        return $this->render('cliente/citas/index', [
            'titulo' => 'Mis Citas',
            'citas' => $citas
        ]);
    }

    public function verCita($idCita) {
        $id_cliente = $_SESSION['usuario_id'];
        
        // Obtener detalles de la cita
        $cita = $this->db->obtenerUno(
            "SELECT c.*, i.titulo as titulo_propiedad, 
                    i.direccion_completa, i.precio,
                    u.nombre as nombre_asesor, u.apellidos as apellidos_asesor,
                    u.email as email_asesor, u.telefono as telefono_asesor
             FROM citas c
             JOIN inmuebles i ON c.id_inmueble = i.id_inmueble
             JOIN usuarios u ON c.id_asesor = u.id_usuario
             WHERE c.id_cita = ? AND c.id_cliente = ?",
            [$idCita, $id_cliente]
        );

        if (!$cita) {
            $this->redireccionar('error/404');
        }

        // Obtener seguimientos de la cita
        $seguimientos = $this->db->obtenerTodos(
            "SELECT * FROM seguimientos_cita 
             WHERE id_cita = ? 
             ORDER BY fecha DESC",
            [$idCita]
        );

        return $this->render('cliente/citas/ver', [
            'titulo' => 'Detalles de la Cita',
            'cita' => $cita,
            'seguimientos' => $seguimientos
        ]);
    }

    public function confirmar($idCita) {
        if (!$this->esPost()) {
            $this->redireccionar('cliente/citas');
        }

        try {
            $id_cliente = $_SESSION['usuario_id'];
            
            // Verificar que la cita pertenezca al cliente
            $cita = $this->db->obtenerUno(
                "SELECT * FROM citas WHERE id_cita = ? AND id_cliente = ?",
                [$idCita, $id_cliente]
            );

            if (!$cita) {
                throw new Exception('Cita no encontrada');
            }

            // Actualizar estado de la cita
            $this->db->consulta(
                "UPDATE citas SET confirmada = 1 WHERE id_cita = ?",
                [$idCita]
            );

            // Registrar confirmación en seguimientos
            $this->db->consulta(
                "INSERT INTO seguimientos_cita (id_cita, nota, fecha) 
                 VALUES (?, 'Cliente confirmó asistencia', NOW())",
                [$idCita]
            );

            $this->setDato('exito', 'Cita confirmada exitosamente');

        } catch (Exception $e) {
            $this->setDato('error', 'Error al confirmar la cita: ' . $e->getMessage());
        }

        $this->redireccionar('cliente/citas');
    }

    public function cancelar($idCita) {
        if (!$this->esPost()) {
            $this->redireccionar('cliente/citas');
        }

        try {
            $id_cliente = $_SESSION['usuario_id'];
            $motivo = $this->obtenerPost('motivo');
            
            // Verificar que la cita pertenezca al cliente
            $cita = $this->db->obtenerUno(
                "SELECT * FROM citas WHERE id_cita = ? AND id_cliente = ?",
                [$idCita, $id_cliente]
            );

            if (!$cita) {
                throw new Exception('Cita no encontrada');
            }

            // Actualizar estado de la cita
            $this->db->consulta(
                "UPDATE citas SET estado = 'cancelada' WHERE id_cita = ?",
                [$idCita]
            );

            // Registrar cancelación en seguimientos
            $this->db->consulta(
                "INSERT INTO seguimientos_cita (id_cita, nota, fecha) 
                 VALUES (?, ?, NOW())",
                [$idCita, 'Cita cancelada por el cliente. Motivo: ' . $motivo]
            );

            $this->setDato('exito', 'Cita cancelada exitosamente');

        } catch (Exception $e) {
            $this->setDato('error', 'Error al cancelar la cita: ' . $e->getMessage());
        }

        $this->redireccionar('cliente/citas');
    }

    public function reprogramar($idCita) {
        if (!$this->esPost()) {
            return $this->render('cliente/citas/reprogramar', [
                'titulo' => 'Reprogramar Cita',
                'cita' => $this->obtenerCita($idCita)
            ]);
        }

        try {
            $id_cliente = $_SESSION['usuario_id'];
            $nueva_fecha = $this->obtenerPost('fecha');
            $nueva_hora = $this->obtenerPost('hora');
            
            // Validar fecha
            $fecha_hora = date('Y-m-d H:i:s', strtotime("$nueva_fecha $nueva_hora"));
            
            if (strtotime($fecha_hora) < time()) {
                throw new Exception('La fecha y hora deben ser futuras');
            }

            // Actualizar fecha de la cita
            $this->db->consulta(
                "UPDATE citas SET fecha_hora = ? WHERE id_cita = ? AND id_cliente = ?",
                [$fecha_hora, $idCita, $id_cliente]
            );

            $this->setDato('exito', 'Cita reprogramada exitosamente');
            $this->redireccionar('cliente/citas');

        } catch (Exception $e) {
            $this->setDato('error', $e->getMessage());
            $this->redireccionar('cliente/citas/reprogramar/' . $idCita);
        }
    }

    private function obtenerCita($idCita) {
        return $this->db->obtenerUno(
            "SELECT c.*, i.titulo as titulo_propiedad,
                    u.nombre as nombre_asesor, u.apellidos as apellidos_asesor
             FROM citas c
             JOIN inmuebles i ON c.id_inmueble = i.id_inmueble
             JOIN usuarios u ON c.id_asesor = u.id_usuario
             WHERE c.id_cita = ? AND c.id_cliente = ?",
            [$idCita, $_SESSION['usuario_id']]
        );
    }
}
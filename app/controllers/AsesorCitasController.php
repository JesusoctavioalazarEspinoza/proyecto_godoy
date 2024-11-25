// app/controllers/AsesorCitasController.php
<?php
class AsesorCitasController extends Controller {
    public function __construct() {
        parent::__construct();
        $this->requiereAutenticacion();
        $this->validarRol('asesor');
    }

    public function nueva() {
        // Obtener propiedades disponibles del asesor
        $propiedades = $this->db->obtenerTodos(
            "SELECT id_inmueble, titulo 
             FROM inmuebles 
             WHERE id_asesor = ? AND estado_inmueble = 'disponible'",
            [$_SESSION['usuario_id']]
        );

        // Obtener clientes del asesor
        $clientes = $this->db->obtenerTodos(
            "SELECT DISTINCT u.id_usuario, u.nombre, u.apellidos
             FROM usuarios u
             JOIN contratos c ON u.id_usuario = c.id_propietario
             WHERE c.id_asesor = ?",
            [$_SESSION['usuario_id']]
        );

        return $this->render('asesor/citas/nueva', [
            'titulo' => 'Nueva Cita',
            'propiedades' => $propiedades,
            'clientes' => $clientes
        ]);
    }

    public function guardar() {
        if (!$this->esPost()) {
            $this->redireccionar('asesor/citas');
        }

        try {
            $fecha_hora = date('Y-m-d H:i:s', strtotime($this->obtenerPost('fecha') . ' ' . $this->obtenerPost('hora')));
            
            // Validar que la fecha no sea en el pasado
            if (strtotime($fecha_hora) < time()) {
                throw new Exception('La fecha de la cita no puede ser en el pasado');
            }

            // Validar disponibilidad del horario
            $citaExistente = $this->db->obtenerUno(
                "SELECT id_cita FROM citas 
                 WHERE id_asesor = ? AND fecha_hora = ? AND estado != 'cancelada'",
                [$_SESSION['usuario_id'], $fecha_hora]
            );

            if ($citaExistente) {
                throw new Exception('Ya tiene una cita programada para este horario');
            }

            // Insertar la cita
            $sql = "INSERT INTO citas (
                        id_inmueble, id_cliente, id_asesor, fecha_hora,
                        tipo_cita, estado, lugar_reunion, notas
                    ) VALUES (?, ?, ?, ?, ?, 'programada', ?, ?)";

            $this->db->consulta($sql, [
                $this->obtenerPost('id_inmueble'),
                $this->obtenerPost('id_cliente'),
                $_SESSION['usuario_id'],
                $fecha_hora,
                $this->obtenerPost('tipo_cita'),
                $this->obtenerPost('lugar_reunion'),
                $this->obtenerPost('notas')
            ]);

            // Enviar notificación al cliente
            $this->enviarNotificacionCita($this->obtenerPost('id_cliente'), $fecha_hora);

            $this->setDato('exito', 'Cita programada exitosamente');
            $this->redireccionar('asesor/citas');

        } catch (Exception $e) {
            $this->setDato('error', $e->getMessage());
            $this->redireccionar('asesor/citas/nueva');
        }
    }

    public function actualizar($idCita) {
        if (!$this->esPost()) {
            return $this->render('asesor/citas/editar', [
                'titulo' => 'Editar Cita',
                'cita' => $this->obtenerCita($idCita)
            ]);
        }

        try {
            $estado = $this->obtenerPost('estado');
            $notas = $this->obtenerPost('notas');

            $sql = "UPDATE citas SET estado = ?, notas = ? WHERE id_cita = ? AND id_asesor = ?";
            $this->db->consulta($sql, [
                $estado,
                $notas,
                $idCita,
                $_SESSION['usuario_id']
            ]);

            $this->setDato('exito', 'Cita actualizada exitosamente');
            $this->redireccionar('asesor/citas');

        } catch (Exception $e) {
            $this->setDato('error', $e->getMessage());
            $this->redireccionar('asesor/citas/editar/' . $idCita);
        }
    }

    private function obtenerCita($idCita) {
        return $this->db->obtenerUno(
            "SELECT c.*, u.nombre as nombre_cliente, u.apellidos as apellidos_cliente,
                    i.titulo as titulo_propiedad
             FROM citas c
             JOIN usuarios u ON c.id_cliente = u.id_usuario
             JOIN inmuebles i ON c.id_inmueble = i.id_inmueble
             WHERE c.id_cita = ? AND c.id_asesor = ?",
            [$idCita, $_SESSION['usuario_id']]
        );
    }

    private function enviarNotificacionCita($idCliente, $fechaHora) {
        // Obtener información del cliente
        $cliente = $this->db->obtenerUno(
            "SELECT nombre, email FROM usuarios WHERE id_usuario = ?",
            [$idCliente]
        );

        // Construir el mensaje
        $mensaje = "Estimado/a {$cliente['nombre']},\n\n";
        $mensaje .= "Se ha programado una cita para el " . date('d/m/Y', strtotime($fechaHora));
        $mensaje .= " a las " . date('H:i', strtotime($fechaHora)) . ".\n\n";
        $mensaje .= "Por favor, confirme su asistencia.\n\n";
        $mensaje .= "Saludos cordiales,\n";
        $mensaje .= "Godoy Houses";

        // Enviar el correo
        mail(
            $cliente['email'],
            "Nueva Cita Programada - Godoy Houses",
            $mensaje,
            "From: notificaciones@godoyhouses.com"
        );
    }
}
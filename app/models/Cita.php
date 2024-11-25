// app/models/Cita.php
<?php
class Cita {
    private $db;
    private $id;
    private $idInmueble;
    private $idCliente;
    private $idAsesor;
    private $fechaHora;
    private $estado;
    
    public function __construct() {
        $this->db = Database::obtenerInstancia();
    }
    
    public static function obtenerPorId($id) {
        $db = Database::obtenerInstancia();
        $cita = $db->obtenerUno(
            "SELECT * FROM citas WHERE id_cita = ?",
            [$id]
        );
        
        if ($cita) {
            $obj = new self();
            $obj->cargarDatos($cita);
            return $obj;
        }
        return null;
    }
    
    public function crear($datos) {
        $sql = "INSERT INTO citas (
                    id_inmueble, id_cliente, id_asesor, 
                    fecha_hora, tipo_cita, estado, 
                    lugar_reunion, notas
                ) VALUES (?, ?, ?, ?, ?, 'programada', ?, ?)";
                
        $this->db->consulta($sql, [
            $datos['id_inmueble'],
            $datos['id_cliente'],
            $datos['id_asesor'],
            $datos['fecha_hora'],
            $datos['tipo_cita'],
            $datos['lugar_reunion'],
            $datos['notas'] ?? null
        ]);
        
        return $this->db->getConnection()->lastInsertId();
    }
    
    public function actualizar($datos) {
        $sql = "UPDATE citas SET 
                fecha_hora = ?, tipo_cita = ?, 
                lugar_reunion = ?, notas = ?
                WHERE id_cita = ?";
                
        return $this->db->consulta($sql, [
            $datos['fecha_hora'],
            $datos['tipo_cita'],
            $datos['lugar_reunion'],
            $datos['notas'],
            $this->id
        ]);
    }
    
    public function cambiarEstado($nuevoEstado) {
        return $this->db->consulta(
            "UPDATE citas SET estado = ? WHERE id_cita = ?",
            [$nuevoEstado, $this->id]
        );
    }
    
    public function confirmar() {
        return $this->db->consulta(
            "UPDATE citas SET confirmada = 1 WHERE id_cita = ?",
            [$this->id]
        );
    }
    
    public function agregarSeguimiento($nota) {
        return $this->db->consulta(
            "INSERT INTO seguimientos_cita (id_cita, nota, fecha) VALUES (?, ?, NOW())",
            [$this->id, $nota]
        );
    }
    
    public function obtenerSeguimientos() {
        return $this->db->obtenerTodos(
            "SELECT * FROM seguimientos_cita WHERE id_cita = ? ORDER BY fecha DESC",
            [$this->id]
        );
    }
    
    private function cargarDatos($datos) {
        $this->id = $datos['id_cita'];
        $this->idInmueble = $datos['id_inmueble'];
        $this->idCliente = $datos['id_cliente'];
        $this->idAsesor = $datos['id_asesor'];
        $this->fechaHora = $datos['fecha_hora'];
        $this->estado = $datos['estado'];
    }

    // Getters
    public function getId() { return $this->id; }
    public function getIdInmueble() { return $this->idInmueble; }
    public function getIdCliente() { return $this->idCliente; }
    public function getIdAsesor() { return $this->idAsesor; }
    public function getFechaHora() { return $this->fechaHora; }
    public function getEstado() { return $this->estado; }
}
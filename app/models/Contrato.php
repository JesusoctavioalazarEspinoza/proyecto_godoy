// app/models/Contrato.php
<?php
class Contrato {
    private $db;
    private $id;
    private $idInmueble;
    private $idPropietario;
    private $idComprador;
    private $idAsesor;
    private $tipoContrato;
    private $estado;
    private $precioAcordado;
    private $comision;
    
    public function __construct() {
        $this->db = Database::obtenerInstancia();
    }
    
    public static function obtenerPorId($id) {
        $db = Database::obtenerInstancia();
        $contrato = $db->obtenerUno(
            "SELECT c.*, i.precio as precio_original 
             FROM contratos c
             JOIN inmuebles i ON c.id_inmueble = i.id_inmueble
             WHERE c.id_contrato = ?",
            [$id]
        );
        
        if ($contrato) {
            $obj = new self();
            $obj->cargarDatos($contrato);
            return $obj;
        }
        return null;
    }
    
    public function crear($datos) {
        try {
            $this->db->getConnection()->beginTransaction();
            
            // Insertar contrato
            $sql = "INSERT INTO contratos (
                        id_inmueble, id_propietario, id_comprador, 
                        id_asesor, tipo_contrato, estado_contrato,
                        precio_acordado, comision, fecha_inicio
                    ) VALUES (?, ?, ?, ?, ?, 'en_proceso', ?, ?, NOW())";
                    
            $this->db->consulta($sql, [
                $datos['id_inmueble'],
                $datos['id_propietario'],
                $datos['id_comprador'],
                $datos['id_asesor'],
                $datos['tipo_contrato'],
                $datos['precio_acordado'],
                $this->calcularComision($datos['precio_acordado'], $datos['tipo_contrato'])
            ]);
            
            $idContrato = $this->db->getConnection()->lastInsertId();
            
            // Actualizar estado del inmueble
            $this->db->consulta(
                "UPDATE inmuebles SET estado_inmueble = 'en_proceso' WHERE id_inmueble = ?",
                [$datos['id_inmueble']]
            );
            
            $this->db->getConnection()->commit();
            return $idContrato;
            
        } catch (Exception $e) {
            $this->db->getConnection()->rollBack();
            throw $e;
        }
    }
    
    public function actualizar($datos) {
        $sql = "UPDATE contratos SET 
                precio_acordado = ?,
                comision = ?,
                estado_contrato = ?
                WHERE id_contrato = ?";
                
        return $this->db->consulta($sql, [
            $datos['precio_acordado'],
            $this->calcularComision($datos['precio_acordado'], $this->tipoContrato),
            $datos['estado_contrato'],
            $this->id
        ]);
    }
    
    public function firmar($fechaFirma = null) {
        try {
            $this->db->getConnection()->beginTransaction();
            
            // Actualizar contrato
            $sql = "UPDATE contratos SET 
                    estado_contrato = 'firmado',
                    fecha_firma = ?
                    WHERE id_contrato = ?";
                    
            $this->db->consulta($sql, [
                $fechaFirma ?? date('Y-m-d H:i:s'),
                $this->id
            ]);
            
            // Actualizar estado del inmueble
            $this->db->consulta(
                "UPDATE inmuebles SET estado_inmueble = 'vendido' WHERE id_inmueble = ?",
                [$this->idInmueble]
            );
            
            $this->db->getConnection()->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->getConnection()->rollBack();
            throw $e;
        }
    }
    
    public function cancelar($motivo) {
        try {
            $this->db->getConnection()->beginTransaction();
            
            // Actualizar contrato
            $sql = "UPDATE contratos SET 
                    estado_contrato = 'cancelado',
                    motivo_cancelacion = ?,
                    fecha_cancelacion = NOW()
                    WHERE id_contrato = ?";
                    
            $this->db->consulta($sql, [$motivo, $this->id]);
            
            // Actualizar estado del inmueble
            $this->db->consulta(
                "UPDATE inmuebles SET estado_inmueble = 'disponible' WHERE id_inmueble = ?",
                [$this->idInmueble]
            );
            
            $this->db->getConnection()->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->getConnection()->rollBack();
            throw $e;
        }
    }
    
    public function agregarDocumento($tipo, $url, $descripcion = null) {
        return $this->db->consulta(
            "INSERT INTO documentos_contrato (
                id_contrato, tipo_documento, url_documento, 
                descripcion, fecha_subida
            ) VALUES (?, ?, ?, ?, NOW())",
            [$this->id, $tipo, $url, $descripcion]
        );
    }
    
    public function obtenerDocumentos() {
        return $this->db->obtenerTodos(
            "SELECT * FROM documentos_contrato WHERE id_contrato = ? ORDER BY fecha_subida DESC",
            [$this->id]
        );
    }
    
    public function agregarPago($monto, $tipo, $referencia = null) {
        return $this->db->consulta(
            "INSERT INTO pagos_contrato (
                id_contrato, monto, tipo_pago, 
                referencia, fecha_pago
            ) VALUES (?, ?, ?, ?, NOW())",
            [$this->id, $monto, $tipo, $referencia]
        );
    }
    
    public function obtenerPagos() {
        return $this->db->obtenerTodos(
            "SELECT * FROM pagos_contrato WHERE id_contrato = ? ORDER BY fecha_pago DESC",
            [$this->id]
        );
    }
    
    public function agregarHistorial($accion, $detalles = null) {
        return $this->db->consulta(
            "INSERT INTO historial_contrato (
                id_contrato, accion, detalles, fecha_registro
            ) VALUES (?, ?, ?, NOW())",
            [$this->id, $accion, $detalles]
        );
    }
    
    public function obtenerHistorial() {
        return $this->db->obtenerTodos(
            "SELECT * FROM historial_contrato WHERE id_contrato = ? ORDER BY fecha_registro DESC",
            [$this->id]
        );
    }
    
    private function calcularComision($precioAcordado, $tipoContrato) {
        // Porcentajes de comisión según tipo de contrato
        $porcentajes = [
            'venta_con_asesoria' => 0.05,  // 5% para ventas con asesoría
            'venta_sin_asesoria' => 0.03   // 3% para ventas sin asesoría
        ];
        
        $porcentaje = $porcentajes[$tipoContrato] ?? 0;
        return $precioAcordado * $porcentaje;
    }
    
    public function verificarDisponibilidad() {
        // Verificar si el inmueble está disponible
        $inmueble = $this->db->obtenerUno(
            "SELECT estado_inmueble FROM inmuebles WHERE id_inmueble = ?",
            [$this->idInmueble]
        );
        
        return $inmueble && $inmueble['estado_inmueble'] === 'disponible';
    }
    
    private function cargarDatos($datos) {
        $this->id = $datos['id_contrato'];
        $this->idInmueble = $datos['id_inmueble'];
        $this->idPropietario = $datos['id_propietario'];
        $this->idComprador = $datos['id_comprador'];
        $this->idAsesor = $datos['id_asesor'];
        $this->tipoContrato = $datos['tipo_contrato'];
        $this->estado = $datos['estado_contrato'];
        $this->precioAcordado = $datos['precio_acordado'];
        $this->comision = $datos['comision'];
    }

    // Getters
    public function getId() { return $this->id; }
    public function getIdInmueble() { return $this->idInmueble; }
    public function getIdPropietario() { return $this->idPropietario; }
    public function getIdComprador() { return $this->idComprador; }
    public function getIdAsesor() { return $this->idAsesor; }
    public function getTipoContrato() { return $this->tipoContrato; }
    public function getEstado() { return $this->estado; }
    public function getPrecioAcordado() { return $this->precioAcordado; }
    public function getComision() { return $this->comision; }
    
    // Métodos adicionales
    public static function obtenerContratosPorAsesor($idAsesor, $estado = null) {
        $db = Database::obtenerInstancia();
        $sql = "SELECT c.*, i.titulo as propiedad, 
                       u1.nombre as nombre_propietario,
                       u2.nombre as nombre_comprador
                FROM contratos c
                JOIN inmuebles i ON c.id_inmueble = i.id_inmueble
                JOIN usuarios u1 ON c.id_propietario = u1.id_usuario
                JOIN usuarios u2 ON c.id_comprador = u2.id_usuario
                WHERE c.id_asesor = ?";
        
        $params = [$idAsesor];
        
        if ($estado) {
            $sql .= " AND c.estado_contrato = ?";
            $params[] = $estado;
        }
        
        $sql .= " ORDER BY c.fecha_inicio DESC";
        
        return $db->obtenerTodos($sql, $params);
    }
    
    public static function obtenerContratosPorCliente($idCliente) {
        $db = Database::obtenerInstancia();
        return $db->obtenerTodos(
            "SELECT c.*, i.titulo as propiedad, 
                    u.nombre as nombre_asesor
             FROM contratos c
             JOIN inmuebles i ON c.id_inmueble = i.id_inmueble
             LEFT JOIN usuarios u ON c.id_asesor = u.id_usuario
             WHERE c.id_propietario = ? OR c.id_comprador = ?
             ORDER BY c.fecha_inicio DESC",
            [$idCliente, $idCliente]
        );
    }
    
}
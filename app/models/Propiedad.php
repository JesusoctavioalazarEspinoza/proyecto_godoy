// app/models/Propiedad.php
<?php
class Propiedad {
    private $db;
    private $id;
    private $titulo;
    private $descripcion;
    private $tipo;
    private $precio;
    private $estado;
    
    public function __construct() {
        $this->db = Database::obtenerInstancia();
    }
    
    public static function obtenerPorId($id) {
        $db = Database::obtenerInstancia();
        $propiedad = $db->obtenerUno(
            "SELECT * FROM inmuebles WHERE id_inmueble = ?",
            [$id]
        );
        
        if ($propiedad) {
            $obj = new self();
            $obj->cargarDatos($propiedad);
            return $obj;
        }
        return null;
    }
    
    public function crear($datos) {
        $sql = "INSERT INTO inmuebles (
                    titulo, tipo_inmueble, descripcion, precio, 
                    superficie, num_habitaciones, num_baños,
                    estacionamientos, direccion_completa, ciudad,
                    estado, codigo_postal, id_propietario,
                    id_asesor, tipo_servicio, estado_inmueble
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'disponible')";
                
        $this->db->consulta($sql, [
            $datos['titulo'],
            $datos['tipo_inmueble'],
            $datos['descripcion'],
            $datos['precio'],
            $datos['superficie'],
            $datos['num_habitaciones'],
            $datos['num_baños'],
            $datos['estacionamientos'],
            $datos['direccion_completa'],
            $datos['ciudad'],
            $datos['estado'],
            $datos['codigo_postal'],
            $datos['id_propietario'],
            $datos['id_asesor'] ?? null,
            $datos['tipo_servicio']
        ]);
        
        return $this->db->getConnection()->lastInsertId();
    }
    
    public function actualizar($datos) {
        $sql = "UPDATE inmuebles SET 
                titulo = ?, descripcion = ?, precio = ?,
                superficie = ?, num_habitaciones = ?, 
                num_baños = ?, estacionamientos = ?, 
                direccion_completa = ?, ciudad = ?,
                estado = ?, codigo_postal = ?
                WHERE id_inmueble = ?";
                
        return $this->db->consulta($sql, [
            $datos['titulo'],
            $datos['descripcion'],
            $datos['precio'],
            $datos['superficie'],
            $datos['num_habitaciones'],
            $datos['num_baños'],
            $datos['estacionamientos'],
            $datos['direccion_completa'],
            $datos['ciudad'],
            $datos['estado'],
            $datos['codigo_postal'],
            $this->id
        ]);
    }
    
    public function cambiarEstado($nuevoEstado) {
        return $this->db->consulta(
            "UPDATE inmuebles SET estado_inmueble = ? WHERE id_inmueble = ?",
            [$nuevoEstado, $this->id]
        );
    }
    
    public function asignarAsesor($idAsesor) {
        return $this->db->consulta(
            "UPDATE inmuebles SET id_asesor = ? WHERE id_inmueble = ?",
            [$idAsesor, $this->id]
        );
    }
    
    public function obtenerImagenes() {
        return $this->db->obtenerTodos(
            "SELECT * FROM imagenes_inmueble WHERE id_inmueble = ? ORDER BY orden",
            [$this->id]
        );
    }
    
    public function obtenerEstancias() {
        return $this->db->obtenerTodos(
            "SELECT * FROM estancias WHERE id_inmueble = ? ORDER BY tipo_estancia",
            [$this->id]
        );
    }
    
    private function cargarDatos($datos) {
        $this->id = $datos['id_inmueble'];
        $this->titulo = $datos['titulo'];
        $this->descripcion = $datos['descripcion'];
        $this->tipo = $datos['tipo_inmueble'];
        $this->precio = $datos['precio'];
        $this->estado = $datos['estado_inmueble'];
    }

    // Getters
    public function getId() { return $this->id; }
    public function getTitulo() { return $this->titulo; }
    public function getDescripcion() { return $this->descripcion; }
    public function getTipo() { return $this->tipo; }
    public function getPrecio() { return $this->precio; }
    public function getEstado() { return $this->estado; }
}
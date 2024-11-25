// app/models/Estancia.php
<?php
class Estancia {
    private $db;
    
    public function __construct() {
        $this->db = Database::obtenerInstancia();
    }
    
    public function crear($datos) {
        $sql = "INSERT INTO estancias (
            id_inmueble, tipo_estancia, descripcion
        ) VALUES (?, ?, ?)";
        
        $this->db->consulta($sql, [
            $datos['id_inmueble'],
            $datos['tipo_estancia'],
            $datos['descripcion']
        ]);
        
        return $this->db->getConnection()->lastInsertId();
    }
    
    public function obtenerPorInmueble($idInmueble) {
        return $this->db->obtenerTodos(
            "SELECT e.*, 
                    (SELECT url_imagen FROM imagenes_estancia WHERE id_estancia = e.id_estancia LIMIT 1) as imagen
             FROM estancias e 
             WHERE e.id_inmueble = ? 
             ORDER BY e.tipo_estancia",
            [$idInmueble]
        );
    }
    
    public function actualizar($id, $datos) {
        $sql = "UPDATE estancias SET 
                tipo_estancia = ?, descripcion = ?
                WHERE id_estancia = ?";
                
        return $this->db->consulta($sql, [
            $datos['tipo_estancia'],
            $datos['descripcion'],
            $id
        ]);
    }
    
    public function eliminar($id) {
        return $this->db->consulta(
            "DELETE FROM estancias WHERE id_estancia = ?",
            [$id]
        );
    }
}
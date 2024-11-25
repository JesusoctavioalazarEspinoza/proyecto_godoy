// app/models/Usuario.php
<?php
class Usuario {
    private $db;
    private $id;
    private $tipo;
    private $nombre;
    private $apellidos;
    private $email;
    private $telefono;
    private $password;
    
    public function __construct() {
        $this->db = Database::obtenerInstancia();
    }
    
    public static function obtenerPorId($id) {
        $db = Database::obtenerInstancia();
        $usuario = $db->obtenerUno(
            "SELECT * FROM usuarios WHERE id_usuario = ?", 
            [$id]
        );
        
        if ($usuario) {
            $obj = new self();
            $obj->cargarDatos($usuario);
            return $obj;
        }
        return null;
    }
    
    public static function obtenerPorEmail($email) {
        $db = Database::obtenerInstancia();
        $usuario = $db->obtenerUno(
            "SELECT * FROM usuarios WHERE email = ?", 
            [$email]
        );
        
        if ($usuario) {
            $obj = new self();
            $obj->cargarDatos($usuario);
            return $obj;
        }
        return null;
    }
    
    public function crear($datos) {
        $sql = "INSERT INTO usuarios (
                    tipo_usuario, nombre, apellidos, email, 
                    telefono, password, direccion, ciudad, 
                    estado, codigo_postal, fecha_registro
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
                
        $params = [
            $datos['tipo_usuario'],
            $datos['nombre'],
            $datos['apellidos'],
            $datos['email'],
            $datos['telefono'],
            password_hash($datos['password'], PASSWORD_DEFAULT),
            $datos['direccion'] ?? '',
            $datos['ciudad'] ?? '',
            $datos['estado'] ?? '',
            $datos['codigo_postal'] ?? ''
        ];
        
        return $this->db->consulta($sql, $params);
    }
    
    public function actualizar($datos) {
        $sql = "UPDATE usuarios SET 
                nombre = ?, apellidos = ?, telefono = ?, 
                direccion = ?, ciudad = ?, estado = ?, 
                codigo_postal = ?
                WHERE id_usuario = ?";
                
        $params = [
            $datos['nombre'],
            $datos['apellidos'],
            $datos['telefono'],
            $datos['direccion'],
            $datos['ciudad'],
            $datos['estado'],
            $datos['codigo_postal'],
            $this->id
        ];
        
        return $this->db->consulta($sql, $params);
    }
    
    public function cambiarPassword($nuevaPassword) {
        return $this->db->consulta(
            "UPDATE usuarios SET password = ? WHERE id_usuario = ?",
            [password_hash($nuevaPassword, PASSWORD_DEFAULT), $this->id]
        );
    }
    
    private function cargarDatos($datos) {
        $this->id = $datos['id_usuario'];
        $this->tipo = $datos['tipo_usuario'];
        $this->nombre = $datos['nombre'];
        $this->apellidos = $datos['apellidos'];
        $this->email = $datos['email'];
        $this->telefono = $datos['telefono'];
    }

    // Getters
    public function getId() { return $this->id; }
    public function getTipo() { return $this->tipo; }
    public function getNombre() { return $this->nombre; }
    public function getApellidos() { return $this->apellidos; }
    public function getEmail() { return $this->email; }
    public function getTelefono() { return $this->telefono; }
}
// app/config/Database.php
<?php
/**
 * Clase para manejar la conexión a la base de datos
 * Implementa el patrón Singleton para asegurar una única conexión
 */
class Database {
    private static $instancia = null;
    private $conexion;

    /**
     * Constructor privado para evitar la creación directa de objetos
     */
    private function __construct() {
        try {
            $this->conexion = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                ]
            );
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    /**
     * Obtener la instancia única de la base de datos
     */
    public static function obtenerInstancia() {
        if (self::$instancia === null) {
            self::$instancia = new self();
        }
        return self::$instancia;
    }

    /**
     * Obtener la conexión activa
     */
    public function getConnection() {
        return $this->conexion;
    }

    /**
     * Ejecutar una consulta SQL
     * @param string $sql Consulta SQL
     * @param array $parametros Parámetros para la consulta
     * @return PDOStatement
     */
    public function consulta($sql, $parametros = []) {
        try {
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute($parametros);
            return $stmt;
        } catch (PDOException $e) {
            die("Error en la consulta: " . $e->getMessage());
        }
    }

    /**
     * Obtener un solo registro
     */
    public function obtenerUno($sql, $parametros = []) {
        $stmt = $this->consulta($sql, $parametros);
        return $stmt->fetch();
    }

    /**
     * Obtener todos los registros
     */
    public function obtenerTodos($sql, $parametros = []) {
        $stmt = $this->consulta($sql, $parametros);
        return $stmt->fetchAll();
    }
    // Agregar en Database.php
public function getError() {
    return $this->conexion->errorInfo();
}
}
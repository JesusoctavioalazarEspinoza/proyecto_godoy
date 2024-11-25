// app/controllers/HomeController.php

<?php
class HomeController extends Controller {
    public function index() {
        $data = [
            'titulo' => 'Godoy Houses - Inicio',
            'propiedadesDestacadas' => $this->obtenerPropiedadesDestacadas(),
            'ultimasPropiedades' => $this->obtenerUltimasPropiedades()
        ];
        
        return $this->render('home/index', $data);
    }
    
    private function obtenerPropiedadesDestacadas() {
        $sql = "SELECT i.*, u.nombre as nombre_propietario 
                FROM inmuebles i 
                LEFT JOIN usuarios u ON i.id_propietario = u.id_usuario 
                WHERE i.estado_inmueble = 'disponible' 
                LIMIT 6";
                
        return $this->db->obtenerTodos($sql) ?? [];
    }
    
    private function obtenerUltimasPropiedades() {
        $sql = "SELECT i.*, u.nombre as nombre_propietario 
                FROM inmuebles i 
                LEFT JOIN usuarios u ON i.id_propietario = u.id_usuario 
                WHERE i.estado_inmueble = 'disponible' 
                ORDER BY i.id_inmueble DESC 
                LIMIT 8";
                
        return $this->db->obtenerTodos($sql) ?? [];
    }
}
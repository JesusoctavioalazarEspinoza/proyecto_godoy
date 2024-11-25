// app/controllers/ContratosController.php
<?php
class ContratosController extends Controller {
    private $contratoModel;
    
    public function __construct() {
        parent::__construct();
        $this->requiereAutenticacion();
        $this->contratoModel = new Contrato();
    }
    
    public function index() {
        // Filtrar por rol de usuario
        if (AuthHelper::esAsesor()) {
            $contratos = Contrato::obtenerContratosPorAsesor($_SESSION['usuario_id']);
        } else if (AuthHelper::esCliente()) {
            $contratos = Contrato::obtenerContratosPorCliente($_SESSION['usuario_id']);
        } else {
            // Para administradores, mostrar todos los contratos
            $contratos = $this->db->obtenerTodos(
                "SELECT c.*, i.titulo as propiedad, 
                        u1.nombre as nombre_propietario,
                        u2.nombre as nombre_comprador,
                        u3.nombre as nombre_asesor
                 FROM contratos c
                 JOIN inmuebles i ON c.id_inmueble = i.id_inmueble
                 JOIN usuarios u1 ON c.id_propietario = u1.id_usuario
                 JOIN usuarios u2 ON c.id_comprador = u2.id_usuario
                 LEFT JOIN usuarios u3 ON c.id_asesor = u3.id_usuario
                 ORDER BY c.fecha_inicio DESC"
            );
        }

        return $this->render('contratos/index', [
            'titulo' => 'Contratos',
            'contratos' => $contratos
        ]);
    }

    public function ver($id) {
        $contrato = Contrato::obtenerPorId($id);
        
        if (!$contrato) {
            $this->redireccionar('error/404');
        }

        // Verificar permisos de acceso
        if (!$this->puedeAccederContrato($contrato)) {
            $this->redireccionar('error/403');
        }

        // Obtener información adicional
        $documentos = $contrato->obtenerDocumentos();
        $pagos = $contrato->obtenerPagos();
        $historial = $contrato->obtenerHistorial();

        return $this->render('contratos/ver', [
            'titulo' => 'Detalles del Contrato',
            'contrato' => $contrato,
            'documentos' => $documentos,
            'pagos' => $pagos,
            'historial' => $historial
        ]);
    }

    public function crear($idInmueble) {
        if (!AuthHelper::esAsesor()) {
            $this->redireccionar('error/403');
        }

        // Verificar si el inmueble existe y está disponible
        $inmueble = $this->db->obtenerUno(
            "SELECT * FROM inmuebles WHERE id_inmueble = ? AND estado_inmueble = 'disponible'",
            [$idInmueble]
        );

        if (!$inmueble) {
            $this->setDato('error', 'El inmueble no está disponible');
            $this->redireccionar('propiedades');
        }

        if ($this->esPost()) {
            try {
                $datos = [
                    'id_inmueble' => $idInmueble,
                    'id_propietario' => $inmueble['id_propietario'],
                    'id_comprador' => $this->obtenerPost('id_comprador'),
                    'id_asesor' => $_SESSION['usuario_id'],
                    'tipo_contrato' => $this->obtenerPost('tipo_contrato'),
                    'precio_acordado' => $this->obtenerPost('precio_acordado')
                ];

                $idContrato = $this->contratoModel->crear($datos);
                
                // Registrar en historial
                $this->contratoModel->agregarHistorial('creacion', 'Contrato creado');
                
                $this->setDato('exito', 'Contrato creado exitosamente');
                $this->redireccionar('contratos/ver/' . $idContrato);
                
            } catch (Exception $e) {
                $this->setDato('error', 'Error al crear el contrato: ' . $e->getMessage());
            }
        }

        // Obtener lista de posibles compradores
        $compradores = $this->db->obtenerTodos(
            "SELECT * FROM usuarios WHERE tipo_usuario = 'cliente'"
        );

        return $this->render('contratos/crear', [
            'titulo' => 'Crear Contrato',
            'inmueble' => $inmueble,
            'compradores' => $compradores
        ]);
    }

    public function firmar($id) {
        if (!$this->esPost()) {
            $this->redireccionar('contratos/ver/' . $id);
        }

        try {
            $contrato = Contrato::obtenerPorId($id);
            
            if (!$contrato) {
                throw new Exception('Contrato no encontrado');
            }

            // Verificar permisos para firmar
            if (!$this->puedeAccederContrato($contrato)) {
                throw new Exception('No tiene permisos para firmar este contrato');
            }

            $contrato->firmar();
            $contrato->agregarHistorial('firma', 'Contrato firmado por ' . $_SESSION['usuario_nombre']);
            
            $this->setDato('exito', 'Contrato firmado exitosamente');
            
        } catch (Exception $e) {
            $this->setDato('error', $e->getMessage());
        }

        $this->redireccionar('contratos/ver/' . $id);
    }

    public function cancelar($id) {
        if (!$this->esPost()) {
            $this->redireccionar('contratos/ver/' . $id);
        }

        try {
            $contrato = Contrato::obtenerPorId($id);
            
            if (!$contrato) {
                throw new Exception('Contrato no encontrado');
            }

            $motivo = $this->obtenerPost('motivo');
            if (empty($motivo)) {
                throw new Exception('Debe especificar un motivo de cancelación');
            }

            $contrato->cancelar($motivo);
            $contrato->agregarHistorial('cancelacion', 'Contrato cancelado. Motivo: ' . $motivo);
            
            $this->setDato('exito', 'Contrato cancelado exitosamente');
            
        } catch (Exception $e) {
            $this->setDato('error', $e->getMessage());
        }

        $this->redireccionar('contratos/ver/' . $id);
    }

    public function agregarPago($id) {
        if (!$this->esPost()) {
            $this->redireccionar('contratos/ver/' . $id);
        }

        try {
            $contrato = Contrato::obtenerPorId($id);
            
            if (!$contrato) {
                throw new Exception('Contrato no encontrado');
            }

            $datos = [
                'monto' => $this->obtenerPost('monto'),
                'tipo' => $this->obtenerPost('tipo_pago'),
                'referencia' => $this->obtenerPost('referencia')
            ];

            $contrato->agregarPago($datos['monto'], $datos['tipo'], $datos['referencia']);
            $contrato->agregarHistorial('pago', 'Pago registrado por $' . number_format($datos['monto'], 2));
            
            $this->setDato('exito', 'Pago registrado exitosamente');
            
        } catch (Exception $e) {
            $this->setDato('error', $e->getMessage());
        }

        $this->redireccionar('contratos/ver/' . $id);
    }

    private function puedeAccederContrato($contrato) {
        $usuarioId = $_SESSION['usuario_id'];
        
        return AuthHelper::esAdmin() ||
               (AuthHelper::esAsesor() && $contrato->getIdAsesor() === $usuarioId) ||
               (AuthHelper::esCliente() && 
                ($contrato->getIdPropietario() === $usuarioId || 
                 $contrato->getIdComprador() === $usuarioId));
    }
}
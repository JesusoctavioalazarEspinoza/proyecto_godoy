// app/controllers/ReportesController.php
<?php
class ReportesController extends Controller {
    public function __construct() {
        parent::__construct();
        $this->requiereAutenticacion();
        $this->validarRol(['administrador', 'asesor']);
    }

    public function index() {
        $periodo = $this->obtenerGet('periodo', 'mes'); // mes, trimestre, año
        $idAsesor = AuthHelper::esAsesor() ? $_SESSION['usuario_id'] : null;
        
        $stats = [
            'general' => $this->obtenerEstadisticasGenerales($periodo, $idAsesor),
            'ventas' => $this->obtenerEstadisticasVentas($periodo, $idAsesor),
            'propiedades' => $this->obtenerEstadisticasPropiedades($idAsesor),
            'citas' => $this->obtenerEstadisticasCitas($periodo, $idAsesor)
        ];

        return $this->render('reportes/index', [
            'titulo' => 'Reportes y Estadísticas',
            'stats' => $stats,
            'periodo' => $periodo
        ]);
    }

    public function exportar() {
        $tipo = $this->obtenerGet('tipo', 'ventas'); // ventas, propiedades, citas
        $formato = $this->obtenerGet('formato', 'excel'); // excel, pdf
        $idAsesor = AuthHelper::esAsesor() ? $_SESSION['usuario_id'] : null;

        switch ($tipo) {
            case 'ventas':
                $data = $this->obtenerDatosVentas($idAsesor);
                $titulo = 'Reporte de Ventas';
                break;
            case 'propiedades':
                $data = $this->obtenerDatosPropiedades($idAsesor);
                $titulo = 'Reporte de Propiedades';
                break;
            case 'citas':
                $data = $this->obtenerDatosCitas($idAsesor);
                $titulo = 'Reporte de Citas';
                break;
            default:
                $this->redireccionar('reportes');
                return;
        }

        if ($formato === 'excel') {
            $this->exportarExcel($data, $titulo);
        } else {
            $this->exportarPDF($data, $titulo);
        }
    }

    private function obtenerEstadisticasGenerales($periodo, $idAsesor = null) {
        $whereAsesor = $idAsesor ? "AND id_asesor = ?" : "";
        $params = $idAsesor ? [$idAsesor] : [];

        // Total de ventas
        $sql = "SELECT COUNT(*) as total, SUM(precio_acordado) as monto_total
                FROM contratos 
                WHERE estado_contrato = 'firmado' 
                AND fecha_firma >= ? 
                $whereAsesor";

        $fechaInicio = $this->obtenerFechaInicio($periodo);
        array_unshift($params, $fechaInicio);
        
        $ventas = $this->db->obtenerUno($sql, $params);

        // Total de propiedades activas
        $sql = "SELECT COUNT(*) as total 
                FROM inmuebles 
                WHERE estado_inmueble = 'disponible' 
                $whereAsesor";
        
        $propiedades = $this->db->obtenerUno($sql, $idAsesor ? [$idAsesor] : []);

        // Total de citas programadas
        $sql = "SELECT COUNT(*) as total 
                FROM citas 
                WHERE estado = 'programada' 
                AND fecha_hora >= NOW() 
                $whereAsesor";
        
        $citas = $this->db->obtenerUno($sql, $idAsesor ? [$idAsesor] : []);

        return [
            'total_ventas' => $ventas['total'],
            'monto_total' => $ventas['monto_total'],
            'propiedades_activas' => $propiedades['total'],
            'citas_programadas' => $citas['total']
        ];
    }

    private function obtenerEstadisticasVentas($periodo, $idAsesor = null) {
        $whereAsesor = $idAsesor ? "AND id_asesor = ?" : "";
        $params = $idAsesor ? [$idAsesor] : [];

        // Ventas por mes
        $sql = "SELECT DATE_FORMAT(fecha_firma, '%Y-%m') as mes,
                       COUNT(*) as total,
                       SUM(precio_acordado) as monto
                FROM contratos 
                WHERE estado_contrato = 'firmado'
                AND fecha_firma >= ?
                $whereAsesor
                GROUP BY DATE_FORMAT(fecha_firma, '%Y-%m')
                ORDER BY mes ASC";

        $fechaInicio = $this->obtenerFechaInicio($periodo);
        array_unshift($params, $fechaInicio);

        $ventasPorMes = $this->db->obtenerTodos($sql, $params);

        // Ventas por tipo de propiedad
        $sql = "SELECT i.tipo_inmueble,
                       COUNT(*) as total,
                       SUM(c.precio_acordado) as monto
                FROM contratos c
                JOIN inmuebles i ON c.id_inmueble = i.id_inmueble
                WHERE c.estado_contrato = 'firmado'
                AND c.fecha_firma >= ?
                $whereAsesor
                GROUP BY i.tipo_inmueble";

        $ventasPorTipo = $this->db->obtenerTodos($sql, $params);

        return [
            'por_mes' => $ventasPorMes,
            'por_tipo' => $ventasPorTipo
        ];
    }

    private function obtenerEstadisticasPropiedades($idAsesor = null) {
        $whereAsesor = $idAsesor ? "WHERE id_asesor = ?" : "";
        $params = $idAsesor ? [$idAsesor] : [];

        // Propiedades por estado
        $sql = "SELECT estado_inmueble,
                       COUNT(*) as total
                FROM inmuebles
                $whereAsesor
                GROUP BY estado_inmueble";

        $porEstado = $this->db->obtenerTodos($sql, $params);

        // Propiedades por tipo
        $sql = "SELECT tipo_inmueble,
                       COUNT(*) as total,
                       AVG(precio) as precio_promedio
                FROM inmuebles
                $whereAsesor
                GROUP BY tipo_inmueble";

        $porTipo = $this->db->obtenerTodos($sql, $params);

        // Propiedades por ciudad
        $sql = "SELECT ciudad,
                       COUNT(*) as total
                FROM inmuebles
                $whereAsesor
                GROUP BY ciudad
                ORDER BY total DESC
                LIMIT 10";

        $porCiudad = $this->db->obtenerTodos($sql, $params);

        return [
            'por_estado' => $porEstado,
            'por_tipo' => $porTipo,
            'por_ciudad' => $porCiudad
        ];
    }

    private function obtenerEstadisticasCitas($periodo, $idAsesor = null) {
        $whereAsesor = $idAsesor ? "AND id_asesor = ?" : "";
        $params = $idAsesor ? [$idAsesor] : [];

        // Citas por estado
        $sql = "SELECT estado,
                       COUNT(*) as total
                FROM citas
                WHERE fecha_hora >= ?
                $whereAsesor
                GROUP BY estado";

        $fechaInicio = $this->obtenerFechaInicio($periodo);
        array_unshift($params, $fechaInicio);

        $porEstado = $this->db->obtenerTodos($sql, $params);

        // Citas por día de la semana
        $sql = "SELECT DAYOFWEEK(fecha_hora) as dia,
                       COUNT(*) as total
                FROM citas
                WHERE fecha_hora >= ?
                $whereAsesor
                GROUP BY DAYOFWEEK(fecha_hora)
                ORDER BY dia";

        $porDia = $this->db->obtenerTodos($sql, $params);

        // Tasa de conversión
        $sql = "SELECT 
                    COUNT(*) as total_citas,
                    SUM(CASE WHEN c.id_cita IN (
                        SELECT id_cita 
                        FROM contratos 
                        WHERE estado_contrato = 'firmado'
                    ) THEN 1 ELSE 0 END) as citas_exitosas
                FROM citas c
                WHERE fecha_hora >= ?
                $whereAsesor";

        $conversion = $this->db->obtenerUno($sql, $params);

        return [
            'por_estado' => $porEstado,
            'por_dia' => $porDia,
            'conversion' => [
                'total' => $conversion['total_citas'],
                'exitosas' => $conversion['citas_exitosas'],
                'tasa' => $conversion['total_citas'] > 0 ? 
                         ($conversion['citas_exitosas'] / $conversion['total_citas'] * 100) : 0
            ]
        ];
    }

    private function obtenerFechaInicio($periodo) {
        switch ($periodo) {
            case 'mes':
                return date('Y-m-d', strtotime('-1 month'));
            case 'trimestre':
                return date('Y-m-d', strtotime('-3 months'));
            case 'año':
                return date('Y-m-d', strtotime('-1 year'));
            default:
                return date('Y-m-d', strtotime('-1 month'));
        }
    }

    private function exportarExcel($data, $titulo) {
        // Implementación de exportación a Excel
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $titulo . '.xlsx"');
        // ... código de exportación a Excel
    }

    private function exportarPDF($data, $titulo) {
        // Implementación de exportación a PDF
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment;filename="' . $titulo . '.pdf"');
        // ... código de exportación a PDF
    }
}
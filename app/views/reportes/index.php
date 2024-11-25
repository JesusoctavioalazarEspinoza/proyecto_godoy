// app/views/reportes/index.php
<div class="container-fluid py-4">
    <div class="row">
        <!-- Filtros y Exportación -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <form id="formFiltros" class="d-flex gap-3">
                                <select class="form-select w-auto" name="periodo">
                                    <option value="mes" <?php echo $periodo === 'mes' ? 'selected' : ''; ?>>Último Mes</option>
                                    <option value="trimestre" <?php echo $periodo === 'trimestre' ? 'selected' : ''; ?>>Último Trimestre</option>
                                    <option value="año" <?php echo $periodo === 'año' ? 'selected' : ''; ?>>Último Año</option>
                                </select>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter"></i> Filtrar
                                </button>
                            </form>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="dropdown d-inline-block">
                                <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-download"></i> Exportar
                                </button>
                                <ul class="dropdown-menu">
                                    <li><h6 class="dropdown-header">Ventas</h6></li>
                                    <li>
                                        <a class="dropdown-item" href="<?php echo url('reportes/exportar?tipo=ventas&formato=excel'); ?>">
                                            Excel
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="<?php echo url('reportes/exportar?tipo=ventas&formato=pdf'); ?>">
                                            PDF
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><h6 class="dropdown-header">Propiedades</h6></li>
                                    <li>
                                        <a class="dropdown-item" href="<?php echo url('reportes/exportar?tipo=propiedades&formato=excel'); ?>">
                                            Excel
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="<?php echo url('reportes/exportar?tipo=propiedades&formato=pdf'); ?>">
                                            PDF
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resumen General -->
        <div class="col-12 mb-4">
            <div class="row">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title mb-1">Total Ventas</h6>
                                    <h3 class="mb-0"><?php echo $stats['general']['total_ventas']; ?></h3>
                                </div>
                                <div class="fs-1">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                            </div>
                            <div class="mt-2">
                                <small>Monto: $<?php echo number_format($stats['general']['monto_total'], 2); ?></small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title mb-1">Propiedades Activas</h6>
                                    <h3 class="mb-0"><?php echo $stats['general']['propiedades_activas']; ?></h3>
                                </div>
                                <div class="fs-1">
                                    <i class="fas fa-home"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title mb-1">Citas Programadas</h6>
                                    <h3 class="mb-0"><?php echo $stats['general']['citas_programadas']; ?></h3>
                                </div>
                                <div class="fs-1">
                                    <i class="fas fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title mb-1">Tasa de Conversión</h6>
                                    <h3 class="mb-0"><?php echo number_format($stats['citas']['conversion']['tasa'], 1); ?>%</h3>
                                </div>
                                <div class="fs-1">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Ventas por Mes</h5>
                </div>
                <div class="card-body">
                    <canvas id="ventasPorMes"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Propiedades por Tipo</h5>
                </div>
                <div class="card-body">
                    <canvas id="propiedadesPorTipo"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Citas por Día</h5>
                </div>
                <div class="card-body">
                    <canvas id="citasPorDia"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Top 10 Ciudades</h5>
                </div>
                <div class="card-body">
                    <canvas id="propiedadesPorCiudad"></canvas>
                </div>
            </div>
        </div>

        <!-- Tabla de Últimas Ventas -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Últimas Ventas</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Propiedad</th>
                                    <th>Vendedor</th>
                                    <th>Comprador</th>
                                    <th>Precio</th>
                                    <th>Comisión</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stats['ventas']['ultimas'] ?? [] as $venta): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y', strtotime($venta['fecha_firma'])); ?></td>
                                    <td><?php echo $venta['titulo_propiedad']; ?></td>
                                    <td><?php echo $venta['nombre_vendedor']; ?></td>
                                    <td><?php echo $venta['nombre_comprador']; ?></td>
                                    <td>$<?php echo number_format($venta['precio_acordado'], 2); ?></td>
                                    <td>$<?php echo number_format($venta['comision'], 2); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Configuración de colores
const colors = {
    primary: '#007bff',
    success: '#28a745',
    info: '#17a2b8',
    warning: '#ffc107',
    danger: '#dc3545'
};

// Gráfico de Ventas por Mes
const ventasPorMesCtx = document.getElementById('ventasPorMes').getContext('2d');
new Chart(ventasPorMesCtx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode(array_map(function($venta) {
            return date('M Y', strtotime($venta['mes'] . '-01'));
        }, $stats['ventas']['por_mes'])); ?>,
        datasets: [{
            label: 'Monto de Ventas',
            data: <?php echo json_encode(array_map(function($venta) {
                return $venta['monto'];
            }, $stats['ventas']['por_mes'])); ?>,
            borderColor: colors.primary,
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// Gráfico de Propiedades por Tipo
const propiedadesPorTipoCtx = document.getElementById('propiedadesPorTipo').getContext('2d');
new Chart(propiedadesPorTipoCtx, {
    type: 'pie',
    data: {
        labels: <?php echo json_encode(array_map(function($prop) {
            return ucfirst($prop['tipo_inmueble']);
        }, $stats['propiedades']['por_tipo'])); ?>,
        datasets: [{
            data: <?php echo json_encode(array_map(function($prop) {
                return $prop['total'];
            }, $stats['propiedades']['por_tipo'])); ?>,
            backgroundColor: Object.values(colors)
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// Gráfico de Citas por Día
const citasPorDiaCtx = document.getElementById('citasPorDia').getContext('2d');
const diasSemana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
new Chart(citasPorDiaCtx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode(array_map(function($cita) use ($diasSemana) {
            return $diasSemana[$cita['dia'] - 1];
        }, $stats['citas']['por_dia'])); ?>,
        datasets: [{
            label: 'Número de Citas',
            data: <?php echo json_encode(array_map(function($cita) {
                return $cita['total'];
            }, $stats['citas']['por_dia'])); ?>,
            backgroundColor: colors.info
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// Gráfico de Propiedades por Ciudad
const propiedadesPorCiudadCtx = document.getElementById('propiedadesPorCiudad').getContext('2d');
new Chart(propiedadesPorCiudadCtx, {
    type: 'horizontalBar',
    data: {
        labels: <?php echo json_encode(array_map(function($prop) {
            return $prop['ciudad'];
        }, $stats['propiedades']['por_ciudad'])); ?>,
        datasets: [{
            label: 'Número de Propiedades',
            data: <?php echo json_encode(array_map(function($prop) {
                return $prop['total'];
            }, $stats['propiedades']['por_ciudad'])); ?>,
            backgroundColor: colors.success
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        indexAxis: 'y'
    }
});

// Actualizar filtros
document.getElementById('formFiltros').addEventListener('submit', function(e) {
    e.preventDefault();
    window.location.href = `<?php echo url('reportes'); ?>?periodo=${this.periodo.value}`;
});
</script>

<style>
.card {
    height: 100%;
}

.card-body {
    display: flex;
    flex-direction: column;
}

canvas {
    min-height: 300px;
}

.fs-1 {
    font-size: 2rem;
}

.table th {
    font-weight: 500;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}
</style>

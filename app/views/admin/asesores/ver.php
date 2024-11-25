app/view/admin/asesores/ver
<div class="container-fluid py-4">
    <div class="row">
        <?php include VIEW_PATH . '/admin/sidebar.php'; ?>
        <div class="col-12 col-lg-10">
            <!-- Encabezado -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">Detalles del Asesor</h4>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="<?php echo url('admin/dashboard'); ?>">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="<?php echo url('admin/asesores'); ?>">Asesores</a></li>
                                    <li class="breadcrumb-item active">Ver Detalles</li>
                                </ol>
                            </nav>
                        </div>
                        <div>
                            <a href="<?php echo url('admin/asesores/editar/' . $asesor['id_usuario']); ?>" 
                               class="btn btn-warning">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="<?php echo url('admin/asesores'); ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Información del Asesor -->
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <div class="avatar-xl mx-auto">
                                    <span class="avatar-initial rounded-circle bg-primary">
                                        <?php echo strtoupper(substr($asesor['nombre'], 0, 1)); ?>
                                    </span>
                                </div>
                            </div>
                            <h5><?php echo $asesor['nombre'] . ' ' . $asesor['apellidos']; ?></h5>
                            <p class="text-muted mb-3">Asesor Inmobiliario</p>
                            <span class="badge bg-<?php echo $asesor['activo'] ? 'success' : 'danger'; ?>">
                                <?php echo $asesor['activo'] ? 'Activo' : 'Inactivo'; ?>
                            </span>
                            
                            <hr>
                            
                            <div class="text-start">
                                <p class="mb-1">
                                    <i class="fas fa-envelope"></i> <?php echo $asesor['email']; ?>
                                </p>
                                <p class="mb-1">
                                    <i class="fas fa-phone"></i> <?php echo $asesor['telefono']; ?>
                                </p>
                                <p class="mb-1">
                                    <i class="fas fa-map-marker-alt"></i> 
                                    <?php echo $asesor['ciudad'] . ', ' . $asesor['estado']; ?>
                                </p>
                                <p class="mb-1">
                                    <i class="fas fa-calendar"></i> 
                                    Miembro desde: <?php echo date('d/m/Y', strtotime($asesor['fecha_registro'])); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas -->
                <div class="col-md-8 mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h6 class="card-title">Propiedades Activas</h6>
                                    <h2><?php echo $stats['propiedades_activas'] ?? 0; ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h6 class="card-title">Ventas Totales</h6>
                                    <h2><?php echo $stats['total_ventas'] ?? 0; ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h6 class="card-title">Citas Pendientes</h6>
                                    <h2><?php echo $stats['citas_pendientes'] ?? 0; ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gráfico de Rendimiento -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">Rendimiento Mensual</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="chartRendimiento" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Propiedades Asignadas -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Propiedades Asignadas</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Propiedad</th>
                                    <th>Cliente</th>
                                    <th>Precio</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($propiedades as $propiedad): ?>
                                <tr>
                                    <td><?php echo $propiedad['titulo']; ?></td>
                                    <td><?php echo $propiedad['nombre_cliente']; ?></td>
                                    <td>$<?php echo number_format($propiedad['precio'], 2); ?></td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            echo $propiedad['estado_inmueble'] === 'disponible' ? 'success' : 
                                                ($propiedad['estado_inmueble'] === 'en_proceso' ? 'warning' : 'secondary'); 
                                        ?>">
                                            <?php echo ucfirst($propiedad['estado_inmueble']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?php echo url('admin/propiedades/ver/' . $propiedad['id_inmueble']); ?>" 
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Últimas Citas -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Últimas Citas</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                    <table class="table">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Cliente</th>
                                    <th>Propiedad</th>
                                    <th>Tipo</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($citas as $cita): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y H:i', strtotime($cita['fecha_hora'])); ?></td>
                                    <td><?php echo $cita['nombre_cliente']; ?></td>
                                    <td><?php echo $cita['titulo_propiedad']; ?></td>
                                    <td><?php echo ucfirst($cita['tipo_cita']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            echo $cita['estado'] === 'programada' ? 'warning' : 
                                                ($cita['estado'] === 'realizada' ? 'success' : 'danger'); 
                                        ?>">
                                            <?php echo ucfirst($cita['estado']); ?>
                                        </span>
                                    </td>
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
// Gráfico de rendimiento
const ctx = document.getElementById('chartRendimiento').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode(array_column($rendimiento_mensual, 'mes')); ?>,
        datasets: [
            {
                label: 'Ventas',
                data: <?php echo json_encode(array_column($rendimiento_mensual, 'ventas')); ?>,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            },
            {
                label: 'Citas',
                data: <?php echo json_encode(array_column($rendimiento_mensual, 'citas')); ?>,
                borderColor: 'rgb(255, 205, 86)',
                tension: 0.1
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'Rendimiento de los últimos 6 meses'
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>

<style>
.avatar-xl {
    width: 100px;
    height: 100px;
}

.avatar-initial {
    font-size: 2.5rem;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.badge {
    padding: 0.5em 1em;
}

.table td {
    vertical-align: middle;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    transition: box-shadow 0.3s ease-in-out;
}

.text-muted {
    color: #6c757d !important;
}

.fas {
    width: 20px;
    text-align: center;
    margin-right: 8px;
}
</style>
                        
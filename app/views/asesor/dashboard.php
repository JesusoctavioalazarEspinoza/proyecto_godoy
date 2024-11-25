// app/views/asesor/dashboard.php
<div class="container-fluid py-4">
    <div class="row">
        <!-- Menú Lateral -->
        <div class="col-12 col-lg-2">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Panel de Asesor</h5>
                    <nav class="nav flex-column">
                        <a class="nav-link active" href="<?php echo url('asesor/dashboard'); ?>">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                        <a class="nav-link" href="<?php echo url('asesor/propiedades'); ?>">
                            <i class="fas fa-building"></i> Propiedades
                        </a>
                        <a class="nav-link" href="<?php echo url('asesor/citas'); ?>">
                            <i class="fas fa-calendar"></i> Citas
                        </a>
                        <a class="nav-link" href="<?php echo url('asesor/clientes'); ?>">
                            <i class="fas fa-users"></i> Clientes
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Contenido Principal -->
        <div class="col-12 col-lg-10">
            <!-- Tarjetas de Resumen -->
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Propiedades</h5>
                            <h2 class="mb-0"><?php echo $stats['total_propiedades']; ?></h2>
                            <small>Total asignadas</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Clientes</h5>
                            <h2 class="mb-0"><?php echo $stats['total_clientes']; ?></h2>
                            <small>Activos</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Próximas Citas -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Próximas Citas</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                <?php foreach ($stats['proximas_citas'] as $cita): ?>
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1"><?php echo $cita['titulo_propiedad']; ?></h6>
                                        <small>
                                            <?php echo date('d/m/Y H:i', strtotime($cita['fecha_hora'])); ?>
                                        </small>
                                    </div>
                                    <p class="mb-1">Cliente: <?php echo $cita['nombre_cliente']; ?></p>
                                    <small><?php echo $cita['lugar_reunion']; ?></small>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Estado de Propiedades</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="propiedadesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de propiedades por estado
    const propiedadesData = <?php echo json_encode($stats['propiedades_estado']); ?>;
    const ctx = document.getElementById('propiedadesChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: propiedadesData.map(item => item.estado_inmueble),
            datasets: [{
                data: propiedadesData.map(item => item.total),
                backgroundColor: [
                    '#4e73df',
                    '#1cc88a',
                    '#36b9cc',
                    '#f6c23e'
                ]
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
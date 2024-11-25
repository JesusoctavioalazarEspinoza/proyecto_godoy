// app/views/admin/dashboard.php
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 col-lg-2">
            <!-- Menú lateral -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Menú Admin</h5>
                    <nav class="nav flex-column">
                        <a class="nav-link active" href="<?php echo url('admin/dashboard'); ?>">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                        <a class="nav-link" href="<?php echo url('admin/asesores'); ?>">
                            <i class="fas fa-users"></i> Asesores
                        </a>
                        <a class="nav-link" href="<?php echo url('admin/clientes'); ?>">
                            <i class="fas fa-user-friends"></i> Clientes
                        </a>
                        <a class="nav-link" href="<?php echo url('admin/propiedades'); ?>">
                            <i class="fas fa-building"></i> Propiedades
                        </a>
                        <a class="nav-link" href="<?php echo url('admin/reportes'); ?>">
                            <i class="fas fa-chart-bar"></i> Reportes
                        </a>
                    </nav>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-lg-10">
            <!-- Tarjetas de estadísticas -->
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Propiedades</h5>
                            <h2><?php echo $stats['total_propiedades']; ?></h2>
                        </div>
                    </div>
                </div>
                
                <?php foreach ($stats['usuarios_tipo'] as $tipo): ?>
                <div class="col-md-3 mb-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total <?php echo ucfirst($tipo['tipo_usuario']); ?>s</h5>
                            <h2><?php echo $tipo['total']; ?></h2>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Gráfica de propiedades por estado -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Propiedades por Estado</h5>
                            <canvas id="propiedadesChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Últimas Transacciones</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Propiedad</th>
                                            <th>Vendedor</th>
                                            <th>Comprador</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($stats['ultimas_transacciones'] as $trans): ?>
                                        <tr>
                                            <td><?php echo $trans['propiedad']; ?></td>
                                            <td><?php echo $trans['propietario']; ?></td>
                                            <td><?php echo $trans['comprador']; ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $trans['estado_contrato'] === 'completado' ? 'success' : 'warning'; ?>">
                                                    <?php echo ucfirst($trans['estado_contrato']); ?>
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
    </div>
</div>

<!-- Scripts para las gráficas -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Gráfica de propiedades por estado
const propiedadesData = <?php echo json_encode($stats['propiedades_estado']); ?>;
const ctx = document.getElementById('propiedadesChart').getContext('2d');
new Chart(ctx, {
    type: 'pie',
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
    }
});
</script>
// app/views/asesor/citas/ver.php
<div class="container-fluid py-4">
    <div class="row">
        <?php include '../sidebar.php'; ?>
        
        <div class="col-12 col-lg-10">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Detalles de la Cita</h5>
                </div>
                <div class="card-body">
                    <!-- Estado de la Cita -->
                    <div class="alert alert-<?php 
                        echo $cita['estado'] === 'programada' ? 'warning' : 
                            ($cita['estado'] === 'realizada' ? 'success' : 'danger'); 
                    ?>">
                        Estado: <?php echo ucfirst($cita['estado']); ?>
                    </div>

                    <!-- Información Principal -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h6 class="mb-0">Información del Cliente</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Nombre:</strong> <?php echo $cita['nombre_cliente'] . ' ' . $cita['apellidos_cliente']; ?></p>
                                    <p><strong>Email:</strong> <?php echo $cita['email_cliente']; ?></p>
                                    <p><strong>Teléfono:</strong> <?php echo $cita['telefono_cliente']; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h6 class="mb-0">Información de la Propiedad</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Título:</strong> <?php echo $cita['titulo_propiedad']; ?></p>
                                    <p><strong>Dirección:</strong> <?php echo $cita['direccion_propiedad']; ?></p>
                                    <p><strong>Precio:</strong> $<?php echo number_format($cita['precio_propiedad'], 2); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detalles de la Cita -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Detalles de la Cita</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <p><strong>Fecha:</strong> <?php echo date('d/m/Y', strtotime($cita['fecha_hora'])); ?></p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Hora:</strong> <?php echo date('H:i', strtotime($cita['fecha_hora'])); ?></p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Tipo:</strong> <?php echo ucfirst($cita['tipo_cita']); ?></p>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <p><strong>Lugar de Reunión:</strong> <?php echo $cita['lugar_reunion']; ?></p>
                                    <p><strong>Notas:</strong></p>
                                    <div class="border p-3 bg-light">
                                        <?php echo nl2br($cita['notas']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Historial de Seguimiento -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Historial de Seguimiento</h6>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <?php foreach ($seguimientos as $seguimiento): ?>
                                    <div class="timeline-item">
                                        <div class="timeline-marker"></div>
                                        <div class="timeline-content">
                                            <h6 class="mb-2"><?php echo date('d/m/Y H:i', strtotime($seguimiento['fecha'])); ?></h6>
                                            <p><?php echo nl2br($seguimiento['nota']); ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="mt-4">
                        <a href="<?php echo url('asesor/citas/editar/' . $cita['id_cita']); ?>" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Editar Cita
                        </a>
                        <a href="<?php echo url('asesor/citas'); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding: 20px 0;
}

.timeline-item {
    position: relative;
    padding-left: 40px;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: 0;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background-color: #007bff;
    border: 3px solid #fff;
    box-shadow: 0 0 0 3px #007bff;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: 9px;
    top: 20px;
    height: 100%;
    width: 2px;
    background-color: #007bff;
}

.timeline-item:last-child::before {
    display: none;
}

.timeline-content {
    padding: 15px;
    background-color: #f8f9fa;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
</style>
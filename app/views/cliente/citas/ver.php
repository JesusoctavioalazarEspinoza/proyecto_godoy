// app/views/cliente/citas/ver.php
<div class="container-fluid py-4">
    <div class="row">
        <?php include '../sidebar.php'; ?>
        
        <div class="col-12 col-lg-10">
            <!-- Encabezado -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">Detalles de la Cita</h4>
                        <span class="badge bg-<?php 
                            echo $cita['estado'] === 'programada' ? 'primary' : 
                                ($cita['estado'] === 'realizada' ? 'success' : 'danger'); 
                        ?> fs-6">
                            <?php echo ucfirst($cita['estado']); ?>
                        </span>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1">
                                <i class="fas fa-calendar"></i> 
                                <strong>Fecha:</strong> 
                                <?php echo date('d/m/Y', strtotime($cita['fecha_hora'])); ?>
                            </p>
                            <p class="mb-1">
                                <i class="fas fa-clock"></i> 
                                <strong>Hora:</strong> 
                                <?php echo date('H:i', strtotime($cita['fecha_hora'])); ?>
                            </p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <?php if ($cita['estado'] === 'programada' && !$cita['confirmada']): ?>
                                <span class="badge bg-warning">Pendiente de confirmar</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Detalles de la Propiedad -->
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0">Información de la Propiedad</h5>
                        </div>
                        <div class="card-body">
                            <h6><?php echo $cita['titulo_propiedad']; ?></h6>
                            <p class="mb-2">
                                <i class="fas fa-map-marker-alt"></i> 
                                <?php echo $cita['direccion_completa']; ?>
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-dollar-sign"></i> 
                                Precio: $<?php echo number_format($cita['precio'], 2); ?>
                            </p>
                            <?php if ($cita['estado'] === 'programada'): ?>
                                <p class="mb-2">
                                    <i class="fas fa-map"></i>
                                    <strong>Lugar de Reunión:</strong><br>
                                    <?php echo $cita['lugar_reunion']; ?>
                                </p>
                            <?php endif; ?>
                            <a href="<?php echo url('propiedades/ver/' . $cita['id_inmueble']); ?>" 
                               class="btn btn-sm btn-primary mt-2">
                                <i class="fas fa-eye"></i> Ver Propiedad
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Información del Asesor -->
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0">Información del Asesor</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-circle me-3">
                                    <?php echo strtoupper(substr($cita['nombre_asesor'], 0, 1)); ?>
                                </div>
                                <div>
                                    <h6 class="mb-1"><?php echo $cita['nombre_asesor'] . ' ' . $cita['apellidos_asesor']; ?></h6>
                                    <p class="mb-0 text-muted">Asesor Inmobiliario</p>
                                </div>
                            </div>
                            <p class="mb-2">
                                <i class="fas fa-envelope"></i> 
                                <?php echo $cita['email_asesor']; ?>
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-phone"></i> 
                                <?php echo $cita['telefono_asesor']; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historial y Seguimiento -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Historial de la Cita</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <?php foreach ($seguimientos as $seguimiento): ?>
                            <div class="timeline-item">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-1"><?php echo $seguimiento['nota']; ?></h6>
                                        <small class="text-muted">
                                            <?php echo date('d/m/Y H:i', strtotime($seguimiento['fecha'])); ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <?php if ($cita['estado'] === 'programada'): ?>
                <div class="card mt-4">
                    <div class="card-body">
                        <div class="d-flex gap-2">
                            <?php if (!$cita['confirmada']): ?>
                                <button type="button" class="btn btn-success" 
                                        onclick="confirmarCita(<?php echo $cita['id_cita']; ?>)">
                                    <i class="fas fa-check"></i> Confirmar Asistencia
                                </button>
                            <?php endif; ?>
                            <button type="button" class="btn btn-warning" 
                                    onclick="reprogramarCita(<?php echo $cita['id_cita']; ?>)">
                                <i class="fas fa-clock"></i> Reprogramar
                            </button>
                            <button type="button" class="btn btn-danger" 
                                    onclick="cancelarCita(<?php echo $cita['id_cita']; ?>)">
                                <i class="fas fa-times"></i> Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="text-end mt-4">
                <a href="<?php echo url('cliente/citas'); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver a Mis Citas
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-circle {
    width: 50px;
    height: 50px;
    background-color: #007bff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    font-weight: bold;
}

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
    height: calc(100% + 10px);
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

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.fas {
    width: 1.2em;
    text-align: center;
    margin-right: 0.3em;
}
</style>

<!-- Incluir los modales de la vista anterior -->
<?php include 'modales.php'; ?>

<script>
// Reutilizar las funciones de la vista anterior
function confirmarCita(idCita) {
    if (confirm('¿Está seguro que desea confirmar su asistencia a esta cita?')) {
        window.location.href = `<?php echo url('cliente/citas/confirmar/'); ?>${idCita}`;
    }
}

function reprogramarCita(idCita) {
    const modal = new bootstrap.Modal(document.getElementById('modalReprogramar'));
    citaActual = idCita;
    modal.show();
}

function cancelarCita(idCita) {
    const modal = new bootstrap.Modal(document.getElementById('modalCancelar'));
    citaActual = idCita;
    modal.show();
}
</script>
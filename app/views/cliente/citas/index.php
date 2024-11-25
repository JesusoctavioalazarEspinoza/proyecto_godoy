// app/views/cliente/citas/index.php
<div class="container-fluid py-4">
    <div class="row">
        <?php include '../sidebar.php'; ?>
        
        <div class="col-12 col-lg-10">
            <!-- Próximas Citas -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Próximas Citas</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($citasProgramadas)): ?>
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-calendar-times fa-3x mb-3"></i>
                            <p>No tiene citas programadas</p>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($citasProgramadas as $cita): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <h6 class="card-title mb-0"><?php echo $cita['titulo_propiedad']; ?></h6>
                                                <?php if (!$cita['confirmada']): ?>
                                                    <span class="badge bg-warning">Pendiente de confirmar</span>
                                                <?php endif; ?>
                                            </div>

                                            <p class="mb-2">
                                                <i class="fas fa-calendar"></i> 
                                                <?php echo date('d/m/Y', strtotime($cita['fecha_hora'])); ?>
                                                <br>
                                                <i class="fas fa-clock"></i> 
                                                <?php echo date('H:i', strtotime($cita['fecha_hora'])); ?>
                                            </p>

                                            <p class="mb-2">
                                                <i class="fas fa-map-marker-alt"></i> 
                                                <?php echo $cita['lugar_reunion']; ?>
                                            </p>

                                            <p class="mb-3">
                                                <i class="fas fa-user"></i> 
                                                Asesor: <?php echo $cita['nombre_asesor'] . ' ' . $cita['apellidos_asesor']; ?>
                                            </p>

                                            <div class="d-flex gap-2">
                                                <a href="<?php echo url('cliente/citas/ver/' . $cita['id_cita']); ?>" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Ver Detalles
                                                </a>
                                                <?php if (!$cita['confirmada']): ?>
                                                    <button type="button" class="btn btn-sm btn-success"
                                                            onclick="confirmarCita(<?php echo $cita['id_cita']; ?>)">
                                                        <i class="fas fa-check"></i> Confirmar
                                                    </button>
                                                <?php endif; ?>
                                                <button type="button" class="btn btn-sm btn-warning"
                                                        onclick="reprogramarCita(<?php echo $cita['id_cita']; ?>)">
                                                    <i class="fas fa-clock"></i> Reprogramar
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger"
                                                        onclick="cancelarCita(<?php echo $cita['id_cita']; ?>)">
                                                    <i class="fas fa-times"></i> Cancelar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Historial de Citas -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Historial de Citas</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Propiedad</th>
                                    <th>Asesor</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($citasPasadas as $cita): ?>
                                    <tr>
                                        <td>
                                            <?php echo date('d/m/Y H:i', strtotime($cita['fecha_hora'])); ?>
                                        </td>
                                        <td><?php echo $cita['titulo_propiedad']; ?></td>
                                        <td>
                                            <?php echo $cita['nombre_asesor'] . ' ' . $cita['apellidos_asesor']; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                echo $cita['estado'] === 'realizada' ? 'success' : 
                                                    ($cita['estado'] === 'cancelada' ? 'danger' : 'warning'); 
                                            ?>">
                                                <?php echo ucfirst($cita['estado']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?php echo url('cliente/citas/ver/' . $cita['id_cita']); ?>" 
                                               class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Ver
                                            </a>
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

<!-- Modales para las acciones -->
<!-- Modal de Cancelación -->
<div class="modal fade" id="modalCancelar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancelar Cita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formCancelar" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                    <label class="form-label">Motivo de la cancelación</label>
                        <textarea class="form-control" name="motivo" rows="3" required
                                  placeholder="Por favor, indique el motivo de la cancelación..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger">Confirmar Cancelación</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Reprogramación -->
<div class="modal fade" id="modalReprogramar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reprogramar Cita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formReprogramar" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nueva Fecha</label>
                        <input type="date" class="form-control" name="fecha" 
                               min="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nueva Hora</label>
                        <input type="time" class="form-control" name="hora" required>
                    </div>
                    <div class="form-text">
                        Las citas solo pueden programarse de lunes a viernes entre las 9:00 y las 18:00.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-warning">Confirmar Reprogramación</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Confirmación -->
<div class="modal fade" id="modalConfirmar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Cita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro que desea confirmar su asistencia a esta cita?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" id="btnConfirmarCita">Confirmar Asistencia</button>
            </div>
        </div>
    </div>
</div>

<script>
let citaActual;

// Función para cancelar cita
function cancelarCita(idCita) {
    citaActual = idCita;
    const modal = new bootstrap.Modal(document.getElementById('modalCancelar'));
    modal.show();
}

// Función para reprogramar cita
function reprogramarCita(idCita) {
    citaActual = idCita;
    const modal = new bootstrap.Modal(document.getElementById('modalReprogramar'));
    modal.show();
}

// Función para confirmar cita
function confirmarCita(idCita) {
    citaActual = idCita;
    const modal = new bootstrap.Modal(document.getElementById('modalConfirmar'));
    modal.show();
}

// Configurar formularios
document.getElementById('formCancelar').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    window.location.href = `<?php echo url('cliente/citas/cancelar/'); ?>${citaActual}?motivo=${formData.get('motivo')}`;
});

document.getElementById('formReprogramar').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const fecha = this.fecha.value;
    const hora = this.hora.value;
    
    // Validar fecha y hora
    const fechaHora = new Date(fecha + ' ' + hora);
    const ahora = new Date();
    
    if (fechaHora < ahora) {
        alert('La fecha y hora deben ser futuras');
        return;
    }
    
    // Validar día de la semana (1-5 = Lunes-Viernes)
    if (fechaHora.getDay() === 0 || fechaHora.getDay() === 6) {
        alert('Las citas solo pueden programarse de lunes a viernes');
        return;
    }
    
    // Validar horario (9-18)
    const horaNum = parseInt(hora.split(':')[0]);
    if (horaNum < 9 || horaNum >= 18) {
        alert('Las citas solo pueden programarse entre las 9:00 y las 18:00');
        return;
    }
    
    window.location.href = `<?php echo url('cliente/citas/reprogramar/'); ?>${citaActual}?fecha=${fecha}&hora=${hora}`;
});

document.getElementById('btnConfirmarCita').addEventListener('click', function() {
    window.location.href = `<?php echo url('cliente/citas/confirmar/'); ?>${citaActual}`;
});
</script>

<style>
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.badge {
    font-size: 0.8em;
    padding: 0.5em 0.7em;
}

.table > :not(caption) > * > * {
    padding: 1rem 0.5rem;
}

.btn-group .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.fas {
    width: 1.2em;
    text-align: center;
    margin-right: 0.3em;
}
</style>
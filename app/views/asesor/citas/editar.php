// app/views/asesor/citas/editar.php
<div class="container-fluid py-4">
    <div class="row">
        <?php include '../sidebar.php'; ?>
        
        <div class="col-12 col-lg-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Editar Cita</h5>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-success" 
                                onclick="actualizarEstado('realizada')" <?php echo $cita['estado'] === 'realizada' ? 'disabled' : ''; ?>>
                            <i class="fas fa-check"></i> Marcar Realizada
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" 
                                onclick="actualizarEstado('cancelada')" <?php echo $cita['estado'] === 'cancelada' ? 'disabled' : ''; ?>>
                            <i class="fas fa-times"></i> Cancelar Cita
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo url('asesor/citas/actualizar/' . $cita['id_cita']); ?>" 
                          method="POST" id="formEditarCita">
                        <input type="hidden" name="estado" id="estadoCita" value="<?php echo $cita['estado']; ?>">
                        
                        <!-- Información de la Cita -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Cliente</label>
                                    <input type="text" class="form-control" 
                                           value="<?php echo $cita['nombre_cliente'] . ' ' . $cita['apellidos_cliente']; ?>" 
                                           readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Propiedad</label>
                                    <input type="text" class="form-control" 
                                           value="<?php echo $cita['titulo_propiedad']; ?>" 
                                           readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Fecha y Hora -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Fecha y Hora</label>
                                    <input type="text" class="form-control" 
                                           value="<?php echo date('d/m/Y H:i', strtotime($cita['fecha_hora'])); ?>" 
                                           readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Estado Actual</label>
                                    <input type="text" class="form-control" 
                                           value="<?php echo ucfirst($cita['estado']); ?>" 
                                           readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Detalles y Notas -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Notas de la Cita</label>
                                    <textarea class="form-control" name="notas" rows="4"><?php echo $cita['notas']; ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Bitácora de Seguimiento -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="fw-bold">Agregar Seguimiento</h6>
                                <div class="mb-3">
                                    <textarea class="form-control" name="nuevo_seguimiento" rows="3" 
                                              placeholder="Agregue notas de seguimiento..."></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Guardar Cambios
                                </button>
                                <a href="<?php echo url('asesor/citas'); ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function actualizarEstado(estado) {
    if (!confirm('¿Está seguro de cambiar el estado de la cita?')) {
        return;
    }
    
    document.getElementById('estadoCita').value = estado;
    document.getElementById('formEditarCita').submit();
}
</script>
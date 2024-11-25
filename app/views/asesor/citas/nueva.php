// app/views/asesor/citas/nueva.php
<div class="container-fluid py-4">
    <div class="row">
        <?php include '../sidebar.php'; ?>
        
        <div class="col-12 col-lg-10">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Programar Nueva Cita</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo url('asesor/citas/guardar'); ?>" method="POST" id="formCita">
                        <!-- Información del Cliente -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="fw-bold">Información del Cliente</h6>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Cliente</label>
                                    <select class="form-select" name="id_cliente" required>
                                        <option value="">Seleccione un cliente</option>
                                        <?php foreach ($clientes as $cliente): ?>
                                            <option value="<?php echo $cliente['id_usuario']; ?>">
                                                <?php echo $cliente['nombre'] . ' ' . $cliente['apellidos']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Propiedad</label>
                                    <select class="form-select" name="id_inmueble" required>
                                        <option value="">Seleccione una propiedad</option>
                                        <?php foreach ($propiedades as $propiedad): ?>
                                            <option value="<?php echo $propiedad['id_inmueble']; ?>">
                                                <?php echo $propiedad['titulo']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Fecha y Hora -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="fw-bold">Fecha y Hora</h6>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Fecha</label>
                                    <input type="date" class="form-control" name="fecha" 
                                            min="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Hora</label>
                                    <input type="time" class="form-control" name="hora" required>
                                </div>
                            </div>
                        </div>

                        <!-- Detalles de la Cita -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="fw-bold">Detalles de la Cita</h6>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tipo de Cita</label>
                                    <select class="form-select" name="tipo_cita" required>
                                        <option value="visita">Visita a Propiedad</option>
                                        <option value="reunion">Reunión</option>
                                        <option value="virtual">Reunión Virtual</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Lugar de Reunión</label>
                                    <input type="text" class="form-control" name="lugar_reunion" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Notas Adicionales</label>
                                    <textarea class="form-control" name="notas" rows="3"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-calendar-check"></i> Programar Cita
                                </button>
                                <a href="<?php echo url('asesor/citas'); ?>" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancelar
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
document.getElementById('formCita').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validar fecha y hora
    const fecha = new Date(this.fecha.value + ' ' + this.hora.value);
    const ahora = new Date();
    
    if (fecha < ahora) {
        alert('La fecha y hora de la cita no puede ser en el pasado');
        return;
    }
    
    // Validar horario laboral (9:00 - 18:00)
    const hora = parseInt(this.hora.value.split(':')[0]);
    if (hora < 9 || hora >= 18) {
        alert('Las citas solo pueden programarse entre las 9:00 y las 18:00');
        return;
    }
    
    // Si todo está bien, enviar el formulario
    this.submit();
});
</script>
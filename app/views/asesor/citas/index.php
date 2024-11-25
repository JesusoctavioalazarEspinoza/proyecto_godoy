<!-- views/asesor/citas/index.php -->
<div class="container-fluid py-4">
    <div class="row">
        <?php include '../sidebar.php'; ?>
        
        <div class="col-12 col-lg-10">
            <!-- Estadísticas de Citas -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h6 class="mb-0">Citas Hoy</h6>
                            <h2><?php echo $stats['citas_hoy'] ?? 0; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6 class="mb-0">Próximas Citas</h6>
                            <h2><?php echo $stats['proximas_citas'] ?? 0; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h6 class="mb-0">Citas Realizadas</h6>
                            <h2><?php echo $stats['citas_realizadas'] ?? 0; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h6 class="mb-0">Tasa de Conversión</h6>
                            <h2><?php echo $stats['tasa_conversion'] ?? '0%'; ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Citas -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Gestión de Citas</h5>
                    <a href="<?php echo url('asesor/citas/nueva'); ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nueva Cita
                    </a>
                </div>
                <div class="card-body">
                    <!-- Filtros -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select class="form-select" id="filtroEstado">
                                <option value="">Todos los estados</option>
                                <option value="programada">Programada</option>
                                <option value="realizada">Realizada</option>
                                <option value="cancelada">Cancelada</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control" id="filtroFecha">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" placeholder="Buscar cliente..." id="buscarCliente">
                        </div>
                    </div>

                    <!-- Tabla de Citas -->
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Fecha y Hora</th>
                                    <th>Cliente</th>
                                    <th>Propiedad</th>
                                    <th>Tipo</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($citas as $cita): ?>
                                <tr>
                                    <td>
                                        <?php echo date('d/m/Y H:i', strtotime($cita['fecha_hora'])); ?>
                                    </td>
                                    <td>
                                        <?php echo $cita['nombre_cliente'] . ' ' . $cita['apellidos_cliente']; ?>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-phone"></i> <?php echo $cita['telefono_cliente']; ?>
                                        </small>
                                    </td>
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
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?php echo url('asesor/citas/ver/' . $cita['id_cita']); ?>" 
                                               class="btn btn-sm btn-info" title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if ($cita['estado'] === 'programada'): ?>
                                                <a href="<?php echo url('asesor/citas/editar/' . $cita['id_cita']); ?>" 
                                                   class="btn btn-sm btn-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-success" 
                                                        onclick="completarCita(<?php echo $cita['id_cita']; ?>)"
                                                        title="Marcar como realizada">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                        onclick="cancelarCita(<?php echo $cita['id_cita']; ?>)"
                                                        title="Cancelar">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
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
                        <textarea class="form-control" name="motivo" rows="3" required></textarea>
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

<script>
let citaActual;

// Filtros
document.getElementById('filtroEstado').addEventListener('change', filtrarCitas);
document.getElementById('filtroFecha').addEventListener('change', filtrarCitas);
document.getElementById('buscarCliente').addEventListener('input', filtrarCitas);

function filtrarCitas() {
    const estado = document.getElementById('filtroEstado').value.toLowerCase();
    const fecha = document.getElementById('filtroFecha').value;
    const cliente = document.getElementById('buscarCliente').value.toLowerCase();
    const filas = document.querySelectorAll('tbody tr');
    
    filas.forEach(fila => {
        const estadoCita = fila.querySelector('td:nth-child(5)').textContent.toLowerCase();
        const fechaCita = fila.querySelector('td:nth-child(1)').textContent.split(' ')[0];
        const nombreCliente = fila.querySelector('td:nth-child(2)').textContent.toLowerCase();
        
        const coincideEstado = !estado || estadoCita.includes(estado);
        const coincideFecha = !fecha || fechaCita === fecha;
        const coincideCliente = !cliente || nombreCliente.includes(cliente);
        
        fila.style.display = coincideEstado && coincideFecha && coincideCliente ? '' : 'none';
    });
}

function completarCita(id) {
    if (confirm('¿Marcar esta cita como realizada?')) {
        window.location.href = `<?php echo url('asesor/citas/completar/'); ?>${id}`;
    }
}

function cancelarCita(id) {
    citaActual = id;
    const modal = new bootstrap.Modal(document.getElementById('modalCancelar'));
    modal.show();
}

document.getElementById('formCancelar').addEventListener('submit', function(e) {
    e.preventDefault();
    const motivo = this.motivo.value;
    window.location.href = `<?php echo url('asesor/citas/cancelar/'); ?>${citaActual}?motivo=${encodeURIComponent(motivo)}`;
});
</script>
<!-- app/views/contratos/index.php -->
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Gestión de Contratos</h5>
            <?php if (AuthHelper::esAsesor()): ?>
                <a href="<?php echo url('contratos/crear'); ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo Contrato
                </a>
            <?php endif; ?>
        </div>
        
        <div class="card-body">
            <!-- Filtros -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <select class="form-select" id="filtroEstado">
                        <option value="">Todos los estados</option>
                        <option value="en_proceso">En Proceso</option>
                        <option value="firmado">Firmado</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="busqueda" 
                           placeholder="Buscar por propiedad...">
                </div>
            </div>

            <!-- Tabla de Contratos -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Propiedad</th>
                            <th>Propietario</th>
                            <th>Comprador</th>
                            <th>Precio</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contratos as $contrato): ?>
                            <tr>
                                <td><?php echo $contrato['id_contrato']; ?></td>
                                <td><?php echo $contrato['propiedad']; ?></td>
                                <td><?php echo $contrato['nombre_propietario']; ?></td>
                                <td><?php echo $contrato['nombre_comprador']; ?></td>
                                <td>$<?php echo number_format($contrato['precio_acordado'], 2); ?></td>
                                <td>
                                    <span class="badge bg-<?php 
                                        echo $contrato['estado_contrato'] === 'firmado' ? 'success' : 
                                            ($contrato['estado_contrato'] === 'en_proceso' ? 'warning' : 'danger'); 
                                    ?>">
                                        <?php echo ucfirst($contrato['estado_contrato']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($contrato['fecha_inicio'])); ?></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?php echo url('contratos/ver/' . $contrato['id_contrato']); ?>" 
                                           class="btn btn-sm btn-info" title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if ($contrato['estado_contrato'] === 'en_proceso'): ?>
                                            <button type="button" class="btn btn-sm btn-success" 
                                                    onclick="firmarContrato(<?php echo $contrato['id_contrato']; ?>)"
                                                    title="Firmar">
                                                <i class="fas fa-signature"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    onclick="cancelarContrato(<?php echo $contrato['id_contrato']; ?>)"
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

<!-- Modal de Cancelación -->
<div class="modal fade" id="modalCancelar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancelar Contrato</h5>
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
let contratoActual;

function firmarContrato(id) {
    if (confirm('¿Está seguro de firmar este contrato?')) {
        window.location.href = `<?php echo url('contratos/firmar/'); ?>${id}`;
    }
}

function cancelarContrato(id) {
    contratoActual = id;
    const modal = new bootstrap.Modal(document.getElementById('modalCancelar'));
    modal.show();
}

// Configurar formulario de cancelación
document.getElementById('formCancelar').addEventListener('submit', function(e) {
    e.preventDefault();
    window.location.href = `<?php echo url('contratos/cancelar/'); ?>${contratoActual}?motivo=${this.motivo.value}`;
});

// Filtros
document.getElementById('filtroEstado').addEventListener('change', filtrarContratos);
document.getElementById('busqueda').addEventListener('input', filtrarContratos);

function filtrarContratos() {
    const estado = document.getElementById('filtroEstado').value.toLowerCase();
    const busqueda = document.getElementById('busqueda').value.toLowerCase();
    const filas = document.querySelectorAll('tbody tr');
    
    filas.forEach(fila => {
        const estadoFila = fila.querySelector('td:nth-child(6)').textContent.toLowerCase();
        const propiedad = fila.querySelector('td:nth-child(2)').textContent.toLowerCase();
        
        const coincideEstado = !estado || estadoFila.includes(estado);
        const coincideBusqueda = !busqueda || propiedad.includes(busqueda);
        
        fila.style.display = coincideEstado && coincideBusqueda ? '' : 'none';
    });
}
</script>
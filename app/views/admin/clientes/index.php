app/view/admin/clientes/index
<div class="container-fluid py-4">
    <div class="row">
        <?php include VIEW_PATH . '/admin/sidebar.php'; ?>
        
        <div class="col-12 col-lg-10">
            <!-- Header -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Gestión de Clientes</h5>
                    <div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalExportar">
                            <i class="fas fa-download"></i> Exportar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h6 class="card-title">Total Clientes</h6>
                            <h2><?php echo $stats['total_clientes']; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6 class="card-title">Clientes Activos</h6>
                            <h2><?php echo $stats['clientes_activos']; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h6 class="card-title">Propiedades</h6>
                            <h2><?php echo $stats['total_propiedades']; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h6 class="card-title">Citas Pendientes</h6>
                            <h2><?php echo $stats['citas_pendientes']; ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="card mb-4">
                <div class="card-body">
                    <form id="formFiltros" class="row g-3">
                        <div class="col-md-3">
                            <input type="text" class="form-control" placeholder="Buscar por nombre..." 
                                   name="busqueda" value="<?php echo $filtros['busqueda'] ?? ''; ?>">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="ciudad">
                                <option value="">Todas las ciudades</option>
                                <?php foreach ($ciudades as $ciudad): ?>
                                    <option value="<?php echo $ciudad; ?>" 
                                            <?php echo ($filtros['ciudad'] ?? '') === $ciudad ? 'selected' : ''; ?>>
                                        <?php echo $ciudad; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" name="estado">
                                <option value="">Todos los estados</option>
                                <option value="activo" <?php echo ($filtros['estado'] ?? '') === 'activo' ? 'selected' : ''; ?>>Activo</option>
                                <option value="inactivo" <?php echo ($filtros['estado'] ?? '') === 'inactivo' ? 'selected' : ''; ?>>Inactivo</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> Filtrar
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-secondary w-100" onclick="limpiarFiltros()">
                                <i class="fas fa-times"></i> Limpiar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de Clientes -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Contacto</th>
                                    <th>Ubicación</th>
                                    <th>Propiedades</th>
                                    <th>Última Actividad</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($clientes as $cliente): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-2">
                                                <span class="avatar-initial rounded-circle bg-primary">
                                                    <?php echo strtoupper(substr($cliente['nombre'], 0, 1)); ?>
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0"><?php echo $cliente['nombre'] . ' ' . $cliente['apellidos']; ?></h6>
                                                <small class="text-muted">
                                                    Cliente desde: <?php echo date('d/m/Y', strtotime($cliente['fecha_registro'])); ?>
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="mb-0"><i class="fas fa-envelope"></i> <?php echo $cliente['email']; ?></p>
                                        <p class="mb-0"><i class="fas fa-phone"></i> <?php echo $cliente['telefono']; ?></p>
                                    </td>
                                    <td>
                                        <?php echo $cliente['ciudad'] . ', ' . $cliente['estado']; ?>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-primary me-2"><?php echo $cliente['total_propiedades']; ?></span>
                                            <small>publicadas</small>
                                        </div>
                                    </td>
                                    <td>
                                        <?php echo $cliente['ultima_actividad'] ? date('d/m/Y H:i', strtotime($cliente['ultima_actividad'])) : 'Sin actividad'; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo $cliente['activo'] ? 'success' : 'danger'; ?>">
                                            <?php echo $cliente['activo'] ? 'Activo' : 'Inactivo'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?php echo url('admin/clientes/ver/' . $cliente['id_usuario']); ?>" 
                                               class="btn btn-sm btn-info" title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-warning" 
                                                    onclick="editarCliente(<?php echo $cliente['id_usuario']; ?>)"
                                                    title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <?php if ($cliente['activo']): ?>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                        onclick="desactivarCliente(<?php echo $cliente['id_usuario']; ?>)"
                                                        title="Desactivar">
                                                    <i class="fas fa-user-times"></i>
                                                </button>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-sm btn-success" 
                                                        onclick="activarCliente(<?php echo $cliente['id_usuario']; ?>)"
                                                        title="Activar">
                                                    <i class="fas fa-user-check"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <?php if ($totalPaginas > 1): ?>
                    <nav class="mt-4">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?php echo $paginaActual <= 1 ? 'disabled' : ''; ?>">
                                <a class="page-link" href="<?php echo url('admin/clientes?pagina=' . ($paginaActual - 1)); ?>">
                                    Anterior
                                </a>
                            </li>
                            
                            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                                <li class="page-item <?php echo $i === $paginaActual ? 'active' : ''; ?>">
                                    <a class="page-link" href="<?php echo url('admin/clientes?pagina=' . $i); ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            
                            <li class="page-item <?php echo $paginaActual >= $totalPaginas ? 'disabled' : ''; ?>">
                                <a class="page-link" href="<?php echo url('admin/clientes?pagina=' . ($paginaActual + 1)); ?>">
                                    Siguiente
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Exportar -->
<div class="modal fade" id="modalExportar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Exportar Clientes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="<?php echo url('admin/clientes/exportar'); ?>" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Formato</label>
                        <select class="form-select" name="formato">
                            <option value="excel">Excel</option>
                            <option value="csv">CSV</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Incluir</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="incluir[]" value="propiedades" checked>
                            <label class="form-check-label">Propiedades</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="incluir[]" value="citas" checked>
                            <label class="form-check-label">Historial de citas</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="incluir[]" value="actividad" checked>
                            <label class="form-check-label">Registro de actividad</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Período</label>
                        <select class="form-select" name="periodo">
                            <option value="todo">Todo el historial</option>
                            <option value="mes">Último mes</option>
                            <option value="trimestre">Último trimestre</option>
                            <option value="año">Último año</option>
                        </select>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-download"></i> Exportar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación -->
<div class="modal fade" id="modalConfirmacion" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalConfirmacionTitulo">Confirmar Acción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="modalConfirmacionMensaje">¿Está seguro de realizar esta acción?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnConfirmarAccion">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<script>
let clienteIdSeleccionado;
let accionPendiente;

// Función para editar cliente
function editarCliente(id) {
    window.location.href = `<?php echo url('admin/clientes/editar/'); ?>${id}`;
}

// Función para desactivar cliente
function desactivarCliente(id) {
    clienteIdSeleccionado = id;
    accionPendiente = 'desactivar';
    
    document.getElementById('modalConfirmacionTitulo').textContent = 'Desactivar Cliente';
    document.getElementById('modalConfirmacionMensaje').textContent = 
        '¿Está seguro que desea desactivar este cliente? Todas sus publicaciones activas serán suspendidas.';
    document.getElementById('btnConfirmarAccion').className = 'btn btn-danger';
    
    new bootstrap.Modal(document.getElementById('modalConfirmacion')).show();
}

// Función para activar cliente
function activarCliente(id) {
    clienteIdSeleccionado = id;
    accionPendiente = 'activar';
    
    document.getElementById('modalConfirmacionTitulo').textContent = 'Activar Cliente';
    document.getElementById('modalConfirmacionMensaje').textContent = 
        '¿Está seguro que desea activar este cliente? Sus publicaciones previas serán reactivadas.';
    document.getElementById('btnConfirmarAccion').className = 'btn btn-success';
    
    new bootstrap.Modal(document.getElementById('modalConfirmacion')).show();
}

// Manejar confirmación de acciones
document.getElementById('btnConfirmarAccion').addEventListener('click', function() {
    const url = accionPendiente === 'desactivar' 
        ? `<?php echo url('admin/clientes/desactivar/'); ?>${clienteIdSeleccionado}`
        : `<?php echo url('admin/clientes/activar/'); ?>${clienteIdSeleccionado}`;
        
    window.location.href = url;
});

// Función para limpiar filtros
function limpiarFiltros() {
    document.querySelector('input[name="busqueda"]').value = '';
    document.querySelector('select[name="ciudad"]').value = '';
    document.querySelector('select[name="estado"]').value = '';
    document.getElementById('formFiltros').submit();
}

// Validación de exportación
document.querySelector('#modalExportar form').addEventListener('submit', function(e) {
    const incluir = document.querySelectorAll('input[name="incluir[]"]:checked');
    if (incluir.length === 0) {
        e.preventDefault();
        alert('Debe seleccionar al menos un tipo de información para exportar');
    }
});
</script>

<style>
.avatar {
    width: 40px;
    height: 40px;
}

.avatar-initial {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
}

.btn-group .btn {
    padding: 0.25rem 0.5rem;
}

.table td {
    vertical-align: middle;
}

.badge {
    font-weight: normal;
    padding: 0.5em 1em;
}

.fas {
    width: 1.2em;
    text-align: center;
    margin-right: 0.3em;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.form-check {
    margin-bottom: 0.5rem;
}

.pagination {
    margin-bottom: 0;
}

.page-link {
    padding: 0.5rem 0.75rem;
}

.table-hover tbody tr:hover {
    background-color: rgba(0,0,0,.02);
}
</style>
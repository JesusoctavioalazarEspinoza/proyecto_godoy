app/view/admin/asesores/index
<div class="container-fluid py-4">
    <div class="row">
        <?php include VIEW_PATH . '/admin/sidebar.php'; ?>
        
        <div class="col-12 col-lg-10">
            <!-- Header -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Gestión de Asesores</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoAsesor">
                        <i class="fas fa-plus"></i> Nuevo Asesor
                    </button>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h6 class="card-title">Total Asesores</h6>
                            <h2><?php echo count($asesores); ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6 class="card-title">Propiedades Asignadas</h6>
                            <h2><?php echo $stats['total_propiedades'] ?? 0; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h6 class="card-title">Citas Programadas</h6>
                            <h2><?php echo $stats['citas_pendientes'] ?? 0; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h6 class="card-title">Ventas del Mes</h6>
                            <h2><?php echo $stats['ventas_mes'] ?? 0; ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Asesores -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Contacto</th>
                                    <th>Propiedades</th>
                                    <th>Ventas</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($asesores as $asesor): ?>
                                <tr>
                                    <td><?php echo $asesor['id_usuario']; ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-2">
                                                <span class="avatar-initial rounded-circle bg-primary">
                                                    <?php echo strtoupper(substr($asesor['nombre'], 0, 1)); ?>
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0"><?php echo $asesor['nombre'] . ' ' . $asesor['apellidos']; ?></h6>
                                                <small class="text-muted">Desde: <?php echo date('d/m/Y', strtotime($asesor['fecha_registro'])); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="mb-0"><i class="fas fa-envelope"></i> <?php echo $asesor['email']; ?></p>
                                        <p class="mb-0"><i class="fas fa-phone"></i> <?php echo $asesor['telefono']; ?></p>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-primary me-2"><?php echo $asesor['total_propiedades'] ?? 0; ?></span>
                                            <small>asignadas</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-success me-2"><?php echo $asesor['total_ventas'] ?? 0; ?></span>
                                            <small>completadas</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo $asesor['activo'] ? 'success' : 'danger'; ?>">
                                            <?php echo $asesor['activo'] ? 'Activo' : 'Inactivo'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?php echo url('admin/asesores/ver/' . $asesor['id_usuario']); ?>" 
                                                class="btn btn-sm btn-info" title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-warning" 
                                                    onclick="editarAsesor(<?php echo $asesor['id_usuario']; ?>)"
                                                    title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    onclick="confirmarEliminacion(<?php echo $asesor['id_usuario']; ?>)"
                                                    title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
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

<!-- Modal Nuevo Asesor -->
<div class="modal fade" id="modalNuevoAsesor" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Asesor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo url('admin/asesores/crear'); ?>" method="POST" id="formNuevoAsesor">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Apellidos</label>
                            <input type="text" class="form-control" name="apellidos" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="tel" class="form-control" name="telefono" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" class="form-control" name="password" required>
                        <small class="form-text text-muted">Mínimo 8 caracteres, incluyendo mayúsculas, minúsculas y números</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Asesor</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Confirmación de Eliminación -->
<div class="modal fade" id="modalEliminar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro que desea eliminar este asesor?</p>
                <p class="text-danger">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<script>
let asesorIdEliminar;

function confirmarEliminacion(id) {
    asesorIdEliminar = id;
    new bootstrap.Modal(document.getElementById('modalEliminar')).show();
}

document.getElementById('btnConfirmarEliminar').addEventListener('click', function() {
    window.location.href = `<?php echo url('admin/asesores/eliminar/'); ?>${asesorIdEliminar}`;
});

function editarAsesor(id) {
    window.location.href = `<?php echo url('admin/asesores/editar/'); ?>${id}`;
}

// Validación del formulario
document.getElementById('formNuevoAsesor').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const password = this.password.value;
    if (password.length < 8 || 
        !/[A-Z]/.test(password) || 
        !/[a-z]/.test(password) || 
        !/[0-9]/.test(password)) {
        alert('La contraseña debe tener al menos 8 caracteres, incluyendo mayúsculas, minúsculas y números');
        return;
    }
    
    this.submit();
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
</style>
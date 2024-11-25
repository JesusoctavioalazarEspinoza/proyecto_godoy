// app/views/asesor/propiedades/index.php
<div class="container-fluid py-4">
    <div class="row">
        <?php include '../sidebar.php'; ?>
        
        <div class="col-12 col-lg-10">
            <!-- Estadísticas -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h6 class="card-title">Total Propiedades</h6>
                            <h2><?php echo count($propiedades); ?></h2>
                            <small>Propiedades asignadas</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6 class="card-title">Propiedades Activas</h6>
                            <h2><?php echo count(array_filter($propiedades, function($p) { 
                                return $p['estado_inmueble'] === 'disponible'; 
                            })); ?></h2>
                            <small>En venta</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h6 class="card-title">En Proceso</h6>
                            <h2><?php echo count(array_filter($propiedades, function($p) { 
                                return $p['estado_inmueble'] === 'en_proceso'; 
                            })); ?></h2>
                            <small>Negociaciones activas</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <?php include '../components/property-filters.php'; ?>

            <!-- Lista de Propiedades -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Propiedades Asignadas</h5>
                    <div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalExportar">
                            <i class="fas fa-download"></i> Exportar
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Imagen</th>
                                    <th>Título</th>
                                    <th>Propietario</th>
                                    <th>Precio</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($propiedades as $propiedad): ?>
                                    <tr>
                                        <td><?php echo $propiedad['id_inmueble']; ?></td>
                                        <td>
                                            <?php if ($propiedad['imagen']): ?>
                                                <img src="<?php echo url('uploads/propiedades/' . $propiedad['id_inmueble'] . '/' . $propiedad['imagen']); ?>" 
                                                     class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="bg-light text-center" style="width: 50px; height: 50px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $propiedad['titulo']; ?></td>
                                        <td><?php echo $propiedad['nombre_propietario']; ?></td>
                                        <td><?php echo PropiedadHelper::formatearPrecio($propiedad['precio']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo PropiedadHelper::obtenerEstadoClase($propiedad['estado_inmueble']); ?>">
                                                <?php echo ucfirst($propiedad['estado_inmueble']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?php echo url('asesor/propiedades/ver/' . $propiedad['id_inmueble']); ?>" 
                                                   class="btn btn-sm btn-info" title="Ver detalles">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo url('asesor/propiedades/editar/' . $propiedad['id_inmueble']); ?>" 
                                                   class="btn btn-sm btn-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                        onclick="confirmarEliminacion(<?php echo $propiedad['id_inmueble']; ?>)"
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

<!-- Modal de Exportación -->
<div class="modal fade" id="modalExportar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Exportar Propiedades</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo url('asesor/propiedades/exportar'); ?>" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Formato</label>
                        <select class="form-select" name="formato">
                            <option value="excel">Excel</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Estado</label>
                        <select class="form-select" name="estado">
                            <option value="">Todos</option>
                            <option value="disponible">Disponible</option>
                            <option value="en_proceso">En Proceso</option>
                            <option value="vendido">Vendido</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-download"></i> Exportar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Confirmación -->
<div class="modal fade" id="modalEliminar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro que desea eliminar esta propiedad?</p>
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
let propiedadIdEliminar;

function confirmarEliminacion(id) {
    propiedadIdEliminar = id;
    new bootstrap.Modal(document.getElementById('modalEliminar')).show();
}

document.getElementById('btnConfirmarEliminar').addEventListener('click', function() {
    window.location.href = `<?php echo url('asesor/propiedades/eliminar/'); ?>${propiedadIdEliminar}`;
});
</script>
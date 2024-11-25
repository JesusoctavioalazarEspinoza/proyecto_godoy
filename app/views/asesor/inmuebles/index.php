// app/views/asesor/inmuebles/index.php

<div class="container-fluid py-4">
    <div class="row">
        <?php include '../sidebar.php'; ?>
        
        <div class="col-12 col-lg-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Propiedades Asignadas</h5>
                    <div>
                        <a href="<?php echo url('asesor/inmuebles/nueva'); ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nueva Propiedad
                        </a>
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
                                        <td>$<?php echo number_format($propiedad['precio'], 2); ?></td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                echo $propiedad['estado_inmueble'] === 'disponible' ? 'success' : 
                                                    ($propiedad['estado_inmueble'] === 'en_proceso' ? 'warning' : 'secondary'); 
                                            ?>">
                                                <?php echo ucfirst($propiedad['estado_inmueble']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?php echo url('asesor/inmuebles/ver/' . $propiedad['id_inmueble']); ?>" 
                                                   class="btn btn-sm btn-info" title="Ver detalles">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo url('asesor/inmuebles/editar/' . $propiedad['id_inmueble']); ?>" 
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

<!-- Modal de Confirmación -->
<div class="modal fade" id="modalEliminar" tabindex="-1">
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
    window.location.href = `<?php echo url('asesor/inmuebles/eliminar/'); ?>${propiedadIdEliminar}`;
});
</script>
// app/views/asesor/propiedades.php
<div class="container-fluid py-4">
    <div class="row">
        <!-- Menú Lateral -->
        <?php include 'sidebar.php'; ?>
        
        <!-- Contenido Principal -->
        <div class="col-12 col-lg-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Mis Propiedades</h5>
                    <a href="<?php echo url('asesor/propiedades/nueva'); ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nueva Propiedad
                    </a>
                </div>
                <div class="card-body">
                    <!-- Filtros -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select class="form-select" id="filtroEstado">
                                <option value="">Todos los estados</option>
                                <option value="disponible">Disponible</option>
                                <option value="en_proceso">En Proceso</option>
                                <option value="vendido">Vendido</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="busqueda" 
                                   placeholder="Buscar propiedad...">
                        </div>
                    </div>

                    <!-- Tabla de Propiedades -->
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
                                        <img src="<?php echo url('uploads/propiedades/' . $propiedad['id_inmueble'] . '/thumb.jpg'); ?>" 
                                             class="img-thumbnail" style="width: 50px;">
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
                                            <a href="<?php echo url('asesor/propiedades/ver/' . $propiedad['id_inmueble']); ?>" 
                                               class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?php echo url('asesor/propiedades/editar/' . $propiedad['id_inmueble']); ?>" 
                                               class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger" 
                                                    onclick="confirmarEliminacion(<?php echo $propiedad['id_inmueble']; ?>)">
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
<div class="modal fade" id="eliminarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                ¿Está seguro que desea eliminar esta propiedad?
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
    new bootstrap.Modal(document.getElementById('eliminarModal')).show();
}

document.getElementById('btnConfirmarEliminar').addEventListener('click', function() {
    window.location.href = `<?php echo url('asesor/propiedades/eliminar/'); ?>${propiedadIdEliminar}`;
});

// Filtros
document.getElementById('filtroEstado').addEventListener('change', filtrarPropiedades);
document.getElementById('busqueda').addEventListener('input', filtrarPropiedades);

function filtrarPropiedades() {
    const estado = document.getElementById('filtroEstado').value.toLowerCase();
    const busqueda = document.getElementById('busqueda').value.toLowerCase();
    const filas = document.querySelectorAll('tbody tr');

    filas.forEach(fila => {
        const estadoFila = fila.querySelector('td:nth-child(6)').textContent.toLowerCase();
        const titulo = fila.querySelector('td:nth-child(3)').textContent.toLowerCase();
        
        const coincideEstado = !estado || estadoFila.includes(estado);
        const coincideBusqueda = !busqueda || titulo.includes(busqueda);
        
        fila.style.display = coincideEstado && coincideBusqueda ? '' : 'none';
    });
}
</script>
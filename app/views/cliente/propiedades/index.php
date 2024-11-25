<!-- views/cliente/propiedades/index.php -->
<div class="container-fluid py-4">
    <div class="row">
        <?php include '../sidebar.php'; ?>
        
        <div class="col-12 col-lg-10">
            <!-- Resumen de Propiedades -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5>Propiedades Publicadas</h5>
                            <h2><?php echo count(array_filter($propiedades, function($p) { 
                                return $p['estado_inmueble'] === 'disponible'; 
                            })); ?></h2>
                            <small>Actualmente en venta</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5>Propiedades Vendidas</h5>
                            <h2><?php echo count(array_filter($propiedades, function($p) { 
                                return $p['estado_inmueble'] === 'vendido'; 
                            })); ?></h2>
                            <small>Transacciones completadas</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h5>En Proceso</h5>
                            <h2><?php echo count(array_filter($propiedades, function($p) { 
                                return $p['estado_inmueble'] === 'en_proceso'; 
                            })); ?></h2>
                            <small>Negociaciones activas</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Propiedades -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Mis Propiedades</h5>
                    <a href="<?php echo url('cliente/propiedades/publicar'); ?>" class="btn btn-primary">
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
                            <select class="form-select" id="filtroServicio">
                                <option value="">Todos los servicios</option>
                                <option value="con_asesoria">Con Asesoría</option>
                                <option value="sin_asesoria">Sin Asesoría</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="busqueda" 
                                   placeholder="Buscar por título...">
                        </div>
                    </div>

                    <!-- Tabla de Propiedades -->
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Imagen</th>
                                    <th>Título</th>
                                    <th>Precio</th>
                                    <th>Tipo</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                    <th>Servicio</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($propiedades as $propiedad): ?>
                                <tr>
                                    <td>
                                        <img src="<?php echo url('uploads/propiedades/' . $propiedad['id_inmueble'] . '/' . $propiedad['imagen']); ?>" 
                                             class="img-thumbnail" style="width: 80px; height: 60px; object-fit: cover;">
                                    </td>
                                    <td><?php echo $propiedad['titulo']; ?></td>
                                    <td>$<?php echo number_format($propiedad['precio'], 2); ?></td>
                                    <td><?php echo ucfirst($propiedad['tipo_inmueble']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            echo $propiedad['estado_inmueble'] === 'disponible' ? 'success' : 
                                                ($propiedad['estado_inmueble'] === 'en_proceso' ? 'warning' : 'secondary'); 
                                        ?>">
                                            <?php echo ucfirst($propiedad['estado_inmueble']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d/m/Y', strtotime($propiedad['fecha_registro'])); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $propiedad['tipo_servicio'] === 'con_asesoria' ? 'primary' : 'info'; ?>">
                                            <?php echo $propiedad['tipo_servicio'] === 'con_asesoria' ? 'Con Asesor' : 'Directo'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?php echo url('cliente/propiedades/ver/' . $propiedad['id_inmueble']); ?>" 
                                               class="btn btn-sm btn-info" title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if ($propiedad['estado_inmueble'] === 'disponible'): ?>
                                                <a href="<?php echo url('cliente/propiedades/editar/' . $propiedad['id_inmueble']); ?>" 
                                                   class="btn btn-sm btn-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                        onclick="confirmarEliminacion(<?php echo $propiedad['id_inmueble']; ?>)"
                                                        title="Eliminar">
                                                    <i class="fas fa-trash"></i>
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

<!-- Modal de Confirmación de Eliminación -->
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

// Filtros
document.getElementById('filtroEstado').addEventListener('change', filtrarPropiedades);
document.getElementById('filtroServicio').addEventListener('change', filtrarPropiedades);
document.getElementById('busqueda').addEventListener('input', filtrarPropiedades);

function filtrarPropiedades() {
    const estado = document.getElementById('filtroEstado').value.toLowerCase();
    const servicio = document.getElementById('filtroServicio').value.toLowerCase();
    const busqueda = document.getElementById('busqueda').value.toLowerCase();
    const filas = document.querySelectorAll('tbody tr');
    
    filas.forEach(fila => {
        const estadoFila = fila.querySelector('td:nth-child(5)').textContent.toLowerCase();
        const servicioFila = fila.querySelector('td:nth-child(7)').textContent.toLowerCase();
        const titulo = fila.querySelector('td:nth-child(2)').textContent.toLowerCase();
        
        const coincideEstado = !estado || estadoFila.includes(estado);
        const coincideServicio = !servicio || servicioFila.includes(servicio);
        const coincideBusqueda = !busqueda || titulo.includes(busqueda);
        
        fila.style.display = coincideEstado && coincideServicio && coincideBusqueda ? '' : 'none';
    });
}

function confirmarEliminacion(id) {
    propiedadIdEliminar = id;
    new bootstrap.Modal(document.getElementById('modalEliminar')).show();
}

document.getElementById('btnConfirmarEliminar').addEventListener('click', function() {
    window.location.href = `<?php echo url('cliente/propiedades/eliminar/'); ?>${propiedadIdEliminar}`;
});
</script>

<style>
.property-card {
    transition: transform 0.3s ease;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.property-card:hover {
    transform: translateY(-5px);
}

.badge {
    font-weight: normal;
    padding: 0.5em 1em;
}

.table td {
    vertical-align: middle;
}

.btn-group .btn {
    padding: 0.25rem 0.5rem;
}

.fas {
    width: 1.2em;
    text-align: center;
}
</style>
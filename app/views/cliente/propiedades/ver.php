// app/views/cliente/propiedades/ver.php
<div class="container-fluid py-4">
    <div class="row">
        <?php include '../sidebar.php'; ?>
        
        <div class="col-12 col-lg-10">
            <!-- Encabezado -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1"><?php echo $propiedad['titulo']; ?></h4>
                            <p class="text-muted mb-0">
                                <i class="fas fa-map-marker-alt"></i> 
                                <?php echo $propiedad['direccion_completa']; ?>
                            </p>
                        </div>
                        <div class="text-end">
                            <h3 class="text-primary mb-1">$<?php echo number_format($propiedad['precio'], 2); ?></h3>
                            <span class="badge bg-<?php 
                                echo $propiedad['estado_inmueble'] === 'disponible' ? 'success' : 
                                    ($propiedad['estado_inmueble'] === 'en_proceso' ? 'warning' : 'secondary'); 
                            ?>">
                                <?php echo ucfirst($propiedad['estado_inmueble']); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido Principal -->
            <div class="row">
                <!-- Galería de Imágenes -->
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div id="carouselPropiedad" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    <?php foreach ($imagenes as $index => $imagen): ?>
                                        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                            <img src="<?php echo url('uploads/propiedades/' . $propiedad['id_inmueble'] . '/' . $imagen['url_imagen']); ?>" 
                                                 class="d-block w-100" style="height: 400px; object-fit: cover;">
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselPropiedad" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselPropiedad" data-bs-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Descripción</h5>
                        </div>
                        <div class="card-body">
                            <p><?php echo nl2br($propiedad['descripcion']); ?></p>
                        </div>
                    </div>

                    <!-- Estancias -->
                    <?php if (!empty($estancias)): ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Estancias</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php foreach ($estancias as $estancia): ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h6><?php echo ucfirst($estancia['tipo_estancia']); ?></h6>
                                                <p class="mb-0"><?php echo $estancia['descripcion']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Información Lateral -->
                <div class="col-md-4">
                    <!-- Detalles Principales -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Características</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-ruler-combined"></i> Superficie</span>
                                    <strong><?php echo $propiedad['superficie']; ?> m²</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-bed"></i> Habitaciones</span>
                                    <strong><?php echo $propiedad['num_habitaciones']; ?></strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-bath"></i> Baños</span>
                                    <strong><?php echo $propiedad['num_baños']; ?></strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-car"></i> Estacionamientos</span>
                                    <strong><?php echo $propiedad['estacionamientos']; ?></strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-home"></i> Tipo</span>
                                    <strong><?php echo ucfirst($propiedad['tipo_inmueble']); ?></strong>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Información de Servicio -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Detalles del Servicio</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <span class="badge bg-<?php echo $propiedad['tipo_servicio'] === 'con_asesoria' ? 'primary' : 'info'; ?> mb-2">
                                    <?php echo $propiedad['tipo_servicio'] === 'con_asesoria' ? 'Con Asesoría' : 'Publicación Directa'; ?>
                                </span>
                                <?php if ($propiedad['tipo_servicio'] === 'con_asesoria' && isset($asesor)): ?>
                                    <div class="mt-3">
                                        <h6>Asesor Asignado:</h6>
                                        <p class="mb-1"><?php echo $asesor['nombre'] . ' ' . $asesor['apellidos']; ?></p>
                                        <p class="mb-1"><i class="fas fa-envelope"></i> <?php echo $asesor['email']; ?></p>
                                        <p class="mb-0"><i class="fas fa-phone"></i> <?php echo $asesor['telefono']; ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <p class="text-muted mb-0">
                                <small>Publicado el: <?php echo date('d/m/Y', strtotime($propiedad['fecha_registro'])); ?></small>
                            </p>
                        </div>
                    </div>

                    <!-- Acciones -->
                    <div class="d-grid gap-2">
                        <a href="<?php echo url('cliente/propiedades/editar/' . $propiedad['id_inmueble']); ?>" 
                           class="btn btn-warning">
                            <i class="fas fa-edit"></i> Editar Propiedad
                        </a>
                        <?php if ($propiedad['estado_inmueble'] === 'disponible'): ?>
                            <button class="btn btn-danger" onclick="confirmarEliminacion(<?php echo $propiedad['id_inmueble']; ?>)">
                                <i class="fas fa-trash"></i> Eliminar Propiedad
                            </button>
                        <?php endif; ?>
                        <a href="<?php echo url('cliente/propiedades'); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación de Eliminación -->
<div class="modal fade" id="eliminarModal" tabindex="-1">
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
function confirmarEliminacion(idPropiedad) {
    const modal = new bootstrap.Modal(document.getElementById('eliminarModal'));
    modal.show();
    
    document.getElementById('btnConfirmarEliminar').onclick = function() {
        window.location.href = `<?php echo url('cliente/propiedades/eliminar/'); ?>${idPropiedad}`;
    };
}
</script>
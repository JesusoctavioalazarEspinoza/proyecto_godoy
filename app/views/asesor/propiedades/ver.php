<div class="container-fluid py-4">
    <div class="row">
        <?php include '../sidebar.php'; ?>
        
        <div class="col-12 col-lg-10">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><?php echo $propiedad['titulo']; ?></h5>
                        <div>
                            <a href="<?php echo url('asesor/propiedades/editar/' . $propiedad['id_inmueble']); ?>" 
                               class="btn btn-warning">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="<?php echo url('asesor/propiedades'); ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Galería de Imágenes -->
                    <div id="carouselPropiedad" class="carousel slide mb-4" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php foreach ($imagenes as $index => $imagen): ?>
                                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                    <img src="<?php echo url('uploads/propiedades/' . $propiedad['id_inmueble'] . '/' . $imagen['url_imagen']); ?>" 
                                         class="d-block w-100" style="height: 400px; object-fit: cover;">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if (count($imagenes) > 1): ?>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselPropiedad" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselPropiedad" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        <?php endif; ?>
                    </div>

                    <div class="row">
                        <!-- Información Principal -->
                        <div class="col-md-8">
                            <h6>Descripción</h6>
                            <p><?php echo nl2br($propiedad['descripcion']); ?></p>

                            <h6 class="mt-4">Características</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <p><strong>Tipo:</strong> <?php echo ucfirst($propiedad['tipo_inmueble']); ?></p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Superficie:</strong> <?php echo $propiedad['superficie']; ?> m²</p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Habitaciones:</strong> <?php echo $propiedad['num_habitaciones']; ?></p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Baños:</strong> <?php echo $propiedad['num_baños']; ?></p>
                                </div>
                            </div>

                            <h6 class="mt-4">Ubicación</h6>
                            <p><?php echo $propiedad['direccion_completa']; ?></p>
                            <p><?php echo $propiedad['ciudad'] . ', ' . $propiedad['estado'] . ' ' . $propiedad['codigo_postal']; ?></p>
                        </div>

                        <!-- Información Lateral -->
                        <div class="col-md-4">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Detalles</h5>
                                    <p class="h3 text-primary"><?php echo PropiedadHelper::formatearPrecio($propiedad['precio']); ?></p>
                                    <hr>
                                    <p class="mb-2">
                                        <strong>Estado:</strong>
                                        <span class="badge bg-<?php echo PropiedadHelper::obtenerEstadoClase($propiedad['estado_inmueble']); ?>">
                                            <?php echo ucfirst($propiedad['estado_inmueble']); ?>
                                        </span>
                                    </p>
                                    <p class="mb-2">
                                        <strong>Tipo de Servicio:</strong>
                                        <?php echo $propiedad['tipo_servicio'] === 'con_asesoria' ? 'Con Asesoría' : 'Sin Asesoría'; ?>
                                    </p>
                                    <p class="mb-0">
                                        <strong>Fecha de Publicación:</strong>
                                        <?php echo date('d/m/Y', strtotime($propiedad['fecha_registro'])); ?>
                                    </p>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Propietario</h5>
                                    <p class="mb-2">
                                        <strong>Nombre:</strong>
                                        <?php echo $propiedad['nombre_propietario']; ?>
                                    </p>
                                    <p class="mb-2">
                                        <strong>Email:</strong>
                                        <?php echo $propiedad['email_propietario']; ?>
                                    </p>
                                    <p class="mb-0">
                                        <strong>Teléfono:</strong>
                                        <?php echo $propiedad['telefono_propietario']; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
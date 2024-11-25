// app/views/propiedades/ver.php
<div class="container py-4">
    <!-- Encabezado -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start flex-wrap">
                <div>
                    <h2 class="mb-2"><?php echo $propiedad['titulo']; ?></h2>
                    <p class="text-muted mb-2">
                        <i class="fas fa-map-marker-alt"></i> 
                        <?php echo $propiedad['direccion_completa']; ?>
                    </p>
                </div>
                <div class="text-end">
                    <h3 class="text-primary mb-2">$<?php echo number_format($propiedad['precio'], 2); ?></h3>
                    <span class="badge bg-info">
                        <?php echo ucfirst($propiedad['tipo_inmueble']); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Galería y Detalles -->
        <div class="col-md-8">
            <!-- Galería de Imágenes -->
            <div class="card mb-4">
                <div class="card-body p-0">
                    <div id="carouselPropiedad" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php foreach ($imagenes as $index => $imagen): ?>
                                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                    <img src="<?php echo url('uploads/propiedades/' . $propiedad['id_inmueble'] . '/' . $imagen['url_imagen']); ?>" 
                                         class="d-block w-100" style="height: 500px; object-fit: cover;"
                                         alt="<?php echo $propiedad['titulo']; ?>">
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
                </div>
            </div>

            <!-- Características -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Características</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <?php if ($propiedad['superficie']): ?>
                            <div class="col-md-3 mb-3">
                                <div class="feature-icon">
                                    <i class="fas fa-ruler-combined fa-2x text-primary"></i>
                                </div>
                                <h6 class="mt-2">Superficie</h6>
                                <p class="mb-0"><?php echo $propiedad['superficie']; ?> m²</p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($propiedad['num_habitaciones']): ?>
                            <div class="col-md-3 mb-3">
                                <div class="feature-icon">
                                    <i class="fas fa-bed fa-2x text-primary"></i>
                                </div>
                                <h6 class="mt-2">Habitaciones</h6>
                                <p class="mb-0"><?php echo $propiedad['num_habitaciones']; ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($propiedad['num_baños']): ?>
                            <div class="col-md-3 mb-3">
                                <div class="feature-icon">
                                    <i class="fas fa-bath fa-2x text-primary"></i>
                                </div>
                                <h6 class="mt-2">Baños</h6>
                                <p class="mb-0"><?php echo $propiedad['num_baños']; ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($propiedad['estacionamientos']): ?>
                            <div class="col-md-3 mb-3">
                                <div class="feature-icon">
                                    <i class="fas fa-car fa-2x text-primary"></i>
                                </div>
                                <h6 class="mt-2">Estacionamientos</h6>
                                <p class="mb-0"><?php echo $propiedad['estacionamientos']; ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Descripción -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Descripción</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0"><?php echo nl2br($propiedad['descripcion']); ?></p>
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
                                    <div class="card h-100">
                                        <?php if ($estancia['imagen']): ?>
                                            <img src="<?php echo url('uploads/propiedades/' . $propiedad['id_inmueble'] . '/estancias/' . $estancia['imagen']); ?>" 
                                                 class="card-img-top" style="height: 200px; object-fit: cover;">
                                        <?php endif; ?>
                                        <div class="card-body">
                                            <h6 class="card-title"><?php echo ucfirst($estancia['tipo_estancia']); ?></h6>
                                            <p class="card-text"><?php echo $estancia['descripcion']; ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Información del Asesor -->
            <?php if ($propiedad['tipo_servicio'] === 'con_asesoria'): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Asesor Inmobiliario</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-circle me-3">
                                <?php echo strtoupper(substr($propiedad['nombre_asesor'], 0, 1)); ?>
                            </div>
                            <div>
                                <h6 class="mb-0">
                                    <?php echo $propiedad['nombre_asesor'] . ' ' . $propiedad['apellidos_asesor']; ?>
                                </h6>
                                <small class="text-muted">Asesor Profesional</small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <p class="mb-2">
                                <i class="fas fa-envelope"></i> <?php echo $propiedad['email_asesor']; ?>
                            </p>
                            <p class="mb-0">
                                <i class="fas fa-phone"></i> <?php echo $propiedad['telefono_asesor']; ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Formulario de Contacto -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Solicitar Información</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($exito)): ?>
                        <div class="alert alert-success">
                            <?php echo $exito; ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo url('propiedades/contactar/' . $propiedad['id_inmueble']); ?>" 
                          method="POST" id="formContacto">
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" name="telefono">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mensaje</label>
                            <textarea class="form-control" name="mensaje" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            Enviar Mensaje
                        </button>
                    </form>
                </div>
            </div>

            <!-- Propiedades Similares -->
            <?php if (!empty($similares)): ?>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Propiedades Similares</h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($similares as $similar): ?>
                            <div class="mb-3">
                                <div class="row g-0">
                                    <div class="col-4">
                                        <img src="<?php echo url('uploads/propiedades/' . $similar['id_inmueble'] . '/' . $similar['imagen']); ?>" 
                                             class="img-fluid rounded" style="height: 80px; object-fit: cover;">
                                    </div>
                                    <div class="col-8 ps-3">
                                        <h6 class="mb-1"><?php echo $similar['titulo']; ?></h6>
                                        <p class="text-primary mb-1">$<?php echo number_format($similar['precio'], 2); ?></p>
                                        <a href="<?php echo url('propiedades/ver/' . $similar['id_inmueble']); ?>" 
                                           class="btn btn-sm btn-outline-primary">Ver Detalles</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.feature-icon {
    width: 60px;
    height: 60px;
    background-color: #f8f9fa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.avatar-circle {
    width: 50px;
    height: 50px;
    background-color: #007bff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    font-weight: bold;
}

.carousel-item img {
    transition: transform 0.3s ease;
}

.carousel-item:hover img {
    transform: scale(1.02);
}

/* Responsive */
@media (max-width: 768px) {
    .carousel-item img {
        height: 300px !important;
    }
}
</style>
//app/views/home/index.php
<!-- Sección Hero -->
<section class="hero-section py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-4">Encuentra tu hogar ideal</h1>
                <p class="lead">Explora las mejores propiedades en el mercado con Godoy Houses.</p>
                <form action="<?php echo BASE_URL; ?>/propiedades/buscar" method="GET" class="mt-4">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Buscar propiedades..." name="q">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-md-6">
                <img src="<?php echo BASE_URL; ?>/public/img/hero-image.jpg" alt="Casa" class="img-fluid rounded">
            </div>
        </div>
    </div>
</section>

<!-- Propiedades Destacadas -->
<section class="featured-properties py-5">
    <div class="container">
        <h2 class="text-center mb-4">Propiedades Destacadas</h2>
        <div class="row">
            <?php foreach ($propiedadesDestacadas as $propiedad): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="<?php echo BASE_URL; ?>/public/uploads/propiedades/<?php echo $propiedad['id_inmueble']; ?>/principal.jpg" 
                                class="card-img-top" alt="<?php echo $propiedad['titulo']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $propiedad['titulo']; ?></h5>
                            <p class="card-text">
                                <i class="fas fa-map-marker-alt"></i> <?php echo $propiedad['ciudad']; ?>, <?php echo $propiedad['estado']; ?>
                            </p>
                            <p class="card-text">
                                <strong>$<?php echo number_format($propiedad['precio'], 2); ?></strong>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">
                                        <i class="fas fa-bed"></i> <?php echo $propiedad['num_habitaciones']; ?> |
                                        <i class="fas fa-bath"></i> <?php echo $propiedad['num_baños']; ?> |
                                        <i class="fas fa-car"></i> <?php echo $propiedad['estacionamientos']; ?>
                                    </small>
                                </div>
                                <a href="<?php echo BASE_URL; ?>/propiedades/ver/<?php echo $propiedad['id_inmueble']; ?>" 
                                    class="btn btn-primary btn-sm">Ver Detalles</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Últimas Propiedades -->
<section class="latest-properties py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4">Últimas Propiedades</h2>
        <div class="row">
            <?php foreach ($ultimasPropiedades as $propiedad): ?>
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <img src="<?php echo BASE_URL; ?>/public/uploads/propiedades/<?php echo $propiedad['id_inmueble']; ?>/principal.jpg" 
                                class="card-img-top" alt="<?php echo $propiedad['titulo']; ?>">
                        <div class="card-body">
                            <h6 class="card-title"><?php echo $propiedad['titulo']; ?></h6>
                            <p class="card-text">
                                <small>
                                    <i class="fas fa-map-marker-alt"></i> <?php echo $propiedad['ciudad']; ?>
                                </small>
                            </p>
                            <p class="card-text">
                                <strong>$<?php echo number_format($propiedad['precio'], 2); ?></strong>
                            </p>
                            <a href="<?php echo BASE_URL; ?>/propiedades/ver/<?php echo $propiedad['id_inmueble']; ?>" 
                                class="btn btn-outline-primary btn-sm w-100">Ver Detalles</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
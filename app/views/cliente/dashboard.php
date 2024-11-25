// app/views/cliente/dashboard.php
<div class="container-fluid py-4">
    <div class="row">
        <!-- Menú Lateral -->
        <div class="col-12 col-lg-2">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Mi Panel</h5>
                    <nav class="nav flex-column">
                        <a class="nav-link active" href="<?php echo url('cliente/dashboard'); ?>">
                            <i class="fas fa-home"></i> Inicio
                        </a>
                        <a class="nav-link" href="<?php echo url('cliente/propiedades/publicar'); ?>">
                            <i class="fas fa-plus"></i> Publicar Propiedad
                        </a>
                        <a class="nav-link" href="<?php echo url('cliente/propiedades'); ?>">
                            <i class="fas fa-building"></i> Mis Propiedades
                        </a>
                        <a class="nav-link" href="<?php echo url('cliente/citas'); ?>">
                            <i class="fas fa-calendar"></i> Mis Citas
                        </a>
                        <a class="nav-link" href="<?php echo url('cliente/perfil'); ?>">
                            <i class="fas fa-user"></i> Mi Perfil
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Contenido Principal -->
        <div class="col-12 col-lg-10">
            <!-- Resumen de Propiedades -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Mis Propiedades</h5>
                            <h2><?php echo count($propiedades); ?></h2>
                            <p class="mb-0">Total de propiedades publicadas</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Próximas Citas</h5>
                            <h2><?php echo count($citas); ?></h2>
                            <p class="mb-0">Citas programadas</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Últimas Propiedades -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Mis Últimas Propiedades</h5>
                    <a href="<?php echo url('cliente/propiedades/publicar'); ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Nueva Propiedad
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php foreach (array_slice($propiedades, 0, 3) as $propiedad): ?>
                            <div class="col-md-4">
                                <div class="card">
                                    <img src="<?php echo url('uploads/propiedades/' . $propiedad['id_inmueble'] . '/' . $propiedad['imagen_principal']); ?>" 
                                         class="card-img-top" alt="<?php echo $propiedad['titulo']; ?>">
                                    <div class="card-body">
                                        <h6 class="card-title"><?php echo $propiedad['titulo']; ?></h6>
                                        <p class="card-text">
                                            <strong>Precio:</strong> $<?php echo number_format($propiedad['precio'], 2); ?><br>
                                            <strong>Estado:</strong> <?php echo ucfirst($propiedad['estado_inmueble']); ?>
                                        </p>
                                        <a href="<?php echo url('cliente/propiedades/editar/' . $propiedad['id_inmueble']); ?>" 
                                           class="btn btn-sm btn-primary">Editar</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Próximas Citas -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Próximas Citas</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($citas)): ?>
                        <p class="text-muted">No hay citas programadas</p>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($citas as $cita): ?>
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1"><?php echo $cita['propiedad']; ?></h6>
                                        <small>
                                            <?php echo date('d/m/Y H:i', strtotime($cita['fecha_hora'])); ?>
                                        </small>
                                    </div>
                                    <p class="mb-1">Asesor: <?php echo $cita['asesor']; ?></p>
                                    <small><?php echo $cita['lugar_reunion']; ?></small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
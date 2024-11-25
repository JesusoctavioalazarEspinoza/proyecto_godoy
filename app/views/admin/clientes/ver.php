app/view/admin/clientes/ver
<div class="container-fluid py-4">
    <div class="row">
        <?php include VIEW_PATH . '/admin/sidebar.php'; ?>
        
        <div class="col-12 col-lg-10">
            <!-- Encabezado -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">Detalles del Cliente</h4>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="<?php echo url('admin/dashboard'); ?>">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="<?php echo url('admin/clientes'); ?>">Clientes</a></li>
                                    <li class="breadcrumb-item active">Ver Cliente</li>
                                </ol>
                            </nav>
                        </div>
                        <div>
                            <a href="<?php echo url('admin/clientes/editar/' . $cliente['id_usuario']); ?>" 
                               class="btn btn-warning">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <?php if ($cliente['activo']): ?>
                                <button type="button" class="btn btn-danger" onclick="confirmarDesactivacion()">
                                    <i class="fas fa-user-times"></i> Desactivar
                                </button>
                            <?php else: ?>
                                <button type="button" class="btn btn-success" onclick="confirmarActivacion()">
                                    <i class="fas fa-user-check"></i> Activar
                                </button>
                            <?php endif; ?>
                            <a href="<?php echo url('admin/clientes'); ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Información del Cliente -->
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <div class="avatar-xl mx-auto">
                                    <span class="avatar-initial rounded-circle bg-primary">
                                        <?php echo strtoupper(substr($cliente['nombre'], 0, 1)); ?>
                                    </span>
                                </div>
                            </div>
                            <h5><?php echo $cliente['nombre'] . ' ' . $cliente['apellidos']; ?></h5>
                            <p class="text-muted mb-3">Cliente desde <?php echo date('d/m/Y', strtotime($cliente['fecha_registro'])); ?></p>
                            <span class="badge bg-<?php echo $cliente['activo'] ? 'success' : 'danger'; ?> mb-3">
                                <?php echo $cliente['activo'] ? 'Activo' : 'Inactivo'; ?>
                            </span>

                            <hr>

                            <div class="text-start">
                                <p class="mb-2">
                                    <i class="fas fa-envelope"></i> <?php echo $cliente['email']; ?>
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-phone"></i> <?php echo $cliente['telefono']; ?>
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-map-marker-alt"></i> 
                                    <?php echo $cliente['direccion']; ?><br>
                                    <span class="ms-4">
                                        <?php echo $cliente['ciudad'] . ', ' . $cliente['estado'] . ' ' . $cliente['codigo_postal']; ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Estadísticas -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Estadísticas</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box bg-primary text-white me-3">
                                            <i class="fas fa-building"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Propiedades</small>
                                            <h6 class="mb-0"><?php echo $stats['total_propiedades']; ?></h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box bg-success text-white me-3">
                                            <i class="fas fa-dollar-sign"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Ventas</small>
                                            <h6 class="mb-0"><?php echo $stats['total_ventas']; ?></h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box bg-warning text-white me-3">
                                            <i class="fas fa-calendar"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Citas</small>
                                            <h6 class="mb-0"><?php echo $stats['total_citas']; ?></h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box bg-info text-white me-3">
                                            <i class="fas fa-user-clock"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Días activo</small>
                                            <h6 class="mb-0"><?php echo $stats['dias_activo']; ?></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Asesor Asignado -->
                    <?php if ($asesor): ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Asesor Asignado</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3">
                                    <span class="avatar-initial rounded-circle bg-primary">
                                        <?php echo strtoupper(substr($asesor['nombre'], 0, 1)); ?>
                                    </span>
                                </div>
                                <div>
                                    <h6 class="mb-1"><?php echo $asesor['nombre'] . ' ' . $asesor['apellidos']; ?></h6>
                                    <small class="text-muted"><?php echo $asesor['email']; ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-8">
                    <!-- Propiedades -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Propiedades</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Título</th>
                                            <th>Precio</th>
                                            <th>Estado</th>
                                            <th>Publicado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($propiedades as $propiedad): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="<?php echo url('uploads/propiedades/' . $propiedad['id_inmueble'] . '/' . $propiedad['imagen']); ?>" 
                                                         class="me-2" alt="" style="width: 40px; height: 40px; object-fit: cover;">
                                                    <span><?php echo $propiedad['titulo']; ?></span>
                                                </div>
                                            </td>
                                            <td>$<?php echo number_format($propiedad['precio'], 2); ?></td>
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
                                                <a href="<?php echo url('admin/propiedades/ver/' . $propiedad['id_inmueble']); ?>" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Historial de Citas -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Historial de Citas</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Propiedad</th>
                                            <th>Asesor</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($citas as $cita): ?>
                                        <tr>
                                            <td><?php echo date('d/m/Y H:i', strtotime($cita['fecha_hora'])); ?></td>
                                            <td><?php echo $cita['titulo_propiedad']; ?></td>
                                            <td><?php echo $cita['nombre_asesor']; ?></td>
                                            <td>
                                                <span class="badge bg-<?php 
                                                    echo $cita['estado'] === 'programada' ? 'warning' : 
                                                        ($cita['estado'] === 'realizada' ? 'success' : 'danger'); 
                                                ?>">
                                                    <?php echo ucfirst($cita['estado']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Registro de Actividad -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Registro de Actividad</h6>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <?php foreach ($actividades as $actividad): ?>
                                <div class="timeline-item">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="mb-1"><?php echo $actividad['descripcion']; ?></h6>
                                            <small class="text-muted">
                                                <?php echo date('d/m/Y H:i', strtotime($actividad['fecha'])); ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación -->
<div class="modal fade" id="modalConfirmacion" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalConfirmacionTitulo">Confirmar Acción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="modalConfirmacionMensaje"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnConfirmarAccion">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<script>
function confirmarDesactivacion() {
    document.getElementById('modalConfirmacionTitulo').textContent = 'Desactivar Cliente';
    document.getElementById('modalConfirmacionMensaje').textContent = 
        '¿Está seguro que desea desactivar este cliente? Sus publicaciones activas serán suspendidas.';
    document.getElementById('btnConfirmarAccion').className = 'btn btn-danger';
    document.getElementById('btnConfirmarAccion').onclick = function() {
        window.location.href = '<?php echo url('admin/clientes/desactivar/' . $cliente['id_usuario']); ?>';
    };
    
    new bootstrap.Modal(document.getElementById('modalConfirmacion')).show();
}

function confirmarActivacion() {
    document.getElementById('modalConfirmacionTitulo').textContent = 'Activar Cliente';
    document.getElementById('modalConfirmacionMensaje').textContent = 
        '¿Está seguro que desea activar este cliente? Sus publicaciones previas serán reactivadas.';
        document.getElementById('btnConfirmarAccion').className = 'btn btn-success';
    document.getElementById('btnConfirmarAccion').onclick = function() {
        window.location.href = '<?php echo url('admin/clientes/activar/' . $cliente['id_usuario']); ?>';
    };
    
    new bootstrap.Modal(document.getElementById('modalConfirmacion')).show();
}
</script>

<style>
.avatar {
    width: 40px;
    height: 40px;
}

.avatar-xl {
    width: 100px;
    height: 100px;
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

.icon-box {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.timeline {
    position: relative;
    padding: 20px 0;
}

.timeline-item {
    position: relative;
    padding-left: 40px;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: 0;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background-color: #007bff;
    border: 3px solid #fff;
    box-shadow: 0 0 0 3px #007bff;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: 9px;
    top: 20px;
    height: calc(100% + 10px);
    width: 2px;
    background-color: #007bff;
}

.timeline-item:last-child::before {
    display: none;
}

.timeline-content {
    padding: 15px;
    background-color: #f8f9fa;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.badge {
    padding: 0.5em 1em;
    font-weight: normal;
}

.table td {
    vertical-align: middle;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.btn-group .btn {
    padding: 0.25rem 0.5rem;
}

.fas {
    width: 1.2em;
    text-align: center;
    margin-right: 0.3em;
}

.breadcrumb {
    margin-bottom: 0;
}

.breadcrumb-item a {
    color: #007bff;
    text-decoration: none;
}

.breadcrumb-item a:hover {
    text-decoration: underline;
}

.stats-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-size: 1.2rem;
    margin-bottom: 0.5rem;
}

.table img {
    border-radius: 4px;
}

.card-header {
    background-color: transparent;
    border-bottom: 1px solid rgba(0,0,0,.125);
}

.card-header h6 {
    margin: 0;
    font-weight: 600;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.text-muted {
    font-size: 0.875rem;
}

.ms-4 {
    margin-left: 2.5rem !important;
}

@media (max-width: 768px) {
    .avatar-xl {
        width: 80px;
        height: 80px;
    }
    
    .timeline-item {
        padding-left: 30px;
    }
    
    .timeline-marker {
        width: 15px;
        height: 15px;
    }
    
    .timeline-item::before {
        left: 7px;
    }
}
</style>
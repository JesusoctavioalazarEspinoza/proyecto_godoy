// app/views/cliente/sidebar.php 
<?php
// Verificar si la sesión está iniciada y obtener datos del usuario de forma segura
$nombreUsuario = isset($_SESSION['usuario_nombre']) ? $_SESSION['usuario_nombre'] : 'Cliente';
$usuarioId = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : null;
?>

<div class="col-12 col-lg-2">
    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center mb-3">
                <div class="avatar me-3">
                    <span class="avatar-initial rounded bg-primary">
                    <?php echo strtoupper(substr($nombreUsuario, 0, 1)); ?>
                    </span>
                </div>
                <div>
                <h6 class="mb-0"><?php echo $nombreUsuario; ?></h6>
                <small class="text-muted">Cliente</small>
                </div>
            </div>

            <nav class="nav flex-column">
                <a class="nav-link <?php echo url_is('cliente/dashboard') ? 'active' : ''; ?>" 
                   href="<?php echo url('cliente/dashboard'); ?>">
                    <i class="fas fa-home"></i> Inicio
                </a>
                
                <a class="nav-link <?php echo url_is('cliente/propiedades/publicar') ? 'active' : ''; ?>" 
                   href="<?php echo url('cliente/propiedades/publicar'); ?>">
                    <i class="fas fa-plus"></i> Publicar Propiedad
                </a>
                
                <a class="nav-link <?php echo url_is('cliente/propiedades') ? 'active' : ''; ?>" 
                   href="<?php echo url('cliente/propiedades'); ?>">
                    <i class="fas fa-building"></i> Mis Propiedades
                </a>
                
                <a class="nav-link <?php echo url_is('cliente/citas') ? 'active' : ''; ?>" 
                   href="<?php echo url('cliente/citas'); ?>">
                    <i class="fas fa-calendar"></i> Mis Citas
                </a>
                
                <a class="nav-link <?php echo url_is('cliente/perfil') ? 'active' : ''; ?>" 
                   href="<?php echo url('cliente/perfil'); ?>">
                    <i class="fas fa-user"></i> Mi Perfil
                </a>
                
                <a class="nav-link text-danger" href="<?php echo url('auth/logout'); ?>">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </a>
            </nav>
        </div>
    </div>
</div>
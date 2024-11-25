// app/views/admin/sidebar.php 
<?php
// Verificar si la sesión está iniciada y si existen las variables
$nombreUsuario = isset($_SESSION['usuario_nombre']) ? $_SESSION['usuario_nombre'] : 'Usuario';
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
                <small class="text-muted">Administrador</small>
                </div>
            </div>

            <nav class="nav flex-column">
                <a class="nav-link <?php echo url_is('admin/dashboard') ? 'active' : ''; ?>" 
                   href="<?php echo url('admin/dashboard'); ?>">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                
                <a class="nav-link <?php echo url_is('admin/asesores') ? 'active' : ''; ?>" 
                   href="<?php echo url('admin/asesores'); ?>">
                    <i class="fas fa-users"></i> Asesores
                </a>
                
                <a class="nav-link <?php echo url_is('admin/clientes') ? 'active' : ''; ?>" 
                   href="<?php echo url('admin/clientes'); ?>">
                    <i class="fas fa-user-friends"></i> Clientes
                </a>
                
                <a class="nav-link <?php echo url_is('admin/propiedades') ? 'active' : ''; ?>" 
                   href="<?php echo url('admin/propiedades'); ?>">
                    <i class="fas fa-building"></i> Propiedades
                </a>
                
                <a class="nav-link <?php echo url_is('admin/reportes') ? 'active' : ''; ?>" 
                   href="<?php echo url('admin/reportes'); ?>">
                    <i class="fas fa-chart-line"></i> Reportes
                </a>
                
                <a class="nav-link <?php echo url_is('admin/configuracion') ? 'active' : ''; ?>" 
                   href="<?php echo url('admin/configuracion'); ?>">
                    <i class="fas fa-cog"></i> Configuración
                </a>
                
                <a class="nav-link text-danger" href="<?php echo url('auth/logout'); ?>">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </a>
            </nav>
        </div>
    </div>
</div>
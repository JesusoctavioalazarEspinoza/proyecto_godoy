<!-- app/views/asesor/sidebar.php -->
<div class="col-12 col-lg-2">
    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center mb-3">
                <div class="avatar me-3">
                    <span class="avatar-initial rounded bg-primary">
                        <?php echo strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)); ?>
                    </span>
                </div>
                <div>
                    <h6 class="mb-0"><?php echo $_SESSION['usuario_nombre']; ?></h6>
                    <small class="text-muted">Asesor Inmobiliario</small>
                </div>
            </div>

            <nav class="nav flex-column">
                <a class="nav-link <?php echo url_is('asesor/dashboard') ? 'active' : ''; ?>" 
                   href="<?php echo url('asesor/dashboard'); ?>">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                
                <a class="nav-link <?php echo url_is('asesor/propiedades') ? 'active' : ''; ?>" 
                   href="<?php echo url('asesor/propiedades'); ?>">
                    <i class="fas fa-building"></i> Propiedades
                </a>
                
                <a class="nav-link <?php echo url_is('asesor/citas') ? 'active' : ''; ?>" 
                   href="<?php echo url('asesor/citas'); ?>">
                    <i class="fas fa-calendar"></i> Citas
                </a>
                
                <a class="nav-link <?php echo url_is('asesor/clientes') ? 'active' : ''; ?>" 
                   href="<?php echo url('asesor/clientes'); ?>">
                    <i class="fas fa-users"></i> Clientes
                </a>
                
                <a class="nav-link <?php echo url_is('asesor/reportes') ? 'active' : ''; ?>" 
                   href="<?php echo url('asesor/reportes'); ?>">
                    <i class="fas fa-chart-bar"></i> Reportes
                </a>
                
                <a class="nav-link <?php echo url_is('asesor/perfil') ? 'active' : ''; ?>" 
                   href="<?php echo url('asesor/perfil'); ?>">
                    <i class="fas fa-user-cog"></i> Mi Perfil
                </a>
                
                <a class="nav-link text-danger" href="<?php echo url('auth/logout'); ?>">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </a>
            </nav>
        </div>
    </div>
</div>
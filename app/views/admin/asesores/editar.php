app/view/admin/asesores/editar
<div class="container-fluid py-4">
    <div class="row">
        <?php include VIEW_PATH . '/admin/sidebar.php'; ?>
        
        <div class="col-12 col-lg-10">
            <!-- Encabezado -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">Editar Asesor</h4>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="<?php echo url('admin/dashboard'); ?>">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="<?php echo url('admin/asesores'); ?>">Asesores</a></li>
                                    <li class="breadcrumb-item active">Editar</li>
                                </ol>
                            </nav>
                        </div>
                        <a href="<?php echo url('admin/asesores'); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Información Personal -->
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Información Personal</h5>
                        </div>
                        <div class="card-body">
                            <form action="<?php echo url('admin/asesores/actualizar/' . $asesor['id_usuario']); ?>" 
                                  method="POST" id="formEditarAsesor">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nombre</label>
                                        <input type="text" class="form-control" name="nombre" 
                                               value="<?php echo $asesor['nombre']; ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Apellidos</label>
                                        <input type="text" class="form-control" name="apellidos" 
                                               value="<?php echo $asesor['apellidos']; ?>" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" 
                                               value="<?php echo $asesor['email']; ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Teléfono</label>
                                        <input type="tel" class="form-control" name="telefono" 
                                               value="<?php echo $asesor['telefono']; ?>" required>
                                    </div>
                                </div>

                                <div class="row">
                                <div class="col-md-12 mb-3">
                                        <label class="form-label">Dirección</label>
                                        <input type="text" class="form-control" name="direccion" 
                                               value="<?php echo $asesor['direccion']; ?>">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Ciudad</label>
                                        <input type="text" class="form-control" name="ciudad" 
                                               value="<?php echo $asesor['ciudad']; ?>">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Estado</label>
                                        <input type="text" class="form-control" name="estado" 
                                               value="<?php echo $asesor['estado']; ?>">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Código Postal</label>
                                        <input type="text" class="form-control" name="codigo_postal" 
                                               value="<?php echo $asesor['codigo_postal']; ?>">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nueva Contraseña</label>
                                        <input type="password" class="form-control" name="password" 
                                               placeholder="Dejar en blanco para mantener la actual">
                                        <small class="form-text text-muted">
                                            Mínimo 8 caracteres, incluyendo mayúsculas, minúsculas y números
                                        </small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Estado</label>
                                        <select class="form-select" name="activo">
                                            <option value="1" <?php echo $asesor['activo'] ? 'selected' : ''; ?>>Activo</option>
                                            <option value="0" <?php echo !$asesor['activo'] ? 'selected' : ''; ?>>Inactivo</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="text-end mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Guardar Cambios
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas y Resumen -->
                <div class="col-md-4">
                    <!-- Resumen de Actividad -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Resumen de Actividad</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Propiedades Activas
                                    <span class="badge bg-primary rounded-pill">
                                        <?php echo $stats['propiedades_activas'] ?? 0; ?>
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Ventas Realizadas
                                    <span class="badge bg-success rounded-pill">
                                        <?php echo $stats['total_ventas'] ?? 0; ?>
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Citas Pendientes
                                    <span class="badge bg-warning rounded-pill">
                                        <?php echo $stats['citas_pendientes'] ?? 0; ?>
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Últimas Actividades -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Últimas Actividades</h5>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <?php foreach ($actividades as $actividad): ?>
                                <div class="timeline-item">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1"><?php echo $actividad['descripcion']; ?></h6>
                                        <small class="text-muted">
                                            <?php echo date('d/m/Y H:i', strtotime($actividad['fecha'])); ?>
                                        </small>
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

<script>
// Validación del formulario
document.getElementById('formEditarAsesor').addEventListener('submit', function(e) {
    const password = this.password.value;
    
    if (password && (
        password.length < 8 || 
        !/[A-Z]/.test(password) || 
        !/[a-z]/.test(password) || 
        !/[0-9]/.test(password)
    )) {
        e.preventDefault();
        alert('La contraseña debe tener al menos 8 caracteres, incluyendo mayúsculas, minúsculas y números');
    }
});
</script>

<style>
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
</style>
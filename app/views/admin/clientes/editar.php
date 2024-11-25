app/view/admin/clientes/editar
<div class="container-fluid py-4">
    <div class="row">
        <?php include VIEW_PATH . '/admin/sidebar.php'; ?>
        
        <div class="col-12 col-lg-10">
            <!-- Encabezado -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">Editar Cliente</h4>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="<?php echo url('admin/dashboard'); ?>">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="<?php echo url('admin/clientes'); ?>">Clientes</a></li>
                                    <li class="breadcrumb-item active">Editar Cliente</li>
                                </ol>
                            </nav>
                        </div>
                        <a href="<?php echo url('admin/clientes'); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>

            <!-- Formulario de Edición -->
            <div class="card">
                <div class="card-body">
                    <form action="<?php echo url('admin/clientes/actualizar/' . $cliente['id_usuario']); ?>" 
                          method="POST" id="formEditarCliente">
                        
                        <!-- Datos Personales -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3">Datos Personales</h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-control" name="nombre" 
                                       value="<?php echo $cliente['nombre']; ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Apellidos</label>
                                <input type="text" class="form-control" name="apellidos" 
                                       value="<?php echo $cliente['apellidos']; ?>" required>
                            </div>
                        </div>

                        <!-- Contacto -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3">Información de Contacto</h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" 
                                       value="<?php echo $cliente['email']; ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" name="telefono" 
                                       value="<?php echo $cliente['telefono']; ?>" required>
                            </div>
                        </div>

                        <!-- Dirección -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3">Dirección</h5>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Dirección Completa</label>
                                <input type="text" class="form-control" name="direccion" 
                                       value="<?php echo $cliente['direccion']; ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Ciudad</label>
                                <input type="text" class="form-control" name="ciudad" 
                                       value="<?php echo $cliente['ciudad']; ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Estado</label>
                                <input type="text" class="form-control" name="estado" 
                                       value="<?php echo $cliente['estado']; ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Código Postal</label>
                                <input type="text" class="form-control" name="codigo_postal" 
                                       value="<?php echo $cliente['codigo_postal']; ?>">
                            </div>
                        </div>

                        <!-- Configuración de Cuenta -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3">Configuración de Cuenta</h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nueva Contraseña</label>
                                <input type="password" class="form-control" name="password" 
                                       placeholder="Dejar en blanco para mantener la actual">
                                <small class="form-text text-muted">
                                    Mínimo 8 caracteres, incluyendo mayúsculas, minúsculas y números
                                </small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Estado de la Cuenta</label>
                                <select class="form-select" name="activo">
                                    <option value="1" <?php echo $cliente['activo'] ? 'selected' : ''; ?>>
                                        Activo
                                    </option>
                                    <option value="0" <?php echo !$cliente['activo'] ? 'selected' : ''; ?>>
                                        Inactivo
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Asignación de Asesor -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3">Asignación de Asesor</h5>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Asesor Asignado</label>
                                <select class="form-select" name="id_asesor">
                                    <option value="">Sin asesor asignado</option>
                                    <?php foreach ($asesores as $asesor): ?>
                                        <option value="<?php echo $asesor['id_usuario']; ?>" 
                                                <?php echo ($cliente['id_asesor'] ?? '') == $asesor['id_usuario'] ? 'selected' : ''; ?>>
                                            <?php echo $asesor['nombre'] . ' ' . $asesor['apellidos']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="row">
                            <div class="col-12 text-end">
                                <button type="button" class="btn btn-secondary" onclick="history.back()">
                                    Cancelar
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('formEditarCliente').addEventListener('submit', function(e) {
    const password = this.password.value;
    
    if (password && (
        password.length < 8 || 
        !/[A-Z]/.test(password) || 
        !/[a-z]/.test(password) || 
        !/[0-9]/.test(password)
    )) {
        e.preventDefault();
        alert('La contraseña debe tener al menos 8 caracteres, incluyendo mayúsculas, minúsculas y números');
        return;
    }
});
</script>

<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

h5 {
    color: #333;
    font-weight: 600;
}

.form-label {
    font-weight: 500;
}

.form-text {
    margin-top: 0.25rem;
}

.btn {
    padding: 0.5rem 1rem;
}

.breadcrumb-item a {
    text-decoration: none;
}

.breadcrumb-item a:hover {
    text-decoration: underline;
}

@media (max-width: 768px) {
    .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
}
</style>
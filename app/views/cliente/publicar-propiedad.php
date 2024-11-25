// app/views/cliente/publicar-propiedad.php
<!-- app/views/cliente/publicar-propiedad.php -->

<!-- Al inicio de la vista, después del header -->
<?php if (isset($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $error; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($errores) && !empty($errores)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            <?php foreach ($errores as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($exito)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $exito; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<div class="container-fluid py-4">
    <div class="row">
        <!-- Menú Lateral -->
        <?php include 'sidebar.php'; ?>
        
        <!-- Contenido Principal -->
        <div class="col-12 col-lg-10">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Publicar Nueva Propiedad</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo url('cliente/propiedades/publicar'); ?>" 
                            method="POST" enctype="multipart/form-data" id="formPropiedad">
                        
                        <!-- Tipo de Servicio -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <h6 class="alert-heading">Seleccione el tipo de servicio:</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="tipo_servicio" 
                                                        value="sin_asesoria" id="sinAsesoria" checked>
                                                <label class="form-check-label" for="sinAsesoria">
                                                    <strong>Publicación Directa (Gratuita)</strong>
                                                    <ul class="mt-2">
                                                        <li>Publicación por 7 días</li>
                                                        <li>Gestión directa de la venta</li>
                                                        <li>Sin comisiones</li>
                                                    </ul>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="tipo_servicio" 
                                                       value="con_asesoria" id="conAsesoria">
                                                <label class="form-check-label" for="conAsesoria">
                                                    <strong>Con Asesoría Profesional</strong>
                                                    <ul class="mt-2">
                                                        <li>Asesor inmobiliario dedicado</li>
                                                        <li>Publicación por 3 meses</li>
                                                        <li>Gestión profesional de la venta</li>
                                                        <li>Servicio premium</li>
                                                    </ul>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información Básica -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="fw-bold mb-3">Información Básica</h6>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Título del Anuncio</label>
                                    <input type="text" class="form-control" name="titulo" required 
                                           placeholder="Ej: Casa moderna en zona residencial">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tipo de Inmueble</label>
                                    <select class="form-select" name="tipo_inmueble" required>
                                        <option value="">Seleccione tipo</option>
                                        <option value="casa">Casa</option>
                                        <option value="departamento">Departamento</option>
                                        <option value="terreno">Terreno</option>
                                        <option value="local">Local Comercial</option>
                                        <option value="oficina">Oficina</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Descripción Detallada</label>
                                    <textarea class="form-control" name="descripcion" rows="4" required
                                              placeholder="Describa las características principales de su propiedad..."></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Ubicación -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="fw-bold mb-3">Ubicación</h6>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Dirección Completa</label>
                                    <input type="text" class="form-control" name="direccion_completa" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Ciudad</label>
                                    <input type="text" class="form-control" name="ciudad" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Estado</label>
                                    <input type="text" class="form-control" name="estado" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Código Postal</label>
                                    <input type="text" class="form-control" name="codigo_postal" required>
                                </div>
                            </div>
                        </div>

                        <!-- Características -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="fw-bold mb-3">Características</h6>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Superficie (m²)</label>
                                    <input type="number" class="form-control" name="superficie" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Habitaciones</label>
                                    <input type="number" class="form-control" name="num_habitaciones">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Baños</label>
                                    <input type="number" class="form-control" name="num_baños">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Estacionamientos</label>
                                    <input type="number" class="form-control" name="estacionamientos">
                                </div>
                            </div>
                        </div>

                        <!-- Precio -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="fw-bold mb-3">Precio</h6>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Precio</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" name="precio" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Imágenes -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="fw-bold mb-3">Imágenes</h6>
                                <div class="mb-3">
                                    <input type="file" class="form-control" name="imagenes[]" 
                                           multiple accept="image/*" required>
                                    <div class="form-text">
                                        Puede seleccionar múltiples imágenes. Formatos permitidos: JPG, PNG
                                    </div>
                                </div>
                                <div id="previewImagenes" class="row g-2"></div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Publicar Propiedad
                                </button>
                                <a href="<?php echo url('cliente/propiedades'); ?>" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancelar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Preview de imágenes
document.querySelector('input[name="imagenes[]"]').addEventListener('change', function(e) {
    const previewContainer = document.getElementById('previewImagenes');
    previewContainer.innerHTML = '';
    
    Array.from(e.target.files).forEach(file => {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'col-md-2';
            div.innerHTML = `
                <div class="card">
                    <img src="${e.target.result}" class="card-img-top" 
                         style="height: 150px; object-fit: cover;">
                </div>
            `;
            previewContainer.appendChild(div);
        }
        
        reader.readAsDataURL(file);
    });
});

// Validación del formulario
document.getElementById('formPropiedad').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const required = this.querySelectorAll('[required]');
    let isValid = true;
    
    // Validar campos requeridos
    required.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
            field.classList.add('is-invalid');
        } else {
            field.classList.remove('is-invalid');
        }
    });
    
    // Validar precio
    const precio = this.querySelector('[name="precio"]');
    if (precio.value <= 0) {
        isValid = false;
        precio.classList.add('is-invalid');
        alert('El precio debe ser mayor a 0');
        return;
    }
    
    // Validar imágenes
    const imagenes = this.querySelector('[name="imagenes[]"]');
    if (imagenes.files.length === 0) {
        isValid = false;
        imagenes.classList.add('is-invalid');
        alert('Debe seleccionar al menos una imagen');
        return;
    }
    
    // Validar tamaño de archivos
    let totalSize = 0;
    Array.from(imagenes.files).forEach(file => {
        totalSize += file.size;
    });
    
    if (totalSize > 5242880) { // 5MB
        isValid = false;
        imagenes.classList.add('is-invalid');
        alert('El tamaño total de las imágenes no debe superar 5MB');
        return;
    }
    
    if (isValid) {
        this.submit();
    } else {
        alert('Por favor, complete todos los campos requeridos correctamente.');
    }
});
</script>
// app/views/publicaciones/crear.php

<div class="container py-4">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Publicar Nueva Propiedad</h5>
        </div>
        <div class="card-body">
            <?php if (isset($errores)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errores as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <form action="<?php echo url('publicaciones/crear'); ?>" method="POST" 
                  enctype="multipart/form-data" id="formPropiedad">
                  
                <!-- Información básica -->
                <div class="mb-3">
                    <label class="form-label">Título</label>
                    <input type="text" class="form-control" name="titulo" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Tipo de Inmueble</label>
                    <select class="form-select" name="tipo_inmueble" required>
                        <option value="">Seleccione tipo...</option>
                        <option value="casa">Casa</option>
                        <option value="departamento">Departamento</option>
                        <option value="terreno">Terreno</option>
                        <option value="local">Local Comercial</option>
                    </select>
                </div>
                
                <!-- Detalles -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Precio</label>
                            <input type="number" class="form-control" name="precio" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Superficie (m²)</label>
                            <input type="number" class="form-control" name="superficie" required>
                        </div>
                    </div>
                </div>
                
                <!-- Características -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Habitaciones</label>
                            <input type="number" class="form-control" name="num_habitaciones">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Baños</label>
                            <input type="number" class="form-control" name="num_baños">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Estacionamientos</label>
                            <input type="number" class="form-control" name="estacionamientos">
                        </div>
                    </div>
                </div>
                
                <!-- Ubicación -->
                <div class="mb-3">
                    <label class="form-label">Dirección Completa</label>
                    <input type="text" class="form-control" name="direccion_completa" required>
                </div>
                
                <div class="row">
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
                
                <!-- Descripción -->
                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea class="form-control" name="descripcion" rows="4" required></textarea>
                </div>
                
                <!-- Imágenes -->
                <div class="mb-3">
                    <label class="form-label">Imágenes</label>
                    <input type="file" class="form-control" name="imagenes[]" 
                           multiple accept="image/*" required>
                    <small class="form-text text-muted">
                        Puede seleccionar múltiples imágenes (máximo 5MB cada una)
                    </small>
                </div>
                
                <!-- Tipo de servicio -->
                <div class="mb-3">
                    <label class="form-label">Tipo de Servicio</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipo_servicio" 
                               value="sin_asesoria" id="sinAsesoria" checked>
                        <label class="form-check-label" for="sinAsesoria">
                            Publicación Directa
                        </label>
                    </div>
                    < class="form-check">
                        <input class="form-check-input" type="radio" name="tipo_servicio" 
                               value="con_asesoria" id="conAsesoria">
                        <label class="form-check-label" for="conAsesoria">
                            Con Asesoría Profesional
                        </label>

                </div>
                
                <!-- Botones -->
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Publicar Propiedad
                    </button>
                    <a href="<?php echo url('cliente/propiedades'); ?>" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('formPropiedad').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validar campos requeridos
    let isValid = true;
    const required = this.querySelectorAll('[required]');
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
    
    // Validar tamaño de imágenes
    let totalSize = 0;
    for (let i = 0; i < imagenes.files.length; i++) {
        totalSize += imagenes.files[i].size;
        if (imagenes.files[i].size > 5242880) { // 5MB
            isValid = false;
            alert(`La imagen ${imagenes.files[i].name} excede el tamaño máximo permitido de 5MB`);
            return;
        }
    }
    
    if (isValid) {
        this.submit();
    }
});

// Preview de imágenes
document.querySelector('[name="imagenes[]"]').addEventListener('change', function(e) {
    const previewContainer = document.createElement('div');
    previewContainer.className = 'row mt-3';
    
    for (let i = 0; i < this.files.length; i++) {
        const file = this.files[i];
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const col = document.createElement('div');
            col.className = 'col-md-2 mb-3';
            col.innerHTML = `
                <div class="card">
                    <img src="${e.target.result}" class="card-img-top" style="height: 150px; object-fit: cover;">
                    <div class="card-body p-2">
                        <small class="text-muted">${file.name}</small>
                    </div>
                </div>
            `;
            previewContainer.appendChild(col);
        };
        
        reader.readAsDataURL(file);
    }
    
    // Reemplazar preview anterior si existe
    const existingPreview = this.parentElement.querySelector('.row');
    if (existingPreview) {
        existingPreview.remove();
    }
    this.parentElement.appendChild(previewContainer);
});
</script>
// app/views/asesor/propiedades/editar.php
<div class="container-fluid py-4">
    <div class="row">
        <?php include '../sidebar.php'; ?>
        
        <div class="col-12 col-lg-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Editar Propiedad</h5>
                    <a href="<?php echo url('asesor/propiedades'); ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
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

                    <form action="<?php echo url('asesor/propiedades/editar/' . $propiedad['id_inmueble']); ?>" 
                          method="POST" enctype="multipart/form-data">
                        
                        <!-- Información básica -->
                        <div class="mb-3">
                            <label class="form-label">Título</label>
                            <input type="text" class="form-control" name="titulo" 
                                   value="<?php echo $propiedad['titulo']; ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tipo de Inmueble</label>
                                    <select class="form-select" name="tipo_inmueble" required>
                                        <?php foreach (PropiedadHelper::obtenerTiposInmueble() as $valor => $texto): ?>
                                            <option value="<?php echo $valor; ?>" 
                                                    <?php echo $propiedad['tipo_inmueble'] === $valor ? 'selected' : ''; ?>>
                                                <?php echo $texto; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Precio</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" name="precio" 
                                               value="<?php echo $propiedad['precio']; ?>" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Características -->
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Superficie (m²)</label>
                                    <input type="number" class="form-control" name="superficie" 
                                           value="<?php echo $propiedad['superficie']; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Habitaciones</label>
                                    <input type="number" class="form-control" name="num_habitaciones" 
                                           value="<?php echo $propiedad['num_habitaciones']; ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Baños</label>
                                    <input type="number" class="form-control" name="num_baños" 
                                           value="<?php echo $propiedad['num_baños']; ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Estacionamientos</label>
                                    <input type="number" class="form-control" name="estacionamientos" 
                                           value="<?php echo $propiedad['estacionamientos']; ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Ubicación -->
                        <div class="mb-3">
                            <label class="form-label">Dirección Completa</label>
                            <input type="text" class="form-control" name="direccion_completa" 
                                   value="<?php echo $propiedad['direccion_completa']; ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Ciudad</label>
                                    <input type="text" class="form-control" name="ciudad" 
                                           value="<?php echo $propiedad['ciudad']; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Estado</label>
                                    <input type="text" class="form-control" name="estado" 
                                           value="<?php echo $propiedad['estado']; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Código Postal</label>
                                    <input type="text" class="form-control" name="codigo_postal" 
                                           value="<?php echo $propiedad['codigo_postal']; ?>" required>
                                </div>
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" name="descripcion" rows="4" required><?php echo $propiedad['descripcion']; ?></textarea>
                        </div>

                        <!-- Imágenes -->
                        <div class="mb-3">
                            <label class="form-label">Imágenes Actuales</label>
                            <div class="row" id="imagenesActuales">
                                <?php foreach ($imagenes as $imagen): ?>
                                    <div class="col-md-2 mb-3">
                                        <div class="card">
                                            <img src="<?php echo url('uploads/propiedades/' . $propiedad['id_inmueble'] . '/' . $imagen['url_imagen']); ?>" 
                                                 class="card-img-top" style="height: 150px; object-fit: cover;">
                                            <div class="card-body p-2 text-center">
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                        onclick="eliminarImagen(<?php echo $imagen['id_imagen']; ?>)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <label class="form-label mt-3">Agregar Nuevas Imágenes</label>
                            <input type="file" class="form-control" name="imagenes[]" 
                                   multiple accept="image/*">
                            <small class="form-text text-muted">
                                Puede seleccionar múltiples imágenes (máximo 5MB cada una)
                            </small>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Cambios
                            </button>
                            <a href="<?php echo url('asesor/propiedades'); ?>" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function eliminarImagen(idImagen) {
    if (confirm('¿Está seguro que desea eliminar esta imagen?')) {
        fetch(`<?php echo url('asesor/propiedades/eliminar-imagen/'); ?>${idImagen}`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelector(`#imagen-${idImagen}`).remove();
            } else {
                alert('Error al eliminar la imagen');
            }
        });
    }
}

// Preview de imágenes nuevas
document.querySelector('input[name="imagenes[]"]').addEventListener('change', function(e) {
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
    
    // Mostrar preview
    const existingPreview = this.parentElement.querySelector('.row:not(#imagenesActuales)');
    if (existingPreview) {
        existingPreview.remove();
    }
    this.parentElement.appendChild(previewContainer);
});
</script>
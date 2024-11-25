<!-- app/views/cliente/propiedades/editar.php -->
<div class="container-fluid py-4">
    <div class="row">
        <?php include '../sidebar.php'; ?>
        
        <div class="col-12 col-lg-10">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Editar Propiedad</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo url('cliente/propiedades/actualizar/' . $propiedad['id_inmueble']); ?>" 
                          method="POST" enctype="multipart/form-data" id="formEditar">
                        
                        <!-- Información Básica -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Título</label>
                                <input type="text" class="form-control" name="titulo" 
                                       value="<?php echo $propiedad['titulo']; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tipo de Inmueble</label>
                                <select class="form-select" name="tipo_inmueble" required>
                                    <option value="casa" <?php echo $propiedad['tipo_inmueble'] === 'casa' ? 'selected' : ''; ?>>Casa</option>
                                    <option value="departamento" <?php echo $propiedad['tipo_inmueble'] === 'departamento' ? 'selected' : ''; ?>>Departamento</option>
                                    <option value="terreno" <?php echo $propiedad['tipo_inmueble'] === 'terreno' ? 'selected' : ''; ?>>Terreno</option>
                                    <option value="local" <?php echo $propiedad['tipo_inmueble'] === 'local' ? 'selected' : ''; ?>>Local Comercial</option>
                                    <option value="oficina" <?php echo $propiedad['tipo_inmueble'] === 'oficina' ? 'selected' : ''; ?>>Oficina</option>
                                </select>
                            </div>
                        </div>

                        <!-- Características -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <label class="form-label">Superficie (m²)</label>
                                <input type="number" class="form-control" name="superficie" 
                                       value="<?php echo $propiedad['superficie']; ?>" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Habitaciones</label>
                                <input type="number" class="form-control" name="num_habitaciones" 
                                       value="<?php echo $propiedad['num_habitaciones']; ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Baños</label>
                                <input type="number" class="form-control" name="num_baños" 
                                       value="<?php echo $propiedad['num_baños']; ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Estacionamientos</label>
                                <input type="number" class="form-control" name="estacionamientos" 
                                       value="<?php echo $propiedad['estacionamientos']; ?>">
                            </div>
                        </div>

                        <!-- Precio -->
                        <div class="mb-4">
                            <label class="form-label">Precio</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" name="precio" 
                                       value="<?php echo $propiedad['precio']; ?>" required>
                            </div>
                        </div>

                        <!-- Ubicación -->
                        <div class="mb-4">
                            <label class="form-label">Dirección Completa</label>
                            <input type="text" class="form-control" name="direccion_completa" 
                                   value="<?php echo $propiedad['direccion_completa']; ?>" required>
                            
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <label class="form-label">Ciudad</label>
                                    <input type="text" class="form-control" name="ciudad" 
                                           value="<?php echo $propiedad['ciudad']; ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Estado</label>
                                    <input type="text" class="form-control" name="estado" 
                                           value="<?php echo $propiedad['estado']; ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Código Postal</label>
                                    <input type="text" class="form-control" name="codigo_postal" 
                                           value="<?php echo $propiedad['codigo_postal']; ?>" required>
                                </div>
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div class="mb-4">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" name="descripcion" rows="4" required><?php echo $propiedad['descripcion']; ?></textarea>
                        </div>

                        <!-- Imágenes Actuales -->
                        <div class="mb-4">
                            <label class="form-label">Imágenes Actuales</label>
                            <div class="row" id="imagenesActuales">
                                <?php foreach ($imagenes as $imagen): ?>
                                    <div class="col-md-3 mb-3">
                                        <div class="card">
                                            <img src="<?php echo url('uploads/propiedades/' . $propiedad['id_inmueble'] . '/' . $imagen['url_imagen']); ?>" 
                                                 class="card-img-top" style="height: 150px; object-fit: cover;">
                                            <div class="card-body">
                                                <button type="button" class="btn btn-sm btn-danger w-100" 
                                                        onclick="eliminarImagen(<?php echo $imagen['id_imagen']; ?>)">
                                                    <i class="fas fa-trash"></i> Eliminar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Nuevas Imágenes -->
                        <div class="mb-4">
                            <label class="form-label">Agregar Nuevas Imágenes</label>
                            <input type="file" class="form-control" name="imagenes[]" multiple accept="image/*">
                            <small class="form-text text-muted">
                                Puede seleccionar múltiples imágenes (máximo 5MB cada una)
                            </small>
                            <div id="previewNuevasImagenes" class="row mt-3"></div>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?php echo url('cliente/propiedades'); ?>" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Preview de nuevas imágenes
document.querySelector('input[name="imagenes[]"]').addEventListener('change', function(e) {
    const previewContainer = document.getElementById('previewNuevasImagenes');
    previewContainer.innerHTML = '';
    
    Array.from(e.target.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'col-md-3 mb-3';
            div.innerHTML = `
                <div class="card">
                    <img src="${e.target.result}" class="card-img-top" style="height: 150px; object-fit: cover;">
                    <div class="card-body">
                        <small class="text-muted">${file.name}</small>
                    </div>
                </div>
            `;
            previewContainer.appendChild(div);
        }
        reader.readAsDataURL(file);
    });
});

// Función para eliminar imagen
function eliminarImagen(idImagen) {
    if (confirm('¿Está seguro que desea eliminar esta imagen?')) {
        fetch(`<?php echo url('cliente/propiedades/eliminar-imagen/'); ?>${idImagen}`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelector(`[data-imagen="${idImagen}"]`).remove();
            } else {
                alert('Error al eliminar la imagen');
            }
        });
    }
}

// Validación del formulario
document.getElementById('formEditar').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const precio = this.precio.value;
    if (precio <= 0) {
        alert('El precio debe ser mayor a 0');
        return;
    }
    
    const imagenes = this.querySelector('input[type="file"]');
    if (imagenes.files.length > 0) {
        let totalSize = 0;
        Array.from(imagenes.files).forEach(file => {
            totalSize += file.size;
        });
        
        if (totalSize > 5242880) {
            alert('El tamaño total de las imágenes no debe superar 5MB');
            return;
        }
    }
    
    this.submit();
});
</script>
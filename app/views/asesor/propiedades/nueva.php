// app/views/asesor/propiedades/nueva.php
<div class="container-fluid py-4">
    <div class="row">
        <?php include '../sidebar.php'; ?>
        
        <div class="col-12 col-lg-10">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Registrar Nueva Propiedad</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo url('asesor/propiedades/guardar'); ?>" method="POST" 
                            enctype="multipart/form-data" id="formPropiedad">
                        <!-- Información Básica -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="fw-bold">Información Básica</h6>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Título de la Propiedad</label>
                                    <input type="text" class="form-control" name="titulo" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tipo de Inmueble</label>
                                    <select class="form-select" name="tipo_inmueble" required>
                                        <option value="casa">Casa</option>
                                        <option value="departamento">Departamento</option>
                                        <option value="terreno">Terreno</option>
                                        <option value="local">Local Comercial</option>
                                        <option value="oficina">Oficina</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Ubicación -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="fw-bold">Ubicación</h6>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Dirección</label>
                                    <input type="text" class="form-control" name="direccion_completa" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Ciudad</label>
                                    <input type="text" class="form-control" name="ciudad" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Estado</label>
                                    <input type="text" class="form-control" name="estado" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Código Postal</label>
                                    <input type="text" class="form-control" name="codigo_postal" required>
                                </div>
                            </div>
                        </div>

                        <!-- Características -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="fw-bold">Características</h6>
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

                        <!-- Precio y Condiciones -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="fw-bold">Precio y Condiciones</h6>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Precio</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" name="precio" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Tipo de Servicio</label>
                                    <select class="form-select" name="tipo_servicio" required>
                                        <option value="con_asesoria">Con Asesoría</option>
                                        <option value="sin_asesoria">Sin Asesoría</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                         <!-- Imágenes -->
                         <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="fw-bold">Imágenes</h6>
                                <div class="mb-3">
                                    <label class="form-label">Imágenes de la Propiedad</label>
                                    <input type="file" class="form-control" name="imagenes[]" multiple 
                                           accept="image/*" id="imagenes" required>
                                    <small class="form-text text-muted">
                                        Puede seleccionar múltiples imágenes. Formatos permitidos: JPG, PNG
                                    </small>
                                </div>
                                <div id="previewImagenes" class="row g-2 mt-2"></div>
                            </div>
                        </div>

                        <!-- Estancias -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="fw-bold d-flex justify-content-between align-items-center">
                                    Estancias
                                    <button type="button" class="btn btn-sm btn-primary" onclick="agregarEstancia()">
                                        <i class="fas fa-plus"></i> Agregar Estancia
                                    </button>
                                </h6>
                                <div id="estanciasContainer">
                                    <!-- Las estancias se agregarán aquí dinámicamente -->
                                </div>
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="fw-bold">Descripción</h6>
                                <div class="mb-3">
                                    <label class="form-label">Descripción Detallada</label>
                                    <textarea class="form-control" name="descripcion" rows="5" required></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Guardar Propiedad
                                </button>
                                <a href="<?php echo url('asesor/propiedades'); ?>" class="btn btn-secondary">
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

<!-- Template para Estancias -->
<template id="templateEstancia">
    <div class="card mb-3 estancia">
        <div class="card-body">
            <div class="row">
                <div class="col-md-11">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Tipo de Estancia</label>
                                <select class="form-select" name="estancias[tipo][]" required>
                                    <option value="sala">Sala</option>
                                    <option value="comedor">Comedor</option>
                                    <option value="cocina">Cocina</option>
                                    <option value="habitacion">Habitación</option>
                                    <option value="baño">Baño</option>
                                    <option value="jardin">Jardín</option>
                                    <option value="garage">Garage</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">Descripción de la Estancia</label>
                                <input type="text" class="form-control" name="estancias[descripcion][]" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Imágenes de la Estancia</label>
                        <input type="file" class="form-control" name="estancias[imagenes][]" multiple accept="image/*">
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarEstancia(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
// Preview de imágenes
document.getElementById('imagenes').addEventListener('change', function(e) {
    const previewContainer = document.getElementById('previewImagenes');
    previewContainer.innerHTML = '';
    
    Array.from(e.target.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'col-md-2';
            div.innerHTML = `
                <div class="card">
                    <img src="${e.target.result}" class="card-img-top" style="height: 150px; object-fit: cover;">
                </div>
            `;
            previewContainer.appendChild(div);
        }
        reader.readAsDataURL(file);
    });
});

// Gestión de estancias
function agregarEstancia() {
    const template = document.getElementById('templateEstancia');
    const container = document.getElementById('estanciasContainer');
    const clone = template.content.cloneNode(true);
    container.appendChild(clone);
}

function eliminarEstancia(button) {
    button.closest('.estancia').remove();
}

// Validación del formulario
document.getElementById('formPropiedad').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validar campos requeridos
    const required = this.querySelectorAll('[required]');
    let isValid = true;
    
    required.forEach(field => {
        if (!field.value) {
            isValid = false;
            field.classList.add('is-invalid');
        } else {
            field.classList.remove('is-invalid');
        }
    });
    
    if (isValid) {
        this.submit();
    } else {
        alert('Por favor, complete todos los campos requeridos.');
    }
});

// Inicializar con una estancia
document.addEventListener('DOMContentLoaded', function() {
    agregarEstancia();
});
</script>

<style>
.estancia {
    position: relative;
    transition: all 0.3s ease;
}

.estancia:hover {
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
}

.btn-danger {
    position: absolute;
    top: 10px;
    right: 10px;
}
</style>
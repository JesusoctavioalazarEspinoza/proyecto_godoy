// app/views/propiedades/index.php
<div class="container py-4">
    <!-- Filtros de Búsqueda -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="<?php echo url('propiedades'); ?>" method="GET" class="row g-3" id="formFiltros">
                <div class="col-md-3">
                    <label class="form-label">Tipo de Inmueble</label>
                    <select class="form-select" name="tipo">
                        <option value="">Todos los tipos</option>
                        <option value="casa" <?php echo $filtros['tipo'] === 'casa' ? 'selected' : ''; ?>>Casa</option>
                        <option value="departamento" <?php echo $filtros['tipo'] === 'departamento' ? 'selected' : ''; ?>>Departamento</option>
                        <option value="terreno" <?php echo $filtros['tipo'] === 'terreno' ? 'selected' : ''; ?>>Terreno</option>
                        <option value="local" <?php echo $filtros['tipo'] === 'local' ? 'selected' : ''; ?>>Local Comercial</option>
                        <option value="oficina" <?php echo $filtros['tipo'] === 'oficina' ? 'selected' : ''; ?>>Oficina</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Ciudad</label>
                    <select class="form-select" name="ciudad">
                        <option value="">Todas las ciudades</option>
                        <?php foreach ($ciudades as $ciudad): ?>
                            <option value="<?php echo $ciudad['ciudad']; ?>" 
                                    <?php echo $filtros['ciudad'] === $ciudad['ciudad'] ? 'selected' : ''; ?>>
                                <?php echo $ciudad['ciudad']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Precio Mínimo</label>
                    <input type="number" class="form-control" name="precio_min" 
                           value="<?php echo $filtros['precio_min']; ?>" placeholder="$ Mínimo">
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Precio Máximo</label>
                    <input type="number" class="form-control" name="precio_max" 
                           value="<?php echo $filtros['precio_max']; ?>" placeholder="$ Máximo">
                </div>
                
                <div class="col-12 text-end">
                    <a href="<?php echo url('propiedades'); ?>" class="btn btn-secondary">Limpiar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Resultados -->
    <div class="row">
        <?php if (empty($propiedades)): ?>
            <div class="col-12 text-center py-5">
                <i class="fas fa-home fa-3x text-muted mb-3"></i>
                <h4>No se encontraron propiedades</h4>
                <p class="text-muted">Intenta con otros filtros de búsqueda</p>
            </div>
        <?php else: ?>
            <?php foreach ($propiedades as $propiedad): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm property-card">
                        <img src="<?php echo url('uploads/propiedades/' . $propiedad['id_inmueble'] . '/' . $propiedad['imagen']); ?>" 
                             class="card-img-top" alt="<?php echo $propiedad['titulo']; ?>"
                             style="height: 200px; object-fit: cover;">
                        
                        <div class="card-body">
                            <h5 class="card-title mb-3"><?php echo $propiedad['titulo']; ?></h5>
                            
                            <p class="card-text text-muted mb-2">
                                <i class="fas fa-map-marker-alt"></i> 
                                <?php echo $propiedad['ciudad'] . ', ' . $propiedad['estado']; ?>
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="text-primary mb-0">
                                    $<?php echo number_format($propiedad['precio'], 2); ?>
                                </h6>
                                <span class="badge bg-info">
                                    <?php echo ucfirst($propiedad['tipo_inmueble']); ?>
                                </span>
                            </div>
                            
                            <div class="property-features mb-3">
                                <?php if ($propiedad['num_habitaciones']): ?>
                                    <span><i class="fas fa-bed"></i> <?php echo $propiedad['num_habitaciones']; ?></span>
                                <?php endif; ?>
                                <?php if ($propiedad['num_baños']): ?>
                                    <span><i class="fas fa-bath"></i> <?php echo $propiedad['num_baños']; ?></span>
                                <?php endif; ?>
                                <?php if ($propiedad['superficie']): ?>
                                    <span><i class="fas fa-ruler-combined"></i> <?php echo $propiedad['superficie']; ?> m²</span>
                                <?php endif; ?>
                            </div>
                            
                            <a href="<?php echo url('propiedades/ver/' . $propiedad['id_inmueble']); ?>" 
                               class="btn btn-outline-primary w-100">Ver Detalles</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<style>
.property-card {
    transition: transform 0.3s ease;
}

.property-card:hover {
    transform: translateY(-5px);
}

.property-features {
    display: flex;
    gap: 1rem;
    color: #666;
}

.property-features span {
    font-size: 0.9rem;
}

.property-features i {
    margin-right: 0.3rem;
}

.card-title {
    height: 48px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.card-img-top {
    position: relative;
}

.card-img-top::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 50%;
    background: linear-gradient(to top, rgba(0,0,0,0.5), transparent);
}

.badge {
    font-weight: normal;
    padding: 0.5em 1em;
}

/* Estilos para los filtros */
.form-select, .form-control {
    border-radius: 0.5rem;
}

.form-select:focus, .form-control:focus {
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
}

/* Responsive */
@media (max-width: 768px) {
    .property-features {
        flex-wrap: wrap;
    }
}
</style>

<script>
document.getElementById('formFiltros').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validar precios
    const precioMin = this.precio_min.value;
    const precioMax = this.precio_max.value;
    
    if (precioMin && precioMax && parseInt(precioMin) > parseInt(precioMax)) {
        alert('El precio mínimo no puede ser mayor al precio máximo');
        return;
    }
    
    this.submit();
});
</script>
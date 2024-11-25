<!-- app/views/contratos/crear.php -->
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Nuevo Contrato</h5>
        </div>
        <div class="card-body">
            <form action="<?php echo url('contratos/crear/' . $inmueble['id_inmueble']); ?>" 
                  method="POST" id="formContrato">
                  
                <!-- Información de la Propiedad -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h6>Detalles de la Propiedad</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Título:</strong> <?php echo $inmueble['titulo']; ?></p>
                                <p><strong>Dirección:</strong> <?php echo $inmueble['direccion_completa']; ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Precio Original:</strong> $<?php echo number_format($inmueble['precio'], 2); ?></p>
                                <p><strong>Tipo:</strong> <?php echo ucfirst($inmueble['tipo_inmueble']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información del Contrato -->
                <div class="mb-4">
                    <h6>Detalles del Contrato</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tipo de Contrato</label>
                                <select class="form-select" name="tipo_contrato" required>
                                    <option value="venta_con_asesoria">Venta con Asesoría</option>
                                    <option value="venta_sin_asesoria">Venta sin Asesoría</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Precio Acordado</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" name="precio_acordado" 
                                           required min="0" step="0.01" value="<?php echo $inmueble['precio']; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Selección de Comprador -->
                <div class="mb-4">
                    <h6>Información del Comprador</h6>
                    <div class="mb-3">
                        <label class="form-label">Seleccione el Comprador</label>
                        <select class="form-select" name="id_comprador" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($compradores as $comprador): ?>
                                <option value="<?php echo $comprador['id_usuario']; ?>">
                                    <?php echo $comprador['nombre'] . ' ' . $comprador['apellidos']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="<?php echo url('contratos'); ?>" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Crear Contrato
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('formContrato').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const precioAcordado = parseFloat(this.precio_acordado.value);
    const precioOriginal = <?php echo $inmueble['precio']; ?>;
    
    // Validar precio acordado
    if (precioAcordado <= 0) {
        alert('El precio acordado debe ser mayor a 0');
        return;
    }
    
    // Advertir si el precio es muy diferente al original
    if (precioAcordado < precioOriginal * 0.7 || precioAcordado > precioOriginal * 1.3) {
        if (!confirm('El precio acordado difiere significativamente del precio original. ¿Desea continuar?')) {
            return;
        }
    }
    
    this.submit();
});
</script>
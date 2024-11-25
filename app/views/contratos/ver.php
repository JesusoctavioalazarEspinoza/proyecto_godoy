<!-- app/views/contratos/ver.php -->
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Contrato #<?php echo $contrato->getId(); ?></h5>
            <span class="badge bg-<?php 
                echo $contrato->getEstado() === 'firmado' ? 'success' : 
                    ($contrato->getEstado() === 'en_proceso' ? 'warning' : 'danger'); 
            ?>">
                <?php echo ucfirst($contrato->getEstado()); ?>
            </span>
        </div>
        
        <div class="card-body">
            <!-- Información Principal -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6>Detalles de la Propiedad</h6>
                            <p class="mb-1"><strong>Título:</strong> <?php echo $propiedad['titulo']; ?></p>
                            <p class="mb-1"><strong>Dirección:</strong> <?php echo $propiedad['direccion_completa']; ?></p>
                            <p class="mb-1">
                                <strong>Precio Original:</strong> 
                                $<?php echo number_format($propiedad['precio'], 2); ?>
                            </p>
                            <p class="mb-0">
                                <strong>Precio Acordado:</strong> 
                                $<?php echo number_format($contrato->getPrecioAcordado(), 2); ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6>Información del Contrato</h6>
                            <p class="mb-1">
                                <strong>Tipo:</strong> 
                                <?php echo ucfirst(str_replace('_', ' ', $contrato->getTipoContrato())); ?>
                            </p>
                            <p class="mb-1">
                                <strong>Fecha de Inicio:</strong> 
                                <?php echo date('d/m/Y', strtotime($contrato['fecha_inicio'])); ?>
                            </p>
                            <?php if ($contrato['fecha_firma']): ?>
                                <p class="mb-1">
                                    <strong>Fecha de Firma:</strong> 
                                    <?php echo date('d/m/Y', strtotime($contrato['fecha_firma'])); ?>
                                </p>
                            <?php endif; ?>
                            <?php if ($contrato->getComision() > 0): ?>
                                <p class="mb-0">
                                    <strong>Comisión:</strong> 
                                    $<?php echo number_format($contrato->getComision(), 2); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
<!-- app/views/contratos/ver.php (continuación) -->

            <!-- Partes Involucradas -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6>Propietario</h6>
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar me-3">
                                    <span class="avatar-initial rounded-circle bg-primary">
                                        <?php echo strtoupper(substr($propietario['nombre'], 0, 1)); ?>
                                    </span>
                                </div>
                                <div>
                                    <p class="mb-1"><?php echo $propietario['nombre'] . ' ' . $propietario['apellidos']; ?></p>
                                    <small class="text-muted">Vendedor</small>
                                </div>
                            </div>
                            <p class="mb-1"><i class="fas fa-envelope"></i> <?php echo $propietario['email']; ?></p>
                            <p class="mb-1"><i class="fas fa-phone"></i> <?php echo $propietario['telefono']; ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6>Comprador</h6>
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar me-3">
                                    <span class="avatar-initial rounded-circle bg-success">
                                        <?php echo strtoupper(substr($comprador['nombre'], 0, 1)); ?>
                                    </span>
                                </div>
                                <div>
                                    <p class="mb-1"><?php echo $comprador['nombre'] . ' ' . $comprador['apellidos']; ?></p>
                                    <small class="text-muted">Comprador</small>
                                </div>
                            </div>
                            <p class="mb-1"><i class="fas fa-envelope"></i> <?php echo $comprador['email']; ?></p>
                            <p class="mb-1"><i class="fas fa-phone"></i> <?php echo $comprador['telefono']; ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6>Asesor</h6>
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar me-3">
                                    <span class="avatar-initial rounded-circle bg-info">
                                        <?php echo strtoupper(substr($asesor['nombre'], 0, 1)); ?>
                                    </span>
                                </div>
                                <div>
                                    <p class="mb-1"><?php echo $asesor['nombre'] . ' ' . $asesor['apellidos']; ?></p>
                                    <small class="text-muted">Asesor Inmobiliario</small>
                                </div>
                            </div>
                            <p class="mb-1"><i class="fas fa-envelope"></i> <?php echo $asesor['email']; ?></p>
                            <p class="mb-1"><i class="fas fa-phone"></i> <?php echo $asesor['telefono']; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documentación -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Documentación</h6>
                    <?php if ($contrato->getEstado() === 'en_proceso'): ?>
                        <button type="button" class="btn btn-sm btn-primary" 
                                data-bs-toggle="modal" data-bs-target="#modalDocumento">
                            <i class="fas fa-upload"></i> Subir Documento
                        </button>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($documentos as $doc): ?>
                                    <tr>
                                        <td><?php echo ucfirst(str_replace('_', ' ', $doc['tipo_documento'])); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($doc['fecha_subida'])); ?></td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                echo $doc['estado'] === 'completado' ? 'success' : 
                                                    ($doc['estado'] === 'pendiente' ? 'warning' : 'info'); 
                                            ?>">
                                                <?php echo ucfirst($doc['estado']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($doc['url_documento']): ?>
                                                <a href="<?php echo url('uploads/documentos/' . $doc['url_documento']); ?>" 
                                                   class="btn btn-sm btn-info" target="_blank">
                                                    <i class="fas fa-download"></i> Descargar
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pagos -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Historial de Pagos</h6>
                    <?php if ($contrato->getEstado() === 'firmado'): ?>
                        <button type="button" class="btn btn-sm btn-primary" 
                                data-bs-toggle="modal" data-bs-target="#modalPago">
                            <i class="fas fa-dollar-sign"></i> Registrar Pago
                        </button>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Tipo</th>
                                    <th>Monto</th>
                                    <th>Referencia</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pagos as $pago): ?>
                                    <tr>
                                        <td><?php echo date('d/m/Y', strtotime($pago['fecha_pago'])); ?></td>
                                        <td><?php echo ucfirst($pago['tipo_pago']); ?></td>
                                        <td>$<?php echo number_format($pago['monto'], 2); ?></td>
                                        <td><?php echo $pago['referencia']; ?></td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                echo $pago['estado'] === 'completado' ? 'success' : 'warning'; 
                                            ?>">
                                                <?php echo ucfirst($pago['estado']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Historial -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Historial de Actividades</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <?php foreach ($historial as $evento): ?>
                            <div class="timeline-item">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-1"><?php echo ucfirst($evento['accion']); ?></h6>
                                        <small class="text-muted">
                                            <?php echo date('d/m/Y H:i', strtotime($evento['fecha_registro'])); ?>
                                        </small>
                                    </div>
                                    <?php if ($evento['detalles']): ?>
                                        <p class="mb-0"><?php echo $evento['detalles']; ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Subida de Documento -->
<div class="modal fade" id="modalDocumento" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Subir Documento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo url('contratos/subirDocumento/' . $contrato->getId()); ?>" 
                  method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tipo de Documento</label>
                        <select class="form-select" name="tipo_documento" required>
                            <option value="contrato_compraventa">Contrato de Compraventa</option>
                            <option value="identificacion_partes">Identificación de Partes</option>
                            <option value="certificado_propiedad">Certificado de Propiedad</option>
                            <option value="avaluo_fiscal">Avalúo Fiscal</option>
                            <option value="documentacion_notarial">Documentación Notarial</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Documento</label>
                        <input type="file" class="form-control" name="documento" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Subir Documento</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Registro de Pago -->
<div class="modal fade" id="modalPago" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar Pago</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo url('contratos/registrarPago/' . $contrato->getId()); ?>" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tipo de Pago</label>
                        <select class="form-select" name="tipo_pago" required>
                            <option value="inicial">Pago Inicial</option>
                            <option value="parcial">Pago Parcial</option>
                            <option value="final">Pago Final</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Monto</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" name="monto" required step="0.01" min="0">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Referencia</label>
                        <input type="text" class="form-control" name="referencia" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Registrar Pago</button>
                </div>
            </form>
        </div>
    </div>
</div>

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

.avatar {
    width: 40px;
    height: 40px;
}

.avatar-initial {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
}
</style>
// app/views/asesor/clientes.php
<div class="container-fluid py-4">
    <div class="row">
        <!-- Menú Lateral -->
        <?php include 'sidebar.php'; ?>
        
        <!-- Contenido Principal -->
        <div class="col-12 col-lg-10">
            <!-- Tarjetas de Resumen -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Clientes</h5>
                            <h2 class="mb-0"><?php echo count($clientes); ?></h2>
                            <small>Clientes activos</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Contratos Activos</h5>
                            <h2 class="mb-0"><?php 
                                echo array_reduce($clientes, function($carry, $cliente) {
                                    return $carry + $cliente['total_contratos'];
                                }, 0);
                            ?></h2>
                            <small>En proceso</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5 class="card-title">Citas Pendientes</h5>
                            <h2 class="mb-0"><?php 
                                echo array_reduce($clientes, function($carry, $cliente) {
                                    return $carry + $cliente['total_citas'];
                                }, 0);
                            ?></h2>
                            <small>Programadas</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Clientes -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Mis Clientes</h5>
                    <div class="input-group w-auto">
                        <input type="text" class="form-control" id="buscarCliente" placeholder="Buscar cliente...">
                        <button class="btn btn-primary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Contacto</th>
                                    <th>Contratos</th>
                                    <th>Citas</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($clientes as $cliente): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-2">
                                                <span class="avatar-initial rounded-circle bg-primary">
                                                    <?php echo strtoupper(substr($cliente['nombre'], 0, 1)); ?>
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0"><?php echo $cliente['nombre'] . ' ' . $cliente['apellidos']; ?></h6>
                                                <small class="text-muted">ID: <?php echo $cliente['id_usuario']; ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <i class="fas fa-envelope"></i> <?php echo $cliente['email']; ?><br>
                                            <i class="fas fa-phone"></i> <?php echo $cliente['telefono']; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">
                                            <?php echo $cliente['total_contratos']; ?> contratos
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?php echo $cliente['total_citas']; ?> citas
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">Activo</span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?php echo url('asesor/clientes/ver/' . $cliente['id_usuario']); ?>" 
                                               class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?php echo url('asesor/citas/nueva/' . $cliente['id_usuario']); ?>" 
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-calendar-plus"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-warning" 
                                                    onclick="enviarMensaje(<?php echo $cliente['id_usuario']; ?>)">
                                                <i class="fas fa-envelope"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Mensaje -->
<div class="modal fade" id="mensajeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enviar Mensaje</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formMensaje">
                    <input type="hidden" id="clienteId" name="cliente_id">
                    <div class="mb-3">
                        <label for="asunto" class="form-label">Asunto</label>
                        <input type="text" class="form-control" id="asunto" name="asunto" required>
                    </div>
                    <div class="mb-3">
                        <label for="mensaje" class="form-label">Mensaje</label>
                        <textarea class="form-control" id="mensaje" name="mensaje" rows="4" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="enviarMensajeCliente()">Enviar</button>
            </div>
        </div>
    </div>
</div>

<script>
// Búsqueda de clientes
document.getElementById('buscarCliente').addEventListener('input', function(e) {
    const busqueda = e.target.value.toLowerCase();
    const filas = document.querySelectorAll('tbody tr');
    
    filas.forEach(fila => {
        const nombre = fila.querySelector('h6').textContent.toLowerCase();
        const email = fila.querySelector('.fa-envelope').nextSibling.textContent.toLowerCase();
        fila.style.display = nombre.includes(busqueda) || email.includes(busqueda) ? '' : 'none';
    });
});

// Función para enviar mensaje
function enviarMensaje(clienteId) {
    document.getElementById('clienteId').value = clienteId;
    new bootstrap.Modal(document.getElementById('mensajeModal')).show();
}

function enviarMensajeCliente() {
    const formData = new FormData(document.getElementById('formMensaje'));
    fetch('<?php echo url("asesor/mensajes/enviar"); ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Mensaje enviado correctamente');
            bootstrap.Modal.getInstance(document.getElementById('mensajeModal')).hide();
        } else {
            alert('Error al enviar el mensaje');
        }
    });
}
</script>
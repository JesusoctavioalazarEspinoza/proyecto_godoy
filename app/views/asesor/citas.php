// app/views/asesor/citas.php
<div class="container-fluid py-4">
    <div class="row">
        <!-- Menú Lateral -->
        <?php include 'sidebar.php'; ?>
        
        <!-- Contenido Principal -->
        <div class="col-12 col-lg-10">
            <div class="row mb-4">
                <!-- Calendario -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Calendario de Citas</h5>
                        </div>
                        <div class="card-body">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>

                <!-- Próximas Citas -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Próximas Citas</h5>
                            <a href="<?php echo url('asesor/citas/nueva'); ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus"></i> Nueva Cita
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                <?php foreach ($citas as $cita): ?>
                                    <?php if (strtotime($cita['fecha_hora']) > time()): ?>
                                        <div class="list-group-item">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1"><?php echo $cita['titulo_propiedad']; ?></h6>
                                                <small class="text-<?php echo strtotime($cita['fecha_hora']) < strtotime('+24 hours') ? 'danger' : 'muted'; ?>">
                                                    <?php echo date('d/m/Y H:i', strtotime($cita['fecha_hora'])); ?>
                                                </small>
                                            </div>
                                            <p class="mb-1">Cliente: <?php echo $cita['nombre_cliente']; ?></p>
                                            <small><?php echo $cita['lugar_reunion']; ?></small>
                                            <div class="mt-2">
                                                <button class="btn btn-sm btn-success" 
                                                        onclick="confirmarCita(<?php echo $cita['id_cita']; ?>)">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" 
                                                        onclick="cancelarCita(<?php echo $cita['id_cita']; ?>)">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historial de Citas -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Historial de Citas</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Cliente</th>
                                    <th>Propiedad</th>
                                    <th>Estado</th>
                                    <th>Notas</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($citas as $cita): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y H:i', strtotime($cita['fecha_hora'])); ?></td>
                                    <td><?php echo $cita['nombre_cliente']; ?></td>
                                    <td><?php echo $cita['titulo_propiedad']; ?></td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            echo $cita['estado'] === 'realizada' ? 'success' : 
                                                ($cita['estado'] === 'programada' ? 'warning' : 'danger'); 
                                        ?>">
                                            <?php echo ucfirst($cita['estado']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo $cita['notas']; ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-info" 
                                                onclick="verDetallesCita(<?php echo $cita['id_cita']; ?>)">
                                            <i class="fas fa-eye"></i>
                                        </button>
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

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css" rel="stylesheet">

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: <?php echo json_encode(array_map(function($cita) {
            return [
                'title' => $cita['titulo_propiedad'],
                'start' => $cita['fecha_hora'],
                'backgroundColor' => $cita['estado'] === 'programada' ? '#ffc107' : 
                    ($cita['estado'] === 'realizada' ? '#28a745' : '#dc3545')
            ];
        }, $citas)); ?>,
        eventClick: function(info) {
            // Mostrar detalles de la cita
        }
    });
    calendar.render();
});

function confirmarCita(idCita) {
    if (confirm('¿Confirmar esta cita?')) {
        window.location.href = `<?php echo url('asesor/citas/confirmar/'); ?>${idCita}`;
    }
}

function cancelarCita(idCita) {
    if (confirm('¿Cancelar esta cita?')) {
        window.location.href = `<?php echo url('asesor/citas/cancelar/'); ?>${idCita}`;
    }
}

function verDetallesCita(idCita) {
    window.location.href = `<?php echo url('asesor/citas/ver/'); ?>${idCita}`;
}
</script>
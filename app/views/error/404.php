<!-- app/views/error/404.php -->
<div class="container py-5">
    <div class="text-center">
        <h1 class="display-1">404</h1>
        <h2>Página no encontrada</h2>
        <p class="lead"><?php echo $message ?? 'La página que buscas no existe.'; ?></p>
        <a href="<?php echo url(''); ?>" class="btn btn-primary">Volver al inicio</a>
    </div>
</div>
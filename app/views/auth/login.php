//app/views/auth/login.php
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="from">
                    <h2 class="card-title text-center mb-4">Iniciar Sesión</h2>
                    
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($exito)): ?>
                        <div class="alert alert-success">
                            <?php echo $exito; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="<?php echo url('auth/login'); ?>">                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">Recordarme</label>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-3">
                    <p>¿No tienes una cuenta? <a href="<?php echo url('auth/registro'); ?>">Regístrate</a></p>
                    <p><a href="<?php echo BASE_URL; ?>/auth/recuperar">¿Olvidaste tu contraseña?</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

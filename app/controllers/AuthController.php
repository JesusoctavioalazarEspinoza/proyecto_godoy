// app/controllers/AuthController.php

<?php
class AuthController extends Controller {
    public function login() {
        if ($this->esPost()) {
            $email = $this->obtenerPost('email');
            $password = $this->obtenerPost('password');
            
            // Validar credenciales
            $sql = "SELECT * FROM usuarios WHERE email = ?";
            $usuario = $this->db->obtenerUno($sql, [$email]);
            
            if ($usuario && password_verify($password, $usuario['password'])) {
                // Iniciar sesión y guardar datos
                $_SESSION['usuario_id'] = $usuario['id_usuario'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['usuario_tipo'] = $usuario['tipo_usuario'];
                
                // Redirigir según el tipo de usuario
                switch($usuario['tipo_usuario']) {
                    case 'administrador':
                        $this->redireccionar('admin/dashboard');
                        break;
                    case 'asesor':
                        $this->redireccionar('asesor/dashboard');
                        break;
                    default:
                        $this->redireccionar('cliente/dashboard');
                }
            } else {
                $this->setDato('error', 'Credenciales inválidas');
            }
        }
        
        return $this->render('auth/login', [
            'titulo' => 'Iniciar Sesión'
        ]);
    }
    public function registro() {
        if ($this->esPost()) {
            $datos = [
                'nombre' => $this->obtenerPost('nombre'),
                'apellidos' => $this->obtenerPost('apellidos'),
                'email' => $this->obtenerPost('email'),
                'telefono' => $this->obtenerPost('telefono'),
                'password' => password_hash($this->obtenerPost('password'), PASSWORD_DEFAULT),
                'direccion' => $this->obtenerPost('direccion', ''),
                'ciudad' => $this->obtenerPost('ciudad', ''),
                'estado' => $this->obtenerPost('estado', ''),
                'codigo_postal' => $this->obtenerPost('codigo_postal', ''),
                'tipo_usuario' => 'cliente'
            ];
            
            // Validar datos
            $errores = $this->validarRegistro($datos);
            
            if (empty($errores)) {
                // Verificar si el email ya existe
                $sql = "SELECT id_usuario FROM usuarios WHERE email = ?";
                $existente = $this->db->obtenerUno($sql, [$datos['email']]);
                
                if (!$existente) {
                    // Insertar nuevo usuario
                    $sql = "INSERT INTO usuarios (nombre, apellidos, email, telefono, password, direccion, ciudad, estado, codigo_postal, tipo_usuario) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $resultado = $this->db->consulta($sql, [
                $datos['nombre'],
                $datos['apellidos'],
                $datos['email'],
                $datos['telefono'],
                $datos['password'],
                $datos['direccion'],
                $datos['ciudad'],
                $datos['estado'],
                $datos['codigo_postal'],
                $datos['tipo_usuario']
            ]);
                    
                    if ($resultado) {
                        $this->setDato('exito', 'Registro exitoso. Por favor inicia sesión.');
                        $this->redireccionar('auth/login');
                    } else {
                        $this->setDato('error', 'Error al registrar el usuario');
                    }
                } else {
                    $this->setDato('error', 'El email ya está registrado');
                }
            } else {
                $this->setDato('errores', $errores);
            }
        }
        
        return $this->render('auth/registro', [
            'titulo' => 'Registro - Godoy Houses'
        ]);
    }
    
    public function logout() {
        // Destruir todas las variables de sesión
        session_unset();
        // Destruir la sesión
        session_destroy();
        // Redirigir al login
        $this->redireccionar('auth/login');
    }
    private function validarRegistro($datos) {
        $errores = [];
        
        if (empty($datos['nombre'])) {
            $errores['nombre'] = 'El nombre es requerido';
        }
        
        if (empty($datos['apellidos'])) {
            $errores['apellidos'] = 'Los apellidos son requeridos';
        }
        
        if (empty($datos['email'])) {
            $errores['email'] = 'El email es requerido';
        } elseif (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
            $errores['email'] = 'El email no es válido';
        }
        
        if (empty($datos['telefono'])) {
            $errores['telefono'] = 'El teléfono es requerido';
        }
        
        if (empty($datos['password'])) {
            $errores['password'] = 'La contraseña es requerida';
        } elseif (strlen($datos['password']) < 6) {
            $errores['password'] = 'La contraseña debe tener al menos 6 caracteres';
        }
        
        return $errores;
    }
}
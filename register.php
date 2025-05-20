<?php
require_once 'db.php';
require_once 'auth.php';

// Redirigir si ya está logueado
if (estaLogueado()) {
    header("Location: index.php");
    exit();
}

$error = '';
$success = '';

// Procesar formulario de registro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    
    if (empty($nombre) || empty($email) || empty($password) || empty($password_confirm)) {
        $error = 'Por favor, completa todos los campos.';
    } elseif ($password !== $password_confirm) {
        $error = 'Las contraseñas no coinciden.';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';
    } else {
        // Verificar si el correo ya está registrado
        $email = $conn->real_escape_string($email);
        $sql = "SELECT id FROM usuarios WHERE email = '$email'";
        $resultado = $conn->query($sql);
        
        if ($resultado->num_rows > 0) {
            $error = 'Ya existe una cuenta con ese correo electrónico.';
        } else {
            // Encriptar contraseña
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            // Insertar nuevo usuario
            $nombre = $conn->real_escape_string($nombre);
            $sql = "INSERT INTO usuarios (nombre, email, password) VALUES ('$nombre', '$email', '$password_hash')";
            
            if ($conn->query($sql) === TRUE) {
                $success = 'Cuenta creada exitosamente. Ahora puedes iniciar sesión.';
            } else {
                $error = 'Error al crear la cuenta: ' . $conn->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse - Guía Verde</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="assets/auth.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Montserrat+Alternates:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&family=VT323&family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <a href="index.php">Guía Verde</a>
            </div>
            
            <div class="menu-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
            
            <ul class="menu">
                <li class="dropdown">
                    <a href="#">Categorías</a>
                    <ul class="submenu">
                        <li><a href="categoria.php?tipo=venenosas">Venenosas</a></li>
                        <li><a href="categoria.php?tipo=comestibles">Comestibles</a></li>
                        <li><a href="categoria.php?tipo=medicinales">Medicinales</a></li>
                        <li><a href="categoria.php?tipo=frutales">Frutales</a></li>
                        <li><a href="categoria.php?tipo=florales">Florales</a></li>
                    </ul>
                </li>
                <li><a href="preguntas.php">Preguntas</a></li>
                <li><a href="login.php">Iniciar Sesión</a></li>
                <li><a href="register.php">Registrarse</a></li>
            </ul>
            
            <button class="theme-toggle" id="themeToggle">🌛</button>
        </nav>
    </header>

    <main class="auth-container">
        <div class="auth-card">
            <h1>Crear Cuenta</h1>
            
            <?php if (!empty($error)): ?>
                <div class="error-message">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="success-message">
                    <?php echo $success; ?>
                    <p><a href="login.php">Iniciar sesión</a></p>
                </div>
            <?php else: ?>
                <form method="POST" action="register.php" class="auth-form">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirm">Confirmar Contraseña</label>
                        <input type="password" id="password_confirm" name="password_confirm" required>
                    </div>
                    
                    <button type="submit" class="btn-auth">Registrarse</button>
                </form>
            <?php endif; ?>
            
            <div class="auth-links">
                <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión</a></p>
            </div>
        </div>
        
        <div class="auth-image">
            <img src="https://images.unsplash.com/photo-1518531933037-91b2f5f229cc?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Plantas">
        </div>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Guía Verde. Todos los derechos reservados.</p>
    </footer>

    <script src="assets/script.js"></script>
</body>
</html>

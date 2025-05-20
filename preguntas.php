<?php
require_once 'db.php';
require_once 'auth.php';

// Obtener todas las preguntas
$sql = "SELECT p.*, u.nombre as autor, p.usuario_id,
        (SELECT COUNT(*) FROM respuestas WHERE pregunta_id = p.id) as num_respuestas 
        FROM preguntas p 
        JOIN usuarios u ON p.usuario_id = u.id 
        ORDER BY p.fecha DESC";
$preguntas = $conn->query($sql);

// Procesar formulario de nueva pregunta
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && estaLogueado()) {
    $titulo = $_POST['titulo'] ?? '';
    $contenido = $_POST['contenido'] ?? '';
    
    if (empty($titulo) || empty($contenido)) {
        $error = 'Por favor, completa todos los campos.';
    } else {
        $usuario_id = $_SESSION['usuario_id'];
        $titulo = $conn->real_escape_string($titulo);
        $contenido = $conn->real_escape_string($contenido);
        
        $sql = "INSERT INTO preguntas (usuario_id, titulo, contenido) VALUES ('$usuario_id', '$titulo', '$contenido')";
        
        if ($conn->query($sql) === TRUE) {
            $success = 'Tu pregunta ha sido publicada.';
            // Recargar la página para mostrar la nueva pregunta
            header("Location: preguntas.php?success=1");
            exit();
        } else {
            $error = 'Error al publicar la pregunta: ' . $conn->error;
        }
    }
}

// Mensaje de éxito desde redirección
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $success = 'Tu pregunta ha sido publicada.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preguntas y Respuestas - Guía Verde</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="assets/preguntas.css">
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
                <?php if (estaLogueado()): ?>
                    <li><a href="jardin.php">Mi Jardín</a></li>
                    <li><a href="logout.php">Cerrar Sesión</a></li>
                <?php else: ?>
                    <li><a href="login.php">Iniciar Sesión</a></li>
                    <li><a href="register.php">Registrarse</a></li>
                <?php endif; ?>
            </ul>
            
            <div class="search-bar">
                <form action="categoria.php" method="GET">
                    <input type="text" name="busqueda" placeholder="Buscar plantas...">
                    <button type="submit"><i class="icon-search">🔍</i></button>
                </form>
            </div>
            
            <button class="theme-toggle" id="themeToggle">🌛</button>
        </nav>
    </header>

    <main>
        <div class="preguntas-header">
            <h1>Preguntas y Respuestas</h1>
            <p>Comparte tus dudas sobre plantas y ayuda a otros usuarios.</p>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="error-message">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="success-message">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <div class="preguntas-container">
            <div class="preguntas-lista">
                <h2>Preguntas recientes</h2>
                
                <?php if ($preguntas->num_rows > 0): ?>
                    <?php while ($pregunta = $preguntas->fetch_assoc()): ?>
                        <div class="pregunta-card">
                            <h3>
                                <a href="pregunta.php?id=<?php echo $pregunta['id']; ?>">
                                    <?php echo htmlspecialchars($pregunta['titulo']); ?>
                                </a>
                            </h3>
                            <div class="pregunta-meta">
                                <span class="pregunta-autor">Por: <?php echo htmlspecialchars($pregunta['autor']); ?></span>
                                <span class="pregunta-fecha"><?php echo date('d/m/Y H:i', strtotime($pregunta['fecha'])); ?></span>
                                <span class="pregunta-respuestas"><?php echo $pregunta['num_respuestas']; ?> respuestas</span>
                                
                                <?php if (estaLogueado() && $_SESSION['usuario_id'] == $pregunta['usuario_id']): ?>
                                    <button class="btn-eliminar-pregunta" data-id="<?php echo $pregunta['id']; ?>">
                                        <i class="icono-eliminar">🗑️</i> Eliminar
                                    </button>
                                <?php endif; ?>
                            </div>
                            <p class="pregunta-preview">
                                <?php echo substr(htmlspecialchars($pregunta['contenido']), 0, 150); ?>
                                <?php if (strlen($pregunta['contenido']) > 150): ?>...<?php endif; ?>
                            </p>
                            <a href="pregunta.php?id=<?php echo $pregunta['id']; ?>" class="btn-ver-pregunta">Ver pregunta</a>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-preguntas">
                        <p>Aún no hay preguntas. ¡Sé el primero en preguntar!</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="nueva-pregunta">
                <h2>Hacer una pregunta</h2>
                
                <?php if (estaLogueado()): ?>
                    <form method="POST" action="preguntas.php" class="pregunta-form">
                        <div class="form-group">
                            <label for="titulo">Título de la pregunta</label>
                            <input type="text" id="titulo" name="titulo" required placeholder="Ej: ¿Cómo cuidar un cactus en interiores?">
                        </div>
                        
                        <div class="form-group">
                            <label for="contenido">Detalles de la pregunta</label>
                            <textarea id="contenido" name="contenido" rows="6" required placeholder="Describe tu pregunta con detalles para obtener mejores respuestas..."></textarea>
                        </div>
                        
                        <button type="submit" class="btn-publicar">Publicar pregunta</button>
                    </form>
                <?php else: ?>
                    <div class="login-required">
                        <p>Debes iniciar sesión para hacer preguntas.</p>
                        <a href="login.php" class="btn-login">Iniciar sesión</a>
                        <a href="register.php" class="btn-register">Registrarse</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Guía Verde. Todos los derechos reservados.</p>
    </footer>

    <script src="assets/script.js"></script>
    <script src="assets/js/preguntas.js"></script>
</body>
</html>

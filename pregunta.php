<?php
require_once 'db.php';
require_once 'auth.php';

// Obtener ID de la pregunta
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: preguntas.php");
    exit();
}

// Obtener detalles de la pregunta
$sql = "SELECT p.*, u.nombre as autor, p.usuario_id FROM preguntas p JOIN usuarios u ON p.usuario_id = u.id WHERE p.id = '$id'";
$resultado = $conn->query($sql);

if ($resultado->num_rows === 0) {
    header("Location: preguntas.php");
    exit();
}

$pregunta = $resultado->fetch_assoc();

// Obtener respuestas
$sql = "SELECT r.*, u.nombre as autor, r.usuario_id,
        (SELECT AVG(puntuacion) FROM calificaciones WHERE respuesta_id = r.id) as promedio_calificacion,
        (SELECT COUNT(*) FROM calificaciones WHERE respuesta_id = r.id) as num_calificaciones
        FROM respuestas r 
        JOIN usuarios u ON r.usuario_id = u.id 
        WHERE r.pregunta_id = '$id' 
        ORDER BY promedio_calificacion DESC, r.fecha ASC";
$respuestas = $conn->query($sql);

// Procesar formulario de nueva respuesta
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && estaLogueado()) {
    $contenido = $_POST['contenido'] ?? '';
    
    if (empty($contenido)) {
        $error = 'Por favor, escribe una respuesta.';
    } else {
        $usuario_id = $_SESSION['usuario_id'];
        $contenido = $conn->real_escape_string($contenido);
        
        $sql = "INSERT INTO respuestas (pregunta_id, usuario_id, contenido) VALUES ('$id', '$usuario_id', '$contenido')";
        
        if ($conn->query($sql) === TRUE) {
            $success = 'Tu respuesta ha sido publicada.';
            // Recargar la página para mostrar la nueva respuesta
            header("Location: pregunta.php?id=$id&success=1");
            exit();
        } else {
            $error = 'Error al publicar la respuesta: ' . $conn->error;
        }
    }
}

// Mensaje de éxito desde redirección
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $success = 'Tu respuesta ha sido publicada.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pregunta['titulo']); ?> - Guía Verde</title>
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
        <div class="pregunta-detalle">
            <div class="pregunta-header">
                <h1><?php echo htmlspecialchars($pregunta['titulo']); ?></h1>
                <div class="pregunta-meta">
                    <span class="pregunta-autor">Por: <?php echo htmlspecialchars($pregunta['autor']); ?></span>
                    <span class="pregunta-fecha"><?php echo date('d/m/Y H:i', strtotime($pregunta['fecha'])); ?></span>
                    
                    <?php if (estaLogueado() && $_SESSION['usuario_id'] == $pregunta['usuario_id']): ?>
                        <button class="btn-eliminar-pregunta" data-id="<?php echo $pregunta['id']; ?>">
                            <i class="icono-eliminar">🗑️</i> Eliminar pregunta
                        </button>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="pregunta-contenido">
                <p><?php echo nl2br(htmlspecialchars($pregunta['contenido'])); ?></p>
            </div>
            
            <div class="pregunta-acciones">
                <a href="preguntas.php" class="btn-volver">Volver a preguntas</a>
            </div>
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
        
        <div class="respuestas-container">
            <h2><?php echo $respuestas->num_rows; ?> Respuestas</h2>
            
            <?php if ($respuestas->num_rows > 0): ?>
                <?php while ($respuesta = $respuestas->fetch_assoc()): ?>
                    <div class="respuesta-card" id="respuesta-<?php echo $respuesta['id']; ?>">
                        <div class="respuesta-contenido">
                            <p><?php echo nl2br(htmlspecialchars($respuesta['contenido'])); ?></p>
                        </div>
                        
                        <div class="respuesta-meta">
                            <span class="respuesta-autor">Respondido por: <?php echo htmlspecialchars($respuesta['autor']); ?></span>
                            <span class="respuesta-fecha"><?php echo date('d/m/Y H:i', strtotime($respuesta['fecha'])); ?></span>
                            
                            <?php if (estaLogueado() && $_SESSION['usuario_id'] == $respuesta['usuario_id']): ?>
                                <button class="btn-eliminar-respuesta" data-id="<?php echo $respuesta['id']; ?>">
                                    <i class="icono-eliminar">🗑️</i> Eliminar
                                </button>
                            <?php endif; ?>
                        </div>
                        
                        <div class="respuesta-calificacion">
                            <div class="calificacion-estrellas" data-respuesta-id="<?php echo $respuesta['id']; ?>">
                                <?php
                                $promedio = round($respuesta['promedio_calificacion'] ?? 0);
                                for ($i = 1; $i <= 5; $i++) {
                                    $clase = $i <= $promedio ? 'estrella-activa' : 'estrella-inactiva';
                                    echo "<span class='estrella $clase' data-valor='$i'>★</span>";
                                }
                                ?>
                            </div>
                            <span class="calificacion-info">
                                <?php if ($respuesta['num_calificaciones'] > 0): ?>
                                    <?php echo number_format($respuesta['promedio_calificacion'], 1); ?>/5 
                                    (<?php echo $respuesta['num_calificaciones']; ?> votos)
                                <?php else: ?>
                                    Sin calificaciones
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-respuestas">
                    <p>Aún no hay respuestas para esta pregunta.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="nueva-respuesta">
            <h2>Tu respuesta</h2>
            
            <?php if (estaLogueado()): ?>
                <form method="POST" action="pregunta.php?id=<?php echo $id; ?>" class="respuesta-form">
                    <div class="form-group">
                        <textarea id="contenido" name="contenido" rows="6" required placeholder="Escribe tu respuesta aquí..."></textarea>
                    </div>
                    
                    <button type="submit" class="btn-publicar">Publicar respuesta</button>
                </form>
            <?php else: ?>
                <div class="login-required">
                    <p>Debes iniciar sesión para responder.</p>
                    <a href="login.php" class="btn-login">Iniciar sesión</a>
                    <a href="register.php" class="btn-register">Registrarse</a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Guía Verde. Todos los derechos reservados.</p>
    </footer>

    <script src="assets/script.js"></script>
    <script>
        // Script para calificar respuestas
        document.addEventListener('DOMContentLoaded', function() {
            const calificaciones = document.querySelectorAll('.calificacion-estrellas');
            
            calificaciones.forEach(calificacion => {
                const estrellas = calificacion.querySelectorAll('.estrella');
                const respuestaId = calificacion.getAttribute('data-respuesta-id');
                
                estrellas.forEach(estrella => {
                    // Mostrar estrellas al pasar el mouse
                    estrella.addEventListener('mouseover', function() {
                        const valor = this.getAttribute('data-valor');
                        
                        estrellas.forEach(e => {
                            const valorEstrella = e.getAttribute('data-valor');
                            e.classList.remove('estrella-activa', 'estrella-inactiva');
                            e.classList.add(valorEstrella <= valor ? 'estrella-activa' : 'estrella-inactiva');
                        });
                    });
                    
                    // Restaurar estrellas al quitar el mouse
                    calificacion.addEventListener('mouseout', function() {
                        const infoCalificacion = this.nextElementSibling;
                        const textoInfo = infoCalificacion.textContent.trim();
                        
                        if (textoInfo === 'Sin calificaciones') {
                            estrellas.forEach(e => {
                                e.classList.remove('estrella-activa');
                                e.classList.add('estrella-inactiva');
                            });
                        } else {
                            const promedio = parseFloat(textoInfo.split('/')[0]);
                            const promedioRedondeado = Math.round(promedio);
                            
                            estrellas.forEach(e => {
                                const valorEstrella = parseInt(e.getAttribute('data-valor'));
                                e.classList.remove('estrella-activa', 'estrella-inactiva');
                                e.classList.add(valorEstrella <= promedioRedondeado ? 'estrella-activa' : 'estrella-inactiva');
                            });
                        }
                    });
                    
                    // Calificar al hacer clic
                    estrella.addEventListener('click', function() {
                        <?php if (estaLogueado()): ?>
                            const valor = this.getAttribute('data-valor');
                            
                            // Realizar petición AJAX
                            fetch('actions/calificar.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: 'respuesta_id=' + respuestaId + '&puntuacion=' + valor
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Actualizar información de calificación
                                    const infoCalificacion = calificacion.nextElementSibling;
                                    infoCalificacion.textContent = data.promedio + '/5 (' + data.votos + ' votos)';
                                    
                                    // Actualizar estrellas
                                    const promedioRedondeado = Math.round(data.promedio);
                                    estrellas.forEach(e => {
                                        const valorEstrella = parseInt(e.getAttribute('data-valor'));
                                        e.classList.remove('estrella-activa', 'estrella-inactiva');
                                        e.classList.add(valorEstrella <= promedioRedondeado ? 'estrella-activa' : 'estrella-inactiva');
                                    });
                                    
                                    alert('¡Gracias por tu calificación!');
                                } else {
                                    alert('Error: ' + data.message);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Ocurrió un error al procesar tu calificación.');
                            });
                        <?php else: ?>
                            alert('Debes iniciar sesión para calificar respuestas.');
                        <?php endif; ?>
                    });
                });
            });
        });
    </script>
     <script src="assets/js/preguntas.js"></script>
</body>
</html>

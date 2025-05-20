<?php
require_once 'db.php';
require_once 'auth.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Gracias por tu visita! - Guía Verde</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="assets/gracias.css">
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
            
            <button class="theme-toggle" id="themeToggle">🌛</button>
        </nav>
    </header>

    <main class="gracias-container">
        <div class="gracias-content">
            <h1>¡Gracias por visitar nuestra Guía Verde!</h1>
            <p class="gracias-mensaje">Esperamos que hayas encontrado la información que buscabas y que vuelvas pronto.</p>
            
            <div class="plantas-decorativas">
                <div class="planta">🌵</div>
                <div class="planta">🌿</div>
                <div class="planta">🌱</div>
                <div class="planta">🌴</div>
                <div class="planta">🍀</div>
            </div>
            
            <p class="cita">"En la naturaleza está la preservación del mundo." - Henry David Thoreau</p>
            
            <div class="gracias-botones">
                <a href="index.php" class="btn-volver">Volver al inicio</a>
                <?php if (estaLogueado()): ?>
                    <a href="jardin.php" class="btn-jardin">Ir a Mi Jardín</a>
                <?php else: ?>
                    <a href="login.php" class="btn-login">Iniciar sesión</a>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="gracias-imagen">
            <img src="https://images.unsplash.com/photo-1466692476868-aef1dfb1e735?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Colección de plantas">
        </div>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Guía Verde. Todos los derechos reservados.</p>
    </footer>

    <script src="assets/script.js"></script>
</body>
</html>

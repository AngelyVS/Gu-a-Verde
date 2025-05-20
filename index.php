<?php
require_once 'db.php';
require_once 'auth.php';
require_once 'api/plantas.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Guía Verde F</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="assets/index.css">
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

    <section class="hero">
        <div class="hero-content">
            <h1>Descubre el mundo de las plantas</h1>
            <p>Explora nuestra guía completa de plantas y encuentra información detallada sobre cuidados, características y más.</p>
        </div>
    </section>

    <section class="categorias">
        <h2>Categorías de Plantas</h2>
        <div class="categoria-grid">
            <a href="categoria.php?tipo=venenosas" class="categoria-card">
                <div class="categoria-icon">⚠️</div>
                <h3>Venenosas</h3>
                <p>Plantas que pueden ser tóxicas para humanos o mascotas</p>
            </a>
            
            <a href="categoria.php?tipo=comestibles" class="categoria-card">
                <div class="categoria-icon">🍽️</div>
                <h3>Comestibles</h3>
                <p>Plantas que pueden ser consumidas como alimento</p>
            </a>
            
            <a href="categoria.php?tipo=medicinales" class="categoria-card">
                <div class="categoria-icon">💊</div>
                <h3>Medicinales</h3>
                <p>Plantas con propiedades curativas o medicinales</p>
            </a>
            
            <a href="categoria.php?tipo=frutales" class="categoria-card">
                <div class="categoria-icon">🍎</div>
                <h3>Frutales</h3>
                <p>Plantas que producen frutas comestibles</p>
            </a>
            
            <a href="categoria.php?tipo=florales" class="categoria-card">
                <div class="categoria-icon">🌸</div>
                <h3>Florales</h3>
                <p>Plantas con flores decorativas o aromáticas</p>
            </a>

             <a href="categoria.php?tipo=interior" class="categoria-card">
                <div class="categoria-icon">🏠</div>
                <h3>Interior</h3>
                <p>Plantas que pueden vivir en interiores</p>
            </a>

        
        </div>
    </section>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Guía Verde. Todos los derechos reservados.</p>
    </footer>

    <script src="assets/script.js"></script>
</body>
</html>

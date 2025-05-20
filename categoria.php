<?php
require_once 'db.php';
require_once 'auth.php';
require_once 'api/plantas.php';

// Obtener parámetros
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

// Título de la página
$titulo = "Todas las plantas";

if ($tipo) {
    $titulos = [
        'venenosas' => 'Plantas Venenosas',
        'comestibles' => 'Plantas Comestibles',
        'medicinales' => 'Plantas Medicinales',
        'frutales' => 'Plantas Frutales',
        'florales' => 'Plantas Florales',
        'interior' =>'Plantas de Interior'
    ];
    $titulo = isset($titulos[$tipo]) ? $titulos[$tipo] : $titulo;
    $plantas = obtenerPlantasPorCategoria($tipo, $pagina);
} elseif ($busqueda) {
    $titulo = "Resultados para: " . htmlspecialchars($busqueda);
    $plantas = buscarPlantas($busqueda, $pagina);
    
    // Guardar búsqueda en la base de datos
    if (estaLogueado()) {
        $usuario_id = $_SESSION['usuario_id'];
        $busqueda_segura = $conn->real_escape_string($busqueda);
        $sql = "INSERT INTO busquedas (usuario_id, termino, fecha) VALUES ('$usuario_id', '$busqueda_segura', NOW())";
        $conn->query($sql);
    }
} else {
    // Si no hay tipo ni búsqueda, mostrar todas las plantas
    $plantas = obtenerPlantasPorCategoria('', $pagina);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo; ?> - Guía Verde</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="assets/categoria.css">
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
                        <li><a href="categoria.php?tipo=interior">Florales</a></li>
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
            
            <button class="theme-toggle" id="themeToggle">🌙</button>
        </nav>
    </header>

    <main>
        <h1><?php echo $titulo; ?></h1>
        
        <div class="plantas-grid">
            <?php if (isset($plantas['data']) && count($plantas['data']) > 0): ?>
                <?php foreach ($plantas['data'] as $planta): ?>
                    <a href="planta.php?id=<?php echo $planta['id']; ?>" class="planta-card">
                        <div class="planta-imagen">
                            <?php if (isset($planta['default_image']['thumbnail'])): ?>
                                <img src="<?php echo htmlspecialchars($planta['default_image']['thumbnail']); ?>" alt="<?php echo htmlspecialchars($planta['common_name']); ?>">
                            <?php else: ?>
                                <div class="no-imagen">Sin imagen</div>
                            <?php endif; ?>
                        </div>
                        <h3><?php echo htmlspecialchars($planta['common_name']); ?></h3>
                        <p class="nombre-cientifico">
                            <?php echo htmlspecialchars(implode(', ', $planta['scientific_name'])); ?>
                        </p>

                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-resultados">
                    <p>No se encontraron plantas que coincidan con tu búsqueda.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="paginacion">
            <?php if ($pagina > 1): ?>
                <a href="?<?php echo $tipo ? "tipo=$tipo" : ""; ?><?php echo $busqueda ? "busqueda=$busqueda" : ""; ?>&pagina=<?php echo $pagina - 1; ?>" class="btn-pagina">Anterior</a>
            <?php endif; ?>
            
            <span class="pagina-actual">Página <?php echo $pagina; ?></span>
            
            <?php if (isset($plantas['data']) && count($plantas['data']) > 0): ?>
                <a href="?<?php echo $tipo ? "tipo=$tipo" : ""; ?><?php echo $busqueda ? "busqueda=$busqueda" : ""; ?>&pagina=<?php echo $pagina + 1; ?>" class="btn-pagina">Siguiente</a>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Guía Verde. Todos los derechos reservados.</p>
    </footer>

    <script src="assets/script.js"></script>
</body>
</html>

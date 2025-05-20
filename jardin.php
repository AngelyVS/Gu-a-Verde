<?php
require_once 'db.php';
require_once 'auth.php';

// Verificar si el usuario está logueado
requiereLogin();

$usuario_id = $_SESSION['usuario_id'];

// Obtener plantas favoritas del usuario
$sql = "SELECT * FROM favoritos WHERE usuario_id = '$usuario_id' ORDER BY fecha_agregado DESC";
$resultado = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Jardín - Guía Verde</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="assets/jardin.css">
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
                <li><a href="jardin.php">Mi Jardín</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
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
        <div class="jardin-header">
            <h1>Mi Jardín</h1>
            <p>Aquí encontrarás todas las plantas que has guardado.</p>
        </div>
        
        <?php if ($resultado->num_rows > 0): ?>
            <div class="plantas-grid">
                <?php while ($planta = $resultado->fetch_assoc()): ?>
                    <div class="planta-card">
                        <div class="planta-imagen">
                            <?php if (!empty($planta['imagen'])): ?>
                                <img src="<?php echo htmlspecialchars($planta['imagen']); ?>" alt="<?php echo htmlspecialchars($planta['nombre']); ?>">
                            <?php else: ?>
                                <div class="no-imagen">Sin imagen</div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="planta-info">
                            <h3><?php echo htmlspecialchars($planta['nombre']); ?></h3>
                            <p class="nombre-cientifico"><?php echo htmlspecialchars($planta['nombre_cientifico']); ?></p>
                            
                            <div class="planta-acciones">
                                <a href="planta.php?id=<?php echo $planta['planta_id']; ?>" class="btn-ver">Ver detalles</a>
                                <button class="btn-eliminar" data-planta-id="<?php echo $planta['planta_id']; ?>">Eliminar</button>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="jardin-vacio">
                <div class="jardin-vacio-icon">🌱</div>
                <h2>Tu jardín está vacío</h2>
                <p>Explora nuestra Guía Verde y agrega tus favoritas a tu jardín.</p>
                <a href="index.php" class="btn-explorar">Explorar plantas</a>
            </div>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Guía Verde. Todos los derechos reservados.</p>
    </footer>

    <script src="assets/script.js"></script>
    <script>
        // Script para eliminar plantas del jardín
        document.addEventListener('DOMContentLoaded', function() {
            const botonesEliminar = document.querySelectorAll('.btn-eliminar');
            
            botonesEliminar.forEach(boton => {
                boton.addEventListener('click', function() {
                    const plantaId = this.getAttribute('data-planta-id');
                    
                    if (confirm('¿Estás seguro de que deseas eliminar esta planta de tu jardín?')) {
                        // Realizar petición AJAX
                        fetch('actions/eliminar_favorito.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: 'planta_id=' + plantaId
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Eliminar la tarjeta de planta del DOM
                                this.closest('.planta-card').remove();
                                
                                // Verificar si quedan plantas
                                const plantasGrid = document.querySelector('.plantas-grid');
                                if (plantasGrid && plantasGrid.children.length === 0) {
                                    location.reload(); // Recargar para mostrar mensaje de jardín vacío
                                }
                                
                                alert(data.message);
                            } else {
                                alert('Error: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Ocurrió un error al procesar tu solicitud.');
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>

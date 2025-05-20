<?php
require_once 'db.php';
require_once 'auth.php';
require_once 'api/plantas.php';

// Obtener ID de la planta
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: index.php");
    exit();
}

// Obtener detalles de la planta
$planta = obtenerPlanta($id);

// Verificar si la planta está en el jardín del usuario
$enJardin = false;
if (estaLogueado()) {
    $usuario_id = $_SESSION['usuario_id'];
    $sql = "SELECT * FROM favoritos WHERE usuario_id = '$usuario_id' AND planta_id = '$id'";
    $resultado = $conn->query($sql);
    $enJardin = $resultado->num_rows > 0;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($planta['common_name']); ?> - Guía Verde</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="assets/planta.css">
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

    <main class="ficha-planta">
        <div class="planta-header">
            <div class="planta-imagen-principal">
                <?php if (isset($planta['default_image']['regular_url'])): ?>
                    <img src="<?php echo htmlspecialchars($planta['default_image']['regular_url']); ?>" alt="<?php echo htmlspecialchars($planta['common_name']); ?>">
                <?php else: ?>
                    <div class="no-imagen">Sin imagen disponible</div>
                <?php endif; ?>
            </div>
            
            <div class="planta-info-basica">
                <h1><?php echo htmlspecialchars($planta['common_name']); ?></h1>
                <p class="nombre-cientifico">
                    <?php
                    echo isset($planta['scientific_name']) 
                        ? htmlspecialchars(implode(', ', $planta['scientific_name'])) 
                        : 'No disponible';
                    ?>
                </p>

                
                <?php if (estaLogueado()): ?>
                    <button id="btnFavorito" class="btn-favorito <?php echo $enJardin ? 'en-jardin' : ''; ?>" data-planta-id="<?php echo $id; ?>">
                        <span class="icono-flor">🌺</span>
                        <span class="texto-btn"><?php echo $enJardin ? 'Quitar de Mi Jardín' : 'Agregar a Mi Jardín'; ?></span>
                    </button>
                <?php else: ?>
                    <a href="login.php" class="btn-login-favorito">Inicia sesión para guardar en tu jardín</a>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="planta-descripcion">
            <h2>Descripción</h2>
            <p><?php echo isset($planta['description']) ? htmlspecialchars($planta['description']) : 'No hay descripción disponible.'; ?></p>
        </div>
        
        <div class="planta-cuidados">
            <h2>Cuidados</h2>
            
            <div class="cuidados-grid">
                <div class="cuidado-item">
                    <div class="cuidado-icono">💧</div>
                    <h3>Riego</h3>
                    <p><?php echo isset($planta['watering']) ? htmlspecialchars($planta['watering']) : 'No disponible'; ?></p>
                </div>
                
                <div class="cuidado-item">
                    <div class="cuidado-icono">🌞</div>
                    <h3>Exposición al sol</h3>
                    <p><?php echo isset($planta['sunlight']) ? htmlspecialchars(is_array($planta['sunlight']) ? implode(', ', $planta['sunlight']) : $planta['sunlight']) : 'No disponible'; ?></p>
                </div>
                
                <div class="cuidado-item">
                    <div class="cuidado-icono">✂️</div>
                    <h3>Poda</h3>
                    <p><?php echo isset($planta['pruning_month']) ? htmlspecialchars(is_array($planta['pruning_month']) ? implode(', ', $planta['pruning_month']) : $planta['pruning_month']) : 'No disponible'; ?></p>
                </div>
                
                <div class="cuidado-item">
                    <div class="cuidado-icono">🌡️</div>
                    <h3>Temperatura</h3>
                    <p>Mínima: <?php echo isset($planta['hardiness']['min']) ? htmlspecialchars($planta['hardiness']['min']) : 'No disponible'; ?></p>
                    <p>Máxima: <?php echo isset($planta['hardiness']['max']) ? htmlspecialchars($planta['hardiness']['max']) : 'No disponible'; ?></p>
                </div>
            </div>
        </div>
        
        <div class="planta-caracteristicas">
            <h2>Características</h2>
            
            <div class="caracteristicas-lista">
                <div class="caracteristica">
                    <span class="etiqueta">Tipo:</span>
                    <span class="valor"><?php echo isset($planta['type']) ? htmlspecialchars($planta['type']) : 'No disponible'; ?></span>
                </div>
                
                <div class="caracteristica">
                    <span class="etiqueta">Ciclo:</span>
                    <span class="valor"><?php echo isset($planta['cycle']) ? htmlspecialchars($planta['cycle']) : 'No disponible'; ?></span>
                </div>
                
                <div class="caracteristica">
                    <span class="etiqueta">Altura:</span>
                    <span class="valor"><?php echo isset($planta['dimensions']['height_max']['cm']) ? htmlspecialchars($planta['dimensions']['height_max']['cm']) . ' cm' : 'No disponible'; ?></span>
                </div>
                
                <div class="caracteristica">
                    <span class="etiqueta">Venenosa:</span>
                    <span class="valor"><?php echo isset($planta['poisonous_to_humans']) && $planta['poisonous_to_humans'] ? 'Sí' : 'No'; ?></span>
                </div>
                
                <div class="caracteristica">
                    <span class="etiqueta">Comestible:</span>
                    <span class="valor"><?php echo isset($planta['edible']) && $planta['edible'] ? 'Sí' : 'No'; ?></span>
                </div>
                
                <div class="caracteristica">
                    <span class="etiqueta">Medicinal:</span>
                    <span class="valor"><?php echo isset($planta['medicinal']) && $planta['medicinal'] ? 'Sí' : 'No'; ?></span>
                </div>

                 <div class="caracteristica">
                    <span class="etiqueta">Interior:</span>
                    <span class="valor"><?php echo isset($planta['indoor']) && $planta['indoor'] ? 'Sí' : 'No'; ?></span>
                </div>

                
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Guía Verde. Todos los derechos reservados.</p>
    </footer>

    <script src="assets/script.js"></script>
    <script>
        // Script para manejar favoritos con AJAX
        document.addEventListener('DOMContentLoaded', function() {
            const btnFavorito = document.getElementById('btnFavorito');
            
            if (btnFavorito) {
                btnFavorito.addEventListener('click', function() {
                    const plantaId = this.getAttribute('data-planta-id');
                    const estaEnJardin = this.classList.contains('en-jardin');
                    
                    // URL de la acción según si está o no en el jardín
                    const url = estaEnJardin ? 'actions/eliminar_favorito.php' : 'actions/guardar_favorito.php';
                    
                    // Realizar petición AJAX
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'planta_id=' + plantaId
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Cambiar estado del botón
                            btnFavorito.classList.toggle('en-jardin');
                            
                            // Actualizar texto del botón
                            const textoBtn = btnFavorito.querySelector('.texto-btn');
                            textoBtn.textContent = estaEnJardin ? 'Agregar a Mi Jardín' : 'Quitar de Mi Jardín';
                            
                            // Mostrar mensaje
                            alert(data.message);
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Ocurrió un error al procesar tu solicitud.');
                    });
                });
            }
        });
    </script>
</body>
</html>

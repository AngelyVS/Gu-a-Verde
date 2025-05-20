<?php
require_once 'auth.php';

// Cerrar sesión
cerrarSesion();

// Redirigir a la página principal
header("Location: gracias.php");
exit();
?>

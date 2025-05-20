<?php
// Configuración de la base de datos
$host = "localhost";
$usuario = "root";
$password = "";
$base_datos = "guia_plantas";

// Crear conexión
$conn = new mysqli($host, $usuario, $password, $base_datos);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Establecer charset
$conn->set_charset("utf8");
?>

<?php
session_start();

// Función para verificar si el usuario está logueado
function estaLogueado() {
    return isset($_SESSION['usuario_id']);
}

// Función para proteger páginas que requieren login
function requiereLogin() {
    if (!estaLogueado()) {
        header("Location: login.php");
        exit();
    }
}

// Función para iniciar sesión
function iniciarSesion($id, $nombre, $email) {
    $_SESSION['usuario_id'] = $id;
    $_SESSION['usuario_nombre'] = $nombre;
    $_SESSION['usuario_email'] = $email;
}

// Función para cerrar sesión
function cerrarSesion() {
    session_unset();
    session_destroy();
}
?>

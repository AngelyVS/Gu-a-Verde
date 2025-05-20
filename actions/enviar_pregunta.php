<?php
require_once '../db.php';
require_once '../auth.php';

// Verificar si el usuario está logueado
if (!estaLogueado()) {
    echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión para hacer preguntas.']);
    exit();
}

// Verificar si se recibieron los parámetros necesarios
if (!isset($_POST['titulo']) || !isset($_POST['contenido']) || empty($_POST['titulo']) || empty($_POST['contenido'])) {
    echo json_encode(['success' => false, 'message' => 'Por favor, completa todos los campos.']);
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$titulo = $conn->real_escape_string($_POST['titulo']);
$contenido = $conn->real_escape_string($_POST['contenido']);

// Insertar la pregunta
$sql = "INSERT INTO preguntas (usuario_id, titulo, contenido) VALUES ('$usuario_id', '$titulo', '$contenido')";

if ($conn->query($sql) === TRUE) {
    $pregunta_id = $conn->insert_id;
    echo json_encode([
        'success' => true, 
        'message' => 'Tu pregunta ha sido publicada.',
        'pregunta_id' => $pregunta_id
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al publicar la pregunta: ' . $conn->error]);
}
?>

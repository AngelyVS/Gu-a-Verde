<?php
require_once '../db.php';
require_once '../auth.php';

// Verificar si el usuario está logueado
if (!estaLogueado()) {
    echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión para guardar búsquedas.']);
    exit();
}

// Verificar si se recibió el término de búsqueda
if (!isset($_POST['termino']) || empty($_POST['termino'])) {
    echo json_encode(['success' => false, 'message' => 'Término de búsqueda no válido.']);
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$termino = $conn->real_escape_string($_POST['termino']);

// Guardar búsqueda
$sql = "INSERT INTO busquedas (usuario_id, termino, fecha) VALUES ('$usuario_id', '$termino', NOW())";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true, 'message' => 'Búsqueda guardada correctamente.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al guardar la búsqueda: ' . $conn->error]);
}
?>

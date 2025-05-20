<?php
require_once '../db.php';
require_once '../auth.php';

// Verificar si el usuario está logueado
if (!estaLogueado()) {
    echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión para eliminar plantas de tu jardín.']);
    exit();
}

// Verificar si se recibió el ID de la planta
if (!isset($_POST['planta_id']) || empty($_POST['planta_id'])) {
    echo json_encode(['success' => false, 'message' => 'ID de planta no válido.']);
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$planta_id = (int)$_POST['planta_id'];

// Eliminar de favoritos
$sql = "DELETE FROM favoritos WHERE usuario_id = '$usuario_id' AND planta_id = '$planta_id'";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true, 'message' => 'Planta eliminada de tu jardín.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al eliminar: ' . $conn->error]);
}
?>

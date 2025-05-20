<?php
require_once '../db.php';
require_once '../auth.php';

// Verificar si el usuario está logueado
if (!estaLogueado()) {
    echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión para realizar esta acción.']);
    exit();
}

// Verificar si se recibió el ID de la pregunta
if (!isset($_POST['pregunta_id']) || empty($_POST['pregunta_id'])) {
    echo json_encode(['success' => false, 'message' => 'ID de pregunta no válido.']);
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$pregunta_id = (int)$_POST['pregunta_id'];

// Verificar si la pregunta pertenece al usuario
$sql = "SELECT id FROM preguntas WHERE id = '$pregunta_id' AND usuario_id = '$usuario_id'";
$resultado = $conn->query($sql);

if ($resultado->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'No tienes permiso para eliminar esta pregunta.']);
    exit();
}

// Iniciar transacción para eliminar la pregunta y sus respuestas
$conn->begin_transaction();

try {
    // Primero eliminar las calificaciones de las respuestas
    $sql = "DELETE c FROM calificaciones c 
            JOIN respuestas r ON c.respuesta_id = r.id 
            WHERE r.pregunta_id = '$pregunta_id'";
    $conn->query($sql);
    
    // Luego eliminar las respuestas
    $sql = "DELETE FROM respuestas WHERE pregunta_id = '$pregunta_id'";
    $conn->query($sql);
    
    // Finalmente eliminar la pregunta
    $sql = "DELETE FROM preguntas WHERE id = '$pregunta_id' AND usuario_id = '$usuario_id'";
    $conn->query($sql);
    
    // Confirmar transacción
    $conn->commit();
    
    echo json_encode(['success' => true, 'message' => 'Pregunta eliminada correctamente.']);
} catch (Exception $e) {
    // Revertir cambios si hay error
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'Error al eliminar la pregunta: ' . $e->getMessage()]);
}
?>

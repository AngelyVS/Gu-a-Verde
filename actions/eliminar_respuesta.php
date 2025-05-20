<?php
require_once '../db.php';
require_once '../auth.php';

// Verificar si el usuario está logueado
if (!estaLogueado()) {
    echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión para realizar esta acción.']);
    exit();
}

// Verificar si se recibió el ID de la respuesta
if (!isset($_POST['respuesta_id']) || empty($_POST['respuesta_id'])) {
    echo json_encode(['success' => false, 'message' => 'ID de respuesta no válido.']);
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$respuesta_id = (int)$_POST['respuesta_id'];

// Verificar si la respuesta pertenece al usuario
$sql = "SELECT pregunta_id FROM respuestas WHERE id = '$respuesta_id' AND usuario_id = '$usuario_id'";
$resultado = $conn->query($sql);

if ($resultado->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'No tienes permiso para eliminar esta respuesta.']);
    exit();
}

// Obtener el ID de la pregunta para redireccionar después
$pregunta_id = $resultado->fetch_assoc()['pregunta_id'];

// Iniciar transacción para eliminar la respuesta y sus calificaciones
$conn->begin_transaction();

try {
    // Primero eliminar las calificaciones
    $sql = "DELETE FROM calificaciones WHERE respuesta_id = '$respuesta_id'";
    $conn->query($sql);
    
    // Luego eliminar la respuesta
    $sql = "DELETE FROM respuestas WHERE id = '$respuesta_id' AND usuario_id = '$usuario_id'";
    $conn->query($sql);
    
    // Confirmar transacción
    $conn->commit();
    
    echo json_encode([
        'success' => true, 
        'message' => 'Respuesta eliminada correctamente.',
        'pregunta_id' => $pregunta_id
    ]);
} catch (Exception $e) {
    // Revertir cambios si hay error
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'Error al eliminar la respuesta: ' . $e->getMessage()]);
}
?>

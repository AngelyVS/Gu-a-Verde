<?php
require_once '../db.php';
require_once '../auth.php';

// Verificar si el usuario está logueado
if (!estaLogueado()) {
    echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión para calificar respuestas.']);
    exit();
}

// Verificar si se recibieron los parámetros necesarios
if (!isset($_POST['respuesta_id']) || !isset($_POST['puntuacion'])) {
    echo json_encode(['success' => false, 'message' => 'Parámetros incompletos.']);
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$respuesta_id = (int)$_POST['respuesta_id'];
$puntuacion = (int)$_POST['puntuacion'];

// Validar puntuación
if ($puntuacion < 1 || $puntuacion > 5) {
    echo json_encode(['success' => false, 'message' => 'La puntuación debe estar entre 1 y 5.']);
    exit();
}

// Verificar si el usuario ya calificó esta respuesta
$sql = "SELECT id FROM calificaciones WHERE respuesta_id = '$respuesta_id' AND usuario_id = '$usuario_id'";
$resultado = $conn->query($sql);

if ($resultado->num_rows > 0) {
    // Actualizar calificación existente
    $sql = "UPDATE calificaciones SET puntuacion = '$puntuacion' WHERE respuesta_id = '$respuesta_id' AND usuario_id = '$usuario_id'";
} else {
    // Insertar nueva calificación
    $sql = "INSERT INTO calificaciones (respuesta_id, usuario_id, puntuacion) VALUES ('$respuesta_id', '$usuario_id', '$puntuacion')";
}

if ($conn->query($sql) === TRUE) {
    // Obtener promedio actualizado
    $sql = "SELECT AVG(puntuacion) as promedio, COUNT(*) as votos FROM calificaciones WHERE respuesta_id = '$respuesta_id'";
    $resultado = $conn->query($sql);
    $datos = $resultado->fetch_assoc();
    
    echo json_encode([
        'success' => true, 
        'message' => 'Calificación guardada correctamente.',
        'promedio' => number_format($datos['promedio'], 1),
        'votos' => $datos['votos']
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al guardar la calificación: ' . $conn->error]);
}
?>

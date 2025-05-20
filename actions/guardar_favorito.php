<?php
require_once '../db.php';
require_once '../auth.php';
require_once '../api/plantas.php';

// Verificar si el usuario está logueado
if (!estaLogueado()) {
    echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión para guardar plantas en tu jardín.']);
    exit();
}

// Verificar si se recibió el ID de la planta
if (!isset($_POST['planta_id']) || empty($_POST['planta_id'])) {
    echo json_encode(['success' => false, 'message' => 'ID de planta no válido.']);
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$planta_id = (int)$_POST['planta_id'];

// Verificar si la planta ya está en favoritos
$sql = "SELECT * FROM favoritos WHERE usuario_id = '$usuario_id' AND planta_id = '$planta_id'";
$resultado = $conn->query($sql);

if ($resultado->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Esta planta ya está en tu jardín.']);
    exit();
}

// Obtener datos de la planta para guardar en la base de datos
$planta = obtenerPlanta($planta_id);

if (!isset($planta['id'])) {
    echo json_encode(['success' => false, 'message' => 'No se pudo obtener información de la planta.']);
    exit();
}

// Guardar en favoritos
$nombre = $conn->real_escape_string($planta['common_name']);
$nombre_cientifico = $conn->real_escape_string(is_array($planta['scientific_name']) ? implode(', ', $planta['scientific_name']) : $planta['scientific_name']);
$imagen = isset($planta['default_image']['thumbnail']) ? $conn->real_escape_string($planta['default_image']['thumbnail']) : '';

$sql = "INSERT INTO favoritos (usuario_id, planta_id, nombre, nombre_cientifico, imagen, fecha_agregado) 
        VALUES ('$usuario_id', '$planta_id', '$nombre', '$nombre_cientifico', '$imagen', NOW())";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true, 'message' => '¡Planta agregada a tu jardín!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al guardar: ' . $conn->error]);
}
?>

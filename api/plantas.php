<?php
// Clave de API de Perenual (deberás obtener una en https://perenual.com/docs/api)
$api_key = "sk-yMI36828eb125f9bd10517";


// Función genérica para usar cURL
function obtenerDesdeAPI($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $respuesta = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) {
        return json_decode($respuesta, true);
    } elseif ($httpCode === 429) {
        return ["error" => "Demasiadas solicitudes (HTTP 429). Intenta más tarde."];
    } else {
        return ["error" => "Error HTTP: $httpCode al acceder a la API."];
    }
}

// Función para obtener plantas por categoría
function obtenerPlantasPorCategoria($categoria, $pagina = 1) {
    global $api_key;

    $filtros = [
        'venenosas' => '&poisonous=1',
        'comestibles' => '&edible=1',
        'medicinales' => '&medicinal=1',
        'frutales' => '&fruit=1',
        'florales' => '&flower=1',
        'interior' =>'&indoor=1',
        'rara' =>'&rare=1'
    ];

    $filtro = isset($filtros[$categoria]) ? $filtros[$categoria] : '';
    $url = "https://perenual.com/api/species-list?key={$api_key}&page={$pagina}{$filtro}";

    return obtenerDesdeAPI($url);
}

// Función para obtener detalles de una planta específica
function obtenerPlanta($id) {
    global $api_key;

    $url = "https://perenual.com/api/species/details/{$id}?key={$api_key}";

    return obtenerDesdeAPI($url);
}

// Función para buscar plantas por término
function buscarPlantas($termino, $pagina = 1) {
    global $api_key;

    $url = "https://perenual.com/api/species-list?key={$api_key}&q=" . urlencode($termino) . "&page={$pagina}";

    return obtenerDesdeAPI($url);
}
?>

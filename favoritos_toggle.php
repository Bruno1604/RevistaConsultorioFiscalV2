<?php
// Endpoint AJAX para agregar/quitar un elemento de "Mis Favoritos"
// (revista, artículo o página). Responde en JSON, igual que ajax_proceso.php.
session_start();
header('Content-Type: application/json; charset=utf-8');

// Favoritos es una función exclusiva de suscriptores (no del administrador)
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['rol']) || !in_array($_SESSION['rol'], ['suscriptor', 'usuario'])) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'Debes iniciar sesión como suscriptor para usar tus favoritos.']);
    exit();
}

require __DIR__ . '/data/favoritos.php';

$tipo = $_POST['tipo'] ?? '';

if ($tipo === 'revista') {

    $numero = trim($_POST['numero'] ?? '');
    if ($numero === '') {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'Falta el número de la revista.']);
        exit();
    }
    $data = [
        'tipo'    => 'revista',
        'numero'  => $numero,
        'titulo'  => trim($_POST['titulo'] ?? ('Ejemplar No. ' . $numero)),
        'periodo' => trim($_POST['periodo'] ?? ''),
    ];

} elseif ($tipo === 'articulo') {

    $articulo_id = trim($_POST['articulo_id'] ?? '');
    if ($articulo_id === '') {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'Falta el identificador del artículo.']);
        exit();
    }
    $data = [
        'tipo'        => 'articulo',
        'articulo_id' => $articulo_id,
        'titulo'      => trim($_POST['titulo'] ?? 'Artículo sin título'),
        'autor'       => trim($_POST['autor'] ?? ''),
        'descripcion' => trim($_POST['descripcion'] ?? ''),
    ];

} elseif ($tipo === 'pagina') {

    $numero = trim($_POST['numero'] ?? '');
    $pagina = (int) ($_POST['pagina'] ?? 0);
    if ($numero === '' || $pagina < 1) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'Escribe un número de página válido.']);
        exit();
    }
    $data = [
        'tipo'    => 'pagina',
        'numero'  => $numero,
        'pagina'  => $pagina,
        'titulo'  => trim($_POST['titulo'] ?? ('Ejemplar No. ' . $numero)),
        'seccion' => trim($_POST['seccion'] ?? ''),
    ];

} else {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Tipo de favorito no válido.']);
    exit();
}

$resultado = toggle_favorito($data);

echo json_encode([
    'ok'     => true,
    'activo' => $resultado['activo'],
    'id'     => $resultado['id'],
]);

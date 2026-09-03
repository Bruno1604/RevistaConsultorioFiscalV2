<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['action']) && $_GET['action'] === 'reset') {
    unset($_SESSION['favoritos']);
    unset($_SESSION['favoritos_next_id']);
    header("Location: ../favoritos.php");
    exit();
}

// Inicializar el arreglo en sesión si no existe (simula la tabla FAVORITO)
// Tipos soportados: 'revista', 'articulo', 'pagina'
if (!isset($_SESSION['favoritos'])) {
    $_SESSION['favoritos'] = [
        '1' => [
            'id'             => '1',
            'tipo'           => 'revista',
            'numero'         => '879',
            'titulo'         => 'Declaración anual de personas físicas',
            'periodo'        => 'Primera quincena de Abril 2026',
            'fecha_guardado' => '2026-08-20',
        ],
        '2' => [
            'id'             => '2',
            'tipo'           => 'articulo',
            'articulo_id'    => '4',
            'titulo'         => 'Alcance de la no deducibilidad de salarios en la disminución de la PTU',
            'autor'          => 'Lucía Muñoz',
            'descripcion'    => 'Un análisis de las reformas de 2014 y su impacto en la base gravable del ISR para la determinación de la PTU pagada.',
            'fecha_guardado' => '2026-08-22',
        ],
        '3' => [
            'id'             => '3',
            'tipo'           => 'pagina',
            'numero'         => '879',
            'pagina'         => 24,
            'titulo'         => 'Declaración anual de personas físicas',
            'seccion'        => 'Doctrina Fiscal',
            'fecha_guardado' => '2026-08-25',
        ],
    ];
}

// Autoincremental simple para nuevos IDs
if (!isset($_SESSION['favoritos_next_id'])) {
    $_SESSION['favoritos_next_id'] = 4;
}

function get_favoritos() {
    return $_SESSION['favoritos'];
}

function get_favoritos_por_tipo($tipo) {
    $resultado = [];
    foreach ($_SESSION['favoritos'] as $id => $f) {
        if ($f['tipo'] === $tipo) {
            $resultado[$id] = $f;
        }
    }
    return $resultado;
}

function get_favorito($id) {
    return $_SESSION['favoritos'][$id] ?? null;
}

// Clave natural que identifica a un mismo elemento dentro de un tipo,
// para no duplicar el favorito si el usuario lo vuelve a marcar.
function favorito_clave($tipo, $item) {
    if ($tipo === 'revista') {
        return (string) ($item['numero'] ?? '');
    }
    if ($tipo === 'articulo') {
        return (string) ($item['articulo_id'] ?? '');
    }
    if ($tipo === 'pagina') {
        return (string) ($item['numero'] ?? '') . '-' . (string) ($item['pagina'] ?? '');
    }
    return '';
}

function buscar_favorito_existente($tipo, $clave) {
    foreach ($_SESSION['favoritos'] as $f) {
        if ($f['tipo'] === $tipo && favorito_clave($tipo, $f) === (string) $clave) {
            return $f;
        }
    }
    return null;
}

function es_favorito($tipo, $clave) {
    return buscar_favorito_existente($tipo, $clave) !== null;
}

function add_favorito($data) {
    $id = (string) $_SESSION['favoritos_next_id'];

    $data['id'] = $id;
    $data['fecha_guardado'] = date('Y-m-d');

    $_SESSION['favoritos'][$id] = $data;
    $_SESSION['favoritos_next_id']++;

    return $id;
}

function delete_favorito($id) {
    if (isset($_SESSION['favoritos'][$id])) {
        unset($_SESSION['favoritos'][$id]);
        return true;
    }
    return false;
}

/**
 * Agrega el elemento a favoritos si no estaba, o lo quita si ya estaba
 * (comportamiento de "estrella"/toggle usado por el botón de favoritos).
 * $data debe incluir 'tipo' y los campos propios de ese tipo.
 * Devuelve ['activo' => bool, 'id' => string|null]
 */
function toggle_favorito($data) {
    $tipo  = $data['tipo'] ?? '';
    $clave = favorito_clave($tipo, $data);

    if ($clave === '') {
        return ['activo' => false, 'id' => null];
    }

    $existente = buscar_favorito_existente($tipo, $clave);
    if ($existente) {
        delete_favorito($existente['id']);
        return ['activo' => false, 'id' => null];
    }

    $id = add_favorito($data);
    return ['activo' => true, 'id' => $id];
}

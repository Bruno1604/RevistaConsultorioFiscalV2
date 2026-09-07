<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['action']) && $_GET['action'] === 'reset') {
    unset($_SESSION['notificaciones_usuario']);
    unset($_SESSION['notificaciones_usuario_next_id']);
    header("Location: ../notificaciones.php");
    exit();
}

// Inicializar el arreglo en sesión si no existe (simula la tabla NOTIFICACION
// del suscriptor). OJO: esta clave es independiente de $_SESSION['notificaciones'],
// que usa data/solicitudes.php para el historial que ve el administrador
// (historial.php) — son dos bandejas distintas, para dos audiencias distintas.
// Tipos soportados: 'revista' (nueva edición publicada) y 'suscripcion' (vencimiento próximo).
if (!isset($_SESSION['notificaciones_usuario'])) {
    $_SESSION['notificaciones_usuario'] = [
        '1' => [
            'id'           => '1',
            'tipo'         => 'revista',
            'titulo'       => 'Edición anterior disponible',
            'mensaje'      => 'El número 878, "Deducciones personales en la declaración anual", sigue disponible en el histórico.',
            'fecha'        => '2026-08-15 09:00:00',
            'leida'        => true,
            'enlace'       => 'contenido.php',
            'enlace_texto' => 'Ver edición',
        ],
        '2' => [
            'id'           => '2',
            'tipo'         => 'revista',
            'titulo'       => 'Nueva edición disponible',
            'mensaje'      => 'Ya está disponible el número 879, "Declaración anual de personas físicas".',
            'fecha'        => '2026-09-01 09:00:00',
            'leida'        => false,
            'enlace'       => 'contenido.php',
            'enlace_texto' => 'Ver edición',
        ],
        '3' => [
            'id'           => '3',
            'tipo'         => 'suscripcion',
            'titulo'       => 'Tu suscripción está por vencer',
            'mensaje'      => 'Tu suscripción vence en 5 días. Renueva para no perder el acceso a los contenidos.',
            'fecha'        => '2026-09-05 08:00:00',
            'leida'        => false,
            'enlace'       => 'perfil.php',
            'enlace_texto' => 'Ver mi suscripción',
        ],
    ];
}

// Autoincremental simple para nuevas notificaciones
if (!isset($_SESSION['notificaciones_usuario_next_id'])) {
    $_SESSION['notificaciones_usuario_next_id'] = 4;
}

function get_notificaciones_usuario() {
    return $_SESSION['notificaciones_usuario'];
}

function get_notificacion_usuario($id) {
    return $_SESSION['notificaciones_usuario'][$id] ?? null;
}

function contar_notificaciones_no_leidas() {
    $total = 0;
    foreach ($_SESSION['notificaciones_usuario'] as $n) {
        if (empty($n['leida'])) {
            $total++;
        }
    }
    return $total;
}

function marcar_notificacion_leida($id) {
    if (isset($_SESSION['notificaciones_usuario'][$id])) {
        $_SESSION['notificaciones_usuario'][$id]['leida'] = true;
        return true;
    }
    return false;
}

function marcar_todas_notificaciones_leidas() {
    foreach ($_SESSION['notificaciones_usuario'] as $id => $n) {
        $_SESSION['notificaciones_usuario'][$id]['leida'] = true;
    }
}

// Para cuando el flujo de suscripciones/publicación esté conectado a la
// base de datos: agregar una notificación nueva (nueva edición, aviso de
// vencimiento, etc.) sin tener que tocar el resto de este archivo.
function add_notificacion_usuario($data) {
    $id = (string) $_SESSION['notificaciones_usuario_next_id'];

    $data['id'] = $id;
    $data['fecha'] = $data['fecha'] ?? date('Y-m-d H:i:s');
    $data['leida'] = false;

    $_SESSION['notificaciones_usuario'][$id] = $data;
    $_SESSION['notificaciones_usuario_next_id']++;

    return $id;
}

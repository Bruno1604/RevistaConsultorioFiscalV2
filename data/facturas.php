<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['action']) && $_GET['action'] === 'reset') {
    unset($_SESSION['solicitudes_factura']);
    header("Location: ../solicitarFactura.php");
    exit();
}

// Esta solicitud solo se envia al área de facturación
// El sistema no genera ni entrega la factura de vuelta al usuario.
if (!isset($_SESSION['solicitudes_factura'])) {
    $_SESSION['solicitudes_factura'] = [];
}

if (!isset($_SESSION['solicitudes_factura_next_id'])) {
    $_SESSION['solicitudes_factura_next_id'] = 1;
}

function get_solicitudes_factura() {
    return $_SESSION['solicitudes_factura'];
}

function get_solicitud_factura($id) {
    return $_SESSION['solicitudes_factura'][$id] ?? null;
}

// Si la suscripción ya tiene una solicitud de factura, no se permite otra.
function factura_ya_solicitada($solicitud_id) {
    foreach ($_SESSION['solicitudes_factura'] as $sf) {
        if ($sf['solicitud_id'] === $solicitud_id) {
            return true;
        }
    }
    return false;
}

function add_solicitud_factura($data) {
    $id = (string) $_SESSION['solicitudes_factura_next_id'];

    $data['id'] = $id;
    $data['fecha_envio'] = date('Y-m-d H:i:s');
    $data['estado'] = 'Enviada a facturación';

    $_SESSION['solicitudes_factura'][$id] = $data;
    $_SESSION['solicitudes_factura_next_id']++;

    return $id;
}

// La factura debe solicitarse dentro del mismo mes en que
// se aprobó la suscripción (fechaInicio = fecha en que quedó Aprobada).
function dias_restantes_para_facturar($fecha_inicio) {
    if (empty($fecha_inicio)) {
        return ['dias' => null, 'vencido' => true];
    }

    $hoy = new DateTime('today');
    $inicio = new DateTime($fecha_inicio);
    $fin_de_mes = new DateTime($inicio->format('Y-m-t')); // Ultimo dia del mes de aprobacion

    if ($hoy > $fin_de_mes) {
        return ['dias' => 0, 'vencido' => true];
    }

    $dias = (int) $hoy->diff($fin_de_mes)->format('%a');
    return ['dias' => $dias, 'vencido' => false];
}
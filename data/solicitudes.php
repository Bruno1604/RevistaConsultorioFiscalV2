<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['action']) && $_GET['action'] === 'reset') {
    unset($_SESSION['solicitudes']);
    unset($_SESSION['notificaciones']);
    header("Location: ../solicitudes.php");
    exit();
}

// Inicializar el arreglo en sesión si no existe
if (!isset($_SESSION['solicitudes'])) {
    $_SESSION['solicitudes'] = [
        '1' => [
            'id' => '1',
            'fechaSolicitud' => '2026-08-10',
            'fechaRevision' => null,
            'nombre' => 'Juan Pérez',
            'correo' => 'juan.perez@unam.mx',
            'afiliacion' => 'Comunidad UNAM',
            'tarifa' => 600.00,
            'descuento' => 300.00,
            'monto' => 300.00,
            'cuenta' => '123456',
            'estado' => 'Comprobante cargado',
            'ficha' => 'FIC-1',
            'fechaFicha' => '2026-08-10',
            'fechaVencimiento' => '2026-11-10',
            'comprobante' => 'comprobante_pago_1.pdf',
            'fechaComprobante' => '2026-08-12',
            'tipoComprobante' => 'PDF',
            'tamComprobante' => '1.2 MB',
            'motivoRechazo' => null,
            'fechaInicio' => null,
            'fechaFin' => null,
            'comentario' => 'Solicito afiliación como académico de la FCA. Adjunto documentos.'
        ],
        '2' => [
            'id' => '2',
            'fechaSolicitud' => '2026-08-11',
            'fechaRevision' => null,
            'nombre' => 'María López',
            'correo' => 'mlopez@fca.unam.mx',
            'afiliacion' => 'Comunidad UNAM',
            'tarifa' => 600.00,
            'descuento' => 300.00,
            'monto' => 800.00,
            'cuenta' => '789012',
            'estado' => 'Ficha asignada',
            'ficha' => 'FIC-2',
            'fechaFicha' => '2026-08-11',
            'fechaVencimiento' => '2026-11-11',
            'comprobante' => null,
            'fechaComprobante' => null,
            'tipoComprobante' => null,
            'tamComprobante' => null,
            'motivoRechazo' => null,
            'fechaInicio' => null,
            'fechaFin' => null,
            'comentario' => 'Renovación de suscripción para trabajador FCA.'
        ],
        '3' => [
            'id' => '3',
            'fechaSolicitud' => '2026-08-05',
            'fechaRevision' => '2026-08-06',
            'nombre' => 'Carlos Hernández',
            'correo' => 'carlos.hernandez@outlook.com',
            'afiliacion' => 'Público general',
            'tarifa' => 600.00,
            'descuento' => 0.00,
            'monto' => 600.00,
            'cuenta' => '456789',
            'estado' => 'Comprobante rechazado',
            'ficha' => 'FIC-3',
            'fechaFicha' => '2026-08-05',
            'fechaVencimiento' => '2026-11-05',
            'comprobante' => 'comprobante_pago_3.pdf',
            'fechaComprobante' => '2026-08-06',
            'tipoComprobante' => 'PDF',
            'tamComprobante' => '950 KB',
            'motivoRechazo' => 'El monto del comprobante no coincide con la ficha de pago. Se requiere un comprobante legible por el importe total de $2,000.00.',
            'fechaInicio' => null,
            'fechaFin' => null,
            'comentario' => 'Suscripción externa anual.'
        ],
        '4' => [
            'id' => '4',
            'fechaSolicitud' => '2026-05-10',
            'fechaRevision' => null,
            'nombre' => 'Ana García',
            'correo' => 'ana.garcia@gmail.com',
            'afiliacion' => 'Comunidad UNAM',
            'tarifa' => 600.00,
            'descuento' => 300.00,
            'monto' => 300.00,
            'cuenta' => '112233',
            'estado' => 'Expirada',
            'ficha' => 'FIC-4',
            'fechaFicha' => '2026-05-10',
            'fechaVencimiento' => '2026-07-10',
            'comprobante' => null,
            'fechaComprobante' => null,
            'tipoComprobante' => null,
            'tamComprobante' => null,
            'motivoRechazo' => null,
            'fechaInicio' => null,
            'fechaFin' => null,
            'comentario' => 'Interesada en la revista mensual.'
        ],
        '5' => [
            'id' => '5',
            'fechaSolicitud' => '2026-08-07',
            'fechaRevision' => '2026-08-08',
            'nombre' => ' Rodríguez',
            'correo' => 'lrodriguez@fca.unam.mx',
            'afiliacion' => 'Comunidad UNAM',
            'tarifa' => 600.00,
            'descuento' => 300.00,
            'monto' => 300.00,
            'cuenta' => '334455',
            'estado' => 'Aprobada',
            'ficha' => 'FIC-6',
            'fechaFicha' => '2026-08-07',
            'fechaVencimiento' => '2026-11-07',
            'comprobante' => 'comprobante_pago_6.pdf',
            'fechaComprobante' => '2026-08-08',
            'tipoComprobante' => 'PDF',
            'tamComprobante' => '1.1 MB',
            'motivoRechazo' => null,
            'fechaInicio' => '2026-08-08',
            'fechaFin' => '2027-08-08',
            'comentario' => 'Suscripción con descuento de sindicato.'
        ],
        '6' => [
            'id' => '6',
            'fechaSolicitud' => '2026-08-07',
            'fechaRevision' => '2026-08-08',
            'nombre' => 'Viri Sanchez',
            'correo' => 'viri.sanchez@fca.unam.mx',
            'afiliacion' => 'Comunidad UNAM',
            'tarifa' => 600.00,
            'descuento' => 300.00,
            'monto' => 300.00,
            'cuenta' => '334455',
            'estado' => 'Aprobada',
            'ficha' => 'FIC-6',
            'fechaFicha' => '2026-08-07',
            'fechaVencimiento' => '2026-11-07',
            'comprobante' => 'comprobante_pago_6.pdf',
            'fechaComprobante' => '2026-08-08',
            'tipoComprobante' => 'PDF',
            'tamComprobante' => '1.1 MB',
            'motivoRechazo' => null,
            'fechaInicio' => '2026-08-08',
            'fechaFin' => '2027-08-08',
            'comentario' => 'Suscripción con descuento de sindicato.'
        ],
        '7' => [
            'id' => '7',
            'fechaSolicitud' => '2026-09-01',
            'fechaRevision' => '2026-09-02',
            'nombre' => 'Suscriptor de Prueba',
            'correo' => 'suscriptor@fca.unam.mx',
            'afiliacion' => 'Comunidad UNAM',
            'tarifa' => 600.00,
            'descuento' => 300.00,
            'monto' => 300.00,
            'cuenta' => '778899',
            'estado' => 'Aprobada',
            'ficha' => 'FIC-7',
            'fechaFicha' => '2026-09-02',
            'fechaVencimiento' => '2026-12-02',
            'comprobante' => 'comprobante_pago_7.pdf',
            'fechaComprobante' => '2026-09-02',
            'tipoComprobante' => 'PDF',
            'tamComprobante' => '1.0 MB',
            'motivoRechazo' => null,
            'fechaInicio' => '2026-09-02',
            'fechaFin' => '2027-09-02',
            'comentario' => 'Suscripción de la cuenta de prueba para el flujo de facturación.'
        ]
    ];
}

// Inicializar el historial de notificaciones si no existe
if (!isset($_SESSION['notificaciones'])) {
    $_SESSION['notificaciones'] = [
        [
            'solicitud_id' => '6',
            'fecha' => '2026-08-08 11:20:45',
            'mensaje' => 'La solicitud de suscripción 6 ha sido aprobada. Tu suscripción está activa.',
            'tipo' => 'success'
        ],
        [
            'solicitud_id' => '3',
            'fecha' => '2026-08-06 14:15:22',
            'mensaje' => 'Tu comprobante de pago fue rechazado. Motivo: El monto del comprobante no coincide con la ficha de pago.',
            'tipo' => 'danger'
        ],
        [
            'solicitud_id' => '2',
            'fecha' => '2026-08-11 09:30:00',
            'mensaje' => 'Tu ficha de pago está disponible para descarga.',
            'tipo' => 'info'
        ]
    ];
}

function get_solicitudes() {
    return $_SESSION['solicitudes'];
}

function get_solicitud($id) {
    return $_SESSION['solicitudes'][$id] ?? null;
}

function update_solicitud($id, $data) {
    if (isset($_SESSION['solicitudes'][$id])) {
        $_SESSION['solicitudes'][$id] = array_merge($_SESSION['solicitudes'][$id], $data);
        return true;
    }
    return false;
}

function add_notificacion($solicitud_id, $mensaje, $tipo = 'info') {
    array_unshift($_SESSION['notificaciones'], [
        'solicitud_id' => $solicitud_id,
        'fecha' => date('Y-m-d H:i:s'),
        'mensaje' => $mensaje,
        'tipo' => $tipo
    ]);
}

function get_notificaciones() {
    return $_SESSION['notificaciones'];
}

function reset_solicitudes() {
    unset($_SESSION['solicitudes']);
    unset($_SESSION['notificaciones']);
    header("Location: solicitudes.php");
    exit();
}

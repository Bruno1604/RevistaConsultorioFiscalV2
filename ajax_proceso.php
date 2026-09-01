<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

// Solo el usuario dueño del proceso puede llamar este endpoint
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'usuario') {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'No autorizado.']);
    exit();
}

require __DIR__ . '/includes/proceso_store.php';

$correo = $_SESSION['correo'];
$nombre = $_SESSION['nombre'] ?? $correo;
$accion = $_POST['accion'] ?? '';

function proceso_responder(array $p) {
    // Si el almacén no es escribible, el estado no persiste y el flujo
    // volvería al primer paso sin explicación: mejor avisarlo.
    if (!is_writable(PROCESO_STORE_PATH)) {
        http_response_code(500);
        echo json_encode([
            'ok'    => false,
            'error' => 'No se pudo guardar tu avance: la carpeta "data" no tiene permisos de escritura en el servidor.'
        ]);
        exit();
    }
    $p['paso_actual'] = proceso_calcular_paso_actual($p);
    echo json_encode(['ok' => true, 'estado' => $p]);
    exit();
}

function proceso_error($mensaje, $codigo = 400) {
    http_response_code($codigo);
    echo json_encode(['ok' => false, 'error' => $mensaje]);
    exit();
}

/**
 * Guarda un PDF subido (credencial o comprobante).
 * Devuelve [nombreOriginal, rutaRelativa] o [null, null] si falla.
 */
function guardar_archivo_subido($campo, $subcarpeta, $correo) {
    if (!isset($_FILES[$campo]) || $_FILES[$campo]['error'] !== UPLOAD_ERR_OK) {
        return [null, null];
    }
    $archivo = $_FILES[$campo];
    $ext = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
    if ($ext !== 'pdf') {
        return [null, null];
    }
    $dirRel = 'uploads/' . $subcarpeta;
    $dirAbs = __DIR__ . '/' . $dirRel;
    if (!is_dir($dirAbs)) {
        mkdir($dirAbs, 0775, true);
    }
    $nombreSeguro = preg_replace('/[^a-zA-Z0-9]/', '_', $correo) . '_' . date('Ymd_His') . '.pdf';
    $rutaAbs = $dirAbs . '/' . $nombreSeguro;
    if (!move_uploaded_file($archivo['tmp_name'], $rutaAbs)) {
        return [null, null];
    }
    return [$archivo['name'], $dirRel . '/' . $nombreSeguro];
}

switch ($accion) {

    case 'consultar_estado':
        proceso_responder(proceso_obtener($correo, $nombre));
        break;

    case 'seleccionar_tarifa':
        $actual = proceso_obtener($correo, $nombre);
        if (!empty($actual['ficha_generada'])) {
            proceso_responder($actual); // ya no se puede cambiar
        }
        $tarifa = ($_POST['tarifa'] ?? 'GENERAL') === 'UNAM' ? 'UNAM' : 'GENERAL';
        proceso_responder(proceso_actualizar($correo, [
            'tarifa_seleccionada' => $tarifa,
            'tarifa_confirmada'   => false,
        ], $nombre));
        break;

    case 'confirmar_tarifa':
        proceso_responder(proceso_actualizar($correo, ['tarifa_confirmada' => true], $nombre));
        break;

    case 'enviar_credencial':
        list($nombreArchivo, $ruta) = guardar_archivo_subido('archivo', 'credenciales', $correo);
        if (!$ruta) {
            proceso_error('Debes adjuntar tu credencial en formato PDF.');
        }
        proceso_responder(proceso_actualizar($correo, [
            'credencial_subida'         => true,
            'credencial_nombre_archivo' => $nombreArchivo,
            'credencial_ruta_archivo'   => $ruta,
            'credencial_fecha_envio'    => date('d/m/Y H:i'),
            'credencial_estado'         => 'en_revision',
            'credencial_motivo_rechazo' => '',
        ], $nombre));
        break;

    case 'reintentar_credencial':
        proceso_responder(proceso_actualizar($correo, ['credencial_estado' => 'sin_enviar'], $nombre));
        break;

    case 'generar_ficha':
        $actual = proceso_obtener($correo, $nombre);
        if (!empty($actual['ficha_generada'])) {
            proceso_responder($actual);
        }
        proceso_responder(proceso_actualizar($correo, [
            'ficha_generada'       => true,
            'fecha_creacion_ficha' => date('d/m/Y'),
        ], $nombre));
        break;

    case 'enviar_comprobante':
        list($nombreArchivo, $ruta) = guardar_archivo_subido('archivo', 'comprobantes', $correo);
        if (!$ruta) {
            proceso_error('Debes adjuntar tu comprobante en formato PDF.');
        }
        $fecha     = $_POST['fecha_pago'] ?? '';
        $importe   = $_POST['importe'] ?? '';
        $clave     = trim($_POST['clave_rastreo'] ?? '');
        $operacion = trim($_POST['num_operacion'] ?? '');
        if ($fecha === '' || $importe === '' || $clave === '' || $operacion === '') {
            proceso_error('Faltan datos del comprobante de pago.');
        }
        proceso_responder(proceso_actualizar($correo, [
            'comprobante_subido'         => true,
            'comprobante_nombre_archivo' => $nombreArchivo,
            'comprobante_ruta_archivo'   => $ruta,
            'comprobante_fecha_envio'    => date('d/m/Y H:i'),
            'comprobante_fecha_pago'     => $fecha,
            'comprobante_importe'        => number_format((float) $importe, 2, '.', ''),
            'comprobante_clave_rastreo'  => $clave,
            'comprobante_num_operacion'  => $operacion,
            'comprobante_estado'         => 'en_revision',
            'comprobante_motivo_rechazo' => '',
        ], $nombre));
        break;

    case 'reintentar_comprobante':
        proceso_responder(proceso_actualizar($correo, ['comprobante_estado' => 'sin_enviar'], $nombre));
        break;

    case 'reset':
        proceso_responder(proceso_reiniciar($correo, $nombre));
        break;

    case 'simular':
        // Mantiene vivo el panel de pruebas, pero ahora persistiendo en el store compartido.
        $modo = $_POST['modo'] ?? '';
        if ($modo === 'reset') {
            proceso_responder(proceso_reiniciar($correo, $nombre));
        }

        $cambios = [];
        switch ($modo) {
            case 'general':
                $cambios = [
                    'tarifa_seleccionada' => 'GENERAL', 'tarifa_confirmada' => true,
                    'ficha_generada' => true, 'fecha_creacion_ficha' => date('d/m/Y'),
                ];
                break;
            case 'unam_revision':
                $cambios = [
                    'tarifa_seleccionada' => 'UNAM', 'tarifa_confirmada' => true,
                    'credencial_subida' => true, 'credencial_estado' => 'en_revision',
                    'ficha_generada' => false,
                ];
                break;
            case 'unam_rechazada':
                $cambios = [
                    'tarifa_seleccionada' => 'UNAM', 'tarifa_confirmada' => true,
                    'credencial_subida' => true, 'credencial_estado' => 'rechazada',
                    'credencial_motivo_rechazo' => 'La imagen de la credencial resulta ilegible en la matrícula y el sello oficial.',
                    'ficha_generada' => false,
                ];
                break;
            case 'unam_aprobada':
                $cambios = [
                    'tarifa_seleccionada' => 'UNAM', 'tarifa_confirmada' => true,
                    'credencial_subida' => true, 'credencial_estado' => 'aprobada',
                    'ficha_generada' => false,
                ];
                break;
            case 'comprobante_revision':
                $cambios = [
                    'tarifa_seleccionada' => 'UNAM', 'tarifa_confirmada' => true,
                    'credencial_subida' => true, 'credencial_estado' => 'aprobada',
                    'ficha_generada' => true, 'fecha_creacion_ficha' => date('d/m/Y'),
                    'comprobante_subido' => true, 'comprobante_estado' => 'en_revision',
                    'comprobante_fecha_pago' => date('d/m/Y'), 'comprobante_importe' => '300.00',
                    'comprobante_clave_rastreo' => '202608274001478291', 'comprobante_num_operacion' => '00849312',
                ];
                break;
            case 'comprobante_rechazado':
                $cambios = [
                    'tarifa_seleccionada' => 'UNAM', 'tarifa_confirmada' => true,
                    'credencial_subida' => true, 'credencial_estado' => 'aprobada',
                    'ficha_generada' => true, 'fecha_creacion_ficha' => date('d/m/Y'),
                    'comprobante_subido' => true, 'comprobante_estado' => 'rechazado',
                    'comprobante_motivo_rechazo' => 'El comprobante no muestra el importe exacto de $300.00 MXN ni la clave de rastreo bancaria.',
                ];
                break;
            case 'completada':
                $cambios = [
                    'tarifa_seleccionada' => 'UNAM', 'tarifa_confirmada' => true,
                    'credencial_subida' => true, 'credencial_estado' => 'aprobada',
                    'ficha_generada' => true, 'fecha_creacion_ficha' => date('d/m/Y'),
                    'comprobante_subido' => true, 'comprobante_estado' => 'aprobado',
                ];
                break;
            default:
                proceso_error('Modo de simulación desconocido.');
        }
        proceso_responder(proceso_actualizar($correo, $cambios, $nombre));
        break;

    default:
        proceso_error('Acción desconocida.');
}

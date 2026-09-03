<?php
/**
 * Almacenamiento "sin base de datos" del proceso de suscripción.
 * Guarda el estado de cada solicitante en un archivo JSON plano
 * (data/proceso_estado.json), y sirve de puente entre las páginas
 * del alumno (proceso.php / ajax_proceso.php) y las páginas de
 * administración (credenciales.php, revisarComprobante.php).
 */

define('PROCESO_STORE_PATH', __DIR__ . '/../data/proceso_estado.json');

/**
 * Estructura por defecto de una solicitud nueva.
 */
function proceso_estado_default($correo, $nombre) {
    return [
        'usuario_nombre'    => $nombre,
        'usuario_correo'    => $correo,
        'folio_preregistro' => 'PRE-' . date('Y') . '-' . str_pad((string) (abs(crc32($correo)) % 9999), 4, '0', STR_PAD_LEFT),
        'fecha_preregistro' => date('d/m/Y'),

        // Estado del flujo
        'tarifa_seleccionada' => 'GENERAL', // 'UNAM' o 'GENERAL'
        'tarifa_confirmada'   => false,
        'modalidad_fca'       => null, 
        'paso_actual'         => 1,

        // Credencial UNAM
        'credencial_subida'         => false,
        'credencial_nombre_archivo' => null,
        'credencial_ruta_archivo'   => null,
        'credencial_fecha_envio'    => null,
        'credencial_estado'         => 'sin_enviar', // sin_enviar | en_revision | aprobada | rechazada
        'credencial_motivo_rechazo' => '',

        // Ficha de pago
        'ficha_generada'         => false,
        'folio_ficha'             => 'FIC-' . date('Y') . '-' . str_pad((string) (abs(crc32($correo . 'ficha')) % 9999), 4, '0', STR_PAD_LEFT),
        'numero_convenio_ficha'   => str_pad((string) (abs(crc32($correo . 'conv')) % 9999999), 7, '0', STR_PAD_LEFT),
        'fecha_creacion_ficha'    => null,
        'fecha_vencimiento_ficha' => date('d/m/Y', strtotime('+30 days')),

        // Comprobante de pago
        'comprobante_subido'         => false,
        'comprobante_nombre_archivo' => null,
        'comprobante_ruta_archivo'   => null,
        'comprobante_fecha_envio'    => null,
        'comprobante_fecha_pago'     => null,
        'comprobante_importe'        => null,
        'comprobante_clave_rastreo'  => null,
        'comprobante_num_operacion'  => null,
        'comprobante_estado'         => 'sin_enviar', // sin_enviar | en_revision | aprobado | rechazado
        'comprobante_motivo_rechazo' => '',

        // Suscripción final
        'numero_suscriptor' => 'SUB-' . date('Y') . '-' . str_pad((string) (abs(crc32($correo . 'sub')) % 9999), 4, '0', STR_PAD_LEFT),
        'vigencia_fin'       => date('d/m/Y', strtotime('+1 year')),
    ];
}

/** Lee (y crea si no existe) el archivo completo con todas las solicitudes, indexado por correo. */
function proceso_cargar_todos() {
    if (!file_exists(PROCESO_STORE_PATH)) {
        $dir = dirname(PROCESO_STORE_PATH);
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
            @chmod($dir, 0777);
        }
        file_put_contents(PROCESO_STORE_PATH, json_encode(new stdClass()));
        // El archivo debe quedar escribible por el usuario con el que corre PHP,
        // que no siempre es el dueño de los archivos subidos por FTP.
        @chmod(PROCESO_STORE_PATH, 0666);
    }

    $fp = @fopen(PROCESO_STORE_PATH, 'r');
    if (!$fp) {
        return [];
    }
    flock($fp, LOCK_SH);
    $contenido = stream_get_contents($fp);
    flock($fp, LOCK_UN);
    fclose($fp);

    $datos = json_decode($contenido, true);
    return is_array($datos) ? $datos : [];
}

/** Escribe el archivo completo con todas las solicitudes (con bloqueo exclusivo). */
function proceso_guardar_todos(array $datos) {
    $dir = dirname(PROCESO_STORE_PATH);
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
    $fp = @fopen(PROCESO_STORE_PATH, 'c+');
    if (!$fp) {
        // Un segundo intento tras corregir permisos: si el archivo existe pero
        // no es escribible, el proceso se quedaría sin guardar y el flujo
        // regresaría siempre al primer paso.
        @chmod(PROCESO_STORE_PATH, 0666);
        $fp = @fopen(PROCESO_STORE_PATH, 'c+');
    }
    if (!$fp) {
        return false;
    }
    flock($fp, LOCK_EX);
    ftruncate($fp, 0);
    rewind($fp);
    fwrite($fp, json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    fflush($fp);
    flock($fp, LOCK_UN);
    fclose($fp);
    return true;
}

/** Obtiene la solicitud de un correo, creándola con valores por defecto si es la primera vez. */
function proceso_obtener($correo, $nombre = null) {
    $todos = proceso_cargar_todos();
    if (!isset($todos[$correo])) {
        $todos[$correo] = proceso_estado_default($correo, $nombre ?: $correo);
        proceso_guardar_todos($todos);
    }
    return $todos[$correo];
}

/** Actualiza (merge) los campos indicados de la solicitud de un correo y guarda. */
function proceso_actualizar($correo, array $cambios, $nombre = null) {
    $todos = proceso_cargar_todos();
    if (!isset($todos[$correo])) {
        $todos[$correo] = proceso_estado_default($correo, $nombre ?: $correo);
    }
    $todos[$correo] = array_merge($todos[$correo], $cambios);
    proceso_guardar_todos($todos);
    return $todos[$correo];
}

/** Reinicia por completo la solicitud de un correo. */
function proceso_reiniciar($correo, $nombre = null) {
    $todos = proceso_cargar_todos();
    $todos[$correo] = proceso_estado_default($correo, $nombre ?: $correo);
    proceso_guardar_todos($todos);
    return $todos[$correo];
}

/** Solicitudes UNAM/FCA con credencial pendiente de revisión (para el panel del admin). */
function proceso_listar_credenciales() {
    $todos = proceso_cargar_todos();
    return array_filter($todos, function ($p) {
        return isset($p['tarifa_seleccionada'], $p['credencial_estado'])
            && in_array($p['tarifa_seleccionada'], ['UNAM', 'FCA'], true)
            && $p['credencial_estado'] === 'en_revision';
    });
}

/** Solicitudes con comprobante de pago pendiente de revisión (para el panel del admin). */
function proceso_listar_comprobantes() {
    $todos = proceso_cargar_todos();
    return array_filter($todos, function ($p) {
        return isset($p['comprobante_estado']) && $p['comprobante_estado'] === 'en_revision';
    });
}

/** Calcula un paso_actual "sensato" a partir del estado guardado (útil al recargar la página). */
function proceso_calcular_paso_actual(array $p) {
    if (($p['comprobante_estado'] ?? '') === 'aprobado') {
        return 6;
    }
    if (!empty($p['comprobante_subido']) || !empty($p['ficha_generada'])) {
        return 5;
    }
    if (in_array($p['tarifa_seleccionada'] ?? '', ['UNAM', 'FCA'], true)) {
        if (($p['credencial_estado'] ?? '') === 'aprobada') {
            return 4;
        }
        if (!empty($p['credencial_subida']) || !empty($p['tarifa_confirmada'])) {
            return 3;
        }
    } else {
        if (!empty($p['tarifa_confirmada'])) {
            return 4;
        }
    }
    return 1;
}

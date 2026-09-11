<?php
require_once __DIR__ . '/solicitudes.php';
require_once __DIR__ . '/../includes/proceso_store.php';
/*
 NOTA IMPORTANTE
 ══════════════════════════════════════════════════════════════
 El sistema todavía no cuenta vistas ni descargas de verdad
 no hay ningún código en articulo.php / contenido.php que registre
 cuando alguien ve o descarga algo. Por eso, para Visualizaciones y
 Descargas usamos datos de ejemplo, con los títulos y números de
 revista reales del catálogo (los mismos de admin_revistas.php),
 pero con cifras de vistas/descargas inventadas.
 
 Suscripciones SÍ es real: viene de data/solicitudes.php.
 
 Cuando se decida instrumentar vistas/descargas de verdad,
 solo hay que reemplazar get_revistas_ejemplo_stats() y
 get_articulos_ejemplo_stats() por funciones que lean de un
 contador real (por ejemplo, un data/vistas.php nuevo), sin tener
 que tocar las pantallas que ya están armadas.
  */
function get_revistas_ejemplo_stats() {
    return [
        ['numero' => '878', 'titulo' => 'Deducciones personales en la declaración anual', 'anio' => '2026', 'mes' => 3, 'vistas' => 512, 'descargas' => 214],
        ['numero' => '877', 'titulo' => 'Declaración anual de personas morales del régimen general', 'anio' => '2026', 'mes' => 2, 'vistas' => 388, 'descargas' => 176],
        ['numero' => '884', 'titulo' => 'Participación de utilidades para personas físicas', 'anio' => '2026', 'mes' => 5, 'vistas' => 645, 'descargas' => 301],
        ['numero' => '886', 'titulo' => 'Intereses por pago indebido', 'anio' => '2026', 'mes' => 7, 'vistas' => 203, 'descargas' => 89],
        ['numero' => '885', 'titulo' => 'Revisiones de las autoridades fiscales y de seguridad social', 'anio' => '2026', 'mes' => 6, 'vistas' => 470, 'descargas' => 198],
        ['numero' => '887', 'titulo' => 'Modalidades de dividendos en ISR', 'anio' => '2026', 'mes' => 8, 'vistas' => 356, 'descargas' => 142],
        ['numero' => '882', 'titulo' => 'Reforma a la LFT', 'anio' => '2026', 'mes' => 4, 'vistas' => 812, 'descargas' => 390],
        ['numero' => '881', 'titulo' => 'PTU: preguntas y respuestas', 'anio' => '2026', 'mes' => 5, 'vistas' => 734, 'descargas' => 355],
        ['numero' => '888', 'titulo' => 'El nuevo régimen de confianza', 'anio' => '2026', 'mes' => 9, 'vistas' => 268, 'descargas' => 121],
    ];
}

function get_articulos_ejemplo_stats() {
    return [
        ['titulo' => 'Artículo 1',      'revista' => 'Deducciones personales en la declaración anual',                       'numero' => '878', 'anio' => '2026', 'mes' => 3, 'vistas' => 245, 'descargas' => 98],
        ['titulo' => 'Artículo A',      'revista' => 'Declaración anual de personas morales del régimen general',           'numero' => '877', 'anio' => '2026', 'mes' => 2, 'vistas' => 190, 'descargas' => 77],
        ['titulo' => 'Artículo X',      'revista' => 'Participación de utilidades para personas físicas',                   'numero' => '884', 'anio' => '2026', 'mes' => 5, 'vistas' => 410, 'descargas' => 205],
        ['titulo' => 'Artículo Único',  'revista' => 'Revisiones de las autoridades fiscales y de seguridad social',        'numero' => '885', 'anio' => '2026', 'mes' => 6, 'vistas' => 320, 'descargas' => 130],
        ['titulo' => 'Artículo B',      'revista' => 'Modalidades de dividendos en ISR',                                    'numero' => '887', 'anio' => '2026', 'mes' => 8, 'vistas' => 156, 'descargas' => 60],
        ['titulo' => 'Introducción',    'revista' => 'El nuevo régimen de confianza',                                       'numero' => '888', 'anio' => '2026', 'mes' => 9, 'vistas' => 134, 'descargas' => 50],
        ['titulo' => 'Artículo Único',  'revista' => 'PTU: preguntas y respuestas',                                          'numero' => '881', 'anio' => '2026', 'mes' => 5, 'vistas' => 501, 'descargas' => 240],
    ];
}

/* 
Suscripciones: EStadisticas reales, basadas en data/solicitudes.php.
*/
function get_stats_suscripciones() {
    $todas = get_solicitudes();

    $stats = [
        'total_registradas' => count($todas),
        'activas'    => 0,
        'por_estado' => [],
        'detalle'    => [],
    ];

    foreach ($todas as $s) {
        $estado = $s['estado'];
        $stats['por_estado'][$estado] = ($stats['por_estado'][$estado] ?? 0) + 1;

        if ($estado === 'Aprobada') {
            $stats['activas']++;
        }

        $fechaRef = $s['fechaInicio'] ?? $s['fechaSolicitud'] ?? null;
        $anio = $fechaRef ? substr($fechaRef, 0, 4) : 'N/D';
        $mes  = $fechaRef ? (int) substr($fechaRef, 5, 2) : 0;

        $stats['detalle'][] = [
            'nombre' => $s['nombre'],
            'correo' => $s['correo'],
            'estado' => $estado,
            'anio'   => $anio,
            'mes'    => $mes,
            'monto'  => $s['monto'] ?? 0,
        ];
    }

    return $stats;
}

/* 
Suscripciones basado en eltramite de suscripcion. Se hizo el camnbio para que no se vea el nomre y correo de nadie. 
Solo sumas y desglosa
*/

function get_stats_suscripciones_tramite(){
    $todos = proceso_cargar_todos();

    $stats = [
        'total_tramites' => count($todos),
        'completadas'    => 0, // paso_actual === 6 (Suscripción Completada)
        'por_tarifa'     => ['GENERAL' => 0, 'UNAM' => 0, 'FCA' => 0],
        'por_modalidad_fca' => ['SUAYED' => 0, 'ESCOLARIZADO' => 0, 'POSGRADO' => 0],
    ];

    foreach ($todos as $correo => $p) {
        $tarifa = $p['tarifa_seleccionada'] ?? 'GENERAL';
        if (!isset($stats['por_tarifa'][$tarifa])) {
            $stats['por_tarifa'][$tarifa] = 0;
        }
        $stats['por_tarifa'][$tarifa]++;

        if ((int) ($p['paso_actual'] ?? 1) === 6) {
            $stats['completadas']++;
        }

        if ($tarifa === 'FCA' && !empty($p['modalidad_fca'])) {
            $mod = $p['modalidad_fca'];
            if (!isset($stats['por_modalidad_fca'][$mod])) {
                $stats['por_modalidad_fca'][$mod] = 0;
            }
            $stats['por_modalidad_fca'][$mod]++;
        }
    }

    return $stats;

}

/** Nombre para mostrar cada tarifa/modalidad en pantalla. */
function etiqueta_tarifa($clave) {
    $mapa = ['GENERAL' => 'Público General', 'UNAM' => 'Comunidad UNAM', 'FCA' => 'Alumnos FCA'];
    return $mapa[$clave] ?? $clave;
}
function etiqueta_modalidad($clave) {
    $mapa = ['SUAYED' => 'SUAyED', 'ESCOLARIZADO' => 'Escolarizado', 'POSGRADO' => 'Posgrado'];
    return $mapa[$clave] ?? $clave;
}

/*
 * Suscripciones en una sola tabla combinada, con las 6 categorías
 * Las primeras 5 son datos REALES (vienen del Trámite de Suscripción,
 * data/proceso_estado.json). "Docentes" es un dato de EJEMPLO, porque
 * todavía no existe como tipo de tarifa real en el sistema (el
 * Trámite de Suscripción solo acepta Público General / Comunidad UNAM /
 * Alumnos FCA) -- por eso se marca aparte con 'ejemplo' => true.
 */
function get_stats_suscripciones_combinado() {
    $real = get_stats_suscripciones_tramite();

    return [
        ['categoria' => 'Público en general', 'cantidad' => $real['por_tarifa']['GENERAL'],          'ejemplo' => false],
        ['categoria' => 'Comunidad UNAM',      'cantidad' => $real['por_tarifa']['UNAM'],             'ejemplo' => false],
        ['categoria' => 'SUAyED',              'cantidad' => $real['por_modalidad_fca']['SUAYED'],       'ejemplo' => false],
        ['categoria' => 'Escolarizado',        'cantidad' => $real['por_modalidad_fca']['ESCOLARIZADO'], 'ejemplo' => false],
        ['categoria' => 'Posgrado',            'cantidad' => $real['por_modalidad_fca']['POSGRADO'],     'ejemplo' => false],
        ['categoria' => 'Docentes',            'cantidad' => 9,                                       'ejemplo' => true],
    ];
}

/**
 * Desglose por tipo de persona para Visualizaciones/Descargas.
 * Dato de EJEMPLO (igual que las revistas/artículos de arriba), con las
 * mismas 6 categorías que la tabla de Suscripciones, para que todo el
 * panel de Estadísticas hable el mismo lenguaje.
 */
function get_ejemplo_por_tipo_usuario() {
    return [
        'vistas' => [
            'Público en general' => 950,
            'Comunidad UNAM'     => 710,
            'SUAyED'             => 480,
            'Escolarizado'       => 690,
            'Posgrado'           => 320,
            'Docentes'           => 640,
        ],
        'descargas' => [
            'Público en general' => 420,
            'Comunidad UNAM'     => 295,
            'SUAyED'             => 210,
            'Escolarizado'       => 305,
            'Posgrado'           => 140,
            'Docentes'           => 310,
        ],
    ];
}

/* Nombre del mes en español, para mostrar en filtros y tablas. */
function nombre_mes($numMes) {
    $meses = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
        7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
    ];
    return $meses[(int) $numMes] ?? '';
}
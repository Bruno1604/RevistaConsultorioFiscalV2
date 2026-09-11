<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once 'data/estadisticas.php';

$tipo = $_GET['tipo'] ?? '';

/** Envía un arreglo de filas como CSV descargable (se abre directo en Excel). */
function exportar_csv($nombreArchivo, $encabezados, $filas) {
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');

    $output = fopen('php://output', 'w');
    fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8, para que Excel muestre bien los acentos
    fputcsv($output, $encabezados);
    foreach ($filas as $fila) {
        fputcsv($output, $fila);
    }
    fclose($output);
    exit();
}

// Filtros que ya estaban aplicados en pantalla (para que el Excel traiga lo mismo que se ve)
$filtroAnio = trim($_GET['anio'] ?? '');
$filtroMes = trim($_GET['mes'] ?? '');
$filtroRevista = trim($_GET['revista'] ?? '');

function filtrar_para_excel($items, $anio, $mes, $revista, $campoRevista) {
    return array_values(array_filter($items, function ($it) use ($anio, $mes, $revista, $campoRevista) {
        if ($anio !== '' && $it['anio'] !== $anio) return false;
        if ($mes !== '' && (int) $it['mes'] !== (int) $mes) return false;
        if ($revista !== '' && $it[$campoRevista] !== $revista) return false;
        return true;
    }));
}

switch ($tipo) {

    case 'suscripciones':
        $filas = [];
        foreach (get_stats_suscripciones_combinado() as $fila) {
            $filas[] = [$fila['categoria'], $fila['cantidad'], $fila['ejemplo'] ? 'Ejemplo' : 'Real'];
        }
        exportar_csv('suscripciones.csv', ['Categoría', 'Cantidad', 'Tipo de dato'], $filas);
        break;

    case 'revistas_vistas':
        $items = filtrar_para_excel(get_revistas_ejemplo_stats(), $filtroAnio, $filtroMes, $filtroRevista, 'titulo');
        usort($items, fn($a, $b) => $b['vistas'] <=> $a['vistas']);
        $filas = array_map(fn($r) => [$r['numero'], $r['titulo'], $r['anio'], nombre_mes($r['mes']), $r['vistas']], $items);
        exportar_csv('revistas_mas_vistas.csv', ['Ejemplar', 'Título', 'Año', 'Mes', 'Vistas'], $filas);
        break;

    case 'articulos_vistos':
        $items = filtrar_para_excel(get_articulos_ejemplo_stats(), $filtroAnio, $filtroMes, $filtroRevista, 'revista');
        usort($items, fn($a, $b) => $b['vistas'] <=> $a['vistas']);
        $filas = array_map(fn($a) => [$a['titulo'], $a['revista'], $a['anio'], nombre_mes($a['mes']), $a['vistas']], $items);
        exportar_csv('articulos_mas_vistos.csv', ['Artículo', 'Revista', 'Año', 'Mes', 'Vistas'], $filas);
        break;

    case 'revistas_descargadas':
        $items = filtrar_para_excel(get_revistas_ejemplo_stats(), $filtroAnio, $filtroMes, $filtroRevista, 'titulo');
        usort($items, fn($a, $b) => $b['descargas'] <=> $a['descargas']);
        $filas = array_map(fn($r) => [$r['numero'], $r['titulo'], $r['anio'], nombre_mes($r['mes']), $r['descargas']], $items);
        exportar_csv('revistas_mas_descargadas.csv', ['Ejemplar', 'Título', 'Año', 'Mes', 'Descargas'], $filas);
        break;

    case 'articulos_descargados':
        $items = filtrar_para_excel(get_articulos_ejemplo_stats(), $filtroAnio, $filtroMes, $filtroRevista, 'revista');
        usort($items, fn($a, $b) => $b['descargas'] <=> $a['descargas']);
        $filas = array_map(fn($a) => [$a['titulo'], $a['revista'], $a['anio'], nombre_mes($a['mes']), $a['descargas']], $items);
        exportar_csv('articulos_mas_descargados.csv', ['Artículo', 'Revista', 'Año', 'Mes', 'Descargas'], $filas);
        break;

    default:
        header("Location: estadisticas.php");
        exit();
}
<?php
session_start();

// Verificar que el usuario sea administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Inicializar el listado de revistas en sesión (si no existe)
if (!isset($_SESSION['revistas']) || empty($_SESSION['revistas'])) {
    // Datos de ejemplo
    $_SESSION['revistas'] = [
        [
            'id' => 1,
            'titulo' => 'Deducciones personales en la declaración anual',
            'numero' => '878',
            'anio' => '2026',
            'fecha' => 'Segunda de marzo 2026',
            'pdf' => 'revista878.pdf',
            'imagenes' => ['pag1.jpg', 'pag2.jpg'],
            'articulos' => [
                ['titulo' => 'Artículo 1', 'inicio' => 1, 'fin' => 5],
                ['titulo' => 'Artículo 2', 'inicio' => 6, 'fin' => 10]
            ]
        ],
        [
            'id' => 2,
            'titulo' => 'Declaración anual de personas morales del régimen general',
            'numero' => '877',
            'anio' => '2026',
            'fecha' => 'Primera de marzo 2026',
            'pdf' => 'revista877.pdf',
            'imagenes' => ['pag1.jpg'],
            'articulos' => [
                ['titulo' => 'Artículo A', 'inicio' => 1, 'fin' => 8]
            ]
        ],
        [
            'id' => 3,
            'titulo' => 'Participación de utilidades para personas físicas',
            'numero' => '884',
            'anio' => '2026',
            'fecha' => 'Segunda de junio 2026',
            'pdf' => 'revista884.pdf',
            'imagenes' => [],
            'articulos' => [
                ['titulo' => 'Artículo X', 'inicio' => 2, 'fin' => 7]
            ]
        ],
        [
            'id' => 4,
            'titulo' => 'Intereses por pago indebido',
            'numero' => '886',
            'anio' => '2026',
            'fecha' => 'Segunda de julio 2026',
            'pdf' => 'revista886.pdf',
            'imagenes' => ['pag1.jpg', 'pag2.jpg', 'pag3.jpg'],
            'articulos' => [
                ['titulo' => 'Artículo 1', 'inicio' => 1, 'fin' => 4],
                ['titulo' => 'Artículo 2', 'inicio' => 5, 'fin' => 9]
            ]
        ],
        [
            'id' => 5,
            'titulo' => 'Revisiones de las autoridades fiscales y de seguridad social',
            'numero' => '885',
            'anio' => '2026',
            'fecha' => 'Primera de julio 2026',
            'pdf' => 'revista885.pdf',
            'imagenes' => ['pag1.jpg'],
            'articulos' => [
                ['titulo' => 'Artículo Único', 'inicio' => 1, 'fin' => 12]
            ]
        ],
        [
            'id' => 6,
            'titulo' => 'Modalidades de dividendos en ISR',
            'numero' => '887',
            'anio' => '2026',
            'fecha' => 'Primera de agosto 2026',
            'pdf' => 'revista887.pdf',
            'imagenes' => [],
            'articulos' => [
                ['titulo' => 'Artículo A', 'inicio' => 1, 'fin' => 6],
                ['titulo' => 'Artículo B', 'inicio' => 7, 'fin' => 10]
            ]
        ],
        [
            'id' => 7,
            'titulo' => 'Reforma a la LFT',
            'numero' => '882',
            'anio' => '2026',
            'fecha' => 'Segunda de mayo 2026',
            'pdf' => 'revista882.pdf',
            'imagenes' => ['pag1.jpg', 'pag2.jpg'],
            'articulos' => [
                ['titulo' => 'Artículo 1', 'inicio' => 1, 'fin' => 3]
            ]
        ],
        [
            'id' => 8,
            'titulo' => 'PTU: preguntas y respuestas',
            'numero' => '881',
            'anio' => '2026',
            'fecha' => 'Primera de mayo 2026',
            'pdf' => 'revista881.pdf',
            'imagenes' => ['pag1.jpg'],
            'articulos' => [
                ['titulo' => 'Artículo Único', 'inicio' => 1, 'fin' => 15]
            ]
        ],
        [
            'id' => 9,
            'titulo' => 'El nuevo régimen de confianza',
            'numero' => '888',
            'anio' => '2026',
            'fecha' => 'Segunda de agosto 2026',
            'pdf' => 'revista888.pdf',
            'imagenes' => ['pag1.jpg', 'pag2.jpg', 'pag3.jpg'],
            'articulos' => [
                ['titulo' => 'Introducción', 'inicio' => 1, 'fin' => 2],
                ['titulo' => 'Desarrollo', 'inicio' => 3, 'fin' => 10]
            ]
        ]
    ];
}

// Función para obtener el siguiente ID disponible
function getNextId($revistas) {
    $max = 0;
    foreach ($revistas as $r) {
        if ($r['id'] > $max) $max = $r['id'];
    }
    return $max + 1;
}

// Procesar acciones (agregar, editar, eliminar) - Simulación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    if ($_POST['accion'] === 'agregar') {
        // Simular agregar una nueva revista (datos del formulario)
        $nueva = [
            'id' => getNextId($_SESSION['revistas']),
            'titulo' => $_POST['titulo'] ?? 'Sin título',
            'numero' => $_POST['numero'] ?? '000',
            'anio' => $_POST['anio'] ?? '2026',
            'fecha' => $_POST['fecha'] ?? date('d/m/Y'),
            'pdf' => $_FILES['pdf']['name'] ?? 'sin-pdf.pdf',
            'imagenes' => $_FILES['imagenes']['name'] ?? [],
            'articulos' => []
        ];
        // Simular artículos (se envían como JSON en un campo oculto)
        if (isset($_POST['articulos_json'])) {
            $nueva['articulos'] = json_decode($_POST['articulos_json'], true);
        } else {
            $nueva['articulos'] = [['titulo' => 'Artículo de ejemplo', 'inicio' => 1, 'fin' => 5]];
        }
        $_SESSION['revistas'][] = $nueva;
        // Redirigir para evitar reenvío del formulario
        header("Location: admin_revistas.php?page=1&msg=agregado");
        exit();
    } elseif ($_POST['accion'] === 'eliminar') {
        $id = intval($_POST['id']);
        $_SESSION['revistas'] = array_filter($_SESSION['revistas'], function($r) use ($id) {
            return $r['id'] !== $id;
        });
        // Reindexar
        $_SESSION['revistas'] = array_values($_SESSION['revistas']);
        header("Location: admin_revistas.php?page=" . ($_GET['page'] ?? 1) . "&msg=eliminado");
        exit();
    }
    // Editar no implementado en esta demo
}

// 1. Obtener parámetros de búsqueda y filtrado
$search_q = isset($_GET['q']) ? trim($_GET['q']) : '';
$search_mes = isset($_GET['mes']) ? intval($_GET['mes']) : 0;
$search_anio = isset($_GET['anio']) ? trim($_GET['anio']) : '';

// 2. Extraer lista dinámica de años disponibles de la sesión
$anios_disponibles = [];
foreach ($_SESSION['revistas'] as $r) {
    if (!empty($r['anio']) && !in_array((string)$r['anio'], $anios_disponibles, true)) {
        $anios_disponibles[] = (string)$r['anio'];
    }
}
rsort($anios_disponibles);

// 3. Aplicar filtros a las revistas de la sesión
$revistas_filtradas = array_filter($_SESSION['revistas'], function($r) use ($search_q, $search_mes, $search_anio) {
    // Filtro por nombre (título) o número de revista
    if ($search_q !== '') {
        $q_lc = mb_strtolower($search_q, 'UTF-8');
        $titulo_lc = mb_strtolower($r['titulo'] ?? '', 'UTF-8');
        $numero_lc = mb_strtolower((string)($r['numero'] ?? ''), 'UTF-8');

        if (strpos($titulo_lc, $q_lc) === false && strpos($numero_lc, $q_lc) === false) {
            return false;
        }
    }

    // Filtro por año
    if ($search_anio !== '') {
        $anio_r = (string)($r['anio'] ?? '');
        $fecha_r = (string)($r['fecha'] ?? '');
        if ($anio_r !== $search_anio && strpos($fecha_r, $search_anio) === false) {
            return false;
        }
    }

    // Filtro por mes
    if ($search_mes >= 1 && $search_mes <= 12) {
        $meses_patrones = [
            1 => ['enero', '/01/', '-01-', '.01.'],
            2 => ['febrero', '/02/', '-02-', '.02.'],
            3 => ['marzo', '/03/', '-03-', '.03.'],
            4 => ['abril', '/04/', '-04-', '.04.'],
            5 => ['mayo', '/05/', '-05-', '.05.'],
            6 => ['junio', '/06/', '-06-', '.06.'],
            7 => ['julio', '/07/', '-07-', '.07.'],
            8 => ['agosto', '/08/', '-08-', '.08.'],
            9 => ['septiembre', '/09/', '-09-', '.09.'],
            10 => ['octubre', '/10/', '-10-', '.10.'],
            11 => ['noviembre', '/11/', '-11-', '.11.'],
            12 => ['diciembre', '/12/', '-12-', '.12.']
        ];

        $fecha_lc = mb_strtolower($r['fecha'] ?? '', 'UTF-8');
        $coincide = false;
        if (isset($meses_patrones[$search_mes])) {
            foreach ($meses_patrones[$search_mes] as $patron) {
                if (strpos($fecha_lc, $patron) !== false) {
                    $coincide = true;
                    break;
                }
            }
        }
        if (!$coincide) {
            return false;
        }
    }

    return true;
});

// Reindexar array de resultados filtrados
$revistas_filtradas = array_values($revistas_filtradas);

// Configuración de paginación sobre las revistas filtradas
$items_por_pagina = 6;
$total_items = count($revistas_filtradas);
$total_paginas = max(1, ceil($total_items / $items_por_pagina));
$pagina_actual = isset($_GET['page']) ? max(1, min($total_paginas, intval($_GET['page']))) : 1;
$offset = ($pagina_actual - 1) * $items_por_pagina;
$revistas_pagina = array_slice($revistas_filtradas, $offset, $items_por_pagina);

// Función auxiliar para construir URLs con parámetros de filtrado
function buildPageUrl($p, $q, $mes, $anio) {
    $params = ['page' => $p];
    if ($q !== '') $params['q'] = $q;
    if ($mes > 0) $params['mes'] = $mes;
    if ($anio !== '') $params['anio'] = $anio;
    return '?' . http_build_query($params);
}

$page_title = "Administrar Revistas - Consultorio Fiscal";
$page = "admin_revistas";
include 'template/header.php';
?>

<!-- Hero de administración -->
<section class="hero-static" style="padding: 60px 0 40px;">
  <div class="cs">
    <div class="hero-static__grid">
      <div class="hero-static__content">
        <span class="c-ph__tag">Administración</span>
        <h1 class="hero-static__title">Gestión de Revistas</h1>
        <div class="gold-line gold-l"></div>
        <p class="hero-static__excerpt">
          Administra los ejemplares de la revista Consultorio Fiscal. Agrega, edita o elimina revistas,
          así como sus artículos e imágenes.
        </p>
        <?php if (isset($_GET['msg'])): ?>
          <div class="mt-3 alert alert-success" style="background: #e8f5e9; color: #2e7d32; padding: 10px 20px; border-radius: 8px; border-left: 4px solid #2e7d32;">
            <?php if ($_GET['msg'] === 'agregado'): ?>
              ✅ Revista agregada correctamente.
            <?php elseif ($_GET['msg'] === 'eliminado'): ?>
              ✅ Revista eliminada correctamente.
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>
      <div class="hero-static__visual">
        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="1.5">
          <path d="M4 4h16v16H4zM8 8h8M8 12h6M8 16h4"/>
          <path d="M4 4l16 16M20 4L4 20" stroke="var(--gold)" stroke-width="1" opacity="0.5"/>
        </svg>
      </div>
    </div>
  </div>
</section>

<section class="about" style="padding: 42px 0 60px;">
  <div class="cs">
    <!-- Barra de búsqueda y filtros -->
    <div style="display: flex; align-items: center; gap: 12px; margin: 0 0 28px;">
      <div style="display: flex; align-items: center;">
        <button class="btn-ghost" id="btnNuevaRevista" style="border-color: var(--navy); color: #fff; background: var(--navy); height: 42px; padding: 0 20px; white-space: nowrap;">
          <span>+ Nueva Revista</span>
        </button>
      </div>

      <div style="flex: 1 1 auto; min-width: 0;">
        <form method="get" action="admin_revistas.php" style="display: flex; align-items: stretch; gap: 10px; width: 100%; min-width: 0; flex-wrap: nowrap;">
          <input type="text" name="q" value="<?= htmlspecialchars($search_q) ?>" class="form-control-custom" placeholder="Nombre o número de revista..." style="flex: 1 1 260px; min-width: 180px; height: 42px; padding: 8px 14px;">

          <select name="mes" class="form-control-custom" style="flex: 0 0 150px; min-width: 120px; height: 42px; padding: 8px 14px; cursor: pointer;">
            <option value="">Meses</option>
            <?php
            $meses_list = [
              1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
              5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
              9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
            ];
            foreach ($meses_list as $num => $nombre):
            ?>
              <option value="<?= $num ?>" <?= ($search_mes === $num) ? 'selected' : '' ?>><?= $nombre ?></option>
            <?php endforeach; ?>
          </select>

          <select name="anio" class="form-control-custom" style="flex: 0 0 130px; min-width: 110px; height: 42px; padding: 8px 14px; cursor: pointer;">
            <option value="">Años</option>
            <?php foreach ($anios_disponibles as $a): ?>
              <option value="<?= htmlspecialchars($a) ?>" <?= ($search_anio === (string)$a) ? 'selected' : '' ?>><?= htmlspecialchars($a) ?></option>
            <?php endforeach; ?>
          </select>

          <button type="submit" class="btn-ghost" style="border-color: var(--gold); color: var(--gold); height: 42px; padding: 0 18px; white-space: nowrap;">
            <span>Buscar</span>
          </button>

          <?php if ($search_q !== '' || $search_mes > 0 || $search_anio !== ''): ?>
            <a href="admin_revistas.php" class="btn-ghost" style="border-color: var(--text-soft); color: var(--text-soft); height: 42px; padding: 0 14px; text-decoration: none; display: inline-flex; align-items: center; white-space: nowrap;">
              <span>Limpiar</span>
            </a>
          <?php endif; ?>
        </form>
      </div>
    </div>

    <!-- Listado de revistas (generado con PHP) -->
    <div id="listaRevistas" style="margin-top: 8px;">
      <div class="row g-4">
        <?php if (empty($revistas_pagina)): ?>
          <div class="col-12 text-center" style="padding: 40px 0;">
            <p style="color: #5a6a7a;">No se encontraron revistas que coincidan con los criterios de búsqueda.</p>
            <?php if ($search_q !== '' || $search_mes > 0 || $search_anio !== ''): ?>
              <a href="admin_revistas.php" class="btn-ghost" style="border-color: var(--gold); color: var(--gold); margin-top: 10px; display: inline-block; text-decoration: none;">
                <span>Ver todas las revistas</span>
              </a>
            <?php endif; ?>
          </div>
        <?php else: ?>
          <?php foreach ($revistas_pagina as $revista): ?>
            <div class="col-md-6 col-lg-4">
              <div class="broadcast-card" style="padding: 20px; height: 100%; display: flex; flex-direction: column;">
                <div class="broadcast-card__head">
                  <span class="broadcast-card__platform">Ejemplar No. <?= htmlspecialchars($revista['numero']) ?></span>
                  <span class="live-dot" style="animation: none; background: var(--gold);"></span>
                </div>
                <h3 class="broadcast-card__channel" style="font-size: 1.1rem; flex-grow: 1;">
                  <?= htmlspecialchars($revista['titulo']) ?>
                </h3>
                <p class="broadcast-card__time"><?= htmlspecialchars($revista['fecha']) ?></p>
                <div class="mt-2 d-flex gap-2 flex-wrap">
                  <button class="btn-ghost btn-sm btn-editar" style="padding: 5px 15px; font-size: 0.6rem;" data-id="<?= $revista['id'] ?>">Editar</button>
                  <form method="post" style="display: inline;" onsubmit="return confirm('¿Eliminar esta revista?')">
                    <input type="hidden" name="accion" value="eliminar">
                    <input type="hidden" name="id" value="<?= $revista['id'] ?>">
                    <button type="submit" class="btn-ghost btn-sm" style="padding: 5px 15px; font-size: 0.6rem; border-color: #d9534f; color: #d9534f;">Eliminar</button>
                  </form>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

    <!-- Paginador -->
    <?php if ($total_paginas > 1): ?>
      <nav aria-label="Paginación de revistas" style="margin-top: 40px;">
        <ul class="pagination" style="justify-content: center; gap: 5px; flex-wrap: wrap;">
          <!-- Anterior -->
          <li class="page-item <?= ($pagina_actual <= 1) ? 'disabled' : '' ?>">
            <a class="page-link" href="<?= buildPageUrl($pagina_actual - 1, $search_q, $search_mes, $search_anio) ?>" style="border-radius: 4px; padding: 8px 16px; color: var(--gold); text-decoration: none; border: 1px solid #e0d6c8; background: #fff;">&laquo;</a>
          </li>

          <?php
          // Mostrar páginas con lógica de "elipsis"
          $rango = 2;
          $inicio = max(1, $pagina_actual - $rango);
          $fin = min($total_paginas, $pagina_actual + $rango);

          if ($inicio > 1) {
            echo '<li class="page-item"><a class="page-link" href="' . buildPageUrl(1, $search_q, $search_mes, $search_anio) . '" style="border-radius: 4px; padding: 8px 16px; color: var(--gold); text-decoration: none; border: 1px solid #e0d6c8; background: #fff;">1</a></li>';
            if ($inicio > 2) echo '<li class="page-item disabled"><span class="page-link" style="border-radius: 4px; padding: 8px 16px; border: 1px solid #e0d6c8; background: #f8f5f0;">…</span></li>';
          }

          for ($i = $inicio; $i <= $fin; $i++) {
            $active_style = ($i === $pagina_actual) ? 'background: var(--gold); color: #fff; border-color: var(--gold);' : 'color: var(--gold); background: #fff;';
            echo '<li class="page-item"><a class="page-link" href="' . buildPageUrl($i, $search_q, $search_mes, $search_anio) . '" style="border-radius: 4px; padding: 8px 16px; text-decoration: none; border: 1px solid #e0d6c8; ' . $active_style . '">' . $i . '</a></li>';
          }

          if ($fin < $total_paginas) {
            if ($fin < $total_paginas - 1) echo '<li class="page-item disabled"><span class="page-link" style="border-radius: 4px; padding: 8px 16px; border: 1px solid #e0d6c8; background: #f8f5f0;">…</span></li>';
            echo '<li class="page-item"><a class="page-link" href="' . buildPageUrl($total_paginas, $search_q, $search_mes, $search_anio) . '" style="border-radius: 4px; padding: 8px 16px; color: var(--gold); text-decoration: none; border: 1px solid #e0d6c8; background: #fff;">' . $total_paginas . '</a></li>';
          }
          ?>

          <!-- Siguiente -->
          <li class="page-item <?= ($pagina_actual >= $total_paginas) ? 'disabled' : '' ?>">
            <a class="page-link" href="<?= buildPageUrl($pagina_actual + 1, $search_q, $search_mes, $search_anio) ?>" style="border-radius: 4px; padding: 8px 16px; color: var(--gold); text-decoration: none; border: 1px solid #e0d6c8; background: #fff;">&raquo;</a>
          </li>
        </ul>
        <div style="text-align: center; margin-top: 12px; font-size: 0.9rem; color: #5a6a7a;">
          Mostrando <?= count($revistas_pagina) ?> de <?= $total_items ?> revistas (Página <?= $pagina_actual ?> de <?= $total_paginas ?>)
        </div>
      </nav>
    <?php endif; ?>

    <!-- Formulario para agregar/editar (oculto por defecto) -->
    <div id="formularioRevista" style="display: none; margin-top: 40px; border-top: 1px solid rgba(184,150,85,0.2); padding-top: 40px;">
      <h2 class="mb-4" style="font-family: var(--serif); font-weight: 300; color: var(--navy);">
        <span id="formTitulo">Nueva Revista</span>
      </h2>

      <form id="revistaForm" method="post" enctype="multipart/form-data" class="row g-4">
        <input type="hidden" name="accion" value="agregar">
        <!-- Datos básicos -->
        <div class="col-md-6">
          <label for="titulo" class="form-label lbl">Título de la revista</label>
          <input type="text" id="titulo" name="titulo" class="form-control-custom" placeholder="Título completo" required>
        </div>
        <div class="col-md-3">
          <label for="numero" class="form-label lbl">Número</label>
          <input type="text" id="numero" name="numero" class="form-control-custom" placeholder="Ej. 878" required>
        </div>
        <div class="col-md-3">
          <label for="anio" class="form-label lbl">Año</label>
          <input type="number" id="anio" name="anio" class="form-control-custom" placeholder="2026" required>
        </div>
        <div class="col-md-6">
          <label for="fecha" class="form-label lbl">Fecha de publicación</label>
          <input type="text" id="fecha" name="fecha" class="form-control-custom" placeholder="Ej. Segunda de marzo 2026" required>
        </div>
        <div class="col-md-6">
          <label for="pdf" class="form-label lbl">Archivo PDF (revista completa)</label>
          <input type="file" id="pdf" name="pdf" class="form-control-custom" accept=".pdf">
        </div>

        <!-- Artículos (división de páginas) -->
        <div class="col-12">
          <div class="d-flex justify-content-between align-items-center">
            <h4 style="font-family: var(--serif); font-weight: 300; color: var(--navy);">Artículos</h4>
            <button type="button" class="btn-ghost btn-sm" id="agregarArticulo" style="padding: 5px 15px; font-size: 0.6rem;">
              + Agregar artículo
            </button>
          </div>
          <div id="articulosContainer" class="mt-3">
            <!-- Filas de artículos se agregarán aquí -->
          </div>
        </div>

        <!-- Campo oculto para enviar artículos como JSON -->
        <input type="hidden" name="articulos_json" id="articulos_json">

        <!-- Imágenes de páginas (JPG) -->
        <div class="col-12">
          <label for="imagenes" class="form-label lbl">Imágenes de las páginas (JPG)</label>
          <input type="file" id="imagenes" name="imagenes[]" class="form-control-custom" accept=".jpg,.jpeg" multiple>
          <small class="text-muted">Selecciona múltiples imágenes (una por página).</small>
        </div>

        <!-- Botones de acción -->
        <div class="col-12 d-flex gap-3 mt-4">
          <button type="submit" class="btn-ghost" style="border-color: var(--gold); color: var(--gold);">
            <span id="btnGuardarTexto">Guardar Revista</span>
          </button>
          <button type="button" class="btn-ghost" id="cancelarForm" style="border-color: var(--text-soft); color: var(--text-soft);">
            <span>Cancelar</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</section>

<style>
  .form-control-custom {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #e1e1e1;
    border-radius: 4px;
    font-family: var(--sans);
    transition: all 0.3s ease;
    background: #fff;
  }
  .form-control-custom:focus {
    outline: none;
    border-color: var(--gold);
    box-shadow: 0 0 0 3px var(--gold-lt);
  }
  .articulo-row {
    display: flex;
    gap: 15px;
    align-items: center;
    margin-bottom: 15px;
    background: var(--bg-warm);
    padding: 15px;
    border-radius: 4px;
    border-left: 3px solid var(--gold);
  }
  .articulo-row input {
    flex: 1;
  }
  .articulo-row .btn-eliminar-articulo {
    background: none;
    border: none;
    color: #d9534f;
    cursor: pointer;
    font-size: 1.2rem;
    padding: 0 10px;
  }
  .btn-sm {
    padding: 5px 15px;
    font-size: 0.6rem;
    letter-spacing: 0.15em;
  }
  /* Estilos para el paginador */
  .pagination .page-item .page-link {
    transition: all 0.2s;
  }
  .pagination .page-item .page-link:hover {
    background: var(--gold-lt, #f5ede4);
    border-color: var(--gold);
    color: var(--gold);
  }
  .pagination .page-item.active .page-link {
    background: var(--gold);
    color: #fff;
    border-color: var(--gold);
  }
  .pagination .page-item.disabled .page-link {
    color: #aaa;
    cursor: not-allowed;
    background: #f8f5f0;
    border-color: #e0d6c8;
  }
  @media (max-width: 768px) {
    .articulo-row {
      flex-wrap: wrap;
    }
    .articulo-row input {
      flex: 1 1 100%;
    }
    .pagination {
      gap: 3px;
    }
    .pagination .page-link {
      padding: 6px 12px;
      font-size: 0.8rem;
    }
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Mostrar/ocultar formulario
    const btnNueva = document.getElementById('btnNuevaRevista');
    const formulario = document.getElementById('formularioRevista');
    const cancelar = document.getElementById('cancelarForm');

    btnNueva.addEventListener('click', function() {
      formulario.style.display = 'block';
      document.getElementById('formTitulo').textContent = 'Nueva Revista';
      document.getElementById('btnGuardarTexto').textContent = 'Guardar Revista';
      // Limpiar formulario
      document.getElementById('revistaForm').reset();
      document.getElementById('articulosContainer').innerHTML = '';
      // Agregar una fila de artículo por defecto
      agregarFilaArticulo();
      window.scrollTo({ top: formulario.offsetTop - 120, behavior: 'smooth' });
    });

    cancelar.addEventListener('click', function() {
      formulario.style.display = 'none';
    });

    // Agregar fila de artículo
    document.getElementById('agregarArticulo').addEventListener('click', agregarFilaArticulo);

    function agregarFilaArticulo() {
      const container = document.getElementById('articulosContainer');
      const row = document.createElement('div');
      row.className = 'articulo-row';
      row.innerHTML = `
        <input type="text" class="form-control-custom" placeholder="Título del artículo" style="flex: 2;">
        <input type="number" class="form-control-custom" placeholder="Página inicio" style="flex: 1; min-width: 80px;">
        <input type="number" class="form-control-custom" placeholder="Página fin" style="flex: 1; min-width: 80px;">
        <button type="button" class="btn-eliminar-articulo" title="Eliminar artículo">&times;</button>
      `;
      container.appendChild(row);

      row.querySelector('.btn-eliminar-articulo').addEventListener('click', function() {
        if (container.children.length > 1) {
          row.remove();
        } else {
          alert('Debe haber al menos un artículo.');
        }
      });
    }

    // Antes de enviar el formulario, recopilar artículos en JSON
    document.getElementById('revistaForm').addEventListener('submit', function(e) {
      // Recoger artículos
      const filas = document.querySelectorAll('.articulo-row');
      const articulos = [];
      filas.forEach(fila => {
        const inputs = fila.querySelectorAll('input');
        const titulo = inputs[0].value.trim();
        const inicio = inputs[1].value.trim();
        const fin = inputs[2].value.trim();
        if (titulo && inicio && fin) {
          articulos.push({ titulo, inicio: parseInt(inicio), fin: parseInt(fin) });
        }
      });

      if (articulos.length === 0) {
        e.preventDefault();
        alert('Agrega al menos un artículo con páginas definidas.');
        return;
      }

      // Guardar JSON en campo oculto
      document.getElementById('articulos_json').value = JSON.stringify(articulos);
      // El formulario se envía normalmente
    });

    // (Opcional) Manejar edición (simulación)
    document.querySelectorAll('.btn-editar').forEach(btn => {
      btn.addEventListener('click', function() {
        alert('Función de edición en desarrollo. Este es un ejemplo de simulación.');
      });
    });
  });
</script>

<?php include 'template/footer.php'; ?>
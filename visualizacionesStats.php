<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once 'data/estadisticas.php';

$revistas = get_revistas_ejemplo_stats();
$articulos = get_articulos_ejemplo_stats();

// ── Filtros (CU-22: Año, Mes, Revista) ───────────────────────
$aniosDisponibles = array_unique(array_column($revistas, 'anio'));
sort($aniosDisponibles);
$revistasDisponibles = array_unique(array_column($revistas, 'titulo'));
sort($revistasDisponibles);

$filtroAnio = trim($_GET['anio'] ?? '');
$filtroMes = trim($_GET['mes'] ?? '');
$filtroRevista = trim($_GET['revista'] ?? '');
$orden = $_GET['orden'] ?? 'vistas';

function filtrar_stats($items, $anio, $mes, $revista, $campoRevista) {
    return array_values(array_filter($items, function ($it) use ($anio, $mes, $revista, $campoRevista) {
        if ($anio !== '' && $it['anio'] !== $anio) return false;
        if ($mes !== '' && (int) $it['mes'] !== (int) $mes) return false;
        if ($revista !== '' && $it[$campoRevista] !== $revista) return false;
        return true;
    }));
}

$revistasFiltradas = filtrar_stats($revistas, $filtroAnio, $filtroMes, $filtroRevista, 'titulo');
$articulosFiltrados = filtrar_stats($articulos, $filtroAnio, $filtroMes, $filtroRevista, 'revista');

usort($revistasFiltradas, fn($a, $b) => $orden === 'titulo' ? strcmp($a['titulo'], $b['titulo']) : $b['vistas'] <=> $a['vistas']);
usort($articulosFiltrados, fn($a, $b) => $orden === 'titulo' ? strcmp($a['titulo'], $b['titulo']) : $b['vistas'] <=> $a['vistas']);

$revistaTop = $revistasFiltradas[0] ?? null;
$articuloTop = $articulosFiltrados[0] ?? null;

$page_title = "Estadísticas de Visualizaciones - Consultorio Fiscal";
$page = "visualizacionesStats";
include 'template/header.php';
?>

<link rel="stylesheet" href="css/suscripciones.css">

<section class="hero-static" style="padding: 60px 0 30px;">
  <div class="cs">
    <div class="hero-static__grid">
      <div class="hero-static__content">
        <span class="c-ph__tag">Estadísticas</span>
        <h1 class="hero-static__title">Visualizaciones</h1>
        <div class="gold-line gold-l"></div>
        <p class="hero-static__excerpt">
          Revistas y artículos más vistos. <em>(Datos de ejemplo -- aún no existe un conteo real de vistas en el sistema.)</em>
        </p>
      </div>
      <div class="hero-static__visual">
        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="1.5">
          <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" />
          <circle cx="12" cy="12" r="3" />
        </svg>
      </div>
    </div>
  </div>
</section>

<section class="about" style="padding: 10px 0 60px;">
  <div class="cs">

    <!-- Indicadores destacados -->
    <div class="row g-3" style="margin-bottom: 25px;">
      <div class="col-md-6">
        <div class="detail-card" style="padding: 22px;">
          <div style="color: var(--text-soft); font-size: 0.8rem; margin-bottom: 4px;">Revista más vista</div>
          <div style="font-size: 1.1rem; color: var(--navy);"><?php echo $revistaTop ? htmlspecialchars($revistaTop['titulo']) : '-'; ?></div>
          <div style="color: var(--gold); font-weight: 600;"><?php echo $revistaTop ? number_format($revistaTop['vistas']) . ' vistas' : ''; ?></div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="detail-card" style="padding: 22px;">
          <div style="color: var(--text-soft); font-size: 0.8rem; margin-bottom: 4px;">Artículo más visto</div>
          <div style="font-size: 1.1rem; color: var(--navy);"><?php echo $articuloTop ? htmlspecialchars($articuloTop['titulo']) : '-'; ?></div>
          <div style="color: var(--gold); font-weight: 600;"><?php echo $articuloTop ? number_format($articuloTop['vistas']) . ' vistas' : ''; ?></div>
        </div>
      </div>
    </div>
    
        <!-- Por tipo de persona -->
    <div class="detail-card" style="margin-bottom: 25px; padding: 20px;">
      <h3 style="font-size: 1rem; margin-bottom: 12px;">Vistas por tipo de persona <em style="font-weight: normal; font-size: 0.75rem; color: var(--text-soft);">(dato de ejemplo)</em></h3>
      <div style="display: flex; flex-wrap: wrap; gap: 10px;">
        <?php foreach (get_ejemplo_por_tipo_usuario()['vistas'] as $tipo => $cantidad): ?>
          <span style="background: rgba(0,0,0,0.04); padding: 6px 14px; border-radius: 20px; font-size: 0.8rem;">
            <?php echo htmlspecialchars($tipo); ?>: <strong><?php echo number_format($cantidad); ?></strong>
          </span>
        <?php endforeach; ?>
      </div>
    </div>
    <!-- Filtros -->
    <form method="get" class="detail-card" style="padding: 20px; margin-bottom: 20px; display: flex; gap: 15px; flex-wrap: wrap; align-items: flex-end;">
      <div>
        <label class="form-label" style="font-size: 0.8rem;">Año</label>
        <select name="anio" class="form-control-custom">
          <option value="">Todos</option>
          <?php foreach ($aniosDisponibles as $a): ?>
            <option value="<?php echo htmlspecialchars($a); ?>" <?php echo $filtroAnio === $a ? 'selected' : ''; ?>><?php echo htmlspecialchars($a); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label class="form-label" style="font-size: 0.8rem;">Mes</label>
        <select name="mes" class="form-control-custom">
          <option value="">Todos</option>
          <?php for ($m = 1; $m <= 12; $m++): ?>
            <option value="<?php echo $m; ?>" <?php echo (int) $filtroMes === $m ? 'selected' : ''; ?>><?php echo nombre_mes($m); ?></option>
          <?php endfor; ?>
        </select>
      </div>
      <div>
        <label class="form-label" style="font-size: 0.8rem;">Revista</label>
        <select name="revista" class="form-control-custom" style="max-width: 260px;">
          <option value="">Todas</option>
          <?php foreach ($revistasDisponibles as $r): ?>
            <option value="<?php echo htmlspecialchars($r); ?>" <?php echo $filtroRevista === $r ? 'selected' : ''; ?>><?php echo htmlspecialchars($r); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label class="form-label" style="font-size: 0.8rem;">Ordenar por</label>
        <select name="orden" class="form-control-custom">
          <option value="vistas" <?php echo $orden === 'vistas' ? 'selected' : ''; ?>>Más vistas primero</option>
          <option value="titulo" <?php echo $orden === 'titulo' ? 'selected' : ''; ?>>Título (A-Z)</option>
        </select>
      </div>
      <div>
        <button type="submit" class="btn-gold-fill" style="height: 42px;"><span>Aplicar</span></button>
      </div>
    </form>

    <!-- Revistas más vistas -->
    <div class="detail-card" style="margin-bottom: 25px;">
      <div style="padding: 18px 18px 0;">
        <h3 style="font-size: 1rem;">Revistas más vistas</h3>
      </div>
      <div class="admin-table-container" style="overflow-x: auto;">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Ejemplar</th>
              <th>Título</th>
              <th>Año</th>
              <th>Mes</th>
              <th>Vistas</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($revistasFiltradas)): ?>
              <tr><td colspan="5" style="text-align:center; padding:30px; color: var(--text-soft);">No hay datos para los filtros seleccionados.</td></tr>
            <?php else: ?>
              <?php foreach ($revistasFiltradas as $r): ?>
                <tr>
                  <td>No. <?php echo htmlspecialchars($r['numero']); ?></td>
                  <td><?php echo htmlspecialchars($r['titulo']); ?></td>
                  <td><?php echo htmlspecialchars($r['anio']); ?></td>
                  <td><?php echo nombre_mes($r['mes']); ?></td>
                  <td><strong><?php echo number_format($r['vistas']); ?></strong></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Artículos más vistos -->
    <div class="detail-card">
      <div style="padding: 18px 18px 0;">
        <h3 style="font-size: 1rem;">Artículos más vistos</h3>
      </div>
      <div class="admin-table-container" style="overflow-x: auto;">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Artículo</th>
              <th>Revista</th>
              <th>Año</th>
              <th>Mes</th>
              <th>Vistas</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($articulosFiltrados)): ?>
              <tr><td colspan="5" style="text-align:center; padding:30px; color: var(--text-soft);">No hay datos para los filtros seleccionados.</td></tr>
            <?php else: ?>
              <?php foreach ($articulosFiltrados as $a): ?>
                <tr>
                  <td><?php echo htmlspecialchars($a['titulo']); ?></td>
                  <td><?php echo htmlspecialchars($a['revista']); ?></td>
                  <td><?php echo htmlspecialchars($a['anio']); ?></td>
                  <td><?php echo nombre_mes($a['mes']); ?></td>
                  <td><strong><?php echo number_format($a['vistas']); ?></strong></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</section>

<?php include 'template/footer.php'; ?>

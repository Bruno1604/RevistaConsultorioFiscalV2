<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once 'data/estadisticas.php';

$stats = get_stats_suscripciones();

// Filtros de año y mes

$aniosDisponibles = array_unique(array_column($stats['detalle'], 'anio'));
sort($aniosDisponibles);

$filtroAnio = trim($_GET['anio'] ?? '');
$filtroMes  = trim($_GET['mes'] ?? '');
$orden      = $_GET['orden'] ?? 'nombre';

$detalle = array_filter($stats['detalle'], function ($d) use ($filtroAnio, $filtroMes) {
    if ($filtroAnio !== '' && $d['anio'] !== $filtroAnio) return false;
    if ($filtroMes !== '' && (int) $d['mes'] !== (int) $filtroMes) return false;
    return true;
});

// Ordenar resultados

usort($detalle, function ($a, $b) use ($orden) {
    if ($orden === 'monto') return $b['monto'] <=> $a['monto'];
    if ($orden === 'estado') return strcmp($a['estado'], $b['estado']);
    return strcmp($a['nombre'], $b['nombre']);
});


$page_title = "Estadísticas de Suscripciones - Consultorio Fiscal";
$page = "suscriptoresStats";
include 'template/header.php';
?>

<link rel="stylesheet" href="css/suscripciones.css">

<section class="hero-static" style="padding: 60px 0 30px;">
  <div class="cs">
    <div class="hero-static__grid">
     <div class="hero-static__content">
        <span class="c-ph__tag">Estadísticas</span>
        <h1 class="hero-static__title">Suscripciones</h1>
        <div class="gold-line gold-l"></div>
        <p class="hero-static__excerpt">
 Indicadores generales de las solicitudes de suscripción registradas en el sistema.
        </p>
      </div>
      <div class="hero-static__visual">
        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="1.5">
          <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
          <circle cx="9" cy="7" r="4" />
          <path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
        </svg>
      </div>
    </div>
  </div>
</section>

<section class="about" style="padding: 10px 0 60px;">
  <div class="cs">

    <!-- Indicadores generales -->
    <div class="row g-3" style="margin-bottom: 25px;">
      <div class="col-md-4">
        <div class="detail-card" style="text-align: center; padding: 22px;">
          <div style="font-size: 2rem; font-family: var(--serif); color: var(--navy);"><?php echo $stats['total_registradas']; ?></div>
          <div style="color: var(--text-soft); font-size: 0.85rem;">Solicitudes registradas</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="detail-card" style="text-align: center; padding: 22px;">
          <div style="font-size: 2rem; font-family: var(--serif); color: #155724;"><?php echo $stats['activas']; ?></div>
          <div style="color: var(--text-soft); font-size: 0.85rem;">Suscriptores activos (Aprobada)</div>
                  </div>
      </div>
      <div class="col-md-4">
        <div class="detail-card" style="text-align: center; padding: 22px;">
          <div style="font-size: 2rem; font-family: var(--serif); color: var(--gold);"><?php echo count($stats['por_estado']); ?></div>
          <div style="color: var(--text-soft); font-size: 0.85rem;">Estados distintos registrados</div>
        </div>
      </div>
    </div>

    <!-- Desglose por estado -->
    <div class="detail-card" style="margin-bottom: 25px; padding: 20px;">
      <h3 style="font-size: 1rem; margin-bottom: 12px;">Desglose por estado</h3>
      <div style="display: flex; flex-wrap: wrap; gap: 10px;">
        <?php foreach ($stats['por_estado'] as $estadoNombre => $cantidad): ?>
          <span style="background: rgba(0,0,0,0.04); padding: 6px 14px; border-radius: 20px; font-size: 0.8rem;">
            <?php echo htmlspecialchars($estadoNombre); ?>: <strong><?php echo $cantidad; ?></strong>
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
        <label class="form-label" style="font-size: 0.8rem;">Ordenar por</label>
        <select name="orden" class="form-control-custom">
          <option value="nombre" <?php echo $orden === 'nombre' ? 'selected' : ''; ?>>Nombre</option>
          <option value="estado" <?php echo $orden === 'estado' ? 'selected' : ''; ?>>Estado</option>
          <option value="monto" <?php echo $orden === 'monto' ? 'selected' : ''; ?>>Monto (mayor a menor)</option>
        </select>
      </div>
      <div>
        <button type="submit" class="btn-gold-fill" style="height: 42px;"><span>Aplicar</span></button>
      </div>
    </form>

    <!-- Tabla de detalle -->
    <div class="detail-card">
      <div class="admin-table-container" style="overflow-x: auto;">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Correo</th>
              <th>Estado</th>
              <th>Monto</th>
              <th>Año</th>
              <th>Mes</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($detalle)): ?>
              <!-- A1: Sin información -->
              <tr>
                <td colspan="6" style="text-align: center; padding: 30px; color: var(--text-soft);">
                  No hay suscripciones registradas para los filtros seleccionados.
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($detalle as $d): ?>
                <tr>
                  <td><?php echo htmlspecialchars($d['nombre']); ?></td>
                  <td><?php echo htmlspecialchars($d['correo']); ?></td>
                  <td><?php echo htmlspecialchars($d['estado']); ?></td>
                  <td>$<?php echo number_format($d['monto'], 2); ?></td>
                  <td><?php echo htmlspecialchars($d['anio']); ?></td>
                  <td><?php echo $d['mes'] ? nombre_mes($d['mes']) : '-'; ?></td>
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
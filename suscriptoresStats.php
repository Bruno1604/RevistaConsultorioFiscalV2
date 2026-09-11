<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once 'data/estadisticas.php';
$stats = get_stats_suscripciones_tramite();
$combinado = get_stats_suscripciones_combinado();

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
          Total de personas que han hecho el Trámite de Suscripción, agrupado por tipo de tarifa y modalidad.
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
      <div class="col-md-6">
        <div class="detail-card" style="text-align: center; padding: 22px;">
          <div style="font-size: 2rem; font-family: var(--serif); color: var(--navy);"><?php echo $stats['total_tramites']; ?></div>
          <div style="color: var(--text-soft); font-size: 0.85rem;">Personas que iniciaron el trámite</div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="detail-card" style="text-align: center; padding: 22px;">
          <div style="font-size: 2rem; font-family: var(--serif); color: #155724;"><?php echo $stats['completadas']; ?></div>
          <div style="color: var(--text-soft); font-size: 0.85rem;">Suscripciones completadas</div>
        </div>
      </div>
    </div>

    <!-- Tabla única combinada -->
    <div class="detail-card">
      <div style="padding: 18px 18px 0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <div>
          <h3 style="font-size: 1rem;">Suscriptores por categoría</h3>
          <p style="font-size: 0.75rem; color: var(--text-soft); margin-top: 4px;">
            * "Docentes" es un dato de ejemplo -- todavía no existe como tipo de tarifa real en el sistema.
          </p>
        </div>
        <a href="exportar_estadisticas.php?tipo=suscripciones" class="btn-filter-navy-small" style="display:inline-flex; align-items:center; gap:6px; text-decoration:none;">
          <i class="fa fa-file-excel-o"></i> Descargar Excel
        </a>
      </div>
      <div class="admin-table-container" style="overflow-x: auto;">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Categoría</th>
              <th>Cantidad de suscriptores</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($combinado as $fila): ?>
              <tr>
                <td>
                  <?php echo htmlspecialchars($fila['categoria']); ?>
                  <?php if ($fila['ejemplo']): ?>
                    <span style="font-size: 0.7rem; color: var(--text-soft);">(ejemplo)*</span>
                  <?php endif; ?>
                </td>
                <td><strong><?php echo $fila['cantidad']; ?></strong></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</section>

<?php include 'template/footer.php'; ?>
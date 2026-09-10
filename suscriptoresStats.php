<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once 'data/estadisticas.php';

$stats = get_status_suscripciones_tramite();

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

    <!-- Desglose por tipo de tarifa -->
    <div class="detail-card" style="margin-bottom: 25px;">
      <div style="padding: 18px 18px 0;">
        <h3 style="font-size: 1rem;">Suscriptores por tipo de tarifa</h3>
      </div>
      <div class="admin-table-container" style="overflow-x: auto;">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Tipo de tarifa</th>
              <th>Cantidad de suscriptores</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($stats['total_tramites'])): ?>
              <tr><td colspan="2" style="text-align:center; padding:30px; color: var(--text-soft);">Aún no hay ningún trámite de suscripción registrado.</td></tr>
            <?php else: ?>
              <?php foreach ($stats['por_tarifa'] as $tarifaClave => $cantidad): ?>
                <tr>
                  <td><?php echo htmlspecialchars(etiqueta_tarifa($tarifaClave)); ?></td>
                  <td><strong><?php echo $cantidad; ?></strong></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Desglose por modalidad (solo Alumnos FCA) -->
    <div class="detail-card">
      <div style="padding: 18px 18px 0;">
        <h3 style="font-size: 1rem;">Alumnos FCA por modalidad</h3>
        <p style="font-size: 0.8rem; color: var(--text-soft); margin-top: 4px;">
          De los <?php echo $stats['por_tarifa']['FCA']; ?> suscriptores de Alumnos FCA, cuántos son de cada modalidad.
        </p>
      </div>
      <div class="admin-table-container" style="overflow-x: auto;">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Modalidad</th>
              <th>Cantidad</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($stats['por_tarifa']['FCA'])): ?>
              <tr><td colspan="2" style="text-align:center; padding:30px; color: var(--text-soft);">Aún no hay suscriptores de Alumnos FCA registrados.</td></tr>
            <?php else: ?>
              <?php foreach ($stats['por_modalidad_fca'] as $modClave => $cantidad): ?>
                <tr>
                  <td><?php echo htmlspecialchars(etiqueta_modalidad($modClave)); ?></td>
                  <td><strong><?php echo $cantidad; ?></strong></td>
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
<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$page_title = "Estadísticas - Consultorio Fiscal";
$page = "estadisticas";
include 'template/header.php';
?>

<section class="hero-static" style="padding: 60px 0 40px;">
  <div class="cs">
    <div class="hero-static__grid">
      <div class="hero-static__content">
        <span class="c-ph__tag">Panel de Administración</span>
        <h1 class="hero-static__title">Estadísticas</h1>
        <div class="gold-line gold-l"></div>
        <p class="hero-static__excerpt">
          Consulta los principales indicadores de la Revista Consultorio Fiscal.
        </p>
      </div>
      <div class="hero-static__visual">
        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="1.5">
          <path d="M4 19V5M4 19h16" />
          <path d="M8 16v-5M12 16V8M16 16v-9" />
        </svg>
      </div>
    </div>
  </div>
</section>

<section class="about" style="padding: 60px 0 80px;">
  <div class="cs">
    <div class="row g-4 justify-content-center">
      <div class="col-md-4 col-sm-6">
        <a href="suscriptoresStats.php" class="broadcast-card" style="padding: 30px 20px; text-align: center; text-decoration: none; display: block; height: 100%; background: #fff; color: var(--navy);">
          <div class="broadcast-card__head" style="justify-content: center; margin-bottom: 15px;">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--navy)" stroke-width="1.5">
              <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
              <circle cx="9" cy="7" r="4" />
              <path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
            </svg>
          </div>
          <h3 class="broadcast-card__channel" style="font-size: 1.1rem; color: var(--navy); text-align: center;">Suscripciones</h3>
          <p class="broadcast-card__time" style="color: var(--text-soft); font-weight: 300; font-size: 0.8rem; text-align: center;">Consultar estadísticas de suscriptores.</p>
        </a>
      </div>

      <div class="col-md-4 col-sm-6">
        <a href="visualizacionesStats.php" class="broadcast-card" style="padding: 30px 20px; text-align: center; text-decoration: none; display: block; height: 100%; background: #fff; color: var(--navy);">
          <div class="broadcast-card__head" style="justify-content: center; margin-bottom: 15px;">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--navy)" stroke-width="1.5">
              <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" />
              <circle cx="12" cy="12" r="3" />
            </svg>
          </div>
          <h3 class="broadcast-card__channel" style="font-size: 1.1rem; color: var(--navy); text-align: center;">Visualizaciones</h3>
          <p class="broadcast-card__time" style="color: var(--text-soft); font-weight: 300; font-size: 0.8rem; text-align: center;">Consultar estadísticas de visualizaciones.</p>
        </a>
      </div>

      <div class="col-md-4 col-sm-6">
        <a href="descargasStats.php" class="broadcast-card" style="padding: 30px 20px; text-align: center; text-decoration: none; display: block; height: 100%; background: #fff; color: var(--navy);">
          <div class="broadcast-card__head" style="justify-content: center; margin-bottom: 15px;">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--navy)" stroke-width="1.5">
              <path d="M12 3v12M7 10l5 5 5-5M4 21h16" />
            </svg>
          </div>
          <h3 class="broadcast-card__channel" style="font-size: 1.1rem; color: var(--navy); text-align: center;">Descargas</h3>
          <p class="broadcast-card__time" style="color: var(--text-soft); font-weight: 300; font-size: 0.8rem; text-align: center;">Consultar estadísticas de descargas.</p>
        </a>
      </div>
    </div>
  </div>
</section>

<?php include 'template/footer.php'; ?>

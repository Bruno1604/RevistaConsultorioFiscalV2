<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$page_title = "Estadísticas de Suscripciones - Consultorio Fiscal";
$page = "suscriptoresStats";
include 'template/header.php';
?>

<section class="hero-static" style="padding: 60px 0 80px;">
  <div class="cs">
    <div class="hero-static__content">
      <span class="c-ph__tag">Estadísticas</span>
      <h1 class="hero-static__title">Suscripciones</h1>
      <div class="gold-line gold-l"></div>
      <p class="hero-static__excerpt">Esta sección estará disponible próximamente.</p>
    </div>
  </div>
</section>

<?php include 'template/footer.php'; ?>

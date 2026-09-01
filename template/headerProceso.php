<header class="cfnav cfnav--static" id="siteNav" role="banner">
  <div class="cfnav__in">

    <a href="index.php" class="cfnav__brand">
      <img src="img/yaca.png" alt="UNAM · Facultad de Contaduría y Administración" class="cfnav__logo">
      <span class="cfnav__wordmark">Revista Consultorio <em>Fiscal</em></span>
    </a>

    <div class="cfnav__right">
      <span class="header-tramite-badge">Trámite de suscripción</span>

      <?php if (!empty($is_logged_in)): ?>
        <div class="cfnav__actions">
          <a href="logout.php" class="cfnav__cta">Cerrar sesión</a>
        </div>
      <?php endif; ?>
    </div>

  </div>
</header>

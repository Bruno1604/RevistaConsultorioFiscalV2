<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$is_logged_in = isset($_SESSION['usuario_id']);
$is_admin = ($is_logged_in && isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin');
$is_suscriptor = ($is_logged_in && isset($_SESSION['rol']) && in_array($_SESSION['rol'], ['suscriptor', 'usuario']));

// En la portada la barra flota sobre el héroe; en el resto arranca sólida.
$nav_mode = (isset($page) && $page === 'inicio') ? '' : 'cfnav--static';

// Cache-busting: añade ?v=<fecha de modificación> a los assets propios,
// para que una nueva subida al servidor invalide la caché del navegador
// sin depender de que alguien recuerde cambiar un número a mano.
function cf_asset($ruta) {
    $abs = __DIR__ . '/../' . $ruta;
    $v = file_exists($abs) ? filemtime($abs) : time();
    return $ruta . '?v=' . $v;
}

function nav_a($id, $current, $label, $url, $external = false) {
    $active = ($id === $current) ? 'active' : '';
    $target = $external ? 'target="_blank" rel="noopener"' : '';
    return "<li><a href='$url' class='$active' $target>$label</a></li>";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300;1,400&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?php echo cf_asset('css/style.css'); ?>">
    <link rel="icon" type="image/x-icon" href="img/fca_negro.png">
</head>
<body id="<?php echo $page; ?>">

<?php if (!empty($use_process_header)): ?>
<?php include __DIR__ . '/headerProceso.php'; ?>
<?php else: ?>

<header class="cfnav <?php echo $nav_mode; ?>" id="siteNav" role="banner">
  <div class="cfnav__in">

    <a href="index.php" class="cfnav__brand">
      <img src="img/yaca.png" alt="UNAM · Facultad de Contaduría y Administración" class="cfnav__logo">
      <span class="cfnav__wordmark">Revista Consultorio <em>Fiscal</em></span>
    </a>

    <div class="cfnav__right">

    <nav aria-label="Navegación principal">
      <ul class="cfnav__links">
        <?php echo nav_a('inicio', $page, 'Inicio', 'index.php'); ?>

        <?php echo nav_a('cuadros', $page, 'Indicadores y cuadros', 'cuadrosPermanentes.php'); ?>

        <li>
          <a href="#" class="<?php echo ($page == 'historico') ? 'active' : ''; ?>">Histórico <i class="fa fa-chevron-down" style="font-size:.5rem;"></i></a>
          <div class="cfnav__drop">
            <a href="buscar.php">Buscador de revistas</a>
            <a href="secciones.php">Secciones</a>
            <a href="tendencias.php">Artículos más leídos</a>
          </div>
        </li>

        <?php if ($is_admin): ?>
        <li>
          <a href="#" class="<?php echo ($page == 'admin' || $page == 'admin_revistas' || $page == 'admin_suscripciones') ? 'active' : ''; ?>">Administración <i class="fa fa-chevron-down" style="font-size:.5rem;"></i></a>
          <div class="cfnav__drop">
            <a href="admin.php">Panel de control</a>
            <a href="admin_revistas.php">Gestionar revistas</a>
            <a href="solicitudes.php">Gestionar suscripciones</a>
          </div>
        </li>
        <?php endif; ?>

        <!-- Menú de usuario: solo suscriptores ven "Mi cuenta" con opciones -->
        <?php if ($is_suscriptor): ?>
        <li>
          <a href="#" class="<?php echo ($page == 'perfil' || $page == 'perfiles_fiscales' || $page == 'solicitar_factura' || $page == 'favoritos') ? 'active' : ''; ?>">Mi cuenta <i class="fa fa-chevron-down" style="font-size:.5rem;"></i></a>
          <div class="cfnav__drop">
            <a href="perfil.php">Perfil</a>
            <a href="favoritos.php">Favoritos</a>
            <a href="perfilesFiscales.php">Perfiles fiscales</a>
            <a href="solicitarFactura.php">Solicitar factura</a>
          </div>
        </li>
        <?php endif; ?>
      </ul>
    </nav>

    <div class="cfnav__actions">
      <?php if ($is_logged_in): ?>
        <a href="logout.php" class="cfnav__cta">Cerrar sesión</a>
      <?php else: ?>
        <a href="login.php" class="cfnav__cta cfnav__cta--gold">Suscribirse</a>
      <?php endif; ?>
    </div>

    <button class="cfnav__burger" id="burger" aria-label="Abrir menú" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>

    </div><!-- /.cfnav__right -->
  </div>
</header>

<!-- Panel lateral (móvil) -->
<nav class="cfnav__panel" id="navPanel" aria-hidden="true">
  <a href="index.php">Inicio</a>
  <a href="cuadrosPermanentes.php">Indicadores y cuadros</a>

  <span class="mobile-label">Histórico</span>
  <a href="buscar.php">Buscador de revistas</a>
  <a href="tendencias.php">Artículos más leídos</a>

  <?php if ($is_admin): ?>
  <span class="mobile-label">Administración</span>
  <a href="admin.php">Panel de control</a>
  <a href="admin_revistas.php">Gestionar revistas</a>
  <a href="solicitudes.php">Gestionar suscripciones</a>
  <?php endif; ?>

  <!-- Menú de usuario móvil: solo suscriptores ven opciones de cuenta -->
  <?php if ($is_suscriptor): ?>
  <span class="mobile-label">Mi cuenta</span>
  <a href="perfil.php">Perfil</a>
  <a href="favoritos.php">Favoritos</a>
  <a href="perfilesFiscales.php">Perfiles fiscales</a>
  <a href="solicitarFactura.php">Solicitar factura</a>
  <?php endif; ?>

  <span class="mobile-label">Cuenta</span>
  <?php if ($is_logged_in): ?>
    <a href="logout.php" style="color:#d98a80;">Cerrar sesión</a>
  <?php else: ?>
    <a href="login.php" style="color:#D4B478;">Suscribirse</a>
  <?php endif; ?>
</nav>

<?php endif; ?>

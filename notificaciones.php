<?php
session_start();

// Precondición: el usuario debe haber iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Esta sección es exclusiva para suscriptores (el administrador no la usa)
if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') {
    header("Location: admin.php");
    exit();
}

require_once 'data/notificaciones.php';

// -----------------------------------------------------
// MARCAR UNA NOTIFICACIÓN COMO LEÍDA (y opcionalmente ir a su enlace)
// -----------------------------------------------------
if (isset($_GET['leida'])) {
    marcar_notificacion_leida($_GET['leida']);

    $destino = $_GET['redirigir'] ?? '';
    // Solo se permite redirigir a una ruta relativa propia del sitio (nunca a otro dominio)
    if ($destino === '' || preg_match('#^https?://#i', $destino) || strpos($destino, '//') === 0) {
        $destino = 'notificaciones.php';
    }

    header("Location: " . $destino);
    exit();
}

// -----------------------------------------------------
// MARCAR TODAS COMO LEÍDAS
// -----------------------------------------------------
if (isset($_GET['marcar_todas'])) {
    marcar_todas_notificaciones_leidas();
    header("Location: notificaciones.php");
    exit();
}

// Más recientes primero (los IDs se asignan de forma incremental)
$notificaciones = array_reverse(get_notificaciones_usuario(), true);
$no_leidas = contar_notificaciones_no_leidas();

$page_title = "Mis Notificaciones - Consultorio Fiscal";
$page = "notificaciones";
include 'template/header.php';
?>

<section class="hero-static">
  <div class="cs">
    <div class="hero-static__grid">
      <div class="hero-static__content reveal reveal--left in">
        <span class="c-ph__tag">Mi cuenta</span>
        <h1 class="hero-static__title">Mis Notificaciones</h1>
        <div class="gold-line gold-l"></div>
        <p class="hero-static__excerpt reveal reveal--left">
          Avisos sobre nuevas ediciones publicadas y sobre la vigencia de tu suscripción.
        </p>
      </div>
      <div class="hero-static__visual reveal reveal--right in">
        <div class="hero-static__img-box">
          <i class="fa-solid fa-bell" style="font-size:2.6rem;color:var(--gold);"></i>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="about" style="padding: 30px 0 70px;">
  <div class="cs">

    <div class="notif-toolbar">
      <span class="notif-toolbar__status">
        <?php if ($no_leidas > 0): ?>
          <?php echo $no_leidas; ?> <?php echo $no_leidas === 1 ? 'notificación' : 'notificaciones'; ?> sin leer
        <?php else: ?>
          Estás al día, no tienes notificaciones sin leer.
        <?php endif; ?>
      </span>
      <?php if ($no_leidas > 0): ?>
      <a href="notificaciones.php?marcar_todas=1" class="btn-ghost" style="border-color: var(--navy); padding: 9px 18px; font-size: .66rem;">
        <span>Marcar todas como leídas</span>
      </a>
      <?php endif; ?>
    </div>

    <?php if (empty($notificaciones)): ?>
      <div class="fav-empty">
        <i class="fa-regular fa-bell"></i>
        <h4>No tienes notificaciones</h4>
        <p>Aquí verás los avisos de nuevas ediciones y de la vigencia de tu suscripción.</p>
      </div>
    <?php else: ?>
      <div class="notif-rows">
        <?php foreach ($notificaciones as $n): ?>
        <?php
          $esRevista = $n['tipo'] === 'revista';
          $icono = $esRevista ? 'fa-book' : 'fa-triangle-exclamation';
          $noLeida = empty($n['leida']);
          $enlaceLeida = 'notificaciones.php?leida=' . urlencode($n['id']) . '&redirigir=' . urlencode($n['enlace'] ?? 'notificaciones.php');
        ?>
        <div class="notif-row<?php echo $noLeida ? ' notif-row--no-leida' : ''; ?>">
          <div class="notif-row__icon"><i class="fa-solid <?php echo $icono; ?>"></i></div>
          <div class="notif-row__body">
            <h4 class="notif-row__title">
              <?php echo htmlspecialchars($n['titulo']); ?>
              <?php if ($noLeida): ?><span class="notif-dot" title="No leída"></span><?php endif; ?>
            </h4>
            <p class="notif-row__meta"><?php echo date('d/m/Y', strtotime($n['fecha'])); ?></p>
            <p class="notif-row__desc"><?php echo htmlspecialchars($n['mensaje']); ?></p>
          </div>
          <div class="notif-row__actions">
            <a href="<?php echo htmlspecialchars($enlaceLeida); ?>" class="notif-row__open">
              <?php echo htmlspecialchars($n['enlace_texto'] ?? 'Ver'); ?> <i class="fa fa-arrow-right"></i>
            </a>
            <?php if ($noLeida): ?>
            <a href="notificaciones.php?leida=<?php echo urlencode($n['id']); ?>" class="notif-row__marcar" title="Marcar como leída">
              <i class="fa-regular fa-circle-check"></i> Marcar como leída
            </a>
            <?php endif; ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

  </div>
</section>

<style>
/* ══════════════ MIS NOTIFICACIONES ══════════════ */
.notif-toolbar{display:flex;align-items:center;justify-content:space-between;gap:16px;
  flex-wrap:wrap;margin-bottom:26px;padding-bottom:18px;border-bottom:1px solid var(--line)}
.notif-toolbar__status{font-family:var(--sans);font-size:.86rem;color:var(--ink-2)}

.notif-rows{display:flex;flex-direction:column;gap:16px}
.notif-row{display:flex;align-items:flex-start;gap:20px;background:#fff;border:1px solid var(--line);
  border-radius:var(--r);padding:22px 24px;box-shadow:0 4px 16px rgba(11,30,61,.04);
  transition:all .25s var(--e2)}
.notif-row:hover{border-color:rgba(176,141,76,.4);box-shadow:0 10px 26px rgba(11,30,61,.08)}
.notif-row--no-leida{background:var(--paper-2);border-color:rgba(176,141,76,.3)}
.notif-row__icon{width:44px;height:44px;border-radius:50%;background:var(--paper-2);color:var(--gold);
  display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:1rem}
.notif-row--no-leida .notif-row__icon{background:var(--gold);color:#fff}
.notif-row__body{flex:1;min-width:0}
.notif-row__title{font-family:'Cormorant Garamond',serif;font-size:1.2rem;font-weight:600;color:var(--navy);
  margin-bottom:6px;display:flex;align-items:center;gap:9px}
.notif-dot{width:8px;height:8px;border-radius:50%;background:var(--gold);flex-shrink:0}
.notif-row__meta{font-family:var(--sans);font-size:.72rem;letter-spacing:.06em;text-transform:uppercase;
  color:var(--ink-3);margin-bottom:8px}
.notif-row__desc{font-family:var(--sans);font-size:.86rem;color:var(--ink-2);line-height:1.6;margin:0}
.notif-row__actions{display:flex;flex-direction:column;align-items:flex-end;gap:12px;flex-shrink:0;white-space:nowrap}
.notif-row__open{font-family:var(--sans);font-size:.72rem;font-weight:600;letter-spacing:.06em;
  text-transform:uppercase;color:var(--navy);white-space:nowrap;display:inline-flex;align-items:center;gap:8px}
.notif-row__open:hover{color:var(--gold)}
.notif-row__marcar{font-family:var(--sans);font-size:.7rem;color:var(--ink-3);display:inline-flex;
  align-items:center;gap:6px;transition:color .25s var(--e2)}
.notif-row__marcar:hover{color:var(--gold)}

@media(max-width:600px){
  .notif-row{flex-direction:column}
  .notif-row__actions{flex-direction:row;align-items:center;justify-content:space-between;width:100%;white-space:normal}
}
</style>

<?php include 'template/footer.php'; ?>

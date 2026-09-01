<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') {
    header("Location: admin.php");
    exit();
}

require_once 'data/solicitudes.php';
require_once 'data/perfilesFiscales.php';
require_once 'data/facturas.php';

$error_form = '';
$exito_form = '';

// Suscripcion facturable
function obtener_mi_suscripcion_facturable($correo, $todas_solicitudes) {
    $mias = array_filter($todas_solicitudes, function ($s) use ($correo) {
        return $s['estado'] === 'Aprobada' && $s['correo'] === $correo;
    });

    if (empty($mias)) {
        return null;
    }

    usort($mias, function ($a, $b) {
        return strtotime($b['fechaInicio']) <=> strtotime($a['fechaInicio']);
    });

    return $mias[0];
}

$todas_solicitudes = get_solicitudes();
$mi_suscripcion = obtener_mi_suscripcion_facturable($_SESSION['correo'] ?? '', $todas_solicitudes);
$perfiles = get_perfiles_fiscales();

$ya_enviada = $mi_suscripcion ? factura_ya_solicitada($mi_suscripcion['id']) : false;
$plazo = $mi_suscripcion ? dias_restantes_para_facturar($mi_suscripcion['fechaInicio']) : null;

//Procesar envio
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $perfil_id = trim($_POST['perfil_id'] ?? '');
    $perfil    = $perfil_id !== '' ? get_perfil_fiscal($perfil_id) : null;

    if (!$mi_suscripcion) {
        $error_form = "No tienes una suscripción con pago aprobado disponible para facturar.";
    } elseif (!$perfil) {
        $error_form = "Selecciona un perfil fiscal válido. Si no tienes ninguno registrado, primero crea uno.";
    } elseif ($ya_enviada) {
        $error_form = "Ya enviaste la solicitud de factura de tu suscripción actual. Solo se permite una por pago.";
    } elseif ($plazo['vencido']) {
        $error_form = "El plazo para solicitar la factura de tu suscripción ya venció (debe hacerse dentro del mismo mes en que se aprobó).";
    } else {
        add_solicitud_factura([
            'solicitud_id'   => $mi_suscripcion['id'],
            'perfil_id'      => $perfil_id,
            'perfil_rfc'     => $perfil['rfc'],
            'perfil_nombre'  => $perfil['razon_social'],
            'suscriptor'     => $mi_suscripcion['nombre'],
            'correo'         => $mi_suscripcion['correo'],
        ]);

        add_notificacion(
            $mi_suscripcion['id'],
            "Se envió una solicitud de factura para la suscripción de {$mi_suscripcion['nombre']} (RFC {$perfil['rfc']}).",
            'info'
        );

        header("Location: solicitarFactura.php?enviado=1");
        exit();
    }
}

if (isset($_GET['enviado'])) {
    $exito_form = "Tu perfil fiscal fue enviado al área de facturación. La factura se generará y enviará por fuera del sistema.";
}

$page_title = "Solicitar Factura";
$page = "solicitar_factura";
include 'template/header.php';
?>

<link rel="stylesheet" href="css/suscripciones.css">

<section class="hero-static">
  <div class="cs">
    <div class="hero-static__grid">
      <div class="hero-static__content reveal reveal--left in">
        <span class="c-ph__tag">Facturación</span>
        <h1 class="hero-static__title">Solicitar Factura</h1>
        <div class="gold-line gold-l"></div>
        <p class="hero-static__excerpt reveal reveal--left">
          Envía tu perfil fiscal al área de facturación para que se genere tu factura.
          Solo puedes solicitarla una vez por pago, dentro del mismo mes en que se aprobó.
        </p>
      </div>
      <div class="hero-static__visual reveal reveal--right in">
        <div class="hero-static__img-box">
          <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="1.5">
            <path d="M9 14l2 2 4-4M7 4h10a2 2 0 0 1 2 2v13l-3-2-3 2-3-2-3 2V6a2 2 0 0 1 2-2z" />
          </svg>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="about" style="padding: 10px 0 60px;">
  <div class="cs">

    <?php if ($exito_form): ?>
      <div class="registro-success" style="margin-bottom: 20px;">
        <?php echo htmlspecialchars($exito_form); ?>
      </div>
    <?php endif; ?>

    <?php if ($error_form): ?>
      <div class="registro-error" style="margin-bottom: 20px;">
        <?php echo htmlspecialchars($error_form); ?>
      </div>
    <?php endif; ?>

    <?php if (empty($perfiles)): ?>

      <div class="detail-card" style="text-align: center; padding: 40px;">
        <p style="margin-bottom: 20px;">
          Aún no tienes un perfil fiscal registrado. Necesitas al menos uno para poder solicitar tu factura.
        </p>
        <a href="perfilesFiscales.php?accion=nuevo" class="btn-gold-fill">
          <span>Registrar perfil fiscal</span>
        </a>
      </div>

    <?php elseif (!$mi_suscripcion): ?>

      <div class="detail-card" style="text-align: center; padding: 40px;">
        <p>No tienes una suscripción con pago aprobado disponible para facturar en este momento.</p>
      </div>

    <?php else: ?>

      <div class="detail-card" style="max-width: 700px; margin: 0 auto 25px;">
        <h3 style="font-size: 1.05rem; margin-bottom: 15px;">Tu suscripción a facturar</h3>
        <div style="display:flex; justify-content:space-between; align-items:center; padding:10px 15px; background:rgba(0,0,0,0.03); border-radius:6px;">
          <span>
            <?php echo htmlspecialchars($mi_suscripcion['nombre']); ?> — $<?php echo number_format($mi_suscripcion['monto'], 2); ?>
            <br><small style="color: var(--text-soft);">Aprobada el <?php echo htmlspecialchars($mi_suscripcion['fechaInicio']); ?></small>
          </span>
          <?php if ($ya_enviada): ?>
            <span class="status-badge status-approved">Ya enviada</span>
          <?php elseif ($plazo['vencido']): ?>
            <span class="status-badge status-rejected">Plazo vencido</span>
          <?php elseif ($plazo['dias'] <= 3): ?>
            <span class="status-badge status-rejected">Quedan <?php echo $plazo['dias']; ?> día(s)</span>
          <?php else: ?>
            <span class="status-badge status-approved">Quedan <?php echo $plazo['dias']; ?> día(s)</span>
          <?php endif; ?>
        </div>
        <small style="display:block; margin-top:12px; color: var(--text-soft);">
          Solo puedes solicitar una factura por esta suscripción, y debe ser dentro del mismo mes en que se aprobó el pago.
        </small>
      </div>

      <?php if ($ya_enviada): ?>

        <div class="detail-card" style="max-width: 700px; margin: 0 auto; text-align: center; padding: 30px;">
          <p>Ya enviaste la solicitud de factura de esta suscripción. Cuando renueves el próximo año, podrás solicitar una nueva.</p>
        </div>

      <?php elseif ($plazo['vencido']): ?>

        <div class="detail-card" style="max-width: 700px; margin: 0 auto; text-align: center; padding: 30px;">
          <p>El plazo del mes para solicitar esta factura ya venció.</p>
        </div>

      <?php else: ?>

        <div class="login-card reveal reveal--left" style="max-width: 700px; margin: 0 auto;">
          <div class="login-card__header">
            <span class="lbl">Nueva solicitud</span>
            <h2 class="login-card__title">Enviar a facturación</h2>
            <div class="gold-line"></div>
          </div>

          <form action="solicitarFactura.php" method="post" class="login-form">

            <div class="form-group">
              <label for="perfil_id" class="form-label">Perfil fiscal a usar</label>
              <select id="perfil_id" name="perfil_id" class="form-control-custom" required>
                <option value="" disabled selected>Selecciona una opción</option>
                <?php foreach ($perfiles as $p): ?>
                  <option value="<?php echo htmlspecialchars($p['id']); ?>">
                    <?php echo htmlspecialchars($p['rfc']); ?> — <?php echo htmlspecialchars($p['razon_social']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <small style="display:block; margin-top:6px; color: var(--text-soft);">
                ¿No es el perfil que buscas? <a href="perfilesFiscales.php" style="text-decoration: underline;">Administra tus perfiles fiscales</a>.
              </small>
            </div>

            <div class="login-actions" style="margin-top: 10px;">
              <button type="submit" class="btn-gold-fill">
                <span>Enviar a facturación</span>
              </button>
            </div>

          </form>
        </div>

      <?php endif; ?>

    <?php endif; ?>

    <?php
      $enviadas = array_filter(get_solicitudes_factura(), function ($sf) {
          return $sf['correo'] === ($_SESSION['correo'] ?? '');
      });
    ?>
    <?php if (!empty($enviadas)): ?>
      <div class="detail-card" style="max-width: 700px; margin: 30px auto 0;">
        <h3 style="font-size: 1.1rem; margin-bottom: 15px;">Mis solicitudes enviadas</h3>
        <div class="admin-table-container" style="overflow-x: auto;">
          <table class="admin-table">
            <thead>
              <tr>
                <th>RFC</th>
                <th>Suscripción</th>
                <th>Fecha de envío</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($enviadas as $sf): ?>
                <tr>
                  <td><?php echo htmlspecialchars($sf['perfil_rfc']); ?></td>
                  <td><?php echo htmlspecialchars($sf['suscriptor']); ?></td>
                  <td><?php echo htmlspecialchars($sf['fecha_envio']); ?></td>
                  <td><span class="status-badge status-review"><?php echo htmlspecialchars($sf['estado']); ?></span></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php endif; ?>

  </div>
</section>

<?php include 'template/footer.php'; ?>
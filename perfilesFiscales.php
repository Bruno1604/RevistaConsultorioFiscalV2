<?php
session_start();

// Precondición del CU-18: el usuario debe haber iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Esta sección es exclusiva para suscriptores (el administrador no la usa)
if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') {
    header("Location: admin.php");
    exit();
}

require_once 'data/perfilesFiscales.php';

$error_form = '';
$exito_form = '';

// -----------------------------------------------------
// ELIMINAR PERFIL (A3 del CU-18)
// -----------------------------------------------------
if (isset($_GET['eliminar'])) {
    $id_eliminar = $_GET['eliminar'];

    // En el futuro: validar que el perfil no esté en una
    // solicitud de factura pendiente antes de eliminar (CU-18 A3).
    delete_perfil_fiscal($id_eliminar);

    header("Location: perfilesFiscales.php");
    exit();
}

// -----------------------------------------------------
// DETERMINAR MODO: lista / nuevo / editar
// -----------------------------------------------------
$modo = 'lista';
$perfil_editar = null;

if (isset($_GET['accion']) && $_GET['accion'] === 'nuevo') {
    $modo = 'form';
} elseif (isset($_GET['editar'])) {
    $perfil_editar = get_perfil_fiscal($_GET['editar']);
    if ($perfil_editar) {
        $modo = 'form';
    }
}

// -----------------------------------------------------
// PROCESAR ENVÍO DEL FORMULARIO (alta o edición)
// -----------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $tipo_persona   = trim($_POST['tipo_persona'] ?? '');
    $rfc            = strtoupper(trim($_POST['rfc'] ?? ''));
    $razon_social   = trim($_POST['razon_social'] ?? '');
    $regimen_fiscal = trim($_POST['regimen_fiscal'] ?? '');
    $codigo_postal  = trim($_POST['codigo_postal'] ?? '');
    $uso_cfdi       = trim($_POST['uso_cfdi'] ?? '');
    $correo         = trim($_POST['correo'] ?? '');
    $id_editar      = trim($_POST['id_perfil'] ?? '');

    // A1. Información obligatoria incompleta
    if ($tipo_persona === '' || $rfc === '' || $razon_social === '' ||
        $regimen_fiscal === '' || $codigo_postal === '' || $uso_cfdi === '' || $correo === '') {
        $error_form = "Por favor, completa todos los campos obligatorios.";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error_form = "Ingresa un correo electrónico válido.";
    } elseif (!preg_match('/^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/', $rfc)) {
        $error_form = "El RFC no tiene un formato válido (ej. PEJJ850312AB1).";
    } elseif (!preg_match('/^[0-9]{5}$/', $codigo_postal)) {
        $error_form = "El código postal debe tener 5 dígitos.";
    } else {

        $data = [
            'tipo_persona'   => $tipo_persona,
            'rfc'            => $rfc,
            'razon_social'   => $razon_social,
            'regimen_fiscal' => $regimen_fiscal,
            'codigo_postal'  => $codigo_postal,
            'uso_cfdi'       => $uso_cfdi,
            'correo'         => $correo,
        ];

        if ($id_editar !== '' && get_perfil_fiscal($id_editar)) {
            update_perfil_fiscal($id_editar, $data);
            $exito_form = "Perfil fiscal actualizado correctamente.";
        } else {
            add_perfil_fiscal($data);
            $exito_form = "Perfil fiscal registrado correctamente.";
        }

        // Regresamos al listado tras guardar con éxito
        header("Location: perfilesFiscales.php?guardado=1");
        exit();
    }

    // Si hubo error, mantenemos los datos capturados en el formulario
    $perfil_editar = [
        'id'             => $id_editar,
        'tipo_persona'   => $tipo_persona,
        'rfc'            => $rfc,
        'razon_social'   => $razon_social,
        'regimen_fiscal' => $regimen_fiscal,
        'codigo_postal'  => $codigo_postal,
        'uso_cfdi'       => $uso_cfdi,
        'correo'         => $correo,
    ];
    $modo = 'form';
}

if (isset($_GET['guardado'])) {
    $exito_form = "Perfil fiscal guardado correctamente.";
}

$perfiles = get_perfiles_fiscales();

$page_title = "Mis Perfiles Fiscales";
$page = "perfiles_fiscales";
include 'template/header.php';
?>

<link rel="stylesheet" href="css/suscripciones.css">

<section class="hero-static">
  <div class="cs">
    <div class="hero-static__grid">
      <div class="hero-static__content reveal reveal--left in">
        <span class="c-ph__tag">Facturación</span>
        <h1 class="hero-static__title">Mis Perfiles Fiscales</h1>
        <div class="gold-line gold-l"></div>
        <p class="hero-static__excerpt reveal reveal--left">
          Registra los datos fiscales que usarás para solicitar tus facturas.
          Puedes tener uno o más perfiles (por ejemplo, personal y de tu empresa).
        </p>
      </div>
      <div class="hero-static__visual reveal reveal--right in">
        <div class="hero-static__img-box">
          <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="1.5">
            <path d="M4 4h16v16H4zM8 8h8M8 12h8M8 16h5" />
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

    <?php if ($modo === 'lista'): ?>

      <!-- ================= LISTADO ================= -->
      <div style="display: flex; justify-content: flex-end; margin-bottom: 18px;">
        <a href="perfilesFiscales.php?accion=nuevo" class="btn-gold-fill">
          <span>+ Agregar perfil fiscal</span>
        </a>
      </div>

      <div class="detail-card">
        <div class="admin-table-container" style="overflow-x: auto;">
          <table class="admin-table">
            <thead>
              <tr>
                <th>RFC</th>
                <th>Razón social / Nombre</th>
                <th>Tipo</th>
                <th>Régimen fiscal</th>
                <th>Código postal</th>
                <th>Uso CFDI</th>
                <th>Correo</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php if (count($perfiles) > 0): ?>
                <?php foreach ($perfiles as $p): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($p['rfc']); ?></td>
                    <td><?php echo htmlspecialchars($p['razon_social']); ?></td>
                    <td>Persona <?php echo htmlspecialchars($p['tipo_persona']); ?></td>
                    <td><?php echo htmlspecialchars($p['regimen_fiscal']); ?></td>
                    <td><?php echo htmlspecialchars($p['codigo_postal']); ?></td>
                    <td><?php echo htmlspecialchars($p['uso_cfdi']); ?></td>
                    <td><?php echo htmlspecialchars($p['correo']); ?></td>
                    <td>
                      <div class="d-flex gap-2">
                        <a href="perfilesFiscales.php?editar=<?php echo urlencode($p['id']); ?>"
                           style="text-decoration: underline;">
                           Editar
                        </a>
                        <a href="perfilesFiscales.php?eliminar=<?php echo urlencode($p['id']); ?>"
                           style="text-decoration: underline; color: var(--status-rechazada-text, #c0392b);"
                           onclick="return confirm('¿Eliminar este perfil fiscal?');">
                           Eliminar
                        </a>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="8" style="text-align: center; padding: 30px; color: var(--text-soft);">
                    Aún no tienes perfiles fiscales registrados.
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

    <?php else: ?>

      <!-- ================= FORMULARIO (alta / edición) ================= -->
      <div class="login-card reveal reveal--left" style="max-width: 700px; margin: 0 auto;">

        <div class="login-card__header">
          <span class="lbl">
            <?php echo ($perfil_editar && !empty($perfil_editar['id'])) ? 'Editar perfil' : 'Nuevo perfil'; ?>
          </span>
          <h2 class="login-card__title">Datos fiscales</h2>
          <div class="gold-line"></div>
        </div>

        <?php if ($error_form): ?>
          <div class="registro-error">
            <?php echo htmlspecialchars($error_form); ?>
          </div>
        <?php endif; ?>

        <form action="perfilesFiscales.php" method="post" class="login-form">

          <input type="hidden" name="id_perfil" value="<?php echo htmlspecialchars($perfil_editar['id'] ?? ''); ?>">

          <!-- TIPO DE PERSONA -->
          <div class="form-group">
            <label for="tipo_persona" class="form-label">Tipo de persona</label>
            <select id="tipo_persona" name="tipo_persona" class="form-control-custom" required>
              <option value="" disabled <?php echo empty($perfil_editar['tipo_persona']) ? 'selected' : ''; ?>>
                Selecciona una opción
              </option>
              <option value="Física" <?php echo (($perfil_editar['tipo_persona'] ?? '') === 'Física') ? 'selected' : ''; ?>>
                Persona física
              </option>
              <option value="Moral" <?php echo (($perfil_editar['tipo_persona'] ?? '') === 'Moral') ? 'selected' : ''; ?>>
                Persona moral
              </option>
            </select>
          </div>

          <!-- RFC -->
          <div class="form-group">
            <label for="rfc" class="form-label">RFC</label>
            <input
              type="text"
              name="rfc"
              id="rfc"
              class="form-control-custom"
              placeholder="Ej. PEJJ850312AB1"
              maxlength="13"
              style="text-transform: uppercase;"
              value="<?php echo htmlspecialchars($perfil_editar['rfc'] ?? ''); ?>"
              required
            />
          </div>

          <!-- RAZÓN SOCIAL / NOMBRE -->
          <div class="form-group">
            <label for="razon_social" class="form-label">Razón social / Nombre completo</label>
            <input
              type="text"
              name="razon_social"
              id="razon_social"
              class="form-control-custom"
              placeholder="Como aparece en tu constancia de situación fiscal"
              value="<?php echo htmlspecialchars($perfil_editar['razon_social'] ?? ''); ?>"
              required
            />
          </div>

          <!-- RÉGIMEN FISCAL -->
          <div class="form-group">
            <label for="regimen_fiscal" class="form-label">Régimen fiscal</label>
            <select id="regimen_fiscal" name="regimen_fiscal" class="form-control-custom" required>
              <?php
                $regimenes = [
                    'Sueldos y Salarios',
                    'Régimen Simplificado de Confianza (RESICO)',
                    'Actividades Empresariales y Profesionales',
                    'Régimen General de Ley Personas Morales',
                    'Arrendamiento',
                ];
                $actual = $perfil_editar['regimen_fiscal'] ?? '';
              ?>
              <option value="" disabled <?php echo $actual === '' ? 'selected' : ''; ?>>Selecciona una opción</option>
              <?php foreach ($regimenes as $r): ?>
                <option value="<?php echo htmlspecialchars($r); ?>" <?php echo ($actual === $r) ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($r); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- CÓDIGO POSTAL -->
          <div class="form-group">
            <label for="codigo_postal" class="form-label">Código postal (del domicilio fiscal)</label>
            <input
              type="text"
              name="codigo_postal"
              id="codigo_postal"
              class="form-control-custom"
              placeholder="Ej. 04510"
              maxlength="5"
              pattern="[0-9]{5}"
              value="<?php echo htmlspecialchars($perfil_editar['codigo_postal'] ?? ''); ?>"
              required
            />
          </div>

          <!-- USO DE CFDI -->
          <div class="form-group">
            <label for="uso_cfdi" class="form-label">Uso de CFDI</label>
            <select id="uso_cfdi" name="uso_cfdi" class="form-control-custom" required>
              <?php
                $usos = [
                    'G03 - Gastos en general',
                    'G01 - Adquisición de mercancías',
                    'P01 - Por definir',
                    'D01 - Honorarios médicos',
                ];
                $actualUso = $perfil_editar['uso_cfdi'] ?? '';
              ?>
              <option value="" disabled <?php echo $actualUso === '' ? 'selected' : ''; ?>>Selecciona una opción</option>
              <?php foreach ($usos as $u): ?>
                <option value="<?php echo htmlspecialchars($u); ?>" <?php echo ($actualUso === $u) ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($u); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- CORREO PARA FACTURA -->
          <div class="form-group">
            <label for="correo" class="form-label">Correo para envío de factura</label>
            <input
              type="email"
              name="correo"
              id="correo"
              class="form-control-custom"
              placeholder="ejemplo@correo.com"
              value="<?php echo htmlspecialchars($perfil_editar['correo'] ?? ''); ?>"
              required
            />
          </div>

          <div class="login-actions" style="margin-top: 10px; display: flex; gap: 15px; align-items: center;">
            <button type="submit" class="btn-gold-fill">
              <span>Guardar perfil fiscal</span>
            </button>
            <a href="perfilesFiscales.php" style="text-decoration: underline; font-size: 0.85rem;">
              Cancelar
            </a>
          </div>

        </form>
      </div>

    <?php endif; ?>

  </div>
</section>

<?php include 'template/footer.php'; ?>
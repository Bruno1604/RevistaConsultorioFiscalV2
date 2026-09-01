<?php
session_start();

// Verificar que el usuario sea administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$page_title = "Solicitudes - Consultorio Fiscal";
$page = "admin_suscripciones";
include 'template/header.php';

// Cargar base de datos simulada
require_once 'data/solicitudes.php';
$solicitudes = get_solicitudes();

// Calcular estadísticas globales
$cnt_total = count($solicitudes);
$cnt_asignada = 0;
$cnt_revision = 0;
$cnt_rechazada = 0;
$cnt_expirada = 0;

foreach ($solicitudes as $s) {
    if ($s['estado'] === 'Ficha asignada') $cnt_asignada++;
    if ($s['estado'] === 'Comprobante cargado') $cnt_revision++;
    if ($s['estado'] === 'Comprobante rechazado') $cnt_rechazada++;
    if ($s['estado'] === 'Expirada') $cnt_expirada++;
}

// Obtener parámetro de filtro
$filtro_estado = isset($_GET['estado']) ? trim($_GET['estado']) : '';

/*
$filtro_afiliacion = isset($_GET['afiliacion']) ? trim($_GET['afiliacion']) : '';
$filtro_cuenta = isset($_GET['cuenta']) ? trim($_GET['cuenta']) : '';
$filtro_buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';


// Aplicar filtros adicionales

if ($filtro_afiliacion !== '' && $s['afiliacion'] !== $filtro_afiliacion) {
    continue;
}

if ($filtro_cuenta !== '' && stripos($s['cuenta'], $filtro_cuenta) === false) {
    continue;
}

if ($filtro_buscar !== '') {
    $term = mb_strtolower($filtro_buscar);

    $match = (stripos($s['id'], $term) !== false) ||
             (stripos($s['nombre'], $term) !== false) ||
             (stripos($s['correo'], $term) !== false) ||
             (stripos($s['cuenta'], $term) !== false);

    if (!$match) {
        continue;
    }
}
*/

// Aplicar únicamente el filtro de estado

$solicitudes_filtradas = [];

foreach ($solicitudes as $s) {
    if ($filtro_estado !== '' && $s['estado'] !== $filtro_estado) {
        continue;
    }

    $solicitudes_filtradas[] = $s;
}

// ===============================
// PAGINACIÓN
// ===============================

$porPagina = 5;

$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

if ($paginaActual < 1) {
    $paginaActual = 1;
}

$totalRegistros = count($solicitudes_filtradas);

$totalPaginas = ceil($totalRegistros / $porPagina);

if ($paginaActual > $totalPaginas && $totalPaginas > 0) {
    $paginaActual = $totalPaginas;
}

$inicio = ($paginaActual - 1) * $porPagina;

$solicitudesPagina = array_slice(
    $solicitudes_filtradas,
    $inicio,
    $porPagina
);

// Retornar clase del badge según estado
function obtener_clase_estado($estado) {
    switch ($estado) {
        case 'Ficha asignada': return 'status-assigned';
        case 'Comprobante cargado': return 'status-review';
        case 'Comprobante rechazado': return 'status-rejected';
        case 'Aprobada': return 'status-approved';
        case 'Expirada': return 'status-expired';
        default: return 'otro';
    }
}
?>

<!-- Hero de administración de suscriptores -->
<section class="hero-static" style="padding: 60px 0 40px;">
  <div class="cs">
    <div class="hero-static__grid">
      
      <div class="hero-static__content">
        <span class="c-ph__tag">Administración</span>

        <h1 class="hero-static__title">Gestión de Solicitudes</h1>

        <div class="gold-line gold-l"></div>

        <p class="hero-static__excerpt">
          Administra las solicitudes de suscripción y revisa los comprobantes de pago.
        </p>
      </div>

      <div class="hero-static__visual">
        <svg width="80" height="80" viewBox="0 0 24 24" fill="none"
             stroke="var(--gold)" stroke-width="1.5">

          <!-- Usuario -->
          <circle cx="12" cy="8" r="3"></circle>
          <path d="M5 20c0-3.5 3-6 7-6s7 2.5 7 6"></path>

          <!-- Documento / gestión -->
          <path d="M17 3h4v4"></path>
          <path d="M21 3l-5 5"></path>

        </svg>
      </div>

    </div>
  </div>
</section>

<!-- Cargar estilos específicos -->
<link rel="stylesheet" href="css/suscripciones.css">

<!-- Contenido principal -->
<section class="about" style="padding: 20px 0 60px;">
  <div class="cs-wide">
    <div class="admin-layout-grid">
      
      <!-- Menú lateral -->
      <?php include 'includes/sidebar.php'; ?>
      
      <!-- Listado de Solicitudes -->
      <div class="admin-content">

        <!-- Tarjeta de Filtros -->
        <div class="filters-card">
          <form method="get" action="solicitudes.php" class="row g-3">

              <div class="col-md-4">
                  <label for="estado" class="lbl" style="margin-bottom: 5px; font-size: 0.6rem;">
                      Filtrar Estado
                  </label>

                  <select name="estado" id="estado" class="form-control-custom">

                      <option value="">-- Todos los estados --</option>

                      <option value="Ficha asignada"
                          <?php echo ($filtro_estado === 'Ficha asignada') ? 'selected' : ''; ?>>
                          Ficha asignada
                      </option>

                      <option value="Solicitud aprobada"
                          <?php echo ($filtro_estado === 'Solicitud aprobada') ? 'selected' : ''; ?>>
                          Solicitud aprobada
                      </option>

                      <option value="Solicitud rechazada"
                          <?php echo ($filtro_estado === 'Solicitud rechazada') ? 'selected' : ''; ?>>
                          Solicitud rechazada
                      </option>

                      <option value="Por revisar Credencial"
                          <?php echo ($filtro_estado === 'Por revisar Credencial') ? 'selected' : ''; ?>>
                          Por revisar Credencial
                      </option>

                      <option value="Por revisar Comprobante"
                          <?php echo ($filtro_estado === 'Por revisar Comprobante') ? 'selected' : ''; ?>>
                          Por revisar Comprobante
                      </option>

                      <option value="Ficha expirada"
                          <?php echo ($filtro_estado === 'Ficha expirada') ? 'selected' : ''; ?>>
                          Ficha expirada
                      </option>
                  </select>
              </div>

              <div class="col-md-4 d-flex align-items-end">
                  <button type="submit" class="btn-filter-navy-small">
                      <i class="fa-solid fa-filter"></i>
                  </button>
              </div>
          </form>
        </div>

        <!-- Tabla de solicitudes -->
        <div class="detail-card">
          <div class="admin-table-container" style="overflow-x: auto;">
            <table class="admin-table">
              <thead>
            <tr>
                <th>Fecha</th>
                <th>Usuario</th>
                <th>Estado</th>
                <th>Costos</th>
                <th>Docs</th>
            </tr>
        </thead>
              <tbody>
                <?php if (count($solicitudes_filtradas) > 0): ?>
                  <?php foreach ($solicitudesPagina as $sol): ?>
                    <tr>  
                      <!-- Fecha -->
                      <td><?php echo date('d/m/Y', strtotime($sol['fechaSolicitud'])); ?></td>
                      
                      <!-- Usuario -->
                      <td>
                        <div style="font-weight: 500; color: var(--text);">
                            <?php echo htmlspecialchars($sol['nombre']); ?>
                        </div>
                        <div style="font-size: 0.7rem; font-style: italic; color: var(--accent-gold);">
                            <?php echo htmlspecialchars($sol['afiliacion']); ?>
                        </div>
                      </td>

                      <!-- Estado -->
                      <td>
                        <span class="status-badge <?php echo obtener_clase_estado($sol['estado']); ?>">
                          <?php echo $sol['estado']; ?>
                        </span>
                      </td>
                      
                      <!-- Costos -->
                      <td>
                        <div style="font-size: 0.78rem;">Costo: $<?php echo number_format($sol['tarifa'], 2); ?></div>
                        <div style="font-size: 0.72rem; color: var(--status-rechazada-text);">Desc: -$<?php echo number_format($sol['descuento'], 2); ?></div>
                        <div style="font-weight: 600; font-size: 0.8rem; color: var(--status-aprobada-text);">Monto: $<?php echo number_format($sol['monto'], 2); ?></div>
                      </td>

                      <!-- Documentos -->
                      <td>
                        <div class="d-flex flex-column gap-1" style="font-size: 0.7rem;">
                          <span>
                            Credencial: 
                            <a href="docs/2cr_credencial.pdf" target="_blank" class="text-primary" style="text-decoration: underline;">Ver</a>
                          </span>
                          
                          <?php if ($sol['ficha']): ?>
                            <span>
                              Ficha de Pago: 
                              <a href="docs/ficha_pago_demo.pdf" target="_blank" class="text-primary" style="text-decoration: underline;">Ver</a>
                            </span>
                          <?php else: ?>
                            <span class="text-soft">Ficha de Pago: N/A</span>
                          <?php endif; ?>

                          <?php if ($sol['comprobante']): ?>
                            <span>
                              Comprobante de Pago:
                              <a href="docs/comprobante_demo.pdf" target="_blank" class="text-success" style="text-decoration: underline; font-weight: 500;">Ver</a>
                            </span>
                          <?php else: ?>
                            <span class="text-soft">Comprobante de Pago: No cargado</span>
                          <?php endif; ?>
                        </div>
                      </td>    
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="8" style="text-align: center; padding: 30px; color: var(--text-soft);">
                      No se encontraron solicitudes con los filtros aplicados.
                    </td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
            <!-- PAGINADOR -->
            <?php if ($totalRegistros > $porPagina): ?>

                <div class="paginador">

                    <?php if ($paginaActual > 1): ?>
                        <a href="?pagina=<?php echo $paginaActual - 1; ?><?php echo ($filtro_estado !== '') ? '&estado=' . urlencode($filtro_estado) : ''; ?>"
                          class="pagina-btn">
                            &laquo; Anterior
                        </a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>

                        <a href="?pagina=<?php echo $i; ?><?php echo ($filtro_estado !== '') ? '&estado=' . urlencode($filtro_estado) : ''; ?>"
                          class="pagina-btn <?php echo ($i == $paginaActual) ? 'pagina-activa' : ''; ?>">
                            <?php echo $i; ?>
                        </a>

                    <?php endfor; ?>

                    <?php if ($paginaActual < $totalPaginas): ?>
                        <a href="?pagina=<?php echo $paginaActual + 1; ?><?php echo ($filtro_estado !== '') ? '&estado=' . urlencode($filtro_estado) : ''; ?>"
                          class="pagina-btn">
                            Siguiente &raquo;
                        </a>
                    <?php endif; ?>

                </div>

            <?php endif; ?>
          </div>
        </div>

      </div>
      
    </div>
  </div>
</section>

<?php include 'template/footer.php'; ?>

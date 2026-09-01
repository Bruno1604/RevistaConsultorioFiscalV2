<?php
session_start();

// Verificar que el usuario sea administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$page_title = "Historial y Notificaciones - Consultorio Fiscal";
$page = "admin_suscripciones";
include 'template/header.php';

// Cargar base de datos simulada
require_once 'data/solicitudes.php';
$notificaciones = get_notificaciones();
?>

<!-- Cargar estilos específicos -->
<link rel="stylesheet" href="css/suscripciones.css">

<!-- Contenido principal -->
<section class="about" style="padding: 20px 0 60px;">
  <div class="cs">
    <div class="admin-layout-grid">
      
      <!-- Menú lateral -->
      <?php include 'includes/sidebar.php'; ?>
      
      <!-- Contenido de Historial -->
      <div class="admin-content">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
          <div>
            <h2 style="font-size: 1.8rem; margin: 0;">Historial de notificaciones</h2>
          </div>
        </div>

        <div class="gold-line gold-l" style="margin-bottom: 30px;"></div>

          <?php if (count($notificaciones) > 0): ?>
            <div class="d-flex flex-column gap-3">
              <?php foreach ($notificaciones as $n): ?>
                <?php
                $alert_class = 'alert-info';
                if ($n['tipo'] === 'success') $alert_class = 'alert-success';
                if ($n['tipo'] === 'danger') $alert_class = 'alert-danger';
                if ($n['tipo'] === 'warning') $alert_class = 'alert-warning';
                ?>
                <div class="notification-banner <?php echo $alert_class; ?>" style="margin-bottom: 0; padding: 15px 20px;">
                  <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div style="font-size: 0.85rem; line-height: 1.5; max-width: 80%;">
                      <strong>[<?php echo htmlspecialchars($n['solicitud_id']); ?>]</strong> 
                      <?php echo htmlspecialchars($n['mensaje']); ?>
                    </div>
                    <div style="font-size: 0.72rem; color: var(--text-soft); font-family: monospace;">
                      <i class="fa-solid fa-clock"></i> <?php echo date('d/m/Y H:i:s', strtotime($n['fecha'])); ?>
                    </div>
                  </div>
                  <div style="font-size: 0.7rem; margin-top: 8px; border-top: 1px solid rgba(0,0,0,0.05); padding-top: 6px;">
                    <a href="solicitud_detalle.php?id=<?php echo urlencode($n['solicitud_id']); ?>" class="text-navy" style="text-decoration: underline; font-weight: 500;">
                      Ver expediente asociado &rarr;
                    </a>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div style="text-align: center; padding: 40px 20px; background: var(--bg-warm); border-radius: 4px; border: 1px solid rgba(184, 150, 85, 0.15);">
              <i class="fa-solid fa-envelope-open" style="font-size: 2.5rem; color: var(--text-soft); margin-bottom: 15px; display: block;"></i>
              <h4 style="font-family: var(--sans); font-size: 1.1rem; color: var(--accent-navy);">Sin notificaciones</h4>
              <p style="margin: 0; font-size: 0.85rem; color: var(--text-soft);">No se registran eventos en la bitácora histórica.</p>
            </div>
          <?php endif; ?>
        </div>

      </div>
      
    </div>
  </div>
</section>

<?php include 'template/footer.php'; ?>

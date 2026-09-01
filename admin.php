<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$page_title = "Panel de Administración - Consultorio Fiscal";
$page = "admin";
include 'template/header.php';
?>

<!-- Contenido del panel admin -->
<section class="hero-static" style="padding: 60px 0 40px;">
  <div class="cs">
    <div class="hero-static__grid">
      <div class="hero-static__content">
        <span class="c-ph__tag">Panel de Administración</span>
        <h1 class="hero-static__title">Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?></h1>
        <div class="gold-line gold-l"></div>
        <p class="hero-static__excerpt">
          Aquí puedes gestionar el contenido de la revista, revisar credenciales y administrar suscripciones.
        </p>
      </div>
      <div class="hero-static__visual">
        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="1.5">
          <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
        </svg>
      </div>
    </div>
  </div>
</section>

<section class="about" style="padding: 20px 0 60px;">
  <div class="cs">
    <div class="row g-4 justify-content-center">
      <!-- Opción 1: Gestionar Revista -->
      <div class="col-md-3 col-sm-6">
        <a href="admin_revistas.php" class="broadcast-card" style="padding: 30px 20px; text-align: center; text-decoration: none; display: block; height: 100%;">
          <div class="broadcast-card__head" style="justify-content: center; margin-bottom: 15px;">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--navy)" stroke-width="1.5">
              <path d="M4 4h16v16H4zM8 8h8M8 12h6M8 16h4"/>
            </svg>
          </div>
          <h3 class="broadcast-card__channel" style="font-size: 1.1rem;">Gestionar Revista</h3>
          <p class="broadcast-card__time" style="color: var(--text-soft); font-weight: 300; font-size: 0.8rem;">
            Agregar, editar o eliminar ejemplares.
          </p>
        </a>
      </div>

      <!-- Opción 2: Gestionar Suscripciones -->
      <div class="col-md-3 col-sm-6">
        <a href="solicitudes.php" class="broadcast-card" style="padding: 30px 20px; text-align: center; text-decoration: none; display: block; height: 100%;">
          <div class="broadcast-card__head" style="justify-content: center; margin-bottom: 15px;">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--navy)" stroke-width="1.5">
              <path d="M22 6.5L12 13 2 6.5M2 6.5L12 3l10 3.5M2 6.5v10.5L12 20l10-3.5V6.5" />
              <path d="M12 13v7" />
            </svg>
          </div>
          <h3 class="broadcast-card__channel" style="font-size: 1.1rem;">Gestionar Suscripciones</h3>
          <p class="broadcast-card__time" style="color: var(--text-soft); font-weight: 300; font-size: 0.8rem;">
            Revisar solicitudes y administrar suscriptores.
          </p>
        </a>
      </div>

      <!-- Opción 3: Cargar Fichas de Pago -->
      <div class="col-md-3 col-sm-6">
        <a href="subirFichas.php" class="broadcast-card" style="padding: 30px 20px; text-align: center; text-decoration: none; display: block; height: 100%;">
          <div class="broadcast-card__head" style="justify-content: center; margin-bottom: 15px;">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--navy)" stroke-width="1.5">
              <path d="M12 16V4M8 8l4-4 4 4" />
              <path d="M4 16v4h16v-4" />
            </svg>
          </div>
          <h3 class="broadcast-card__channel" style="font-size: 1.1rem;">Cargar Fichas de Pago</h3>
          <p class="broadcast-card__time" style="color: var(--text-soft); font-weight: 300; font-size: 0.8rem;">
            Subir fichas de pago de suscriptores.
          </p>
        </a>
      </div>

      <!-- Opción 4: Generar Excel para facturas -->
      <div class="col-md-3 col-sm-6">
        <a href="generar_excel_facturas.php" class="broadcast-card" style="padding: 30px 20px; text-align: center; text-decoration: none; display: block; height: 100%;">
          <div class="broadcast-card__head" style="justify-content: center; margin-bottom: 15px;">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--navy)" stroke-width="1.5">
              <path d="M14 3v4a1 1 0 0 0 1 1h4" />
              <path d="M17 21H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2z" />
              <path d="M8 12l2 2 4-4" />
            </svg>
          </div>
          <h3 class="broadcast-card__channel" style="font-size: 1.1rem;">Generar Excel para Facturas</h3>
          <p class="broadcast-card__time" style="color: var(--text-soft); font-weight: 300; font-size: 0.8rem;">
            Exportar datos para facturación.
          </p>
        </a>
      </div>
    </div>
  </div>
</section>

<?php include 'template/footer.php'; ?>
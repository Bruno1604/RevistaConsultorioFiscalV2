<?php
session_start();

// Hace falta haber iniciado sesión para leer la edición completa
if (!isset($_SESSION['usuario_id'])) {
    header("Location: accesoRestringido.php");
    exit();
}

require_once 'data/revistas.php';

$numero = $_GET['numero'] ?? '';
$revista = $numero !== '' ? get_revista_por_numero($numero) : null;
$paginaInicial = isset($_GET['pagina']) ? max(1, (int) $_GET['pagina']) : 1;

// Nos aseguramos de que las "imágenes" de esta revista sean rutas web
// reales que existan en disco -- si alguien subió nombres sueltos (como
// "pag1.jpg" sin carpeta, de datos de ejemplo viejos) los ignoramos, para
// no mostrar imágenes rotas.
$imagenesValidas = [];
if ($revista && !empty($revista['imagenes'])) {
    foreach ($revista['imagenes'] as $img) {
        if (file_exists($img)) {
            $imagenesValidas[] = $img;
        }
    }
}

$page_title = $revista ? ('Edición ' . $revista['numero'] . ' - Consultorio Fiscal') : 'Visor - Consultorio Fiscal';
$page = "historico";
include 'template/header.php';
?>

<link rel="stylesheet" href="css/suscripciones.css">

<main class="preview-page">

  <?php if (!$revista): ?>

    <section class="about" style="padding: 80px 0;">
      <div class="cs">
        <div class="detail-card" style="text-align: center; padding: 40px;">
          <p>No se encontró esa edición.</p>
          <a href="index.php" class="btn-gold-fill" style="margin-top: 15px; display: inline-flex;"><span>Volver al inicio</span></a>
        </div>
      </div>
    </section>

  <?php elseif (empty($imagenesValidas)): ?>

    <section class="about" style="padding: 80px 0;">
      <div class="cs">
        <div class="detail-card" style="text-align: center; padding: 40px;">
          <p><strong><?php echo htmlspecialchars($revista['titulo']); ?></strong> (No. <?php echo htmlspecialchars($revista['numero']); ?>)</p>
          <p style="color: var(--text-soft); margin-top: 10px;">Esta edición todavía no tiene sus páginas disponibles en el visor. Vuelve a intentarlo más tarde.</p>
        </div>
      </div>
    </section>

  <?php else: ?>

    <section class="about" style="padding: 30px 0 60px;">
      <div class="cs">

        <div class="detail-card" style="padding: 15px 20px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
          <div>
            <h1 style="font-size: 1.2rem; margin: 0;"><?php echo htmlspecialchars($revista['titulo']); ?></h1>
            <p style="font-size: 0.8rem; color: var(--text-soft); margin: 4px 0 0;">No. <?php echo htmlspecialchars($revista['numero']); ?> — <?php echo htmlspecialchars($revista['fecha']); ?></p>
          </div>
          <div style="display: flex; align-items: center; gap: 10px;">
            <button type="button" id="btnPagAnterior" class="btn-ghost" style="border-color: var(--navy);"><i class="fa fa-chevron-left"></i></button>
            <span id="indicadorPagina" style="font-size: 0.85rem; white-space: nowrap;">Página <?php echo (int) $paginaInicial; ?> de <?php echo count($imagenesValidas); ?></span>
            <button type="button" id="btnPagSiguiente" class="btn-ghost" style="border-color: var(--navy);"><i class="fa fa-chevron-right"></i></button>
          </div>
        </div>

        <!--
          Visor con efecto de pasar página (StPageFlip): cada página es una
          imagen JPG ya generada (por conversión automática del PDF, o
          subida a mano). No se entrega ningún PDF original para descargar,
          y el clic derecho queda bloqueado -- así se evita el
          "guardar PDF completo" con un clic.
        -->
        <div id="visorWrap" style="position: relative; display: flex; justify-content: center; background: #f4f2ec; border-radius: 8px; padding: 25px 20px; user-select: none;">
          <div id="book"></div>

          <!-- Marca de agua: identifica a quién se le mostró esta página, por si se comparte una captura -->
          <div id="marcaAgua" style="position:absolute; inset:0; pointer-events:none; overflow:hidden; display:flex; align-items:center; justify-content:center;">
            <div style="
              transform: rotate(-30deg);
              font-size: 1rem;
              color: rgba(0,0,0,0.07);
              font-family: var(--sans);
              text-align: center;
              line-height: 3.2;
              white-space: nowrap;
            ">
              <?php
                $marca = htmlspecialchars($_SESSION['correo'] ?? $_SESSION['nombre'] ?? 'Consultorio Fiscal');
                echo str_repeat($marca . '&nbsp;&nbsp;&nbsp;&nbsp;', 14);
              ?>
            </div>
          </div>
        </div>

      </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/page-flip@2.0.7/dist/js/page-flip.browser.js"></script>
    <script>
      const imagenes = <?php echo json_encode(array_values($imagenesValidas)); ?>;
      const paginaInicial = Math.min(Math.max(<?php echo (int) $paginaInicial; ?>, 1), imagenes.length);
      const indicador = document.getElementById('indicadorPagina');
      const btnAnterior = document.getElementById('btnPagAnterior');
      const btnSiguiente = document.getElementById('btnPagSiguiente');

      const pageFlip = new St.PageFlip(document.getElementById('book'), {
        width: 480,
        height: 680,
        size: 'stretch',
        minWidth: 280,
        maxWidth: 1100,
        minHeight: 400,
        maxHeight: 1500,
        maxShadowOpacity: 0.5,
        showCover: false,
        mobileScrollSupport: true
      });

      pageFlip.loadFromImages(imagenes);

      pageFlip.on('flip', function (e) {
        const num = e.data + 1;
        indicador.textContent = 'Página ' + num + ' de ' + imagenes.length;
        btnAnterior.disabled = num <= 1;
        btnSiguiente.disabled = num >= imagenes.length;
      });

      pageFlip.on('init', function () {
        if (paginaInicial > 1) {
          pageFlip.flip(paginaInicial - 1);
        }
      });

      btnAnterior.addEventListener('click', function () { pageFlip.flipPrev(); });
      btnSiguiente.addEventListener('click', function () { pageFlip.flipNext(); });

      // Bloquea el clic derecho (guardar imagen / inspeccionar) sobre el visor
      document.getElementById('visorWrap').addEventListener('contextmenu', function (e) {
        e.preventDefault();
      });
    </script>

  <?php endif; ?>

</main>

<?php include 'template/footer.php'; ?>
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

require_once 'data/favoritos.php';

// -----------------------------------------------------
// QUITAR UN FAVORITO
// -----------------------------------------------------
if (isset($_GET['eliminar'])) {
    delete_favorito($_GET['eliminar']);
    $destino = 'favoritos.php';
    if (!empty($_GET['tab'])) {
        $destino .= '?tab=' . urlencode($_GET['tab']);
    }
    header("Location: " . $destino);
    exit();
}

// Los más recientes primero (los IDs se asignan de forma incremental)
$favRevistas  = array_reverse(get_favoritos_por_tipo('revista'), true);
$favArticulos = array_reverse(get_favoritos_por_tipo('articulo'), true);
$favPaginas   = array_reverse(get_favoritos_por_tipo('pagina'), true);

$tabInicial = $_GET['tab'] ?? 'revistas';
if (!in_array($tabInicial, ['revistas', 'articulos', 'paginas'])) {
    $tabInicial = 'revistas';
}

$page_title = "Mis Favoritos - Consultorio Fiscal";
$page = "favoritos";
include 'template/header.php';
?>

<section class="hero-static">
  <div class="cs">
    <div class="hero-static__grid">
      <div class="hero-static__content reveal reveal--left in">
        <span class="c-ph__tag">Mi cuenta</span>
        <h1 class="hero-static__title">Mis Favoritos</h1>
        <div class="gold-line gold-l"></div>
        <p class="hero-static__excerpt reveal reveal--left">
          Guarda revistas, artículos y páginas para volver a ellos cuando quieras, sin tener
          que buscarlos de nuevo.
        </p>
      </div>
      <div class="hero-static__visual reveal reveal--right in">
        <div class="hero-static__img-box">
          <i class="fa-solid fa-star" style="font-size:2.6rem;color:var(--gold);"></i>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="about" style="padding: 30px 0 70px;">
  <div class="cs">

    <div class="fav-tabs" role="tablist" aria-label="Tipos de favoritos">
      <button type="button" class="fav-tab" data-tab="revistas" role="tab">
        <i class="fa-solid fa-book"></i> Revistas <span class="fav-tab__count"><?php echo count($favRevistas); ?></span>
      </button>
      <button type="button" class="fav-tab" data-tab="articulos" role="tab">
        <i class="fa-solid fa-file-lines"></i> Artículos <span class="fav-tab__count"><?php echo count($favArticulos); ?></span>
      </button>
      <button type="button" class="fav-tab" data-tab="paginas" role="tab">
        <i class="fa-solid fa-bookmark"></i> Páginas <span class="fav-tab__count"><?php echo count($favPaginas); ?></span>
      </button>
    </div>

    <!-- ================= REVISTAS ================= -->
    <div class="fav-panel" data-panel="revistas">
      <?php if (empty($favRevistas)): ?>
        <div class="fav-empty">
          <i class="fa-regular fa-star"></i>
          <h4>Aún no tienes revistas en favoritos</h4>
          <p>Cuando encuentres una edición que te interese, guárdala desde su vista previa para volver a ella fácilmente.</p>
          <a href="buscar.php" class="btn-ghost" style="border-color: var(--navy);"><span>Explorar el histórico →</span></a>
        </div>
      <?php else: ?>
        <div class="row g-4">
          <?php foreach ($favRevistas as $f): ?>
          <div class="col-md-6 col-lg-4">
            <div class="broadcast-card fav-card">
              <img src="img/portadas/<?php echo htmlspecialchars($f['numero']); ?>.jpg"
                   class="card-img-portada"
                   onerror="this.onerror=null; this.style.display='none';"
                   alt="Portada del ejemplar <?php echo htmlspecialchars($f['numero']); ?>">
              <div class="broadcast-card__content">
                <span class="broadcast-card__platform">Ejemplar No. <?php echo htmlspecialchars($f['numero']); ?></span>
                <h3 class="broadcast-card__channel" style="margin: 6px 0 6px;">
                  <?php echo htmlspecialchars($f['titulo']); ?>
                </h3>
                <p class="broadcast-card__time" style="margin-bottom: 18px;">
                  <?php echo htmlspecialchars($f['periodo'] ?? ''); ?>
                </p>
                <div class="fav-card__actions">
                  <a href="contenido.php" class="eco-card__btn" style="color: var(--navy); border-color: rgba(11,30,61,.28);">
                    Ver revista →
                  </a>
                  <a href="favoritos.php?eliminar=<?php echo urlencode($f['id']); ?>&tab=revistas"
                     class="fav-card__remove"
                     onclick="return confirm('¿Quitar esta revista de tus favoritos?');"
                     title="Quitar de favoritos" aria-label="Quitar de favoritos">
                    <i class="fa-solid fa-trash-can"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- ================= ARTÍCULOS ================= -->
    <div class="fav-panel" data-panel="articulos">
      <?php if (empty($favArticulos)): ?>
        <div class="fav-empty">
          <i class="fa-regular fa-star"></i>
          <h4>Aún no tienes artículos en favoritos</h4>
          <p>Marca los artículos que quieras leer más tarde desde su vista previa.</p>
          <a href="secciones.php" class="btn-ghost" style="border-color: var(--navy);"><span>Explorar secciones →</span></a>
        </div>
      <?php else: ?>
        <div class="fav-rows">
          <?php foreach ($favArticulos as $f): ?>
          <div class="fav-row">
            <div class="fav-row__icon"><i class="fa-solid fa-file-lines"></i></div>
            <div class="fav-row__body">
              <h4 class="fav-row__title"><?php echo htmlspecialchars($f['titulo']); ?></h4>
              <?php if (!empty($f['autor'])): ?>
              <p class="fav-row__meta">Por <?php echo htmlspecialchars($f['autor']); ?></p>
              <?php endif; ?>
              <?php if (!empty($f['descripcion'])): ?>
              <p class="fav-row__desc"><?php echo htmlspecialchars($f['descripcion']); ?></p>
              <?php endif; ?>
            </div>
            <div class="fav-row__actions">
              <a href="articulo.php?id=<?php echo urlencode($f['articulo_id']); ?>" class="fav-row__open">
                Leer artículo <i class="fa fa-arrow-right"></i>
              </a>
              <a href="favoritos.php?eliminar=<?php echo urlencode($f['id']); ?>&tab=articulos"
                 class="fav-row__remove"
                 onclick="return confirm('¿Quitar este artículo de tus favoritos?');"
                 title="Quitar de favoritos" aria-label="Quitar de favoritos">
                <i class="fa-solid fa-trash-can"></i>
              </a>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- ================= PÁGINAS ================= -->
    <div class="fav-panel" data-panel="paginas">
      <?php if (empty($favPaginas)): ?>
        <div class="fav-empty">
          <i class="fa-regular fa-star"></i>
          <h4>Aún no tienes páginas en favoritos</h4>
          <p>Desde una edición puedes guardar el número de página exacto al que quieras volver dentro del libro en línea.</p>
          <a href="buscar.php" class="btn-ghost" style="border-color: var(--navy);"><span>Explorar el histórico →</span></a>
        </div>
      <?php else: ?>
        <div class="fav-rows">
          <?php foreach ($favPaginas as $f): ?>
          <div class="fav-row">
            <div class="fav-row__icon"><i class="fa-solid fa-bookmark"></i></div>
            <div class="fav-row__body">
              <h4 class="fav-row__title">Página <?php echo (int) $f['pagina']; ?> · Ejemplar No. <?php echo htmlspecialchars($f['numero']); ?></h4>
              <p class="fav-row__meta">
                <?php echo htmlspecialchars($f['titulo']); ?><?php echo !empty($f['seccion']) ? ' · ' . htmlspecialchars($f['seccion']) : ''; ?>
              </p>
            </div>
            <div class="fav-row__actions">
              <a href="https://repositorios.fca.unam.mx/RevistaConsultorioFiscal/revista-consultorio/#page=<?php echo (int) $f['pagina']; ?>"
                 class="fav-row__open" target="_blank" rel="noopener">
                Abrir en el libro en línea <i class="fa-solid fa-arrow-up-right-from-square"></i>
              </a>
              <a href="favoritos.php?eliminar=<?php echo urlencode($f['id']); ?>&tab=paginas"
                 class="fav-row__remove"
                 onclick="return confirm('¿Quitar esta página de tus favoritos?');"
                 title="Quitar de favoritos" aria-label="Quitar de favoritos">
                <i class="fa-solid fa-trash-can"></i>
              </a>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

  </div>
</section>

<style>
/* ══════════════ MIS FAVORITOS ══════════════ */
.fav-tabs{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:32px;
  border-bottom:1px solid var(--line);padding-bottom:18px}
.fav-tab{display:inline-flex;align-items:center;gap:10px;padding:11px 20px;border-radius:100px;
  border:1px solid var(--line);background:#fff;color:var(--ink-2);font-family:var(--sans);
  font-size:.8rem;font-weight:500;cursor:pointer;transition:all .25s var(--e2)}
.fav-tab i{color:var(--gold);font-size:.82rem}
.fav-tab:hover{border-color:var(--gold);color:var(--navy)}
.fav-tab.on{background:var(--navy);border-color:var(--navy);color:#fff}
.fav-tab.on i{color:var(--gold-lt)}
.fav-tab__count{background:rgba(0,0,0,.06);border-radius:100px;padding:1px 9px;font-size:.72rem;font-weight:600}
.fav-tab.on .fav-tab__count{background:rgba(255,255,255,.18)}

.fav-panel{display:none}
.fav-panel.on{display:block;animation:favIn .4s var(--e)}
@keyframes favIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none}}

/* Tarjetas de revista (reutiliza broadcast-card / card-img-portada) */
.fav-card__actions{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-top:auto}
.fav-card__actions .eco-card__btn{width:auto;flex:1}
.fav-card__remove{display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;
  border-radius:50%;border:1px solid var(--line);color:var(--ink-3);flex-shrink:0;transition:all .25s var(--e2)}
.fav-card__remove:hover{border-color:#c0392b;color:#c0392b;background:rgba(192,57,43,.06)}

/* Filas de artículo / página */
.fav-rows{display:flex;flex-direction:column;gap:16px}
.fav-row{display:flex;align-items:flex-start;gap:20px;background:#fff;border:1px solid var(--line);
  border-radius:var(--r);padding:22px 24px;box-shadow:0 4px 16px rgba(11,30,61,.04);
  transition:all .25s var(--e2)}
.fav-row:hover{border-color:rgba(176,141,76,.4);box-shadow:0 10px 26px rgba(11,30,61,.08)}
.fav-row__icon{width:44px;height:44px;border-radius:50%;background:var(--paper-2);color:var(--gold);
  display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:1rem}
.fav-row__body{flex:1;min-width:0}
.fav-row__title{font-family:'Cormorant Garamond',serif;font-size:1.2rem;font-weight:600;color:var(--navy);margin-bottom:6px}
.fav-row__meta{font-family:var(--sans);font-size:.78rem;color:var(--gold);font-weight:600;margin-bottom:6px}
.fav-row__desc{font-family:var(--sans);font-size:.86rem;color:var(--ink-2);line-height:1.6;margin:0}
.fav-row__actions{display:flex;flex-direction:column;align-items:flex-end;gap:14px;flex-shrink:0}
.fav-row__open{font-family:var(--sans);font-size:.72rem;font-weight:600;letter-spacing:.06em;
  text-transform:uppercase;color:var(--navy);white-space:nowrap;display:inline-flex;align-items:center;gap:8px}
.fav-row__open:hover{color:var(--gold)}
.fav-row__remove{color:var(--ink-3);transition:color .25s var(--e2)}
.fav-row__remove:hover{color:#c0392b}

@media(max-width:600px){
  .fav-row{flex-direction:column}
  .fav-row__actions{flex-direction:row;align-items:center;justify-content:space-between;width:100%}
}

/* Estado vacío */
.fav-empty{text-align:center;padding:60px 24px;background:var(--paper-2);border:1px dashed var(--line);
  border-radius:var(--r)}
.fav-empty i{font-size:2.2rem;color:var(--gold);margin-bottom:16px;display:block}
.fav-empty h4{font-family:'Cormorant Garamond',serif;font-size:1.3rem;color:var(--navy);margin-bottom:8px}
.fav-empty p{color:var(--ink-2);font-size:.9rem;max-width:420px;margin:0 auto 22px}
</style>

<script>
(function () {
  const tabs = document.querySelectorAll('.fav-tab');
  const panels = document.querySelectorAll('.fav-panel');
  const inicial = <?php echo json_encode($tabInicial); ?>;

  function activar(nombre) {
    tabs.forEach(function (t) { t.classList.toggle('on', t.dataset.tab === nombre); });
    panels.forEach(function (p) { p.classList.toggle('on', p.dataset.panel === nombre); });
  }

  tabs.forEach(function (t) {
    t.addEventListener('click', function () { activar(t.dataset.tab); });
  });

  activar(inicial);
})();
</script>

<?php include 'template/footer.php'; ?>

<?php
  $page_title = "Cuadros de Información Permanente - Consultorio Fiscal | FCA UNAM";
  $page = "cuadros";

  /* ══════════════════════════════════════════════════════════════
     Las tarifas viven en inc/tablas_isr_2026.php como un bloque
     continuo. Aquí se captura esa salida y se reparte en fichas
     independientes, para mostrar una a la vez en lugar de
     obligar al usuario a recorrer treinta tablas seguidas.
     ══════════════════════════════════════════════════════════════ */
  ob_start();
  include 'inc/tablas_isr_2026.php';
  $raw = ob_get_clean();

  $ini = strpos($raw, '<div class="tablas-datos"');
  if ($ini !== false) { $raw = substr($raw, $ini); }

  // Encabezados de nivel 2 (grupos) y 3 (fichas), en orden de aparición
  preg_match_all('/<h([23])\s+id="([^"]*)"[^>]*>(.*?)<\/h\1>/su', $raw, $enc, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);

  function cf_limpia($txt) {
      $txt = strip_tags($txt);
      $txt = preg_replace('/^\s*[IVX0-9]+\.?-?\s*/u', '', $txt);   // "1.- " / "I.- "
      $txt = preg_replace('/Tarifas?\s+ISR\s+2026:\s*/iu', '', $txt);
      $txt = trim($txt);
      // Mayúscula inicial respetando acentos
      if ($txt !== '' && function_exists('mb_substr')) {
          $txt = mb_strtoupper(mb_substr($txt, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($txt, 1, null, 'UTF-8');
      } else {
          $txt = ucfirst($txt);
      }
      return $txt;
  }

  $grupos = array();   // [ ['titulo'=>…, 'fichas'=>[ ['id','nav','titulo','html'], … ]], … ]
  $g = -1;

  foreach ($enc as $k => $e) {
      $nivel  = $e[1][0];
      $id     = $e[2][0];
      $titulo = trim(strip_tags($e[3][0]));

      $desde = $e[0][1] + strlen($e[0][0]);
      $hasta = isset($enc[$k + 1]) ? $enc[$k + 1][0][1] : strlen($raw);
      $cuerpo = substr($raw, $desde, $hasta - $desde);

      // Tablas contenidas en este tramo, con su rótulo <h4> si lo tiene
      preg_match_all('/(?:<h4[^>]*>(.*?)<\/h4>\s*)?(<div class="table-wrapper"[^>]*>.*?<\/div>)/su',
                     $cuerpo, $tab, PREG_SET_ORDER);

      $html = '';
      $slots = 0;
      foreach ($tab as $t) {
          $rotulo = isset($t[1]) ? trim(strip_tags($t[1])) : '';
          $html .= '<div class="ficha__slot">';
          if ($rotulo !== '') { $html .= '<h4 class="ficha__sub">' . htmlspecialchars($rotulo) . '</h4>'; }
          $html .= $t[2] . '</div>';
          $slots++;
      }
      if ($html !== '') {
          $html = '<div class="ficha__tables" data-slots="' . $slots . '">' . $html . '</div>';
      }

      if ($nivel === '2') {
          $rotulo_grupo = cf_limpia($titulo);
          $cortos = array(
              'Tarifa para el cálculo del impuesto correspondiente al ejercicio 2026' => 'Cálculo anual',
              'Tarifas mensuales de pagos provisionales de ISR para personas físicas con actividad empresarial' => 'Pagos provisionales',
              'Retenciones periódicas de ISR' => 'Retenciones de salarios',
              'Tarifas bimestrales para los RIF' => 'Régimen de incorporación (RIF)',
          );
          if (isset($cortos[$rotulo_grupo])) { $rotulo_grupo = $cortos[$rotulo_grupo]; }
          $grupos[] = array('titulo' => $rotulo_grupo, 'fichas' => array());
          $g++;
          // Un grupo puede traer su propia tabla (la tarifa anual)
          if ($html !== '') {
              $grupos[$g]['fichas'][] = array(
                  'id' => $id, 'nav' => 'Tarifa anual', 'titulo' => $titulo, 'html' => $html
              );
          }
      } elseif ($g >= 0 && $html !== '') {
          $grupos[$g]['fichas'][] = array(
              'id' => $id, 'nav' => cf_limpia($titulo), 'titulo' => $titulo, 'html' => $html
          );
      }
  }

  // Total de fichas y ficha inicial
  $total = 0;
  foreach ($grupos as $gr) { $total += count($gr['fichas']); }
  $primera = '';
  foreach ($grupos as $gr) {
      if (!empty($gr['fichas'])) { $primera = $gr['fichas'][0]['id']; break; }
  }

  include 'template/header.php';
?>

<section class="cuadros">
  <div class="wrap">

    <!-- Encabezado -->
    <div class="cuadros__head">
      <div>
        <span class="eyebrow">Recursos fiscales</span>
        <h1 class="cuadros__title">Cuadros de información permanente</h1>
        <p class="cuadros__lead">
          Tablas del ISR 2026 actualizadas: tarifa anual, retenciones periódicas de salarios con su
          subsidio al empleo, pagos provisionales de actividad empresarial y tarifas bimestrales del RIF.
          Elige un cuadro en el índice y se muestra aquí mismo.
        </p>
        <div class="cuadros__meta">
          <span class="cuadros__pill"><i class="fa fa-table-list"></i> <?php echo $total; ?> cuadros</span>
          <span class="cuadros__pill"><i class="fa fa-rotate"></i> Actualización enero 2026</span>
        </div>
      </div>

      <div class="cuadros__tools">
        <button type="button" class="cfbtn cfbtn--gold" id="pdfAll">
          <i class="fa fa-file-pdf"></i> Descargar todo en PDF
        </button>
        <button type="button" class="cfbtn cfbtn--line" id="pdfOne">
          <i class="fa fa-download"></i> Descargar este cuadro
        </button>
      </div>
    </div>

    <!-- Navegador + ficha -->
    <div class="cuadros__grid">

      <aside class="cuadros__nav">
        <button type="button" class="cuadros__toggle" id="cuadroToggle" aria-expanded="false" aria-controls="cuadroCuerpo">
          <span><i class="fa fa-list-ul"></i> Índice de cuadros</span>
          <i class="fa fa-chevron-down cuadros__toggle-ico"></i>
        </button>

        <div class="cuadros__nav-body" id="cuadroCuerpo">
        <div class="cuadros__search">
          <i class="fa fa-magnifying-glass"></i>
          <input type="search" id="cuadroFiltro" placeholder="Filtrar cuadros…" aria-label="Filtrar cuadros">
        </div>

        <div class="cuadros__nav-scroll" id="cuadroIndice">
          <?php foreach ($grupos as $gr): if (empty($gr['fichas'])) continue; ?>
          <div class="cuadros__group">
            <span class="cuadros__group-title"><?php echo htmlspecialchars($gr['titulo']); ?></span>
            <ul>
              <?php foreach ($gr['fichas'] as $f): ?>
              <li>
                <button type="button" class="cuadros__nav-btn<?php echo ($f['id'] === $primera) ? ' on' : ''; ?>"
                        data-target="<?php echo htmlspecialchars($f['id']); ?>">
                  <?php echo htmlspecialchars($f['nav']); ?>
                </button>
              </li>
              <?php endforeach; ?>
            </ul>
          </div>
          <?php endforeach; ?>
          <p class="cuadros__empty" id="cuadroVacio" hidden>Sin coincidencias.</p>
        </div>
        </div>
      </aside>

      <div class="cuadros__panel" id="cuadroPanel">
        <?php foreach ($grupos as $gr): foreach ($gr['fichas'] as $f): ?>
        <article class="ficha<?php echo ($f['id'] === $primera) ? ' on' : ''; ?>"
                 id="ficha-<?php echo htmlspecialchars($f['id']); ?>"
                 data-nav="<?php echo htmlspecialchars($f['nav']); ?>">
          <header class="ficha__head">
            <span class="ficha__group"><?php echo htmlspecialchars($gr['titulo']); ?></span>
            <h2 class="ficha__title"><?php echo htmlspecialchars($f['titulo']); ?></h2>
          </header>
          <?php echo $f['html']; ?>
        </article>
        <?php endforeach; endforeach; ?>

        <footer class="ficha__foot">
          <p>Fuente: Servicio de Administración Tributaria (SAT) — Resolución Miscelánea Fiscal 2026.</p>
          <p>Información con fines de referencia. Consulte siempre la normativa oficial.</p>
        </footer>
      </div>

    </div>
  </div>
</section>

<style>
/* ══════════════ CUADROS PERMANENTES ══════════════ */
.cuadros{padding:70px 0 96px;background:var(--paper)}
.cuadros__head{display:grid;grid-template-columns:1fr auto;gap:40px;align-items:end;
  padding-bottom:34px;border-bottom:1px solid var(--line);margin-bottom:38px}
.cuadros__title{font-family:var(--serif);font-weight:300;color:var(--navy);
  font-size:clamp(2rem,3.8vw,3rem);line-height:1.06;margin-bottom:16px}
.cuadros__lead{color:var(--ink-2);font-size:.96rem;max-width:640px}
.cuadros__meta{display:flex;gap:12px;flex-wrap:wrap;margin-top:20px}
.cuadros__pill{display:inline-flex;align-items:center;gap:9px;padding:8px 18px;border-radius:100px;
  background:var(--paper-2);border:1px solid var(--line);font-family:var(--sans);font-size:.7rem;
  font-weight:500;letter-spacing:.08em;text-transform:uppercase;color:var(--ink-2)}
.cuadros__pill i{color:var(--gold)}
.cuadros__tools{display:flex;flex-direction:column;gap:10px;flex-shrink:0}

.cuadros__grid{display:grid;grid-template-columns:290px 1fr;gap:36px;align-items:start}

/* Índice lateral */
.cuadros__nav{position:sticky;top:152px;background:var(--paper-2);border:1px solid var(--line);
  border-radius:var(--r);overflow:hidden}
.cuadros__search{display:flex;align-items:center;gap:10px;padding:14px 18px;
  border-bottom:1px solid var(--line);background:#fff}
.cuadros__search i{color:var(--ink-3);font-size:.8rem}
.cuadros__search input{flex:1;border:none;background:transparent;font-family:var(--sans);
  font-size:.84rem;color:var(--ink);outline:none}
.cuadros__nav-scroll{max-height:calc(100vh - 256px);overflow-y:auto;padding:14px 0;
  scrollbar-width:thin;scrollbar-color:var(--gold) transparent}
.cuadros__group{padding:0 0 12px}
.cuadros__group-title{display:block;padding:12px 20px 8px;font-family:var(--sans);font-size:.6rem;
  font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:var(--gold)}
.cuadros__group ul{list-style:none;margin:0;padding:0}
.cuadros__nav-btn{display:block;width:100%;text-align:left;padding:9px 20px;border:none;
  background:transparent;font-family:var(--sans);font-size:.83rem;color:var(--ink-2);cursor:pointer;
  border-left:2px solid transparent;transition:all .25s var(--e2)}
.cuadros__nav-btn:hover{background:rgba(176,141,76,.09);color:var(--navy)}
.cuadros__nav-btn.on{background:rgba(176,141,76,.14);color:var(--navy);font-weight:600;
  border-left-color:var(--gold)}
.cuadros__empty{padding:18px 20px;font-size:.82rem;color:var(--ink-3)}

/* Ficha */
.cuadros__panel{min-width:0}
.ficha{display:none}
.ficha.on{display:block;animation:fichaIn .45s var(--e)}
@keyframes fichaIn{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:none}}
.ficha__head{margin-bottom:24px}
.ficha__group{display:block;font-family:var(--sans);font-size:.62rem;font-weight:600;
  letter-spacing:.2em;text-transform:uppercase;color:var(--gold);margin-bottom:10px}
.ficha__title{font-family:var(--serif);font-weight:300;color:var(--navy);
  font-size:clamp(1.4rem,2.6vw,2rem);line-height:1.14}
.ficha__tables{display:block}
.ficha__slot + .ficha__slot{margin-top:30px}
.ficha__sub{font-family:var(--sans);font-size:.68rem;font-weight:700;letter-spacing:.16em;
  text-transform:uppercase;color:var(--ink-2);margin:0 0 12px}

.table-wrapper{overflow-x:auto;border:1px solid var(--line);border-radius:var(--r);background:#fff;
  box-shadow:0 4px 18px rgba(11,30,61,.04)}
.isr-table{width:100%;border-collapse:collapse;font-size:.86rem}
.isr-table th{background:var(--paper-2);color:var(--navy);font-family:var(--sans);font-size:.62rem;
  font-weight:700;letter-spacing:.1em;text-transform:uppercase;padding:14px 18px;text-align:right;
  border-bottom:1px solid var(--line);white-space:nowrap}
.isr-table th:first-child{text-align:left}
.isr-table td{padding:13px 18px;text-align:right;color:var(--ink-2);
  border-bottom:1px solid rgba(11,30,61,.05);font-variant-numeric:tabular-nums;white-space:nowrap}
.isr-table td:first-child{text-align:left}
.isr-table tr:last-child td{border-bottom:none}
.isr-table tbody tr:hover td{background:rgba(176,141,76,.06);color:var(--navy)}

.ficha__foot{margin-top:34px;padding-top:20px;border-top:1px solid var(--line)}
.ficha__foot p{font-size:.78rem;color:var(--ink-3);line-height:1.6;margin:0}

/* Plegado del índice (sólo móvil) */
.cuadros__toggle{display:none;width:100%;align-items:center;justify-content:space-between;gap:12px;
  padding:16px 18px;border:none;background:var(--paper-2);cursor:pointer;
  font-family:var(--sans);font-size:.9rem;font-weight:500;color:var(--navy);min-height:54px}
.cuadros__toggle i{color:var(--gold)}
.cuadros__toggle-ico{transition:transform .35s var(--e)}
.cuadros__toggle[aria-expanded="true"] .cuadros__toggle-ico{transform:rotate(180deg)}

@media(max-width:1000px){
  .cuadros{padding:40px 0 70px}
  .cuadros__head{grid-template-columns:1fr;gap:26px;padding-bottom:26px;margin-bottom:26px}
  .cuadros__tools{flex-direction:column;width:100%}
  .cuadros__tools .cfbtn{width:100%;min-height:52px}
  .cuadros__grid{grid-template-columns:1fr;gap:22px}
  .cuadros__nav{position:static}
  .cuadros__toggle{display:flex}
  .cuadros__nav-body{display:none}
  .cuadros__nav-body.open{display:block;border-top:1px solid var(--line)}
  .cuadros__nav-scroll{max-height:min(58vh,420px)}
  .cuadros__nav-btn{padding:14px 20px;font-size:.9rem;min-height:48px}
  .cuadros__search{min-height:54px}
  .cuadros__search input{font-size:16px}   /* evita el zoom automático en iOS */
  .isr-table{font-size:.8rem}
  .isr-table th,.isr-table td{padding:11px 13px}
  .ficha__title{font-size:1.35rem}
}
@media(max-width:560px){
  .cuadros__pill{font-size:.64rem;padding:7px 14px}
  .isr-table th,.isr-table td{padding:10px 11px;font-size:.76rem}
  .table-wrapper{-webkit-overflow-scrolling:touch}
}

/* ── Impresión / PDF: horizontal, un cuadro por hoja ── */
@media print{
  @page{size:A4 landscape;margin:12mm}

  .cfnav,.cfnav__panel,.cfnav__burger,.cuadros__nav,.cuadros__tools,.cuadros__meta,
  .footer-a,.footer-b{display:none!important}

  html,body{background:#fff!important;font-size:11pt}
  .cuadros{padding:0}
  .wrap{max-width:none;padding:0}
  .cuadros__grid{display:block}
  .cuadros__panel{min-width:0}

  /* Portadilla: sólo en la primera hoja */
  .cuadros__head{display:block;border:none;padding:0 0 10mm;margin:0}
  .cuadros__title{font-size:20pt;margin-bottom:4mm}
  .cuadros__lead{font-size:9.5pt;max-width:none}

  /* Cada ficha ocupa su propia hoja */
  .ficha{display:block!important;break-after:page;page-break-after:always;
         break-inside:avoid;page-break-inside:avoid;margin:0}
  .ficha:last-of-type{break-after:auto;page-break-after:auto}
  .ficha__head{margin-bottom:5mm}
  .ficha__group{font-size:7.5pt;letter-spacing:.16em}
  .ficha__title{font-size:15pt;line-height:1.15}
  .ficha__sub{font-size:8pt;margin:5mm 0 2mm}
  .ficha__sub:first-of-type{margin-top:0}

  /* Dos tablas de la misma ficha caben en paralelo al imprimir en horizontal */
  .ficha__tables[data-slots="2"]{display:grid;grid-template-columns:1fr 1fr;gap:7mm;align-items:start}
  .ficha__slot + .ficha__slot{margin-top:0}
  .ficha__slot{break-inside:avoid;page-break-inside:avoid}
  .table-wrapper{overflow:visible;border:1px solid #c9c2b4;border-radius:3px;box-shadow:none;
                 break-inside:avoid;page-break-inside:avoid}
  .isr-table{font-size:8pt;width:100%}
  .isr-table th{background:#f1ead9!important;color:#0B1E3D!important;padding:3mm 3mm;font-size:7pt;
                -webkit-print-color-adjust:exact;print-color-adjust:exact}
  .isr-table td{padding:2.4mm 3mm;color:#1B2130}
  .isr-table tbody tr:hover td{background:transparent}

  .ficha__foot{break-before:auto;margin-top:6mm;padding-top:3mm;border-top:1px solid #ddd}
  .ficha__foot p{font-size:8pt}

  /* Al descargar un solo cuadro: se oculta el resto y no sobra hoja */
  body.print-uno .ficha{display:none!important}
  body.print-uno .ficha.on{display:block!important;break-after:auto;page-break-after:auto}
  body.print-uno .cuadros__head{padding-bottom:6mm}
  body.print-uno .ficha__foot{display:none}
}
</style>

<script>
(function () {
  const botones = document.querySelectorAll('.cuadros__nav-btn');
  const fichas  = document.querySelectorAll('.ficha');
  const filtro  = document.getElementById('cuadroFiltro');
  const vacio   = document.getElementById('cuadroVacio');

  function mostrar(id) {
    fichas.forEach((f) => f.classList.toggle('on', f.id === 'ficha-' + id));
    botones.forEach((b) => b.classList.toggle('on', b.dataset.target === id));
    if (window.innerWidth < 1000) {
      const panel = document.getElementById('cuadroPanel');
      if (panel) window.scrollTo({ top: panel.offsetTop - 100, behavior: 'smooth' });
    }
  }

  botones.forEach((b) => b.addEventListener('click', () => mostrar(b.dataset.target)));

  // Índice plegable en móvil
  const toggle = document.getElementById('cuadroToggle');
  const cuerpo = document.getElementById('cuadroCuerpo');
  if (toggle && cuerpo) {
    toggle.addEventListener('click', () => {
      const abierto = cuerpo.classList.toggle('open');
      toggle.setAttribute('aria-expanded', abierto);
    });
    // Al elegir un cuadro, el índice se cierra solo
    botones.forEach((b) => b.addEventListener('click', () => {
      if (window.innerWidth < 1000) {
        cuerpo.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
      }
    }));
  }

  // Filtro del índice
  if (filtro) {
    filtro.addEventListener('input', () => {
      const q = filtro.value.trim().toLowerCase();
      let hay = 0;
      document.querySelectorAll('.cuadros__group').forEach((g) => {
        const titulo = g.querySelector('.cuadros__group-title');
        const grupoCoincide = !!q && titulo && titulo.textContent.toLowerCase().indexOf(q) !== -1;
        let visibles = 0;
        g.querySelectorAll('.cuadros__nav-btn').forEach((b) => {
          const ok = !q || grupoCoincide || b.textContent.toLowerCase().indexOf(q) !== -1;
          b.parentElement.hidden = !ok;
          if (ok) { visibles++; hay++; }
        });
        g.hidden = visibles === 0;
      });
      if (vacio) vacio.hidden = hay > 0;
    });
  }

  // Descarga en PDF mediante el diálogo de impresión del navegador
  function imprimir(soloUno) {
    document.body.classList.toggle('print-uno', soloUno);
    window.print();
    setTimeout(() => document.body.classList.remove('print-uno'), 400);
  }
  const todo = document.getElementById('pdfAll');
  const uno  = document.getElementById('pdfOne');
  if (todo) todo.addEventListener('click', () => imprimir(false));
  if (uno)  uno.addEventListener('click',  () => imprimir(true));
})();
</script>

<?php include 'template/footer.php'; ?>
